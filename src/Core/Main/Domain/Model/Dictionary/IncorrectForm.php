<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

class IncorrectForm
{
    /**
     * @var int The id of this word.
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
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return IncorrectForm
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set correctWord
     * @param Word $correctWord
     * @return IncorrectForm
     */
    public function setCorrectWord(Word $correctWord = null)
    {
        $this->correctWord = $correctWord;

        return $this;
    }

    /**
     * Get correctWord
     * @return Word
     */
    public function getCorrectWord()
    {
        return $this->correctWord;
    }
}
