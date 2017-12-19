<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

class AddSourceMetadataRequest
{
    /**
     * @var int
     */
    protected $imageId;

    /**
     * @var string
     */
    protected $source;

    /**
     * AddSourceMetadataRequest constructor.
     * @param int $imageId
     * @param string $source
     */
    public function __construct(int $imageId, string $source)
    {
        $this->imageId = $imageId;
        $this->source = $source;
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
    public function getSource(): string
    {
        return $this->source;
    }
}
