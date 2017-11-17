<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException;

use Core\Main\Application\Service\Payment\DisconnectedAccountException;
use Pimple\Container;
use Caridea\Http\ProblemDetails;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\Uri;

class DisconnectedAccountProblemDetails extends GenericProblemDetailsFromException
{
    /**
     * @param \Exception $exception
     * @param Container $app
     * @return ProblemDetails
     */
    public function __invoke(\Exception $exception, Container $app): ProblemDetails
    {
        /* @var $exception DisconnectedAccountException */
        $problemDetailsOptions = array_replace_recursive(
            [
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_CONFLICT],
                'status' => Response::HTTP_CONFLICT,
                'detail' => $exception->getMessage(),
                'instance' => null,
                'extensions' => [
                    'body' => [
                        'property' => $exception->getProperty(),
                        'unit' => $exception->getUnit(),
                        'month' => $exception->getPeriod()
                    ]
                ]
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
