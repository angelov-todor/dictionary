<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="incorrect_form")
 */
class IncorrectForm
{
    /**
     * @var int The id of this word.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_word"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"get_word"})
     */
    private $name;

    /**
     * @var Word
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="incorrectForms")
     * @ORM\JoinColumn(name="correct_word_id", referencedColumnName="id")
     */
    private $correctWord;

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
     * @return IncorrectForm
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
     * Set correctWord
     *
     * @param Word $correctWord
     *
     * @return IncorrectForm
     */
    public function setCorrectWord(Word $correctWord = null)
    {
        $this->correctWord = $correctWord;

        return $this;
    }

    /**
     * Get correctWord
     *
     * @return Word
     */
    public function getCorrectWord()
    {
        return $this->correctWord;
    }
}
