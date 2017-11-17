<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Dictionary
{
    /**
     * @var int The id of this word.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="ID")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $word;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $characteristic;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $rhymeform;

    /**
     * @var string
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $normalized;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @param string $word
     * @return Dictionary
     */
    public function setWord(string $word): Dictionary
    {
        $this->word = $word;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharacteristic(): string
    {
        return $this->characteristic;
    }

    /**
     * @param string $characteristic
     * @return Dictionary
     */
    public function setCharacteristic(string $characteristic): Dictionary
    {
        $this->characteristic = $characteristic;
        return $this;
    }

    /**
     * @return string
     */
    public function getRhymeform(): string
    {
        return $this->rhymeform;
    }

    /**
     * @param string $rhymeform
     * @return Dictionary
     */
    public function setRhymeform(string $rhymeform): Dictionary
    {
        $this->rhymeform = $rhymeform;
        return $this;
    }

    /**
     * @return string
     */
    public function getNormalized(): string
    {
        return $this->normalized;
    }

    /**
     * @param string $normalized
     * @return Dictionary
     */
    public function setNormalized(string $normalized): Dictionary
    {
        $this->normalized = $normalized;
        return $this;
    }
}
