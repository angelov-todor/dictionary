<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Test;

use Core\Main\Domain\Model\Test\CognitiveType;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineCognitiveTypeRepository extends EntityRepository implements CognitiveTypeRepositoryInterface
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
     * @param CognitiveType $cognitiveType
     * @return CognitiveType
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(CognitiveType $cognitiveType): CognitiveType
    {
        $this->getEntityManager()->persist($cognitiveType);
        $this->getEntityManager()->flush($cognitiveType);
        return $cognitiveType;
    }

    /**
     * @param CognitiveType $cognitiveType
     * @return CognitiveType
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(CognitiveType $cognitiveType): CognitiveType
    {
        $this->getEntityManager()->flush($cognitiveType);
        return $cognitiveType;
    }

    /**
     * @param string $id
     * @return CognitiveType|null
     */
    public function ofId(string $id): ?CognitiveType
    {
        /** @var CognitiveType $cognitiveType */
        $cognitiveType = $this->find($id);
        return $cognitiveType;
    }

    /**
     * @param CognitiveType $cognitiveType
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(CognitiveType $cognitiveType): void
    {
        $this->getEntityManager()->remove($cognitiveType);
        $this->getEntityManager()->flush($cognitiveType);
    }
}
