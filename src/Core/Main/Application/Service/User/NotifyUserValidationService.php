<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;
use Core\Main\Infrastructure\Ui\Web\Silex\Encoders\StringEncoderInterface;
use Ddd\Application\Service\ApplicationService;

class NotifyUserValidationService implements ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var SwiftMailerService
     */
    protected $mailService;

    /**
     * @var StringEncoderInterface
     */
    protected $stringEncoder;

    /**
     * @var \stdClass
     */
    protected $emailConfig;

    /**
     * UserValidateEmailService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param SwiftMailerService $mailService
     * @param StringEncoderInterface $stringEncoder
     * @param \stdClass $emailConfig
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        SwiftMailerService $mailService,
        StringEncoderInterface $stringEncoder,
        \stdClass $emailConfig
    ) {
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
        $this->stringEncoder = $stringEncoder;
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
     * @return UserRepositoryInterface
     */
    protected function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    /**
     * @return StringEncoderInterface
     */
    protected function getStringEncoder(): StringEncoderInterface
    {
        return $this->stringEncoder;
    }

    /**
     * @param NotifyUserValidationRequest $request
     * @return int
     */
    public function execute($request = null): int
    {
        $user = $this->getUserRepository()->ofId($request->getUserId());
        $checksum = $this->getStringEncoder()->encode($user->getEmail());
        $emailVerificationPath = preg_replace('/{hash}/', $checksum, $this->emailConfig->emailVerificationPath);
        $activationLink = $this->emailConfig->baseUrl . $emailVerificationPath;
        $data['activation_link'] = $activationLink;
        $data['base_url'] = $this->emailConfig->baseUrl;

        return $this->getMailService()->sendTemplate(
            $user->getEmail(),
            MailerServiceInterface::ACTIVATION_EMAIL,
            $data,
            $user->getLocale()
        );
    }
}
