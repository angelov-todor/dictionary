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
     * @var string
     */
    protected $gradingScale;

    /**
     * @var null|int
     */
    protected $minAge;

    /**
     * @var null|int
     */
    protected $maxAge;

    /**
     * @var null|Methodology
     */
    protected $methodology;

    /**
     * @var null|int
     */
    protected $pointsRequired;

    /**
     * @var null|int
     */
    protected $timeToConduct;

    /**
     * @var null|string
     */
    protected $notes;

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
     * @param string $gradingScale
     * @param int|null $minAge
     * @param int|null $maxAge
     * @param Methodology|null $methodology
     * @param int|null $pointsRequired
     * @param int|null $timeToConduct
     * @param null|string $notes
     * @param User $creator
     */
    public function __construct(
        ?string $id,
        string $name,
        array $units,
        CognitiveSkill $cognitiveSkill,
        string $gradingScale,
        ?int $minAge,
        ?int $maxAge,
        ?Methodology $methodology,
        ?int $pointsRequired,
        ?int $timeToConduct,
        ?string $notes,
        User $creator
    ) {

        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->units = $units;
        $this->cognitiveSkill = $cognitiveSkill;
        $this->gradingScale = $gradingScale;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->methodology = $methodology;
        $this->pointsRequired = $pointsRequired;
        $this->timeToConduct = $timeToConduct;
        $this->notes = $notes;
        $this->creator = $creator;
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
     * @return Test
     */
    public function setName(string $name): Test
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Unit[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @param Unit[] $units
     * @return Test
     */
    public function setUnits(array $units): Test
    {
        $this->units = $units;
        return $this;
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

    /**
     * @return string
     */
    public function getGradingScale(): string
    {
        return $this->gradingScale;
    }

    /**
     * @param string $gradingScale
     * @return Test
     */
    public function setGradingScale(string $gradingScale): Test
    {
        $this->gradingScale = $gradingScale;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    /**
     * @param int|null $minAge
     * @return Test
     */
    public function setMinAge(?int $minAge): Test
    {
        $this->minAge = $minAge;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    /**
     * @param int|null $maxAge
     * @return Test
     */
    public function setMaxAge(?int $maxAge): Test
    {
        $this->maxAge = $maxAge;
        return $this;
    }

    /**
     * @return Methodology|null
     */
    public function getMethodology(): ?Methodology
    {
        return $this->methodology;
    }

    /**
     * @param Methodology|null $methodology
     * @return Test
     */
    public function setMethodology(?Methodology $methodology): Test
    {
        $this->methodology = $methodology;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPointsRequired(): ?int
    {
        return $this->pointsRequired;
    }

    /**
     * @param int|null $pointsRequired
     * @return Test
     */
    public function setPointsRequired(?int $pointsRequired): Test
    {
        $this->pointsRequired = $pointsRequired;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimeToConduct(): ?int
    {
        return $this->timeToConduct;
    }

    /**
     * @param int|null $timeToConduct
     * @return Test
     */
    public function setTimeToConduct(?int $timeToConduct): Test
    {
        $this->timeToConduct = $timeToConduct;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param null|string $notes
     * @return Test
     */
    public function setNotes(?string $notes): Test
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return Test
     */
    public function setCreator(User $creator): Test
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
