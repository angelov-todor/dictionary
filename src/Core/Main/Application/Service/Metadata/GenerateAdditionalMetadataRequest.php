<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

class GenerateAdditionalMetadataRequest
{
    /**
     * @var int
     */
    protected $imageId;

    /**
     * @var string
     */
    protected $imageMetadataValue;

    /**
     * GenerateAdditionalMetadataRequest constructor.
     * @param int $imageId
     * @param string $imageMetadataValue
     */
    public function __construct(int $imageId, string $imageMetadataValue)
    {
        $this->imageId = $imageId;
        $this->imageMetadataValue = $imageMetadataValue;
    }

    /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }

    /**
     * @return string
     */
    public function getImageMetadataValue(): string
    {
        return $this->imageMetadataValue;
    }
}
