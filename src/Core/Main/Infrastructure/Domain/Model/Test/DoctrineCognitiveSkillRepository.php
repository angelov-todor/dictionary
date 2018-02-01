<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Test;

use Core\Main\Domain\Model\Test\CognitiveSkill;
use Core\Main\Domain\Repository\CognitiveSkillRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineCognitiveSkillRepository extends EntityRepository implements CognitiveSkillRepositoryInterface
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
     * @param CognitiveSkill $cognitiveSkill
     * @return CognitiveSkill
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(CognitiveSkill $cognitiveSkill): CognitiveSkill
    {
        $this->getEntityManager()->persist($cognitiveSkill);
        $this->getEntityManager()->flush($cognitiveSkill);
        return $cognitiveSkill;
    }

    /**
     * @param CognitiveSkill $cognitiveSkill
     * @return CognitiveSkill
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(CognitiveSkill $cognitiveSkill): CognitiveSkill
    {
        $this->getEntityManager()->flush($cognitiveSkill);
        return $cognitiveSkill;
    }

    /**
     * @param string $id
     * @return CognitiveSkill|null
     */
    public function ofId(string $id): ?CognitiveSkill
    {
        /** @var CognitiveSkill $cognitiveSkill */
        $cognitiveSkill = $this->find($id);
        return $cognitiveSkill;
    }

    /**
     * @param CognitiveSkill $cognitiveSkill
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(CognitiveSkill $cognitiveSkill): void
    {
        $this->getEntityManager()->remove($cognitiveSkill);
        $this->getEntityManager()->flush($cognitiveSkill);
    }
}
