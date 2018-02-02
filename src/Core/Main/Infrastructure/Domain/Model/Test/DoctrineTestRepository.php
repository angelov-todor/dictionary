<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Test;

use Core\Main\Domain\Model\Test\Test;
use Core\Main\Domain\Repository\TestRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineTestRepository extends EntityRepository implements TestRepositoryInterface
{
    /**
     * @return int
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

        $qb = $this->createQueryBuilder('c')
            ->setMaxResults($limit)
            ->orderBy('c.name', Criteria::ASC)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Test $test
     * @return Test
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(Test $test): Test
    {
        $this->getEntityManager()->persist($test);
        $this->getEntityManager()->flush($test);
        return $test;
    }

    /**
     * @param Test $test
     * @return Test
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Test $test): Test
    {
        $this->getEntityManager()->flush($test);
        return $test;
    }

    /**
     * @param string $id
     * @return Test|null
     */
    public function ofId(string $id): ?Test
    {
        /** @var Test $test */
        $test = $this->find($id);
        return $test;
    }

    /**
     * @param Test $test
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Test $test): void
    {
        $this->getEntityManager()->remove($test);
        $this->getEntityManager()->flush($test);
    }
}
