<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserViewRequest
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
