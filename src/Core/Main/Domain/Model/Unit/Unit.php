<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Unit;

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
     * @var Category[]
     */
    protected $categories;

    /**
     * Unit constructor.
     * @param null|string $id
     * @param string $text
     * @param int $rows
     * @param int $cols
     */
    public function __construct(?string $id, string $text, int $rows, int $cols)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->setText($text)
            ->setRows($rows)
            ->setCols($cols);
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
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     * @return Unit
     */
    public function setCategories(array $categories): Unit
    {
        $this->categories = $categories;
        return $this;
    }
}
