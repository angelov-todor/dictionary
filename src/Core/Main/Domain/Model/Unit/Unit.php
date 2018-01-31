<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Unit;

use Core\Main\Domain\Model\Test\CognitiveType;
use Ramsey\Uuid\Uuid;

class Unit
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var int
     */
    protected $cols;

    /**
     * @var int
     */
    protected $rows;

    /**
     * @var UnitImage[]
     */
    protected $unitImages;

    /**
     * @var CognitiveType
     */
    protected $cognitiveType;

    /**
     * @var null|CognitiveType
     */
    protected $cognitiveSubtype;

    /**
     * Unit constructor.
     * @param null|string $id
     * @param string $text
     * @param int $rows
     * @param int $cols
     * @param CognitiveType $cognitiveType
     */
    public function __construct(?string $id, string $text, int $rows, int $cols, CognitiveType $cognitiveType)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->setText($text)
            ->setRows($rows)
            ->setCols($cols)
            ->setCognitiveType($cognitiveType);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Unit
     */
    public function setText(string $text): Unit
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return int
     */
    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * @param int $cols
     * @return Unit
     */
    public function setCols(int $cols): Unit
    {
        $this->cols = $cols;
        return $this;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @return Unit
     */
    public function setRows(int $rows): Unit
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return UnitImage[]
     */
    public function getUnitImages()
    {
        return $this->unitImages;
    }

    /**
     * @param UnitImage[] $unitImages
     * @return Unit
     */
    public function setUnitImages(array $unitImages): Unit
    {
        $this->unitImages = $unitImages;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return CognitiveType
     */
    public function getCognitiveType(): CognitiveType
    {
        return $this->cognitiveType;
    }

    /**
     * @return CognitiveType|null
     */
    public function getCognitiveSubtype(): ?CognitiveType
    {
        return $this->cognitiveSubtype;
    }

    /**
     * @param CognitiveType $cognitiveType
     * @return Unit
     */
    public function setCognitiveType(CognitiveType $cognitiveType): Unit
    {
        $this->cognitiveType = $cognitiveType;
        return $this;
    }

    /**
     * @param CognitiveType|null $cognitiveSubtype
     * @return Unit
     */
    public function setCognitiveSubtype(?CognitiveType $cognitiveSubtype): Unit
    {
        $this->cognitiveSubtype = $cognitiveSubtype;
        return $this;
    }
}
