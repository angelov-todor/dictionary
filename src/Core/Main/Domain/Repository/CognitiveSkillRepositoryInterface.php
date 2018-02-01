<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\Test\CognitiveSkill;

interface CognitiveSkillRepositoryInterface
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
     * @return CognitiveSkill|null
     */
    public function ofId(string $id): ?CognitiveSkill;

    /**
     * @param CognitiveSkill $cognitiveSkill
     * @return CognitiveSkill
     */
    public function add(CognitiveSkill $cognitiveSkill): CognitiveSkill;

    /**
     * @param CognitiveSkill $cognitiveSkill
     * @return CognitiveSkill
     */
    public function update(CognitiveSkill $cognitiveSkill): CognitiveSkill;

    /**
     * @param CognitiveSkill $cognitiveSkill
     */
    public function remove(CognitiveSkill $cognitiveSkill): void;
}
