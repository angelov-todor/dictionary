<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

class Reduction extends Phonemes
{
    /**
     * Table map for reduction
     * positions:
     *          0 -> after stressed vowel
     *          1 -> 1st before stressed
     *          2 -> 2nd before stressed
     * @var array
     */
    protected $reductionTable = [
        'а' => ['Ə', '^', 'Ə'],
        'ъ' => ['Ə', '^', 'Ə'],
        'о' => ['ö', 'ọ', 'ö'],
        'у' => ['у', 'ö', 'У']
    ];

    /**
     * The vowel reduction algorithm
     *
     * @param string $word The word to make reduction to
     * @param boolean $asArray Should return as array
     * @return string|array The word with vowel reduction. FALSE if word not found in dictionary.
     */
    public function reduct($word, $asArray = false)
    {
        $wordObj = $this->findWord($word);
        if (!$wordObj) {
            return false;
        }

        $this->setCharacteristic($wordObj['characteristic']);

        $withStress = $wordObj['word'];
        $chars = $this->toArray($withStress);

        $stressedPosition = $this->findStressedPosition($chars);

        $vowels = [];
        //  find all vowels in the word
        foreach ($chars as $p => $char) {
            if ($this->isVowel($char)) {
                $vowels[$p] = $char;
            }
        }
        $keys = array_keys($vowels);
        $toTransform = [];
        $i = 0;
        for ($i = 0; $i < count($keys); $i++) {
            if ($keys[$i] == $stressedPosition) {
                continue;
            }
            //  position -2
            if (isset($keys[$i + 2]) && $keys[$i + 2] == $stressedPosition) {
                $toTransform[2] = $keys[$i];
                continue;
            }
            //  position -1
            if (isset($keys[$i + 1]) && $keys[$i + 1] == $stressedPosition) {
                $toTransform[1] = $keys[$i];
                continue;
            }
            //  position +1
            if (isset($keys[$i - 1]) && $keys[$i - 1] == $stressedPosition) {
                $toTransform[0] = $keys[$i];
                continue;
            }
        }
        $transformed = $this->toPhonemes($wordObj['normalized'], true);

        foreach ($toTransform as $position => $key) {
            if (!isset($transformed[$key])) {
                echo sprintf(
                    "ERROR: Check this word: %s with phonemes: %s",
                    $word,
                    print_r($transformed, true)
                ), PHP_EOL;
                continue;
            }
            $transformed[$key] = $this->transform($position, $transformed[$key]);
        }

        if ($asArray) {
            return $transformed;
        }

        return implode('', $transformed);
    }

    /**
     * Map the character trough the map with the given position.
     * If not found returns the character itself unmodified.
     *
     * @param int $position The character position
     * @param string $char The character
     * @return string
     */
    protected function transform($position, $char)
    {
        if (false === array_key_exists($char, $this->reductionTable)) {
            return $char;
        }
        $map = $this->reductionTable[$char];
        return $map[$position];
    }
}
