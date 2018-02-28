<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineMetadataRepository extends EntityRepository implements MetadataRepositoryInterface
{
    use LikeQueryHelpers;

    /**
     * @param Metadata $metadata
     * @return Metadata
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(Metadata $metadata): Metadata
    {
        $this->getEntityManager()->persist($metadata);
        $this->getEntityManager()->flush();

        return $metadata;
    }

    /**
     * @param Metadata $metadata
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Metadata $metadata): void
    {
        $this->getEntityManager()->remove($metadata);
        $this->getEntityManager()->flush();
    }

    /**
     * @param null|string $string
     * @param int $page
     * @param int $limit
     * @param bool|int|null $parent
     * @return array
     */
    public function viewBy(?string $string, int $page, int $limit, $parent): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('m')
            ->where("m.name LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string))
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('m.name', Criteria::ASC);

        $isFalse = filter_var($parent, FILTER_VALIDATE_BOOLEAN);
        $isInt = filter_var($parent, FILTER_VALIDATE_INT);

        if (!is_null($parent)) {
            if (false === $isFalse) {
                $qb->andWhere('m.parent IS NULL');
            } elseif ($isInt) {
                $qb->andWhere('m.parent = :parent')
                    ->setParameter('parent', $parent);
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param null|string $string
     * @param null|int|bool $parent
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(?string $string, $parent): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where("m.name LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string));

        $isFalse = filter_var($parent, FILTER_VALIDATE_BOOLEAN);
        $isInt = filter_var($parent, FILTER_VALIDATE_INT);
        if (!is_null($parent)) {
            if (false === $isFalse) {
                $qb->andWhere('m.parent IS NULL');
            } elseif ($isInt) {
                $qb->andWhere('m.parent = :parent')
                    ->setParameter('parent', $parent);
            }
        }

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    /**
     * @param string $name
     * @return null|Metadata
     */
    public function byName(string $name): ?Metadata
    {
        /** @var Metadata $metadata */
        $metadata = $this->findOneBy(['name' => $name]);
        return $metadata;
    }

    /**
     * @param Metadata $metadata
     * @return Metadata
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Metadata $metadata): Metadata
    {
        $this->getEntityManager()->flush($metadata);
        return $metadata;
    }
}
