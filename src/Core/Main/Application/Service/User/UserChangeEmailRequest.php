<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserChangeEmailRequest
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    protected $email;

    /**
     * UserChangeEmailRequest constructor.
     * @param string $userId
     * @param string $email
     */
    public function __construct(string $userId, string $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
