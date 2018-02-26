<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

/**
 * Metadata
 */
class Metadata
{
    const TYPE_NUMBER = 'number';
    const TYPE_TEXT = 'text';
    const TYPE_BOOLEAN = 'bool';

    protected static $types = [
        self::TYPE_NUMBER,
        self::TYPE_TEXT,
        self::TYPE_BOOLEAN
    ];

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = self::TYPE_TEXT;

    /**
     * @var Metadata
     */
    protected $parent;

    /**
     * @var Metadata[]
     */
    protected $children;

    /**
     * @var string
     */
    protected $values;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Metadata
     */
    public function setName(string $name): Metadata
    {
        $this->name = $name;
        return $this;
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
     * @return Metadata
     */
    public function setType(string $type): Metadata
    {
        if (!in_array($type, self::$types)) {
            throw new \InvalidArgumentException('Invalid type given for metadata');
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @return null|Metadata
     */
    public function getParent(): ?Metadata
    {
        return $this->parent;
    }

    /**
     * @param Metadata $parent
     * @return Metadata
     */
    public function setParent(?Metadata $parent): Metadata
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getValues(): ?string
    {
        return $this->values;
    }

    /**
     * @param null|string $values
     * @return Metadata
     */
    public function setValues(?string $values): Metadata
    {
        $this->values = $values;
        return $this;
    }
}
