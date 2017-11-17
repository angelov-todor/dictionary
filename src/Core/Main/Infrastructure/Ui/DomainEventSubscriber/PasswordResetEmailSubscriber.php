<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\User\NotifyPasswordResetLinkRequest;
use Core\Main\Domain\Model\User\PasswordReseted;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventSubscriber;

class PasswordResetEmailSubscriber implements DomainEventSubscriber
{

    /**
     * @var ApplicationService
     */
    protected $service;


    public function __construct(ApplicationService $service)
    {
        $this->service = $service;
    }

    /**
     * @return ApplicationService
     */
    public function getService(): ApplicationService
    {
        return $this->service;
    }

    /**
     * @param PasswordReseted $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        $this->getService()->execute(
            new NotifyPasswordResetLinkRequest(
                $aDomainEvent->getId()
            )
        );
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return is_a($aDomainEvent, PasswordReseted::class);
    }
}
