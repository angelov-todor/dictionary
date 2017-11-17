<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException;

use Pimple\Container;
use Caridea\Http\ProblemDetails;

/**
 * Interface for converters from Exception -> ProblemDetails
 *
 * @package Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException
 */
interface ProblemDetailsFromExceptionInterface
{
    /**
     * Converts an exception to problem details
     * @param \Exception $exception
     * @param Container $app
     * @return ProblemDetails
     */
    public function __invoke(\Exception $exception, Container $app): ProblemDetails;
}
