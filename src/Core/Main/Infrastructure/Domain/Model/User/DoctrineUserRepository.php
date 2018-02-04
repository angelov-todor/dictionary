<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\User;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;

class DoctrineUserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function ofId(string $id): ?User
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    /**
     * {@inheritdoc}
     */
    public function ofEmail(string $name): ?User
    {
        return $this->findOneBy(['email' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        $this->getEntityManager()->flush($user);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function viewBy(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('m')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('m.email', Criteria::ASC);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countBy(): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m)');

        return intval($qb->getQuery()->getSingleScalarResult());
    }
}
