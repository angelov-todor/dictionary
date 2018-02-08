<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Answer;

class MultiSelectAnswer implements AnswerInterface
{
    /**
     * @var SelectAnswer[]
     */
    protected $selectAnswers;

    /**
     * @var string
     */
    protected $type = Answer::TYPE_MULTI_SELECT;

    /**
     * MultiSelectAnswer constructor.
     * @param SelectAnswer[] $selectAnswers
     */
    public function __construct(array $selectAnswers)
    {
        $this->selectAnswers = $selectAnswers;
    }

}
