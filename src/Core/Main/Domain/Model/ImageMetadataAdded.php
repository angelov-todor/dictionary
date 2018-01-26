<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

use Ddd\Domain\DomainEvent;

class ImageMetadataAdded implements DomainEvent
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
     * @var int
     */
    protected $imageMetadataId;

    /**
     * @var int
     */
    protected $metadataId;

    /**
     * @var string
     */
    protected $metadataName;

    /**
     * @var string
     */
    protected $imageMetadataValue;

    /**
     * ImageMetadataAdded constructor.
     * @param int $imageId
     * @param int $imageMetadataId
     * @param int $metadataId
     * @param string $metadataName
     * @param string $imageMetadataValue
     */
    public function __construct(
        int $imageId,
        int $imageMetadataId,
        int $metadataId,
        string $metadataName,
        string $imageMetadataValue
    ) {
        $this->imageId = $imageId;
        $this->imageMetadataId = $imageMetadataId;
        $this->metadataId = $metadataId;
        $this->metadataName = $metadataName;
        $this->imageMetadataValue = $imageMetadataValue;
        $this->occurredAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getImageMetadataValue(): string
    {
        return $this->imageMetadataValue;
    }

    /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }

    /**
     * @return int
     */
    public function getImageMetadataId(): int
    {
        return $this->imageMetadataId;
    }

    /**
     * @return int
     */
    public function getMetadataId(): int
    {
        return $this->metadataId;
    }

    /**
     * @return string
     */
    public function getMetadataName(): string
    {
        return $this->metadataName;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        return $this->occurredAt;
    }
}
