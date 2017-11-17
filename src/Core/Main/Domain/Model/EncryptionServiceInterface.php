<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

interface EncryptionServiceInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string;

    /**
     * @param string $hash
     * @param string $password
     * @return bool
     */
    public function hashEquals(string $hash, string $password): bool;
}
