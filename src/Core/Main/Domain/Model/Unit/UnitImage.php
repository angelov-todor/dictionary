<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Unit;

use Core\Main\Domain\Model\Image;
use Ramsey\Uuid\Uuid;

class UnitImage
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var Image
     */
    protected $image;

    /**
     * @var Position
     */
    protected $position;

    /**
     * @var Unit
     */
    protected $unit;

    /**
     * @var bool
     */
    protected $isCorrect;

    /**
     * UnitImage constructor.
     * @param string $id
     * @param Image $image
     * @param Position $position
     * @param Unit $unit
     * @param bool $isCorrect
     */
    public function __construct(?string $id, Image $image, Position $position, Unit $unit, bool $isCorrect)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->image = $image;
        $this->position = $position;
        $this->unit = $unit;
        $this->isCorrect = $isCorrect;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return UnitImage
     */
    public function setId(string $id): UnitImage
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return UnitImage
     */
    public function setImage(Image $image): UnitImage
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @param Position $position
     * @return UnitImage
     */
    public function setPosition(Position $position): UnitImage
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool $isCorrect
     * @return UnitImage
     */
    public function setIsCorrect(bool $isCorrect): UnitImage
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }
}
