<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\Unit;

class Position
{
    /**
     * @var int
     */
    protected $column;

    /**
     * @var int
     */
    protected $row;

    /**
     * Position constructor.
     * @param int $column
     * @param int $row
     */
    public function __construct(int $column, int $row)
    {
        $this->column = $column;
        $this->row = $row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }
}
