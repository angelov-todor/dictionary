<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Dictionary\Dictionary;
use Core\Main\Domain\Repository\DictionaryRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\ORM\EntityRepository;

class DoctrineDictionaryRepository extends EntityRepository implements DictionaryRepositoryInterface
{
    use LikeQueryHelpers;

    public function viewBy(?string $string, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('m')
            ->where("m.word LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string))
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function countBy(?string $string): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->where("m.word LIKE :term ESCAPE '!'")
            ->setParameter('term', $this->makeLikeParam($string));

        return intval($qb->getQuery()->getSingleScalarResult());
    }

    /**
     * @param string $name
     * @return null|Dictionary
     */
    public function byName(string $name): ?Dictionary
    {
        /** @var Dictionary $metadata */
        $metadata = $this->findOneBy(['word' => $name]);
        return $metadata;
    }
}
