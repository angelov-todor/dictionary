<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Application\Service\Metadata\GenerateAdditionalMetadataRequest;
use Core\Main\Application\Service\Metadata\GenerateAdditionalMetadataService;
use Core\Main\Domain\Model\ImageMetadataAdded;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventSubscriber;

class ImageMetadataAddedSubscriber implements DomainEventSubscriber
{
    /**
     * @var GenerateAdditionalMetadataService
     */
    protected $generateAdditionalMetadataService;

    /**
     * ImageMetadataAddedSubscriber constructor.
     * @param ApplicationService $generateAdditionalMetadataService
     */
    public function __construct(ApplicationService $generateAdditionalMetadataService)
    {
        $this->generateAdditionalMetadataService = $generateAdditionalMetadataService;
    }

    /**
     * @param ImageMetadataAdded $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        if ($aDomainEvent->getMetadataName() == 'Description') {
            try {
                $this->generateAdditionalMetadataService->execute(
                    new GenerateAdditionalMetadataRequest(
                        $aDomainEvent->getImageId(),
                        $aDomainEvent->getImageMetadataValue()
                    )
                );
            } catch (ResourceNotFoundException $e) {
                // no op
            }
        }
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return is_a($aDomainEvent, ImageMetadataAdded::class);
    }
}
