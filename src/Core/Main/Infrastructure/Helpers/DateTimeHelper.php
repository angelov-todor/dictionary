<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Helpers;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DateTimeHelper
{
    protected static $dateFormat = 'Y-m-d';

    /**
     * @param Request $request
     * @param string $key
     * @param bool $nullable
     * @return \DateTime|null
     * @throws InvalidArgumentException
     */
    public static function createDateFromRequest(
        Request $request,
        string $key,
        bool $nullable = false
    ): ?\DateTime {
        $date = self::getDate($request, $key, $nullable);
        if (null === $date) {
            return $date;
        }
        return $date;
    }

    /**
     * @param Request $request
     * @param string $key
     * @param bool $nullable
     * @return \DateTime|null
     * @throws InvalidArgumentException
     */
    protected static function getDate(
        Request $request,
        string $key,
        bool $nullable = false
    ): ?\DateTime {
        $validFormats = ['Y-m-d\TH:i:s.uP', \DateTime::ATOM];
        $value = $request->get($key, null);
        if (null === $value && $nullable) {
            return null;
        }

        Assertion::string($value, "Parameter `$key` is not a valid ISO8601 datetime string", $key);
        foreach ($validFormats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date) {
                return $date;
            }
        }

        throw new InvalidArgumentException(
            "Parameter `$key` is not a valid ISO8601 datetime string",
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $key,
            $value
        );
    }
}
