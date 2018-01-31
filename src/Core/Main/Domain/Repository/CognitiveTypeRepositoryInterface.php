<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Test\CognitiveType;

interface CognitiveTypeRepositoryInterface
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
     * @return CognitiveType|null
     */
    public function ofId(string $id): ?CognitiveType;

    /**
     * @param CognitiveType $cognitiveType
     * @return CognitiveType
     */
    public function add(CognitiveType $cognitiveType): CognitiveType;

    /**
     * @param CognitiveType $cognitiveType
     * @return CognitiveType
     */
    public function update(CognitiveType $cognitiveType): CognitiveType;

    /**
     * @param CognitiveType $cognitiveType
     */
    public function remove(CognitiveType $cognitiveType): void;
}
