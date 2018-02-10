<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Answer;

use Core\Main\Domain\Filter\AnswersFilter;
use Core\Main\Domain\Model\Answer\Answer;
use Core\Main\Domain\Repository\AnswerRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;

class DoctrineAnswerRepository extends EntityRepository implements AnswerRepositoryInterface
{
    use LikeQueryHelpers;

    /**
     * @param string $testId
     * @param AnswersFilter $filter
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(string $testId, AnswersFilter $filter): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->where('a.test = :test')
            ->setParameter('test', $testId);

        $this->applyFilter($qb, $filter);

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    protected function applyFilter(QueryBuilder $qb, AnswersFilter $filter)
    {
        if ($filter->getUser()) {
            $qb->leftJoin('a.user', 'u')
                ->andWhere("u.email LIKE :user ESCAPE '!'")
                ->setParameter('user', $this->makeLikeParam($filter->getUser()));
        }
        if ($filter->getUnit()) {
            $qb->leftJoin('a.unit', 'un')
                ->andWhere("un.name LIKE :unit ESCAPE '!'")
                ->setParameter('unit', $this->makeLikeParam($filter->getUnit()));;
        }
    }

    /**
     * @param string $testId
     * @param int $page
     * @param int $limit
     * @param AnswersFilter $filter
     * @return mixed
     */
    public function viewBy(string $testId, int $page, int $limit, AnswersFilter $filter)
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('a')
            ->where('a.test = :test')
            ->setParameter('test', $testId)
            ->setMaxResults($limit)
            ->orderBy('a.user', Criteria::ASC)
            ->setFirstResult($offset);

        $this->applyFilter($qb, $filter);

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
