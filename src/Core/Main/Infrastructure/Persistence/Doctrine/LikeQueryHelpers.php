<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine;

trait LikeQueryHelpers
{
    /**
     * @param $search
     * @param string $pattern
     * @return string
     */
    protected function makeLikeParam($search, $pattern = '%%%s%%')
    {
        /**
         * Function defined in-line so it doesn't show up for type-hinting on
         * classes that implement this trait.
         *
         * Makes a string safe for use in an SQL LIKE search query by escaping all
         * special characters with special meaning when used in a LIKE query.
         *
         * Uses ! character as default escape character because \ character in
         * Doctrine/DQL had trouble accepting it as a single \ and instead kept
         * trying to escape it as "\\". Resulted in DQL parse errors about "Escape
         * character must be 1 character"
         *
         * % = match 0 or more characters
         * _ = match 1 character
         *
         * Examples:
         *      gloves_pink   becomes  gloves!_pink
         *      gloves%pink   becomes  gloves!%pink
         *      glo_ves%pink  becomes  glo!_ves!%pink
         *
         * @param string $search
         * @return string
         */
        $sanitizeLikeValue = function ($search) {
            $escapeChar = '!';
            $escape = [
                '\\' . $escapeChar, // Must escape the escape-character for regex
                '\%',
                '\_',
            ];
            $pattern = sprintf('/([%s])/', implode('', $escape));
            return preg_replace($pattern, $escapeChar . '$0', $search);
        };
        return sprintf($pattern, $sanitizeLikeValue($search));
    }
}
