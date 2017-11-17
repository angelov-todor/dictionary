<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;
use Ddd\Application\Service\ApplicationService;

class NotifyPasswordResetLinkService implements ApplicationService
{
    /**
     * @var UserResetPasswordRepositoryInterface
     */
    protected $passwordRepository;

    /**
     * @var SwiftMailerService
     */
    protected $mailService;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var \stdClass
     */
    protected $emailConfig;

    /**
     * NotifyPasswordResetLinkService constructor.
     * @param UserResetPasswordRepositoryInterface $passwordRepository
     * @param UserRepositoryInterface $userRepository
     * @param SwiftMailerService $mailService
     * @param \stdClass $emailConfig
     */
    public function __construct(
        UserResetPasswordRepositoryInterface $passwordRepository,
        UserRepositoryInterface $userRepository,
        SwiftMailerService $mailService,
        \stdClass $emailConfig
    ) {
        $this->passwordRepository = $passwordRepository;
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
        $this->emailConfig = $emailConfig;
    }

    /**
     * @return SwiftMailerService
     */
    protected function getMailService(): SwiftMailerService
    {
        return $this->mailService;
    }

    /**
     * @return UserResetPasswordRepositoryInterface
     */
    protected function getPasswordRepository(): UserResetPasswordRepositoryInterface
    {
        return $this->passwordRepository;
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    /**
     * @param NotifyPasswordResetLinkRequest $request
     * @return int
     */
    public function execute($request = null): int
    {
        $passwordReset = $this->getPasswordRepository()->ofId($request->getResetPasswordId());
        $user = $this->getUserRepository()->ofId($passwordReset->getUserId());

        $resetPasswordPath = preg_replace('/{id}/', $passwordReset->getId(), $this->emailConfig->resetPasswordPath);
        $resetLink = $this->emailConfig->baseUrl . $resetPasswordPath;
        $data['reset_password_link'] = $resetLink;

        return $this->getMailService()->sendTemplate(
            $user->getEmail(),
            MailerServiceInterface::RESET_PASSWORD_EMAIL,
            $data,
            $user->getLocale()
        );
    }
}
