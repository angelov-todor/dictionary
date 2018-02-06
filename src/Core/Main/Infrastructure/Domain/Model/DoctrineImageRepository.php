<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

class DoctrineImageRepository extends EntityRepository implements ImageRepositoryInterface
{
    use LikeQueryHelpers;

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
     * @throws ORMException
     */
    public function add(Image $image): Image
    {
        $this->getEntityManager()->persist($image);
        $this->getEntityManager()->flush($image);
        return $image;
    }

    /**
     * @param Image $image
     * @throws ORMException
     */
    public function remove(Image $image): void
    {
        $this->getEntityManager()->remove($image);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Image
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getImageByCriteria(string $criteria): Image
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

    /**
     * @param int $page
     * @param int $limit
     * @param null|string $term
     * @return array
     */
    public function viewBy(int $page, int $limit, ?string $term): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('i')
            ->setMaxResults($limit)
            ->setFirstResult($offset);
        if ($term) {
            $qb->innerJoin('i.imageMetadata', 'im')
                ->where("im.value LIKE :term ESCAPE '!'")
                ->setParameter('term', $this->makeLikeParam($term));
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param null|string $term
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(?string $term): int
    {
        $qb = $this->createQueryBuilder('i')
            ->select('count(i)');
        if ($term) {
            $qb->innerJoin('i.imageMetadata', 'im')
                ->where("im.value LIKE :term ESCAPE '!'")
                ->setParameter('term', $this->makeLikeParam($term));
        }

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    /**
     * @param int $id
     * @return Image|null
     */
    public function ofId(int $id): ?Image
    {
        /** @var Image $image */
        $image = $this->find($id);
        return $image;
    }
}
