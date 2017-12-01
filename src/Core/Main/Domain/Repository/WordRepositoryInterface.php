<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

interface WordRepositoryInterface
{
    /**
     * @param null|string $string
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function viewBy(?string $string, int $page, int $limit): array;

    /**
     * @param null|string $string
     * @return int
     */
    public function countBy(?string $string): int;
}
