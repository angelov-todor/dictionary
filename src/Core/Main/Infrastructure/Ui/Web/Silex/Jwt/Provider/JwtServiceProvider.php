<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Provider;

use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Service\JwtEncodeService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\JwtKey;
use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Listener\JwtListener;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['security.authentication_listener.factory.jwt'] = $app->protect(
            function ($name, $options) use ($app) {

                // merge provided options with the default ones
                $options = array_replace_recursive([
                    'life_time' => 86400,
                    'public_key' => '',
                    'private_key' => '',
                ], $options);

                $app['security.jwt.key'] = function () use ($app, $options) {
                    return JwtKey::createKeyPair(
                        $options['public_key'],
                        $options['private_key']
                    );
                };

                $app['security.jwt.encoder'] = function () use ($app, $options) {
                    return new JwtEncodeService($app['security.jwt.key'], $options['life_time']);
                };

                // define the authentication provider object
                $app['security.authentication_provider.' . $name . '.jwt'] = function () use ($app) {
                    return new JwtAuthenticationProvider(
                        $app[UserProviderInterface::class],
                        $app['security.user_checker']
                    );
                };

                // define the authentication listener object
                $app['security.authentication_listener.' . $name . '.jwt'] = function () use ($app, $options) {
                    return new JwtListener(
                        $app['security.token_storage'],
                        $app['security.authentication_manager'],
                        $app['security.jwt.encoder'],
                        $options
                    );
                };

                return [
                    // the authentication provider id
                    'security.authentication_provider.' . $name . '.jwt',
                    // the authentication listener id
                    'security.authentication_listener.' . $name . '.jwt',
                    // the entry point id
                    null,
                    // the position of the listener in the stack
                    'pre_auth',
                ];
            }
        );
    }
}
