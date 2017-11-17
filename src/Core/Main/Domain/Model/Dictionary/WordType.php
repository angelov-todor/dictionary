<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

class WordType
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $rules;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $rulesTest;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $exampleWord;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return WordType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set idiNumber
     *
     * @param integer $idiNumber
     *
     * @return WordType
     */
    public function setIdiNumber($idiNumber)
    {
        $this->idiNumber = $idiNumber;

        return $this;
    }

    /**
     * Get idiNumber
     *
     * @return integer
     */
    public function getIdiNumber()
    {
        return $this->idiNumber;
    }

    /**
     * Set speechPart
     *
     * @param string $speechPart
     *
     * @return WordType
     */
    public function setSpeechPart($speechPart)
    {
        $this->speechPart = $speechPart;

        return $this;
    }

    /**
     * Get speechPart
     *
     * @return string
     */
    public function getSpeechPart()
    {
        return $this->speechPart;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return WordType
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set rules
     *
     * @param string $rules
     *
     * @return WordType
     */
    public function setRules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Get rules
     *
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set rulesTest
     *
     * @param string $rulesTest
     *
     * @return WordType
     */
    public function setRulesTest($rulesTest)
    {
        $this->rulesTest = $rulesTest;

        return $this;
    }

    /**
     * Get rulesTest
     *
     * @return string
     */
    public function getRulesTest()
    {
        return $this->rulesTest;
    }

    /**
     * Set exampleWord
     *
     * @param string $exampleWord
     *
     * @return WordType
     */
    public function setExampleWord($exampleWord)
    {
        $this->exampleWord = $exampleWord;

        return $this;
    }

    /**
     * Get exampleWord
     *
     * @return string
     */
    public function getExampleWord()
    {
        return $this->exampleWord;
    }
}
