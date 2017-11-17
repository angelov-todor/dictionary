<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Domain\Model\User\User;
use Core\Main\Domain\Model\User\UserDoesNotExistException;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Ddd\Application\Service\ApplicationService;

class ChangeContactNameService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * ChangeContactNameService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    /**
     * @param ChangeContactNameRequest $request
     * @throws UserDoesNotExistException
     * @return User
     */
    public function execute($request = null): User
    {
        $user = $this->getUserRepository()->ofId($request->getUserId());
        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }
        $user->setContactName($request->getContactName());
        $this->getUserRepository()->update($user);

        return $user;
    }
}
