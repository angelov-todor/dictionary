<?php

declare(strict_types=1);

namespace Core\Main\Application\Helper;

class Util
{
    /**
     * @param string $name
     * @return string
     */
    public static function normalize(string $name): string
    {
        $lower = mb_strtolower($name);

        $normalized = preg_replace_callback('/(\d+)/', function ($matches) {
            return str_pad($matches[1], 8, '0', STR_PAD_LEFT);
        }, $lower);

        return $normalized;
    }
}
