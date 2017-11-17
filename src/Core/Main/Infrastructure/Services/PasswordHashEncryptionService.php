<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

use Core\Main\Domain\Model\EncryptionServiceInterface;

class PasswordHashEncryptionService implements EncryptionServiceInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $hash
     * @param string $password
     * @return bool
     */
    public function hashEquals(string $hash, string $password): bool
    {
        return password_verify($password, $hash);
    }
}
