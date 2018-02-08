<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Answer;

class SelectAnswer implements AnswerInterface
{
    /**
     * @var string
     */
    protected $unitImageId;

    /**
     * @var bool
     */
    protected $isCorrect;

    /**
     * @var string
     */
    protected $type = Answer::TYPE_SELECT;

    /**
     * SelectAnswer constructor.
     * @param string $unitImageId
     * @param bool $isCorrect
     */
    public function __construct(string $unitImageId, bool $isCorrect)
    {
        $this->unitImageId = $unitImageId;
        $this->isCorrect = $isCorrect;
    }

    /**
     * @return string
     */
    public function getUnitImageId(): string
    {
        return $this->unitImageId;
    }

    /**
     * @param string $unitImageId
     * @return SelectAnswer
     */
    public function setUnitImageId(string $unitImageId): SelectAnswer
    {
        $this->unitImageId = $unitImageId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool $isCorrect
     * @return SelectAnswer
     */
    public function setIsCorrect(bool $isCorrect): SelectAnswer
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }
}
