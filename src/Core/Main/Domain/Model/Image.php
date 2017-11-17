<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

class Image
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var ImageMetadata[]
     */
    protected $imageMetadata = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return Image
     */
    public function setSrc(string $src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @param array $meta
     * @return Image
     */
    public function addImageMetadata($meta): Image
    {
        foreach ($meta as $iMeta) {
            $this->imageMetadata[] = $iMeta;
        }
        return $this;
    }

    /**
     * @param ImageMetadata $meta
     * @return Image
     */
    public function addMetadata(ImageMetadata $meta): Image
    {
        $this->imageMetadata[] = $meta;
        return $this;
    }

    /**
     * @param array $meta
     * @return Image
     */
    public function removeImageMetadata($meta): Image
    {
        foreach ($meta as $iMeta) {
            $this->imageMetadata->removeElement($iMeta);
        }
        return $this;
    }

    /**
     * @return ImageMetadata[]
     */
    public function getImageMetadata()
    {
        return $this->imageMetadata;
    }
}
