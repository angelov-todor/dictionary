<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\ImageMetadata;

interface ImageMetadataRepositoryInterface
{
    /**
     * @param ImageMetadata $imageMetadata
     * @return ImageMetadata
     */
    public function add(ImageMetadata $imageMetadata): ImageMetadata;

    /**
     * @param ImageMetadata $imageMetadata
     */
    public function remove(ImageMetadata $imageMetadata): void;
}
