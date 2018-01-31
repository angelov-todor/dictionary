<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Domain\Model\Test\CognitiveType;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TestServicesProvider implements ServiceProviderInterface
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
        $app[CognitiveTypeRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(CognitiveType::class);
        };
    }
}
