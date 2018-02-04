<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Service;

use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\JwtKey;
use Core\Main\Infrastructure\Ui\Web\Silex\User\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtEncodeService
{
    const ALLOWED_ALGORITHMS = ['HS256', 'RS256'];

    const ISSUER = 'dictionary';

    /** @var  JwtKey */
    protected $jwtKey;

    /** @var  int */
    protected $lifetime;

    /** @var  string */
    protected $algorithm;

    /**
     * JwtEncodeService constructor.
     *
     * @param JwtKey $jwtKey
     * @param int $lifetime
     * @param string $algorithm
     */
    public function __construct(JwtKey $jwtKey, int $lifetime, string $algorithm = 'RS256')
    {
        $this->setJwtKey($jwtKey);
        $this->setLifetime($lifetime);
        $this->setAlgorithm($algorithm);
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     */
    public function setAlgorithm(string $algorithm)
    {
        if (!in_array($algorithm, self::ALLOWED_ALGORITHMS)) {
            throw new \InvalidArgumentException("Invalid algorithm selected.");
        }

        $this->algorithm = $algorithm;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime(int $lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @return JwtKey
     */
    public function getJwtKey(): JwtKey
    {
        return $this->jwtKey;
    }

    /**
     * @param JwtKey $jwtKey
     */
    public function setJwtKey(JwtKey $jwtKey)
    {
        $this->jwtKey = $jwtKey;
    }

    /**
     * @param UserInterface $user
     * @return string
     */
    public function encode(UserInterface $user): string
    {
        $payload = [
            'exp' => time() + $this->getLifetime(), // expiration time
            'iat' => time(), // issued time
            'iss' => self::ISSUER, //
            'roles' => $user->getRoles()
        ];

        if ($user instanceof User) {
            $payload['user_id'] = $user->getId();
            $payload['locale'] = $user->getLocale();
        }

        return JWT::encode(
            $payload,
            $this->jwtKey->getPrivateKey(),
            $this->getAlgorithm()
        );
    }

    /**
     * @param string $jwt
     * @return array
     */
    public function decode(string $jwt): array
    {
        $payload = [];

        try {
            if ($this->algorithm === 'RS256') {
                $payload = (array)JWT::decode($jwt, $this->jwtKey->getPublicKey(), self::ALLOWED_ALGORITHMS);
            }
        } catch (ExpiredException $e) {
            throw new AuthenticationExpiredException("Authentication token expired.");
        } catch (SignatureInvalidException $e) {
            throw new AuthenticationServiceException("Invalid token signature.");
        } catch (\DomainException $e) {
            throw new AuthenticationServiceException("Invalid token.");
        }

        return $payload;
    }
}
