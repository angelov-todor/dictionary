<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Test;

use Ramsey\Uuid\Uuid;

class CognitiveSkill
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
     * CognitiveSkill constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(?string $id, string $name)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
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
     * @param string $name
     * @return CognitiveSkill
     */
    public function setName(string $name): CognitiveSkill
    {
        $this->name = $name;
        return $this;
    }
}
