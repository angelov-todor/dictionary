<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

interface ChecksumInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
