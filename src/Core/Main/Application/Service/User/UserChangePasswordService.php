<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\Password;
use \Core\Main\Domain\Model\User\UserDoesNotExistException;
use \Core\Main\Domain\Model\EncryptionServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class UserChangePasswordService implements ApplicationService
{
    /**
     * @var EncryptionServiceInterface
     */
    private $encryptionService;

    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * UserChangePasswordService constructor.
     * @param EncryptionServiceInterface $encryptionService
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        EncryptionServiceInterface $encryptionService,
        UserRepositoryInterface $repository
    ) {
        $this->encryptionService = $encryptionService;
        $this->repository = $repository;
    }

    /**
     * @param UserChangePasswordRequest $request
     * @return User
     * @throws UserDoesNotExistException
     * @throws AssertionFailedException
     */
    public function execute($request = null): User
    {
        $id = $request->getUserId();
        /* @var $user User */
        $user = $this->repository->ofId($id);
        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }
        $currentPassword = $request->getCurrentPassword();
        $newPassword = $request->getNewPassword();
        if ($currentPassword === $newPassword) {
            throw new InvalidArgumentException(
                "New password is same as current password.",
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'new_password',
                ''
            );
        }
        if (!$this->encryptionService->hashEquals($user->getPassword()->getPassword(), $currentPassword)) {
            throw new InvalidArgumentException(
                "Incorrect current password.",
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'current_password',
                ''
            );
        }
        $password = new Password($this->encryptionService);
        $password->changePassword($newPassword);
        $user->setPassword($password);
        $this->repository->update($user);

        return $user;
    }
}
