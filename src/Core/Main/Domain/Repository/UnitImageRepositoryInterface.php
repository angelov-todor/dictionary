<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Unit\UnitImage;

interface UnitImageRepositoryInterface
{
    /**
     * @param UnitImage $unitImage
     * @return UnitImage
     */
    public function add(UnitImage $unitImage): UnitImage;

    /**
     * @param UnitImage $unitImage
     */
    public function remove(UnitImage $unitImage): void;
}
