<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\Timeline\CreateTimelineRequest;
use Core\Main\Domain\Model\User\UserCreated;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventSubscriber;

class UserCreatedSubscriber implements DomainEventSubscriber
{
    /**
     * @var ApplicationService
     */
    protected $service;

    /**
     * UserCreatedSubscriber constructor.
     * @param ApplicationService $service
     */
    public function __construct(ApplicationService $service)
    {
        $this->service = $service;
    }

    /**
     * @param DomainEvent $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        /* @var $aDomainEvent UserCreated */
        $this->service->execute(new CreateTimelineRequest(
            $aDomainEvent->getId()
        ));
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return is_a($aDomainEvent, UserCreated::class);
    }
}
