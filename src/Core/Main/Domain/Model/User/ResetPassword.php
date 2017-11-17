<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

class ResetPassword
{
    /** @var  string */
    protected $id;

    /** @var  string */
    protected $userId;

    /** @var  \DateTimeInterface */
    protected $expiresAt;

    /** @var  \DateTimeInterface */
    protected $resetAt;

    /** @var  \DateTimeInterface */
    protected $createdAt;

    /**
     * ResetPassword constructor.
     *
     * @param string $id
     * @param string $userId
     * @param \DateTimeInterface $expiresAt
     * @param \DateTimeInterface|null $resetAt
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(
        string $id,
        string $userId,
        \DateTimeInterface $expiresAt,
        ?\DateTimeInterface $resetAt,
        \DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->expiresAt = $expiresAt;
        $this->resetAt = $resetAt;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTimeInterface $expiresAt
     */
    public function setExpiresAt(\DateTimeInterface $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getResetAt(): ?\DateTimeInterface
    {
        return $this->resetAt;
    }

    /**
     * @param \DateTimeInterface $resetAt
     */
    public function setResetAt(\DateTimeInterface $resetAt)
    {
        $this->resetAt = $resetAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
