<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\User\NotifyUserValidationRequest;
use Core\Main\Domain\Model\User\UserCreated;
use Core\Main\Domain\Model\User\UserEmailChanged;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEvent;

class UserValidateEmailSubscriber
{

    /**
     * @var string[]
     */
    private $events = [UserCreated::class, UserEmailChanged::class];

    /**
     * @var ApplicationService
     */
    protected $service;

    /**
     * UserValidateEmailSubscriber constructor.
     * @param ApplicationService $service
     */
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
     * @param UserCreated|UserEmailChanged $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
//        $this->getService()->execute(
//            new NotifyUserValidationRequest(
//                $aDomainEvent->getId()
//            )
//        );
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent): bool
    {
        foreach ($this->events as $className) {
            if (is_a($aDomainEvent, $className)) {
                return true;
            }
        }
        return false;
    }
}
