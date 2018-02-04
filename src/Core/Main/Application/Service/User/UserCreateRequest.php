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
     * UserCreateRequest constructor.
     * @param string $email
     * @param string $password
     * @param string $locale
     */
    public function __construct(string $email, string $password, string $locale)
    {
        $this->email = $email;
        $this->password = $password;
        $this->locale = $locale;
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
