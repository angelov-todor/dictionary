<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Infrastructure\Ui\Web\Silex\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * DoctrineUserProvider constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @internal param UserViewService $userViewService
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     * @return User|UserInterface
     */
    public function loadUserByUsername($username): User
    {
        $user = $this->userRepository->ofEmail($username);

        if (is_null($user)) {
            throw new UsernameNotFoundException();
        }

        return new User($user);
    }

    /**
     * @param string $id
     * @return User
     */
    public function loadUserById(string $id): User
    {
        $user = $this->userRepository->ofId($id);

        if (is_null($user)) {
            throw new UsernameNotFoundException();
        }

        return new User($user);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     * @return User|UserInterface
     */
    public function refreshUser(UserInterface $user): User
    {
        $user = $this->userRepository->ofId($user->getId());

        if (is_null($user)) {
            throw new UsernameNotFoundException();
        }

        return new User($user);
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return $class instanceof User;
    }
}
