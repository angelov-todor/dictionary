<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Domain\Model\ChecksumInterface;
use Core\Main\Domain\Model\User\PasswordReset;
use Core\Main\Domain\Model\User\ResetPassword;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Ddd\Application\Service\ApplicationService;
use Ddd\Domain\DomainEventPublisher;

class UserPasswordResetClaimService implements ApplicationService
{
    /** @var UserRepositoryInterface */
    protected $repository;

    /** @var  UserResetPasswordRepositoryInterface */
    protected $passwordRepository;

    /** @var ChecksumInterface */
    protected $checksum;

    /**
     * UserPasswordResetClaimService constructor.
     * @param UserRepositoryInterface $repository
     * @param UserResetPasswordRepositoryInterface $passwordRepository
     * @param ChecksumInterface $checksum
     */
    public function __construct(
        UserRepositoryInterface $repository,
        UserResetPasswordRepositoryInterface $passwordRepository,
        ChecksumInterface $checksum
    ) {
        $this->repository = $repository;
        $this->passwordRepository = $passwordRepository;
        $this->checksum = $checksum;
    }

    /**
     * @param UserPasswordResetClaimRequest|null $request
     *
     * @return string
     * @throws RequestUnprocessableException
     * @throws ResourceNotFoundException
     * @throws AssertionFailedException
     */
    public function execute($request = null): string
    {
        /* @var $request UserViewRequest */
        $email = $request->getEmail();
        Assertion::email($email, 'Invalid email provided', 'email');

        $user = $this->repository->ofEmail($email);
        if (null === $user) {
            throw new ResourceNotFoundException('User does not exist.');
        }
        $resetPasswords = $this->passwordRepository->findOfUserId($user->getId());

        // if there is previous reset password request and is still valid return it instead of creating new one
        foreach ($resetPasswords as $password) {
            if (is_null($password->getResetAt()) && $password->getExpiresAt()->getTimestamp() > time()) {
                return $password->getId();
            }
        }

        $resetPassword = new ResetPassword(
            $this->checksum->generate(),
            $user->getId(),
            (new \DateTime())->modify('+ 24 hours'),
            null,
            new \DateTime()
        );
        $resetHash = $this->passwordRepository->add($resetPassword);

        DomainEventPublisher::instance()->publish(
            new PasswordReset($resetPassword->getId())
        );

        return $resetHash;
    }
}
