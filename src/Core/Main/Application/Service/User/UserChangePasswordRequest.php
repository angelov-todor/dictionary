<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserChangePasswordRequest
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    protected $currentPassword;

    /**
     * @var string
     */
    protected $newPassword;

    /**
     * UserChangePasswordRequest constructor.
     * @param string $userId
     * @param string $currentPassword
     * @param string $newPassword
     */
    public function __construct(string $userId, string $currentPassword, string $newPassword)
    {
        $this->userId = $userId;
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
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
    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }
}
