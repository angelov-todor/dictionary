<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class AuthenticateRequest
{
    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /**
     * AuthenticateRequest constructor.
     *
     * @param null|string $email
     * @param null|string $password
     */
    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
