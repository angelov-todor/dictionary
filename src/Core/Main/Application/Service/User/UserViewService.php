<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\UserDoesNotExistException;

class UserViewService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    /**
     * UserViewService constructor.
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UserViewRequest $request
     * @return User
     * @throws UserDoesNotExistException
     */
    public function execute($request = null): User
    {
        $id = $request->getUserId();

        $user = $this->repository->ofId($id);
        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }

        return $user;
    }
}
