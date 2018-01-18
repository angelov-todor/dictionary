<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

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
     * @throws OptimisticLockException
     */
    public function add(Image $image): Image
    {
        $this->getEntityManager()->persist($image);
        $this->getEntityManager()->flush($image);
        return $image;
    }

    /**
     * @param Image $image
     * @throws OptimisticLockException
     */
    public function remove(Image $image): void
    {
        $this->getEntityManager()->remove($image);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Image
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRandomImage(): Image
    {
        $qb = $this->createQueryBuilder('i')
            ->select('count(i)');
        $limit = 1;
        $count = intval($qb->getQuery()->getSingleScalarResult());

        $rand = rand(1, $count - 1);

        $offset = ($rand - 1) * $limit;

        $qb = $this->createQueryBuilder('i')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $images = $qb->getQuery()->getResult();

        return $images[0];
    }
}
