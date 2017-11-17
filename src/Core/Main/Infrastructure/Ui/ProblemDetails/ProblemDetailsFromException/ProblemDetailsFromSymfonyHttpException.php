<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException;

use Pimple\Container;
use Caridea\Http\ProblemDetails;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\Uri;

/**
 * Converts a Symfony HttpExceptionInterface to ProblemDetails
 *
 * @package Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException
 */
class ProblemDetailsFromSymfonyHttpException extends GenericProblemDetailsFromException
{
    /**
     * @param \Exception $exception
     * @param Container $app
     * @return ProblemDetails
     */
    public function __invoke(\Exception $exception, Container $app): ProblemDetails
    {
        /* @var $exception HttpExceptionInterface */
        $problemDetailsOptions = array_replace_recursive(
            [
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[$exception->getStatusCode()],
                'status' => $exception->getStatusCode(),
                'detail' => $exception->getMessage(),
                'instance' => null,
                'extensions' => []
            ],
            $this->problemDetailsDefaults
        );

        return new ProblemDetails(
            $problemDetailsOptions['type'],
            $problemDetailsOptions['title'],
            $problemDetailsOptions['status'],
            $problemDetailsOptions['detail'],
            $problemDetailsOptions['instance'],
            $problemDetailsOptions['extensions']
        );
    }
}
