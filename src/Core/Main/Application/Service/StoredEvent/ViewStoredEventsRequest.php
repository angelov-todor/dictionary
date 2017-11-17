<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\StoredEvent;

class ViewStoredEventsRequest
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * ViewStoredEventsRequest constructor.
     * @param int $limit
     * @param int $offset
     */
    public function __construct(int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
