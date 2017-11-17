<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

interface StoredEventRepositoryInterface
{
    /**
     * @return int
     */
    public function findOfCount(): int;

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findOf(int $limit, int $offset): array;
}
