<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Test\Test;

interface TestRepositoryInterface
{
    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function viewBy(int $page, int $limit);

    /**
     * @return int
     */
    public function countBy(): int;

    /**
     * @param string $id
     * @return Test|null
     */
    public function ofId(string $id): ?Test;

    /**
     * @param Test $test
     * @return Test
     */
    public function add(Test $test): Test;

    /**
     * @param Test $test
     * @return Test
     */
    public function update(Test $test): Test;

    /**
     * @param Test $test
     */
    public function remove(Test $test): void;
}
