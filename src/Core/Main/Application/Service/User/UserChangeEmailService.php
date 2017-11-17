<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\AssertionFailedException;
use Core\Main\Domain\Model\User\UserEmailChanged;
use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\UserDoesNotExistException;
use \Core\Main\Domain\Model\User\UserAlreadyExistsException;
use Ddd\Domain\DomainEventPublisher;

class UserChangeEmailService implements ApplicationService
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
     * @param UserChangeEmailRequest $request
     * @return User
     * @throws UserDoesNotExistException
     * @throws UserAlreadyExistsException
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

        $email = $request->getEmail();
        $userExists = $this->repository->ofEmail($email);
        if (null !== $userExists) {
            throw new UserAlreadyExistsException('User exists.');
        }

        $user->setEmail($email);
        $user->setEmailVerified(false);
        $this->repository->update($user);

        DomainEventPublisher::instance()->publish(
            new UserEmailChanged($user->getId(), $user->getEmail())
        );

        return $user;
    }
}
