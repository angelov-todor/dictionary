<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineImageRepository extends EntityRepository implements ImageRepositoryInterface
{

    /**
     * @return array
     */
    public function view()
    {
        return $this->findAll();
    }

    /**
     * @param Image $image
     * @return Image
     */
    public function add(Image $image): Image
    {
        $this->getEntityManager()->persist($image);
        $this->getEntityManager()->flush($image);
        return $image;
    }

    /**
     * @param Image $image
     */
    public function remove(Image $image): void
    {
        $this->getEntityManager()->remove($image);
        $this->getEntityManager()->flush();
    }
}
