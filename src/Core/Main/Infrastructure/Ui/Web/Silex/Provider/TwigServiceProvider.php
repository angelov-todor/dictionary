<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class TwigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * We don use the built-in TwigServiceProvider because it has some issues with security provider
     * only when used in CLI context
     */
    public function register(Container $app)
    {
        $app['twig'] = function () use ($app) {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Templates');
            $twig = new \Twig_Environment($loader, array(
                'cache' => __DIR__ . '/../../../../../../../../var/cache/twig',
            ));

            if (isset($app['translator'])) {
                $twig->addExtension(new TranslationExtension($app['translator']));
            }

            return $twig;
        };
    }
}
