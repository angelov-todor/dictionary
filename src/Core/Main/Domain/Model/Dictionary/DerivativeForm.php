<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

class DerivativeForm
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
     * @var string
     */
    private $nameBroken;

    /**
     * @var string
     */
    private $nameCondensed;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $isInfinitive;

    /**
     * @var Word
     */
    private $baseWord;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return DerivativeForm
     */
    public function setName($name): DerivativeForm
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
     * @param string $nameBroken
     * @return DerivativeForm
     */
    public function setNameBroken($nameBroken): DerivativeForm
    {
        $this->nameBroken = $nameBroken;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameBroken()
    {
        return $this->nameBroken;
    }

    /**
     * @param string $nameCondensed
     * @return DerivativeForm
     */
    public function setNameCondensed($nameCondensed): DerivativeForm
    {
        $this->nameCondensed = $nameCondensed;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameCondensed()
    {
        return $this->nameCondensed;
    }

    /**
     * @param string $description
     * @return DerivativeForm
     */
    public function setDescription($description): DerivativeForm
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param boolean $isInfinitive
     * @return DerivativeForm
     */
    public function setIsInfinitive($isInfinitive): DerivativeForm
    {
        $this->isInfinitive = $isInfinitive;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsInfinitive()
    {
        return $this->isInfinitive;
    }

    /**
     * @param Word $baseWord
     * @return DerivativeForm
     */
    public function setBaseWord(Word $baseWord = null): DerivativeForm
    {
        $this->baseWord = $baseWord;
        return $this;
    }

    /**
     * @return Word
     */
    public function getBaseWord()
    {
        return $this->baseWord;
    }
}
