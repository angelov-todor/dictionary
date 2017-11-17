<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\StoredEvent;

use Core\Main\Domain\Model\StoredEvent;

class ViewStoredEventsResponse
{
    /**
     * @var StoredEvent[]
     */
    protected $storedEvents;

    /**
     * @var int
     */
    protected $count;

    /**
     * ViewStoredEventsResponse constructor.
     * @param StoredEvent[] $storedEvents
     * @param int $count
     */
    public function __construct(array $storedEvents, $count)
    {
        $this->storedEvents = $storedEvents;
        $this->count = $count;
    }


    /**
     * @return StoredEvent[]
     */
    public function getStoredEvents()
    {
        return $this->storedEvents;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
