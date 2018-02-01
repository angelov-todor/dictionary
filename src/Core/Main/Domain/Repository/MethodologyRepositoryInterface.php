<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Test\Methodology;

interface MethodologyRepositoryInterface
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
     * @return Methodology|null
     */
    public function ofId(string $id): ?Methodology;

    /**
     * @param Methodology $methodology
     * @return Methodology
     */
    public function add(Methodology $methodology): Methodology;

    /**
     * @param Methodology $methodology
     * @return Methodology
     */
    public function update(Methodology $methodology): Methodology;

    /**
     * @param Methodology $methodology
     */
    public function remove(Methodology $methodology): void;
}
