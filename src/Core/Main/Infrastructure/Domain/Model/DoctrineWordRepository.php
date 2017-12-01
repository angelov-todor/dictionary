<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Repository\WordRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\ORM\EntityRepository;

class DoctrineWordRepository extends EntityRepository implements WordRepositoryInterface
{
    use LikeQueryHelpers;

    public function viewBy(?string $string, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('w')
            ->where("w.name LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string))
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function countBy(?string $string): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where("m.name LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string));

        return intval($qb->getQuery()->getSingleScalarResult());
    }
}
