<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class JwtToken extends AbstractToken
{
    /** @var  string */
    protected $requestToken;

    /** @var  string */
    protected $id;

    /** @var  string */
    protected $credentials;

    /** @var  string */
    protected $providerKey = 'jwt';

    /**
     * JWToken constructor.
     *
     * @param mixed $user
     * @param string $credentials
     * @param array $roles
     */
    public function __construct($user, string $credentials = null, array $roles = [])
    {
        parent::__construct($roles);

        $this->setUser($user);
        $this->credentials = $credentials;

        parent::setAuthenticated(count($roles) > 0);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the user credentials.
     *
     * @return string The user credentials
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * @return string
     */
    public function getRequestToken(): string
    {
        return $this->requestToken;
    }

    /**
     * @param string $requestToken
     */
    public function setRequestToken(string $requestToken)
    {
        $this->requestToken = $requestToken;
    }
}
