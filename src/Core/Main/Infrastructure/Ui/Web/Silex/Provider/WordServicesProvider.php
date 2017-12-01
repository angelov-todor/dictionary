<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Application\Service\WordTools;
use Core\Main\Domain\Model\Dictionary\Dictionary;
use Core\Main\Domain\Model\Dictionary\Word;
use Core\Main\Domain\Repository\DictionaryRepositoryInterface;
use Core\Main\Domain\Repository\WordRepositoryInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class WordServicesProvider implements ServiceProviderInterface
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
        $app[WordRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Word::class);
        };

        $app[DictionaryRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Dictionary::class);
        };

        $app[WordTools::class] = function () use ($app) {
            return new WordTools($app['em']);
        };
    }
}
