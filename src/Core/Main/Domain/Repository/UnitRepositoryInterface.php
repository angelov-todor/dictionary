<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Unit\Unit;

interface UnitRepositoryInterface
{
    /**
     * @return int
     */
    public function countBy(): int;

    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function viewBy(int $page, int $limit);

    /**
     * @param Unit $unit
     * @return Unit
     */
    public function add(Unit $unit): Unit;

    /**
     * @param Unit $unit
     * @return Unit
     */
    public function update(Unit $unit): Unit;

    /**
     * @param string $id
     * @return Unit|null
     */
    public function ofId(string $id): ?Unit;

    /**
     * @param Unit $unit
     */
    public function remove(Unit $unit): void;
}
