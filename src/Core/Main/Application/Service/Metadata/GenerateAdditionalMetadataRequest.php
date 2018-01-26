<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

class GenerateAdditionalMetadataRequest
{
    /**
     * @var string
     */
    protected $imageId;

    /**
     * @var string
     */
    protected $imageMetadataValue;

    /**
     * GenerateAdditionalMetadataRequest constructor.
     * @param string $imageId
     * @param string $imageMetadataValue
     */
    public function __construct(string $imageId, string $imageMetadataValue)
    {
        $this->imageId = $imageId;
        $this->imageMetadataValue = $imageMetadataValue;
    }

    /**
     * @return string
     */
    public function getImageId(): string
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
