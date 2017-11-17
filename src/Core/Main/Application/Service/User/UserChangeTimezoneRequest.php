<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserChangeTimezoneRequest
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * UserChangeTimezoneRequest constructor.
     * @param string $userId
     * @param string $timezone
     */
    public function __construct(string $userId, string $timezone)
    {
        $this->userId = $userId;
        $this->timezone = $timezone;
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
    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
