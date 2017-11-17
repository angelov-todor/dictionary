<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\Timeline\AddReminderToTimelineRequest;
use Core\Main\Application\Service\Timeline\AddReminderToTimelineService;
use Core\Main\Domain\Model\Timeline\ReminderSent;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventSubscriber;

class ReminderSentSubscriber implements DomainEventSubscriber
{
    /**
     * @var AddReminderToTimelineService
     */
    protected $addReminderToTimelineService;

    /**
     * ReminderSentSubscriber constructor.
     *
     * @param ApplicationService $addReminderToTimelineService
     */
    public function __construct(ApplicationService $addReminderToTimelineService)
    {
        $this->addReminderToTimelineService = $addReminderToTimelineService;
    }

    /**
     * @return ApplicationService
     */
    protected function getAddReminderToTimelineService(): ApplicationService
    {
        return $this->addReminderToTimelineService;
    }

    /**
     * @param ReminderSent $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        $this->getAddReminderToTimelineService()->execute(
            new AddReminderToTimelineRequest(
                $aDomainEvent->getTenantName(),
                $aDomainEvent->getTenantEmail(),
                $aDomainEvent->getAmount(),
                $aDomainEvent->getCurrency(),
                $aDomainEvent->getPropertyName(),
                $aDomainEvent->getPropertyId(),
                $aDomainEvent->getUnitName(),
                $aDomainEvent->getUnitId(),
                $aDomainEvent->getLeaseId(),
                $aDomainEvent->getLandlordId()
            )
        );
    }

    /**
     * @param DomainEvent $aDomainEvent
     *
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return is_a($aDomainEvent, ReminderSent::class);
    }
}
