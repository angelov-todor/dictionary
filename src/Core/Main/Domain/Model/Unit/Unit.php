<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Unit;

use Assert\Assertion;
use Core\Main\Domain\Model\Test\CognitiveType;
use Ramsey\Uuid\Uuid;

class Unit
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_SHORT_ANSWER = 'short_answer';
    const TYPE_ESSAY = 'essay';
    const TYPE_MULTI_SELECT = 'multi_select';
    const TYPE_SELECT = 'select';

    /**
     * @var array Available types
     */
    protected $availableTypes = [
        self::TYPE_SELECT
    ];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

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
     * @var string
     */
    protected $type;

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
     * @var int
     */
    protected $timeToConduct = 0;

    /**
     * Unit constructor.
     * @param null|string $id
     * @param string $name
     * @param string $text
     * @param string $type
     * @param int $rows
     * @param int $cols
     * @param CognitiveType $cognitiveType
     * @param int $timeToConduct
     */
    public function __construct(
        ?string $id,
        string $name,
        string $text,
        string $type,
        int $rows,
        int $cols,
        CognitiveType $cognitiveType,
        int $timeToConduct
    ) {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->setName($name)
            ->setText($text)
            ->setType($type)
            ->setRows($rows)
            ->setCols($cols)
            ->setCognitiveType($cognitiveType)
            ->setTimeToConduct($timeToConduct);
    }

    /**
     * @param string $name
     * @return Unit
     */
    public function setName(string $name): Unit
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Unit
     */
    public function setType(string $type): Unit
    {
        Assertion::inArray($type, $this->availableTypes, null, 'type');
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeToConduct(): int
    {
        return $this->timeToConduct;
    }

    /**
     * @param int $timeToConduct
     * @return Unit
     */
    public function setTimeToConduct(int $timeToConduct): Unit
    {
        $this->timeToConduct = $timeToConduct;
        return $this;
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
