<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

class Rhymeform
{
    /**
     * Check if the character is the stressed in word
     * @param string $mb_char
     * @return boolean
     */
    private static function checkStressed($mb_char)
    {
        switch ($mb_char) {
            case 'А':
            case 'Е':
            case 'И':
            case 'О':
            case 'У':
            case 'Ъ':
            case 'Я':
            case 'Ю':
                $result = true;
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * Find words rhymeform
     * @param string $word UTF-8 string
     * @return string The rhymeform of the word
     */
    public static function findRhymeform($word)
    {
        $stressPosition = -1;
        $chrArray = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);

        $wordLength = count($chrArray);
        foreach ($chrArray as $index => $char) {
            if (true == self::checkStressed($char)) {
                $stressPosition = $index;
                break;
            }
        }
        // no stress char is found in the word set the first as default stress char
        if ($stressPosition == -1) {
            $stressPosition = 0;
        }
        $stressPosition++;

        $length = $wordLength - $stressPosition + 1;
        $start = $stressPosition - 1;
        $rhymeForm = null;
        if ($stressPosition < $wordLength) {
            $rhymeForm = self::cutRhymeform($chrArray, $start, $length);
        }
        if ($stressPosition == $wordLength) {
            if ($stressPosition == 1) {
                $rhymeForm = self::cutRhymeform($chrArray, $start, $length);
            } else {
                $rhymeForm = self::cutRhymeform($chrArray, $wordLength - 2, 2);
            }
        }
        return $rhymeForm;
    }

    private static function cutRhymeform($wordArray, $start, $length)
    {
        $result = '';
        foreach ($wordArray as $index => $char) {
            if ($index >= $start && $index < $start + $length) {
                $result .= $char;
            }
        }
        return $result;
    }
}
