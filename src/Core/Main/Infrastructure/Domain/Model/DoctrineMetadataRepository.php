<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model;

use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\LikeQueryHelpers;
use Doctrine\ORM\EntityRepository;

class DoctrineMetadataRepository extends EntityRepository implements MetadataRepositoryInterface
{
    use LikeQueryHelpers;

    /**
     * @param Metadata $metadata
     * @return Metadata
     */
    public function add(Metadata $metadata): Metadata
    {
        $this->getEntityManager()->persist($metadata);
        $this->getEntityManager()->flush();

        return $metadata;
    }

    public function remove(Metadata $metadata): void
    {
        $this->getEntityManager()->remove($metadata);
        $this->getEntityManager()->flush();
    }

    public function viewBy(?string $string, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('m')
            ->where("m.name LIKE :term ESCAPE '!'")
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

    /**
     * @param string $name
     * @return null|Metadata
     */
    public function byName(string $name): ?Metadata
    {
        /** @var Metadata $metadata */
        $metadata = $this->findOneBy(['name' => $name]);
        return $metadata;
    }
}
