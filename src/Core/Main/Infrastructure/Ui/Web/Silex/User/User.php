<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\User;

use Core\Main\Domain\Model\User\User as DomainUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /** @var  DomainUser */
    protected $domainUser;

    /**
     * User constructor.
     *
     * @param DomainUser $domainUser
     */
    public function __construct(DomainUser $domainUser)
    {
        $this->setDomainUser($domainUser);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->domainUser->getId();
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array The user roles
     */
    public function getRoles()
    {
        return $this->domainUser->getRoles();
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword(): string
    {
        return $this->domainUser->getPassword()->getPassword();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return '';
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->domainUser->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // we do no store any sensitive information in the user object
    }

    /**
     * @return DomainUser
     */
    public function getDomainUser(): DomainUser
    {
        return $this->domainUser;
    }

    /**
     * @param DomainUser $domainUser
     */
    public function setDomainUser(DomainUser $domainUser)
    {
        $this->domainUser = $domainUser;
    }

    /**
     * @return null|string
     */
    public function getLocale(): ?string
    {
        return $this->domainUser->getLocale();
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->domainUser->getCurrency();
    }
}
