<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

use Core\Main\Domain\Model\ChecksumInterface;

class RandomBytesChecksum implements ChecksumInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return bin2hex(random_bytes(32));
    }
}
