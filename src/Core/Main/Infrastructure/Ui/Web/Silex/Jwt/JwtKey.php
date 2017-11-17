<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt;

class JwtKey
{
    /** @var  string */
    private $secret;

    /** @var  string */
    private $publicKey;

    /** @var  string */
    private $privateKey;

    /**
     * Create HS256 based key
     * @param string $secret
     * @return JwtKey
     */
    public static function createSecretKey(string $secret): JwtKey
    {
        $key = new self();
        $key->setSecret($secret);

        return $key;
    }

    /**
     * Create RS256 based key
     * @param string $publicKey
     * @param string $privateKey
     * @return JwtKey
     */
    public static function createKeyPair(string $publicKey, string $privateKey): JwtKey
    {
        $key = new self();
        $key->setPublicKey($publicKey);
        $key->setPrivateKey($privateKey);

        return $key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }
}
