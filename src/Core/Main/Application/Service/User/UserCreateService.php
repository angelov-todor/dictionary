<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\AssertionFailedException;
use Ddd\Domain\DomainEventPublisher;
use Core\Main\Domain\Model\User\UserCreated;
use Ddd\Application\Service\ApplicationService;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;
use \Core\Main\Domain\Model\User\Password;
use \Core\Main\Domain\Model\User\UserAlreadyExistsException;
use \Core\Main\Domain\Model\EncryptionServiceInterface;

class UserCreateService implements ApplicationService
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
     * UserCreateService constructor.
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
     * @param UserCreateRequest $request
     * @return User
     * @throws UserAlreadyExistsException
     * @throws AssertionFailedException
     */
    public function execute($request = null): User
    {
        /* @var $request UserCreateRequest */
        $email = $request->getEmail();

        $user = $this->repository->ofEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistsException('User exists.');
        }

        $password = new Password($this->encryptionService);
        $password->setPassword($request->getPassword());

        $user = User::create($email, $password, $request->getLocale(), $request->getCurrency());

        $this->repository->add($user);

        DomainEventPublisher::instance()->publish(
            new UserCreated($user->getId(), $user->getEmail())
        );

        return $user;
    }
}
