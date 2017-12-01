<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

class WordType
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
     * @var int
     */
    private $idiNumber;

    /**
     * @var string
     */
    private $speechPart;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $rules;

    /**
     * @var string
     */
    private $rulesTest;

    /**
     * @var string
     */
    private $exampleWord;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return WordType
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
     * @param integer $idiNumber
     * @return WordType
     */
    public function setIdiNumber($idiNumber)
    {
        $this->idiNumber = $idiNumber;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdiNumber()
    {
        return $this->idiNumber;
    }

    /**
     * @param string $speechPart
     * @return WordType
     */
    public function setSpeechPart($speechPart)
    {
        $this->speechPart = $speechPart;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpeechPart()
    {
        return $this->speechPart;
    }

    /**
     * @param string $comment
     * @return WordType
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $rules
     * @return WordType
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param string $rulesTest
     * @return WordType
     */
    public function setRulesTest($rulesTest)
    {
        $this->rulesTest = $rulesTest;
        return $this;
    }

    /**
     * @return string
     */
    public function getRulesTest()
    {
        return $this->rulesTest;
    }

    /**
     * @param string $exampleWord
     * @return WordType
     */
    public function setExampleWord($exampleWord)
    {
        $this->exampleWord = $exampleWord;
        return $this;
    }

    /**
     * @return string
     */
    public function getExampleWord()
    {
        return $this->exampleWord;
    }
}
