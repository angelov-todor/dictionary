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

    /**
     * @param string $id
     * @return UnitImage|null
     */
    public function ofId(string $id): ? UnitImage;

    /**
     * @param UnitImage $unitImage
     * @return UnitImage
     */
    public function update(UnitImage $unitImage): UnitImage;
}
