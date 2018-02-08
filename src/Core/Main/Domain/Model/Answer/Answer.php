<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Answer;

use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Model\User\User;
use Ramsey\Uuid\Uuid;

class Answer
{
    const TYPE_SELECT = 'select';
    const TYPE_MULTI_SELECT = 'multi_select';

    public static $types = [
        self::TYPE_SELECT,
        self::TYPE_MULTI_SELECT
    ];

    /**
     * @var array answer type to class mapping
     */
    public static $typeToClassMap = [
        self::TYPE_SELECT => SelectAnswer::class,
        self::TYPE_MULTI_SELECT => MultiSelectAnswer::class
    ];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Unit
     */
    protected $unit;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var AnswerInterface
     */
    protected $answer;

    /**
     * @var \DateTime
     */
    protected $occurredAt;

    /**
     * Answer constructor.
     * @param Unit $unit
     * @param User $user
     * @param AnswerInterface $answer
     */
    public function __construct(Unit $unit, User $user, AnswerInterface $answer)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->unit = $unit;
        $this->user = $user;
        $this->answer = $answer;
        $this->occurredAt = new \DateTime();
    }

    /**
     * @param Unit $unit
     * @param User $user
     * @param string $unitImageId
     * @param bool $isCorrect
     * @return Answer
     */
    public static function createSelectAnswer(
        Unit $unit,
        User $user,
        string $unitImageId,
        bool $isCorrect
    ) {
        $answerBody = new SelectAnswer($unitImageId, $isCorrect);
        $answer = new Answer($unit, $user, $answerBody);

        return $answer;
    }

    /**
     * @param Unit $unit
     * @param User $user
     * @param array $answers
     * @return Answer
     */
    public static function createMultiSelectAnswer(
        Unit $unit,
        User $user,
        array $answers
    ) {
        $selects = [];
        foreach ($answers as $answer) {
            $selects[] = new SelectAnswer($answer['unit_image_id'], $answer['is_correct']);
        }
        $answerBody = new MultiSelectAnswer($selects);

        return new Answer($unit, $user, $answerBody);
    }
}
