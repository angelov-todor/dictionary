<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

class GenerateMetadataRequest
{
    /**
     * @var int
     */
    protected $imageId;

    /**
     * GenerateMetadataRequest constructor.
     * @param int $imageId
     */
    public function __construct(int $imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     * @return int
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }
}
