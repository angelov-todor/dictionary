<?php
declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\Uri;
use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException\ProblemDetailsFromExceptionInterface;
use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException\GenericProblemDetailsFromException;
use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException\ProblemDetailsFromSymfonyHttpException;
use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsFromException\ProblemDetailsFromAssertionException;

return [
    \Core\Main\Application\Exception\ResourceNotFoundExceptionInterface::class
    => function (): ProblemDetailsFromExceptionInterface {
        return new GenericProblemDetailsFromException([
            'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
            'title' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            'status' => Response::HTTP_NOT_FOUND
        ]);
    },

    \Core\Main\Application\Exception\ResourceConflictExceptionInterface::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new GenericProblemDetailsFromException([
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_CONFLICT],
                'status' => Response::HTTP_CONFLICT
            ]);
        },

    \Core\Main\Application\Exception\RequestUnprocessableExceptionInterface::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new GenericProblemDetailsFromException([
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        },

    \Core\Main\Application\Exception\BadRequestExceptionInterface::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new GenericProblemDetailsFromException([
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                'status' => Response::HTTP_BAD_REQUEST
            ]);
        },

    // JWT and Symfony security common parent AuthenticationException class
    \Symfony\Component\Security\Core\Exception\AuthenticationException::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new GenericProblemDetailsFromException([
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                'status' => Response::HTTP_UNAUTHORIZED,
                'detail' => 'The request requires user authentication'
            ]);
        },

    \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new ProblemDetailsFromSymfonyHttpException();
        },

    \Assert\AssertionFailedException::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new ProblemDetailsFromAssertionException();
        },

    Core\Main\Infrastructure\Ui\Web\Silex\Encoders\DecodeProblemException::class
    => function (): ProblemDetailsFromExceptionInterface {
        return new GenericProblemDetailsFromException([
            'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
            'title' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            'status' => Response::HTTP_NOT_FOUND,
            'detail' => 'Resource not found'
        ]);
    },
    \Symfony\Component\Security\Core\Exception\AccessDeniedException::class
    => function (): ProblemDetailsFromExceptionInterface {
        return new GenericProblemDetailsFromException([
            'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
            'title' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
            'status' => Response::HTTP_UNAUTHORIZED,
            'detail' => 'Access denied.'
        ]);
    },
    // All other exceptions and errors
    \Throwable::class =>
        function (): ProblemDetailsFromExceptionInterface {
            return new GenericProblemDetailsFromException([
                'type' => new Uri('http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'),
                'title' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }

];
