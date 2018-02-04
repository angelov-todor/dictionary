<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Assert\AssertionFailedException;
use Core\Main\Application\Helper\Locale;
use Ramsey\Uuid\Uuid;
use Assert\Assertion;

class User
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_CREATOR = 'ROLE_CREATOR';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @var bool
     */
    private $emailVerified;

    /**
     * @var string
     */
    private $timezone;

    /**
     * @var string
     */
    private $locale;

    /**
     * User constructor.
     * @param string|null $id
     * @param string $email
     * @param Password $password
     * @throws AssertionFailedException
     */
    public function __construct(?string $id, string $email = null, Password $password = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();

        if (false === is_null($email)) {
            $this->setEmail($email);
        }

        if (false === is_null($password)) {
            $this->setPassword($password);
        }

        $this->createdAt = new \DateTime();
        $this->emailVerified = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param string $id
     * @return User
     */
    public function setId($id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $email
     * @return User
     * @throws AssertionFailedException
     */
    public function setEmail(string $email): User
    {
        Assertion::email($email, null, 'email');
        $this->email = $email;

        return $this;
    }

    /**
     * @param Password $password
     * @return User
     */
    public function setPassword(Password $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    /**
     * @param bool $emailVerified
     * @return User
     */
    public function setEmailVerified(bool $emailVerified): User
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return User
     * @throws AssertionFailedException
     */
    public function setTimezone(string $timezone): User
    {
        Assertion::inArray(
            $timezone,
            \DateTimeZone::listIdentifiers(),
            null,
            'timezone'
        );
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return User
     * @throws AssertionFailedException
     */
    public function setLocale(string $locale): User
    {
        Assertion::true(
            Locale::isValidLocale($locale),
            'Value "%s" is not valid locale.',
            'locale'
        );
        $this->locale = $locale;
        return $this;
    }

    /**
     * @param string $email
     * @param Password $password
     * @param string $locale
     * @return User
     *
     * @throws AssertionFailedException
     */
    public static function create(string $email, Password $password, string $locale): User
    {
        $user = new User(null, $email, $password);
        $user->setLocale($locale)
            ->setRoles([self::ROLE_USER]);

        return $user;
    }
}
