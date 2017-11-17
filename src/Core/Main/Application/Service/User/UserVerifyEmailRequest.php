<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserVerifyEmailRequest
{
    /**
     * @var string
     */
    protected $email;

    /**
     * UserVerifyEmailRequest constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
