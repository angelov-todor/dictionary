<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\DomainEventSubscriber;

use Core\Main\Application\Service\Metadata\AddSourceMetadataRequest;
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
     * @var ApplicationService
     */
    protected $addSourceMetadataService;

    /**
     * ImageMetadataCreatedSubscriber constructor.
     * @param ApplicationService $generateMetadataService
     * @param ApplicationService $addSourceMetadataService
     */
    public function __construct(
        ApplicationService $generateMetadataService,
        ApplicationService $addSourceMetadataService
    ) {
        $this->generateMetadataService = $generateMetadataService;
        $this->addSourceMetadataService = $addSourceMetadataService;
    }

    /**
     * @param ImageCreated $aDomainEvent
     */
    public function handle($aDomainEvent)
    {
        $this->generateMetadataService->execute(
            new GenerateMetadataRequest($aDomainEvent->getImageId())
        );

        if ($aDomainEvent->getSource()) {
            $this->addSourceMetadataService->execute(
                new AddSourceMetadataRequest($aDomainEvent->getImageId(), $aDomainEvent->getSource())
            );
        }
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
