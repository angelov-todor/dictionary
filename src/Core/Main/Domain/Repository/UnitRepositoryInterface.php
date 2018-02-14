<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Test\Test;
use Core\Main\Domain\Model\Unit\Unit;

interface UnitRepositoryInterface
{
    /**
     * @param null|Test $test
     * @return int
     */
    public function countBy(?Test $test): int;

    /**
     * @param int $page
     * @param int $limit
     * @param null|Test $test
     * @return array
     */
    public function viewBy(int $page, int $limit, ?Test $test);

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

    /**
     * @param Test $test
     * @param int $count
     * @return Unit[]
     */
    public function getRandomByTest(Test $test, int $count);
}
