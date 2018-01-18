<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Domain\Model\Unit\Category;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Model\Unit\UnitImage;
use Core\Main\Domain\Repository\CategoryRepositoryInterface;
use Core\Main\Domain\Repository\UnitImageRepositoryInterface;
use Core\Main\Domain\Repository\UnitRepositoryInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UnitServicesProvider implements ServiceProviderInterface
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
        $app[UnitRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Unit::class);
        };

        $app[CategoryRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Category::class);
        };
        $app[UnitImageRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(UnitImage::class);
        };
    }
}
