<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class ChangeContactNameRequest
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $contactName;

    /**
     * ChangeContactNameRequest constructor.
     * @param string $userId
     * @param string $contactName
     */
    public function __construct($userId, $contactName)
    {
        $this->userId = $userId;
        $this->contactName = $contactName;
    }

    /**
     * @return string
     */
    public function getContactName(): string
    {
        return $this->contactName;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
