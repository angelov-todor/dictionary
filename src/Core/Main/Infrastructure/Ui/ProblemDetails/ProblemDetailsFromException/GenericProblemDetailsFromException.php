<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException;

use Pimple\Container;
use Caridea\Http\ProblemDetails;

/**
 * Converts any Exception to generic ProblemDetails
 *
 * @package Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException
 */
class GenericProblemDetailsFromException implements ProblemDetailsFromExceptionInterface
{
    /**
     * @var array
     */
    protected $problemDetailsDefaults = [];

    /**
     * GenericProblemDetailsFromException constructor.
     * @param array $problemDetailsDefaults
     */
    public function __construct(array $problemDetailsDefaults = [])
    {
        $this->problemDetailsDefaults = $problemDetailsDefaults;
    }

    /**
     * @param \Exception $exception
     * @param Container $app
     * @return ProblemDetails
     */
    public function __invoke(\Exception $exception, Container $app): ProblemDetails
    {
        $problemDetailsOptions = array_replace_recursive(
            [
                'type' => null,
                'title' => null,
                'status' => 0,
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
