<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

use Ddd\Domain\DomainEvent;

class ImageCreated implements DomainEvent
{
    /**
     * @var \DateTime
     */
    protected $occurredAt;

    /**
     * @var int
     */
    protected $imageId;

    /**
     * ImageCreated constructor.
     * @param int $imageId
     */
    public function __construct(int $imageId)
    {
        $this->imageId = $imageId;
        $this->occurredAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        return $this->occurredAt;
    }

    /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }
}
