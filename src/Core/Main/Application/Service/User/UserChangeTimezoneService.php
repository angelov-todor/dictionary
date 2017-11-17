<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\UserDoesNotExistException;
use \Core\Main\Domain\Model\User\UserAlreadyExistsException;

class UserChangeTimezoneService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * UserChangeTimezoneService constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserChangeTimezoneRequest $request
     * @return User
     * @throws UserDoesNotExistException
     * @throws UserAlreadyExistsException
     */
    public function execute($request = null): User
    {
        $id = $request->getUserId();
        $user = $this->repository->ofId($id);

        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }

        $timezone = $request->getTimezone();
        $user->setTimezone($timezone);
        $this->repository->update($user);

        return $user;
    }
}
