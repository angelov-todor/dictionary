<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException;

use Pimple\Container;
use Caridea\Http\ProblemDetails;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\Uri;

/**
 * Class ProblemDetailsFromAssertionException
 * @package Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException
 */
class ProblemDetailsFromAssertionException extends GenericProblemDetailsFromException
{
    /**
     * @param \Exception $exception
     * @param Container $app
     * @return ProblemDetails
     */
    public function __invoke(\Exception $exception, Container $app): ProblemDetails
    {
        /* @var $exception \Assert\AssertionFailedException */
        return new ProblemDetails(
            new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
            Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'The request was well formed but was unable to be followed due to semantic errors',
            null,
            [
                'validationMessages' => [
                    $exception->getPropertyPath() => [
                        'message' => $exception->getMessage()
                    ]
                ]
            ]
        );
    }
}
