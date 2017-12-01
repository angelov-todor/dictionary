<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

class ImageMetadata
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Image
     */
    protected $image;

    /**
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

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return ImageMetadata
     */
    public function setImage(Image $image): ImageMetadata
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param Metadata $metadata
     * @return ImageMetadata
     */
    public function setMetadata(Metadata $metadata): ImageMetadata
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
