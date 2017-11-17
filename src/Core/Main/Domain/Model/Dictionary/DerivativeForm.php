<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

use Doctrine\ORM\Mapping as ORM;

class DerivativeForm
{
    /**
     * @var int The id of this word.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $nameBroken;

    /**
     * @var string
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $nameCondensed;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isInfinitive;

    /**
     * @var Word
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="derivativeForms")
     * @ORM\JoinColumn(name="base_word_id", referencedColumnName="id")
     */
    private $baseWord;


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
     * @return DerivativeForm
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
     * Set nameBroken
     *
     * @param string $nameBroken
     * @return DerivativeForm
     */
    public function setNameBroken($nameBroken)
    {
        $this->nameBroken = $nameBroken;

        return $this;
    }

    /**
     * Get nameBroken
     * @return string
     */
    public function getNameBroken()
    {
        return $this->nameBroken;
    }

    /**
     * Set nameCondensed
     *
     * @param string $nameCondensed
     * @return DerivativeForm
     */
    public function setNameCondensed($nameCondensed)
    {
        $this->nameCondensed = $nameCondensed;

        return $this;
    }

    /**
     * Get nameCondensed
     * @return string
     */
    public function getNameCondensed()
    {
        return $this->nameCondensed;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return DerivativeForm
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isInfinitive
     *
     * @param boolean $isInfinitive
     * @return DerivativeForm
     */
    public function setIsInfinitive($isInfinitive)
    {
        $this->isInfinitive = $isInfinitive;

        return $this;
    }

    /**
     * Get isInfinitive
     * @return boolean
     */
    public function getIsInfinitive()
    {
        return $this->isInfinitive;
    }

    /**
     * Set baseWord
     *
     * @param Word $baseWord
     * @return DerivativeForm
     */
    public function setBaseWord(Word $baseWord = null)
    {
        $this->baseWord = $baseWord;

        return $this;
    }

    /**
     * Get baseWord
     *
     * @return Word
     */
    public function getBaseWord()
    {
        return $this->baseWord;
    }
}
