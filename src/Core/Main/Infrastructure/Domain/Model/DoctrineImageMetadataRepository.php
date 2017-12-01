<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineImageMetadataRepository extends EntityRepository implements ImageMetadataRepositoryInterface
{
    /**
     * ${@inheritdoc}
     */
    public function add(ImageMetadata $imageMetadata): ImageMetadata
    {
        $this->getEntityManager()->persist($imageMetadata);
        $this->getEntityManager()->flush($imageMetadata);
        return $imageMetadata;
    }

    /**
     * ${@inheritdoc}
     */
    public function remove(ImageMetadata $imageMetadata): void
    {
        $this->getEntityManager()->remove($imageMetadata);
        $this->getEntityManager()->flush($imageMetadata);
    }
}
