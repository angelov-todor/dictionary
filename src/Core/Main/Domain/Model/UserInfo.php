<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

use Core\Main\Domain\Model\User\User;
use Ramsey\Uuid\Uuid;

class UserInfo
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserInfo constructor.
     * @param string $id
     * @param string $name
     * @param string $phone
     * @param string $address
     * @param User $user
     */
    public function __construct(?string $id, string $name, string $phone, string $address, User $user)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->user = $user;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param string $name
     * @return UserInfo
     */
    public function setName(string $name): UserInfo
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $phone
     * @return UserInfo
     */
    public function setPhone(string $phone): UserInfo
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $address
     * @return UserInfo
     */
    public function setAddress(string $address): UserInfo
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param User $user
     * @return UserInfo
     */
    public function setUser(User $user): UserInfo
    {
        $this->user = $user;
        return $this;
    }
}
