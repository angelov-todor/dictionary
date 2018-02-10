<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Filter\AnswersFilter;
use Core\Main\Domain\Model\Answer\Answer;

interface AnswerRepositoryInterface
{
    /**
     * @param string $testId
     * @param int $page
     * @param int $limit
     * @param AnswersFilter $filter
     * @return mixed
     */
    public function viewBy(string $testId, int $page, int $limit, AnswersFilter $filter);

    /**
     * @param string $testId
     * @param AnswersFilter $filter
     * @return int
     */
    public function countBy(string $testId, AnswersFilter $filter): int;

    /**
     * @param string $id
     * @return Answer|null
     */
    public function ofId(string $id): ?Answer;

    /**
     * @param Answer $answer
     * @return Answer
     */
    public function add(Answer $answer): Answer;

    /**
     * @param Answer $answer
     * @return Answer
     */
    public function update(Answer $answer): Answer;

    /**
     * @param Answer $answer
     */
    public function remove(Answer $answer): void;
}
