<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Assert\Assertion;
use \Core\Main\Domain\Model\EncryptionServiceInterface;

class Password
{
    const PASSWORD_MIN_LENGTH = 6;

    /**
     * @var EncryptionServiceInterface
     */
    private $encryptionService;

    /**
     * @var string
     */
    private $password;

    /**
     * Password constructor.
     * @param EncryptionServiceInterface $encryptionService
     */
    public function __construct(EncryptionServiceInterface $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * @return EncryptionServiceInterface
     */
    public function getEncryptionService(): EncryptionServiceInterface
    {
        return $this->encryptionService;
    }

    /**
     * @param EncryptionServiceInterface $encryptionService
     * @return Password
     */
    public function setEncryptionService(EncryptionServiceInterface $encryptionService): Password
    {
        $this->encryptionService = $encryptionService;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Password
     */
    public function setPassword(string $password): Password
    {
        Assertion::minLength($password, self::PASSWORD_MIN_LENGTH, null, 'password');

        $this->password = $this->encryptionService->hashPassword($password);
        return $this;
    }

    /**
     * @param string $password
     * @return Password
     */
    public function changePassword(string $password): Password
    {
        Assertion::minLength($password, self::PASSWORD_MIN_LENGTH, null, 'new_password');

        $this->password = $this->encryptionService->hashPassword($password);
        return $this;
    }

    /**
     * @param string $password
     * @return Password
     */
    public function setEncryptedPassword(string $password): Password
    {
        $this->password = $password;
        return $this;
    }
}
