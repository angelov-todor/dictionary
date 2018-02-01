<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Test;

use Core\Main\Domain\Model\Test\Methodology;
use Core\Main\Domain\Repository\MethodologyRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineMethodologyRepository extends EntityRepository implements MethodologyRepositoryInterface
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
     * @param Methodology $methodology
     * @return Methodology
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(Methodology $methodology): Methodology
    {
        $this->getEntityManager()->persist($methodology);
        $this->getEntityManager()->flush($methodology);
        return $methodology;
    }

    /**
     * @param Methodology $methodology
     * @return Methodology
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Methodology $methodology): Methodology
    {
        $this->getEntityManager()->flush($methodology);
        return $methodology;
    }

    /**
     * @param string $id
     * @return Methodology|null
     */
    public function ofId(string $id): ?Methodology
    {
        /** @var Methodology $methodology */
        $methodology = $this->find($id);
        return $methodology;
    }

    /**
     * @param Methodology $methodology
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Methodology $methodology): void
    {
        $this->getEntityManager()->remove($methodology);
        $this->getEntityManager()->flush($methodology);
    }
}
