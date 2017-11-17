<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserPasswordResetRequest
{
    /** @var string */
    private $hash;

    /** @var  string */
    private $password;

    /**
     * UserPasswordResetRequest constructor.
     *
     * @param null|string $hash
     * @param null|string $password
     */
    public function __construct(?string $hash, ?string $password)
    {
        $this->hash = $hash;
        $this->password = $password;
    }

    /**
     * @return null|string
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
