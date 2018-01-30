<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Test;

use Ramsey\Uuid\Uuid;

class CognitiveType
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|CognitiveType
     */
    protected $parent;

    /**
     * CognitiveType constructor.
     * @param string $id
     * @param string $name
     * @param CognitiveType|null $parent
     */
    public function __construct(?string $id, string $name, ?CognitiveType $parent)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return CognitiveType|null
     */
    public function getParent(): ?CognitiveType
    {
        return $this->parent;
    }

    /**
     * @param string $name
     * @return CognitiveType
     */
    public function setName(string $name): CognitiveType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param CognitiveType|null $parent
     * @return CognitiveType
     */
    public function setParent(?CognitiveType $parent): CognitiveType
    {
        $this->parent = $parent;
        return $this;
    }
}
