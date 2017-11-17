<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserPasswordResetClaimRequest
{
    /** @var string */
    private $email;

    /**
     * UserPasswordResetClaimRequest constructor.
     *
     * @param null|string $email
     */
    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
