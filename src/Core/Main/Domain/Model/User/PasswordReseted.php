<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Ddd\Domain\DomainEvent;

class PasswordReseted implements DomainEvent
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
     * PasswordReseted constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->occurredAt = new \DateTime();
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
