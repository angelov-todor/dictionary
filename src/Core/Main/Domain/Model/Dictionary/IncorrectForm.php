<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

class IncorrectForm
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Word
     */
    private $correctWord;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return IncorrectForm
     */
    public function setName($name): IncorrectForm
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Word $correctWord
     * @return IncorrectForm
     */
    public function setCorrectWord(Word $correctWord = null): IncorrectForm
    {
        $this->correctWord = $correctWord;
        return $this;
    }

    /**
     * Get correctWord
     * @return Word
     */
    public function getCorrectWord(): ?Word
    {
        return $this->correctWord;
    }
}
