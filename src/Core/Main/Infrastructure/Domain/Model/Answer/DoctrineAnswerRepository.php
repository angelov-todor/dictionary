<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Answer;

use Core\Main\Domain\Model\Answer\Answer;
use Core\Main\Domain\Repository\AnswerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineAnswerRepository extends EntityRepository implements AnswerRepositoryInterface
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
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Answer $answer
     * @return Answer
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function add(Answer $answer): Answer
    {
        $this->getEntityManager()->persist($answer);
        $this->getEntityManager()->flush($answer);
        return $answer;
    }

    /**
     * @param Answer $answer
     * @return Answer
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(Answer $answer): Answer
    {
        $this->getEntityManager()->flush($answer);
        return $answer;
    }

    /**
     * @param string $id
     * @return Answer|null
     */
    public function ofId(string $id): ?Answer
    {
        /** @var Answer $answer */
        $answer = $this->find($id);
        return $answer;
    }

    /**
     * @param Answer $answer
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Answer $answer): void
    {
        $this->getEntityManager()->remove($answer);
        $this->getEntityManager()->flush($answer);
    }
}