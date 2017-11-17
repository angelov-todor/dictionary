<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class ChangeLocaleRequest
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $locale;

    /**
     * ChangeLocaleRequest constructor.
     * @param string $userId
     * @param string $locale
     */
    public function __construct(string $userId, string $locale)
    {
        $this->userId = $userId;
        $this->locale = $locale;
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
    public function getLocale(): string
    {
        return $this->locale;
    }
}
