<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Unit;

use Core\Main\Domain\Model\Unit\UnitImage;
use Core\Main\Domain\Repository\UnitImageRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

class DoctrineUnitImageRepository extends EntityRepository implements UnitImageRepositoryInterface
{
    /**
     * @param UnitImage $unitImage
     * @return UnitImage
     * @throws OptimisticLockException
     */
    public function add(UnitImage $unitImage): UnitImage
    {
        $this->getEntityManager()->persist($unitImage);
        $this->getEntityManager()->flush($unitImage);
        return $unitImage;
    }

    /**
     * @param UnitImage $unitImage
     * @throws OptimisticLockException
     */
    public function remove(UnitImage $unitImage): void
    {
        $this->getEntityManager()->remove($unitImage);
        $this->getEntityManager()->flush($unitImage);
    }
}
