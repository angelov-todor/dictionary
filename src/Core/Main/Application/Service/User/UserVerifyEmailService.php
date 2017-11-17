<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\UserDoesNotExistException;

class UserVerifyEmailService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * UserChangeEmailService constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserVerifyEmailRequest $request
     * @return User
     * @throws UserDoesNotExistException
     */
    public function execute($request = null): User
    {
        $email = $request->getEmail();
        /* @var $user User */
        $user = $this->repository->ofEmail($email);
        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }

        $user->setEmailVerified(true);
        $this->repository->update($user);

        return $user;
    }
}
