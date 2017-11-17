<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\AssertionFailedException;
use Core\Main\Domain\Model\User\UserDoesNotExistException;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Ddd\Application\Service\ApplicationService;

class ChangeLocaleService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * ChangeLocaleService constructor.
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
     * @param ChangeLocaleRequest $request
     * @return mixed
     * @throws UserDoesNotExistException
     * @throws AssertionFailedException
     */
    public function execute($request = null)
    {
        $user = $this->getUserRepository()->ofId($request->getUserId());
        if (null === $user) {
            throw new UserDoesNotExistException('User does not exist.');
        }

        $user->setLocale($request->getLocale());
        $this->getUserRepository()->update($user);

        return $user;
    }
}
