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
     * @var CognitiveType[]
     */
    protected $cognitiveTypes;

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

    /**
     * @param CognitiveType $cognitiveType
     * @return CognitiveSkill
     */
    public function addCognitiveType(CognitiveType $cognitiveType): CognitiveSkill
    {
        $this->cognitiveTypes[] = $cognitiveType;
        return $this;
    }

    /**
     * @param CognitiveType $cognitiveType
     * @return CognitiveSkill
     */
    public function removeCognitiveType(CognitiveType $cognitiveType): CognitiveSkill
    {
        foreach ($this->cognitiveTypes as $k => $u) {
            if ($cognitiveType->getId() == $u->getId()) {
                unset($this->cognitiveTypes[$k]);
                break;
            }
        }

        return $this;
    }

    /**
     * @return CognitiveType[]
     */
    public function getCognitiveTypes()
    {
        return $this->cognitiveTypes;
    }
}
