<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Unit;

use Core\Main\Domain\Model\Test\Test;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Repository\UnitRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

class DoctrineUnitRepository extends EntityRepository implements UnitRepositoryInterface
{
    /**
     * @param null|Test $test
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(?Test $test): int
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u)');
        if ($test) {
            $qb->where('u.cognitiveType IN (:cognitiveTypes)')
                ->setParameter('cognitiveTypes', $test->getCognitiveSkill()->getCognitiveTypes());

            if (count($test->getUnits())) {
                $qb->andWhere('u NOT IN (:units)')
                    ->setParameter('units', $test->getUnits());
            }
        }

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    /**
     * @param int $page
     * @param int $limit
     * @param null|Test $test
     * @return array
     */
    public function viewBy(int $page, int $limit, ?Test $test)
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('u')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if ($test) {
            $qb->where('u.cognitiveType IN (:cognitiveTypes)')
                ->setParameter('cognitiveTypes', $test->getCognitiveSkill()->getCognitiveTypes());

            if (count($test->getUnits())) {
                $qb->andWhere('u NOT IN (:units)')
                    ->setParameter('units', $test->getUnits());
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Unit $unit
     * @return Unit
     * @throws ORMException
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
     * @throws ORMException
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
     * @throws ORMException
     */
    public function remove(Unit $unit): void
    {
        $this->getEntityManager()->remove($unit);
        $this->getEntityManager()->flush($unit);
    }

    /**
     * @param Test $test
     * @param int $count
     * @return Unit[]
     */
    public function getRandomByTest(Test $test, int $count)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.cognitiveType IN (:cognitiveTypes)')
            ->setMaxResults($count)
            ->setParameter('cognitiveTypes', $test->getCognitiveSkill()->getCognitiveTypes());

        if (count($test->getUnits())) {
            $qb->andWhere('u NOT IN (:units)')
                ->setParameter('units', $test->getUnits());
        }
        $units = $qb->getQuery()->getResult();

        return $units;
    }
}
