<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

/**
 * ImageMetadata
 */
class ImageMetadata
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Image
     */
    protected $image;

    /**
     *
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $value;

    /**
     * @return Metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return ImageMetadata
     */
    public function setValue(string $value): ImageMetadata
    {
        $this->value = $value;
        return $this;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): ImageMetadata
    {
        $this->image = $image;
        return $this;
    }

    public function setMetadata(Metadata $metadata): ImageMetadata
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
