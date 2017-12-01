<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Word;

interface AdapterInterface
{
    /**
     * @param string $word
     * @return mixed
     */
    public function findWord($word);
}
