<?php
declare(strict_types=1);

namespace Core\Main\Domain\Filter;

class AnswersFilter
{
    /**
     * @var null|string
     */
    protected $user;

    /**
     * @var null|string
     */
    protected $unit;

    /**
     * AnswersFilter constructor.
     * @param null|string $user
     * @param null|string $unit
     */
    public function __construct(?string $user, ?string $unit)
    {
        $this->user = $user;
        $this->unit = $unit;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
