<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Domain\Model\EncryptionServiceInterface;
use Core\Main\Domain\Model\User\User;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Infrastructure\Services\PasswordHashEncryptionService;
use Ddd\Application\Service\ApplicationService;

class AuthenticateService implements ApplicationService
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var PasswordHashEncryptionService */
    private $passwordEncoder;

    /**
     * AuthenticateService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param EncryptionServiceInterface $passwordEncoder
     */
    public function __construct(UserRepositoryInterface $userRepository, EncryptionServiceInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param $request AuthenticateRequest
     *
     * @return User
     * @throws RequestUnprocessableException
     * @throws ResourceNotFoundException
     */
    public function execute($request = null): User
    {
        $email = $request->getEmail();
        $password = $request->getPassword();

        if (is_null($email) || is_null($password)) {
            throw new RequestUnprocessableException('Missing authentication credentials.');
        }

        $user = $this->userRepository->ofEmail($email);

        if (is_null($user)) {
            throw new ResourceNotFoundException('Invalid email or password.');
        }

        if (!$this->passwordEncoder->hashEquals($user->getPassword()->getPassword(), $password)) {
            throw new ResourceNotFoundException('Invalid email or password.');
        }

        return $user;
    }
}
