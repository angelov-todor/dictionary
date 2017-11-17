<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails;

use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException\ProblemDetailsFromExceptionInterface;
use Caridea\Http\ProblemDetails;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This is a service provider hooked to the exception events that returns a response with Problem Details JSON Object
 *
 * @package Core\Main\Infrastructure\Ui\ProblemDetails
 */
class ProblemDetailsProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    /**
     * @var Container
     */
    protected $app;

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
        $app['problem.details.exception.map'] = function () {
            return new Container(
                include __DIR__ . '/problemDetailsFromExceptionMap.php'
            );
        };

        $this->app = $app;
    }

    /**
     * Subscribe to the event when an exception is being thrown
     *
     * @param Container $app
     * @param EventDispatcherInterface $dispatcher
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(
            KernelEvents::EXCEPTION,
            function (GetResponseForExceptionEvent $exceptionEvent) {
                $exception = $exceptionEvent->getException();

                $problemDetailsObject = $this->problemDetailsFromException($exception);

                if ($problemDetailsObject->getStatus() >= 500) {
                    Rollbar::log(Level::ERROR, $exception);
                }
                $response = new Response(
                    $problemDetailsObject,
                    $problemDetailsObject->getStatus(),
                    ['Content-type' => ProblemDetails::MIME_TYPE_JSON]
                );
                $exceptionEvent->setResponse($response);
            },
            1000
        );
    }

    /**
     * Converts an exception to problem details using a map of factories by interface
     * @param \Exception $exception
     * @return ProblemDetails
     */
    protected function problemDetailsFromException(\Exception $exception): ProblemDetails
    {
        /* @var $problemDetailsObject ProblemDetails */
        $problemDetailsObject = null;

        /* @var $problemDetailsExceptionMapContainer Container */
        $problemDetailsExceptionMapContainer = $this->app['problem.details.exception.map'];

        foreach ($problemDetailsExceptionMapContainer->keys() as $exceptionClassName) {
            if (is_a($exception, $exceptionClassName)) {
                /* @var $problemDetailsConverter ProblemDetailsFromExceptionInterface */
                $problemDetailsConverter = $problemDetailsExceptionMapContainer[$exceptionClassName];

                $problemDetailsObject = $problemDetailsConverter(
                    $exception,
                    $this->app
                );
                break;
            }
        }

        if (!$problemDetailsObject) {
            // It would be a good place to throw an exception here and leave it to the Silex to handle that,
            // but this may trigger circular exception handling indefinite chain
            $problemDetailsObject = new ProblemDetails(
                null,
                'Unhandled exception occurred!',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }

        $problemDetailsObject = $this->addExceptionInfoAsExtensionsToProblemDetails($problemDetailsObject, $exception);

        return $problemDetailsObject;
    }

    /**
     * Decorate the problem details with additional exception info if in debug mode
     * @param ProblemDetails $problemDetailsObject
     * @param \Exception $exception
     * @return ProblemDetails
     */
    protected function addExceptionInfoAsExtensionsToProblemDetails(
        ProblemDetails $problemDetailsObject,
        \Exception $exception
    ): ProblemDetails {
        if ($this->app['debug']) {
            $extensionsWithExceptionInfo = array_replace(
                $problemDetailsObject->getExtensions(),
                [
                    'exceptionType' => get_class($exception),
                    'exceptionCode' => $exception->getCode(),
                    'exceptionStacktrace' => $exception->getTraceAsString()
                ]
            );

            $problemDetailsObject = new ProblemDetails(
                $problemDetailsObject->getType(),
                $problemDetailsObject->getTitle(),
                $problemDetailsObject->getStatus(),
                $problemDetailsObject->getDetail(),
                $problemDetailsObject->getInstance(),
                $extensionsWithExceptionInfo
            );
        }

        return $problemDetailsObject;
    }
}
