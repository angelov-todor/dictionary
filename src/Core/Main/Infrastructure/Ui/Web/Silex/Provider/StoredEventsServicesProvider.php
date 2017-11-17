<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Application\Service\StoredEvent\ViewStoredEventsService;
use Ddd\Application\Service\TransactionalApplicationService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class StoredEventsServicesProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {

        $app[ViewStoredEventsService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new ViewStoredEventsService(
                    $app['event_store']
                ),
                $app['tx_session']
            );
        };
    }
}
