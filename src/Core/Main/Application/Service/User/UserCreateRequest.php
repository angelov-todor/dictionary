<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class UserCreateRequest
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $currency;

    /**
     * UserCreateRequest constructor.
     * @param string $email
     * @param string $password
     * @param string $locale
     * @param string $currency
     */
    public function __construct(string $email, string $password, string $locale, string $currency)
    {
        $this->email = $email;
        $this->password = $password;
        $this->locale = $locale;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
}
