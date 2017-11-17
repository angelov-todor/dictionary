<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Rollbar;

use Caridea\Http\ProblemDetails;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class RollbarProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     * @return void
     */
    public function register(Container $app): void
    {
        if (isset($app['exception-handler-register']) &&
            $app['exception-handler-register'] === false
        ) {
            //  do not register if explicitly told so
            return;
        }

        $excHandler = ExceptionHandler::register($app['debug']);
        $env = $app['app-config']['environment'] ?? 'development';
        $config = array_replace_recursive([
            'access_token' => null,
            'environment' => $env
        ], $app['app-config']['rollbar']);

        if (null == $config['access_token']) {
            return;
        }

        Rollbar::init(
            $config,
            false,
            false,
            false
        );
        $excHandler->setHandler([$this, 'handler']);
    }

    /**
     * @param \Throwable $exception
     */
    public function handler(\Throwable $exception)
    {
        if (!headers_sent()) {
            header(sprintf('HTTP/1.0 %s', Response::HTTP_INTERNAL_SERVER_ERROR));
            header(sprintf('Content-Type: application/json', ProblemDetails::MIME_TYPE_JSON));
        }
        Rollbar::log(Level::EMERGENCY, $exception);
        $pd = new ProblemDetails(
            null,
            'Unhandled exception occurred!',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $exception->getMessage()
        );

        echo $pd;
    }
}
