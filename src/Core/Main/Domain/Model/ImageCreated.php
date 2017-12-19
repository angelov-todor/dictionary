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
     * @var null|string
     */
    protected $source;

    /**
     * ImageCreated constructor.
     * @param int $imageId
     * @param null|string $source
     */
    public function __construct(int $imageId, ?string $source)
    {
        $this->imageId = $imageId;
        $this->source = $source;
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

    /**
     * @return null|string
     */
    public function getSource()
    {
        return $this->source;
    }
}
