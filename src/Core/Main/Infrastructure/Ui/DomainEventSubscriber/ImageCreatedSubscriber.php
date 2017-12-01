<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\Metadata\GenerateMetadataRequest;
use Core\Main\Domain\Model\ImageCreated;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEventSubscriber;

class ImageCreatedSubscriber implements DomainEventSubscriber
{
    /**
     * @var ApplicationService
     */
    protected $generateMetadataService;

    /**
     * ImageMetadataCreatedSubscriber constructor.
     * @param ApplicationService $generateMetadataService
     */
    public function __construct(ApplicationService $generateMetadataService)
    {
        $this->generateMetadataService = $generateMetadataService;
    }

    /**
     * @param ImageCreated $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        $this->generateMetadataService->execute(
            new GenerateMetadataRequest($aDomainEvent->getImageId())
        );
    }

    /**
     * @param ImageCreated $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return is_a($aDomainEvent, ImageCreated::class);
    }
}
