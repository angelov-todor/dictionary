<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ImagineServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        if (class_exists('\Gmagick')) {
            $app['imagine.driver'] = 'Gmagick';
        } elseif (class_exists('\Imagick')) {
            $app['imagine.driver'] = 'Imagick';
        } else {
            $app['imagine.driver'] = 'Gd';
        }
        $app['imagine'] = function (Container $app) {
            $classname = sprintf('\Imagine\%s\Imagine', $app['imagine.driver']);
            return new $classname;
        };
    }
}
