<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Ddd\Domain\DomainEvent;

class UserEmailChanged implements DomainEvent
{
    /**
     * @var \DateTimeInterface
     */
    protected $occurredAt;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * UserCreated constructor.
     * @param string $id
     * @param string $email
     */
    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
        $this->occurredAt = new \DateTime();
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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn(): \DateTime
    {
        return $this->occurredAt;
    }
}
