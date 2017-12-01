<?php
declare(strict_types=1);

namespace Core\Main\Application\Service;

use Core\Main\Application\Service\Word\AdapterInterface;

abstract class Word
{
    /**
     * @var string Default character encoding
     */
    protected $characterEncoding = 'UTF-8';

    /**
     * @var AdapterInterface
     */
    protected $adapterInterface = null;

    /**
     * @param $char
     * @return mixed
     */
    abstract protected function isVowel($char);

    /**
     * Find the stressed vowel within word presented as array
     * @param array $chars The characters of the word
     * @return int The position of the stressed vowel. FALSE if no stressed vowel found.
     */
    protected function findStressedPosition(array $chars)
    {

        foreach ($chars as $position => $char) {
            if (!$this->isVowel($char)) {
                continue;
            }
            $toLower = mb_strtolower($char, $this->characterEncoding);

            if ($char === $toLower) {
                continue;
            }
            return $position;
        }
        return false;
    }

    /**
     * @return AdapterInterface
     * @throws \Exception When no adapter set
     */
    protected function getAdapter()
    {
        if (null == $this->adapterInterface) {
            throw new \Exception('No adapter set');
        }
        return $this->adapterInterface;
    }

    /**
     * @param AdapterInterface $adapter
     * @return Word
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapterInterface = $adapter;
        return $this;
    }

    /**
     * Finds word characteristic from the words table
     * If none found returns empty string
     *
     * @param string $word
     * @return array|bool The word array from the database. FALSE if the word was not found.
     *
     * @throws \Exception When no adapter set
     */
    public function findWord($word)
    {
        $item = mb_strtolower($word, $this->characterEncoding);

        $result = $this->getAdapter()->findWord($item);

        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * Finds word characteristic from the words table
     * If none found returns empty string
     *
     * @param string $word The word to lookup in the database
     * @return string The characteristic of the word. FALSE if word not found.
     *
     * @throws \Exception When no adapter set
     */
    public function findCharacteristic($word)
    {
        $wordArr = $this->findWord($word);
        if (!$wordArr) {
            return false;
        }

        $characteristic = '';
        if (isset($wordArr['characteristic'])) {
            $characteristic = $wordArr['characteristic'];
        }

        return $characteristic;
    }

    /**
     * Make word as array. Optional to merge special chars (default=true)
     *
     * @param string $word
     * @param boolean $mergeSpecial Whether to merge the special chars as one.('дж','дз')
     * @return array
     */
    protected function toArray($word, $mergeSpecial = true)
    {
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
        if (!$mergeSpecial) {
            return $chars;
        }
        $merged = [];
        $buffer = '';
        //  запазва 'дз' и 'дж' като една буква
        foreach ($chars as $char) {
            if ($char == 'д') {
                $buffer = $char;
                continue;
            }
            if (!empty($buffer) && ($char != "ж" && $char != "з")) {
                $merged[] = $buffer;
                $buffer = '';
            }
            if (!empty($buffer) && ($char == "з" || $char == "ж")) {
                $char = $buffer . $char;
                $buffer = '';
            }
            $merged[] = $char;
        }

        if (!empty($buffer)) {
            $merged[] = $buffer;
        }

        return $merged;
    }

    /**
     * Return word lowecased
     *
     * @param string $word
     * @return string
     */
    protected function toLowerCase($word)
    {
        return mb_strtolower($word, $this->characterEncoding);
    }
}
