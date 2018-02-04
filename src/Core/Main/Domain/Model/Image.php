<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

use Core\Main\Domain\Model\User\User;

class Image
{
    const IMAGE_LOCATION = 'assets';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var User
     */
    protected $creator;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ImageMetadata[]
     */
    protected $imageMetadata = [];

    /**
     * Image constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->createdAt = new \DateTime();
        $this->setCreator($user);
    }

    /**
     * @param User $creator
     * @return Image
     */
    public function setCreator(User $creator): Image
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return ImageMetadata[]
     */
    public function getImageMetadata()
    {
        return $this->imageMetadata;
    }
}
