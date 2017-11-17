<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Ddd\Application\Service\ApplicationService;

class ViewResetPasswordExpirationService implements ApplicationService
{
    /** @var UserResetPasswordRepositoryInterface */
    protected $passwordRepository;

    /**
     * UserViewService constructor.
     *
     * @param UserResetPasswordRepositoryInterface $passwordRepository
     */
    public function __construct(
        UserResetPasswordRepositoryInterface $passwordRepository
    ) {
        $this->passwordRepository = $passwordRepository;
    }

    /**
     * @param null|ViewResetPasswordExpirationRequest $request
     *
     * @return \DateTimeInterface
     * @throws RequestUnprocessableException
     * @throws ResourceNotFoundException
     */
    public function execute($request = null): \DateTimeInterface
    {
        if (is_null($request->getHash())) {
            throw new RequestUnprocessableException("Missing password reset hash.");
        }

        $resetPassword = $this->passwordRepository->ofId($request->getHash());

        if (null === $resetPassword) {
            throw new ResourceNotFoundException('Reset password hash does not exist.');
        }

        return $resetPassword->getExpiresAt();
    }
}
