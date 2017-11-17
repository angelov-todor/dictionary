<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\InvalidArgumentException;
use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Exception\ResourceConflictException;
use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Domain\Model\EncryptionServiceInterface;
use Core\Main\Domain\Model\User\Password;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Model\User\User;

class UserPasswordResetService implements ApplicationService
{
    /** @var EncryptionServiceInterface */
    protected $encryptionService;

    /** @var UserRepositoryInterface */
    protected $repository;

    /** @var UserResetPasswordRepositoryInterface */
    protected $passwordRepository;

    /**
     * UserViewService constructor.
     *
     * @param EncryptionServiceInterface $encryptionService
     * @param UserRepositoryInterface $repository
     * @param UserResetPasswordRepositoryInterface $passwordRepository
     */
    public function __construct(
        EncryptionServiceInterface $encryptionService,
        UserRepositoryInterface $repository,
        UserResetPasswordRepositoryInterface $passwordRepository
    ) {
        $this->encryptionService = $encryptionService;
        $this->repository = $repository;
        $this->passwordRepository = $passwordRepository;
    }

    /**
     * @param UserPasswordResetRequest|null $request
     * @return User
     * @throws \Exception
     */
    public function execute($request = null): User
    {
        $hash = $request->getHash();
        $password = $request->getPassword();

        if (is_null($hash) || is_null($password)) {
            throw new RequestUnprocessableException("Missing hash and/or password");
        }

        $resetPassword = $this->passwordRepository->ofId($hash);

        if (null === $resetPassword) {
            throw new ResourceNotFoundException('Reset password hash does not exist.');
        }

        if (false === is_null($resetPassword->getResetAt())) {
            throw new ResourceConflictException('Reset password hash already used.');
        }

        $user = $this->repository->ofId($resetPassword->getUserId());

        if (null === $user) {
            throw new ResourceNotFoundException('Reset password hash for user does not exist.');
        }

        $expirationTime = $resetPassword->getExpiresAt()->getTimestamp();

        if (($expirationTime - time()) <= 0) {
            throw new ResourceConflictException('Reset token expired.');
        }

        $password = new Password($this->encryptionService);

        try {
            $password->setPassword($request->getPassword());
        } catch (InvalidArgumentException $e) {
            throw new RequestUnprocessableException($e->getMessage());
        }

        $user->setPassword($password);
        $this->repository->update($user);

        $resetPassword->setResetAt(new \DateTime());
        $this->passwordRepository->update($resetPassword);

        return $user;
    }
}
