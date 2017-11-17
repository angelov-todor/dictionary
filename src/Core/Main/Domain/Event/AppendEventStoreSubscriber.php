<?php
declare(strict_types=1);

namespace Core\Main\Domain\Event;

use Ddd\Application\EventStore;
use Ddd\Domain\DomainEventSubscriber;
use Ddd\Domain\DomainEvent;

class AppendEventStoreSubscriber implements DomainEventSubscriber
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * AppendEventStoreSubscriber constructor.
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param DomainEvent $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        $this->eventStore->append($aDomainEvent);
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent): bool
    {
        return true;
    }
}
