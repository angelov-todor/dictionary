<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Unit;

use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Repository\UnitRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

class DoctrineUnitRepository extends EntityRepository implements UnitRepositoryInterface
{
    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(): int
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u)');

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    public function viewBy(int $page, int $limit)
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Unit $unit
     * @return Unit
     * @throws OptimisticLockException
     */
    public function add(Unit $unit): Unit
    {
        $this->getEntityManager()->persist($unit);
        $this->getEntityManager()->flush($unit);
        return $unit;
    }

    /**
     * @param Unit $unit
     * @return Unit
     * @throws OptimisticLockException
     */
    public function update(Unit $unit): Unit
    {
        $this->getEntityManager()->flush($unit);
        return $unit;
    }

    /**
     * @param string $id
     * @return Unit|null
     */
    public function ofId(string $id): ?Unit
    {
        /** @var Unit $unit */
        $unit = $this->find($id);
        return $unit;
    }

    /**
     * @param Unit $unit
     * @throws OptimisticLockException
     */
    public function remove(Unit $unit): void
    {
        $this->getEntityManager()->remove($unit);
        $this->getEntityManager()->flush($unit);
    }
}
