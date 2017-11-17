<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use \Core\Main\Infrastructure\Services\PasswordHashEncryptionService;
use \Core\Main\Domain\Model\User\Password as UserPassword;

class Password extends StringType
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'password';
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getPassword();
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return UserPassword
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $password = new UserPassword(new PasswordHashEncryptionService());
        $password->setEncryptedPassword($value);

        return $password;
    }
}
