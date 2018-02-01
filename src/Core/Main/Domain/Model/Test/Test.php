<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Test;

use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Model\User\User;
use Ramsey\Uuid\Uuid;

class Test
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
     * @var Unit[]
     */
    protected $units;

    /**
     * @var CognitiveSkill
     */
    protected $cognitiveSkill;

    /**
     * @var User
     */
    protected $creator;

    /**
     * Test constructor.
     * @param string $id
     * @param string $name
     * @param Unit[] $units
     * @param CognitiveSkill $cognitiveSkill
     * @param User $creator
     */
    public function __construct(?string $id, string $name, array $units, CognitiveSkill $cognitiveSkill, User $creator)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->units = $units;
        $this->creator = $creator;
        $this->cognitiveSkill = $cognitiveSkill;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Unit[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Unit $unit
     * @return Test
     */
    public function addUnit(Unit $unit): Test
    {
        $this->units[] = $unit;
        return $this;
    }

    /**
     * @return CognitiveSkill
     */
    public function getCognitiveSkill(): CognitiveSkill
    {
        return $this->cognitiveSkill;
    }

    /**
     * @param CognitiveSkill $cognitiveSkill
     * @return Test
     */
    public function setCognitiveSkill(CognitiveSkill $cognitiveSkill): Test
    {
        $this->cognitiveSkill = $cognitiveSkill;
        return $this;
    }
}
