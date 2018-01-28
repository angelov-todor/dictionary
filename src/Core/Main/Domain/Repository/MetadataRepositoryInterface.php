<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Metadata;

interface MetadataRepositoryInterface
{
    /**
     * @param Metadata $metadata
     * @return Metadata
     */
    public function add(Metadata $metadata): Metadata;

    /**
     * @param Metadata $metadata
     */
    public function remove(Metadata $metadata): void;

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

    /**
     * @param string $name
     * @return Metadata|null
     */
    public function byName(string $name): ?Metadata;

    /**
     * @param Metadata $metadata
     * @return Metadata
     */
    public function update(Metadata $metadata): Metadata;
}
