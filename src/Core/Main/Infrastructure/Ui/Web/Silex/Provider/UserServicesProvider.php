<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Application\Service\User\ChangeLocaleService;
use Core\Main\Application\Service\User\NotifyPasswordResetLinkService;
use Core\Main\Application\Service\User\AuthenticateService;
use Core\Main\Application\Service\User\UserChangeEmailService;
use Core\Main\Application\Service\User\UserChangePasswordService;
use Core\Main\Application\Service\User\UserChangeTimezoneService;
use Core\Main\Application\Service\User\UserCreateService;
use Core\Main\Application\Service\User\UserPasswordResetClaimService;
use Core\Main\Application\Service\User\UserPasswordResetService;
use Core\Main\Application\Service\User\NotifyUserValidationService;
use Core\Main\Application\Service\User\UserVerifyEmailService;
use Core\Main\Application\Service\User\UserViewService;
use Core\Main\Application\Service\User\ViewResetPasswordExpirationService;
use Core\Main\Domain\Model\ChecksumInterface;
use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Model\User\ResetPassword;
use Core\Main\Domain\Model\User\User;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Core\Main\Infrastructure\Services\PasswordHashEncryptionService;
use Core\Main\Infrastructure\Services\RandomBytesChecksum;
use Core\Main\Infrastructure\Ui\Web\Silex\Encoders\StringEncoder;
use Core\Main\Infrastructure\Ui\Web\Silex\Encoders\StringEncoderInterface;
use Ddd\Application\Service\TransactionalApplicationService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserServicesProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app[UserRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(User::class);
        };

        $app[PasswordHashEncryptionService::class] = function () use ($app) {
            return new PasswordHashEncryptionService();
        };

        $app[ChecksumInterface::class] = function () use ($app) {
            return new RandomBytesChecksum();
        };

        $app[UserCreateService::class] = function () use ($app) {
            return new UserCreateService(
                $app[PasswordHashEncryptionService::class],
                $app[UserRepositoryInterface::class]
            );
        };
        $app[UserViewService::class] = function () use ($app) {
            return new UserViewService(
                $app[UserRepositoryInterface::class]
            );
        };

        // authenticate service
        $app[AuthenticateService::class] = function () use ($app) {
            return new AuthenticateService(
                $app[UserRepositoryInterface::class],
                $app[PasswordHashEncryptionService::class]
            );
        };

        // reset-password
        $app[UserResetPasswordRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(ResetPassword::class);
        };

        $app[UserPasswordResetClaimService::class] = function () use ($app) {
            return new UserPasswordResetClaimService(
                $app[UserRepositoryInterface::class],
                $app[UserResetPasswordRepositoryInterface::class],
                $app[ChecksumInterface::class]
            );
        };

        $app[UserPasswordResetService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new UserPasswordResetService(
                    $app[PasswordHashEncryptionService::class],
                    $app[UserRepositoryInterface::class],
                    $app[UserResetPasswordRepositoryInterface::class]
                ),
                $app['tx_session']
            );
        };

        $app[ViewResetPasswordExpirationService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new ViewResetPasswordExpirationService(
                    $app[UserResetPasswordRepositoryInterface::class]
                ),
                $app['tx_session']
            );
        };

        $app[UserChangePasswordService::class] = function () use ($app) {
            return new UserChangePasswordService(
                $app[PasswordHashEncryptionService::class],
                $app[UserRepositoryInterface::class]
            );
        };

        $app[UserChangeEmailService::class] = function () use ($app) {
            return new UserChangeEmailService($app[UserRepositoryInterface::class]);
        };
        $app[UserVerifyEmailService::class] = function () use ($app) {
            return new UserVerifyEmailService($app[UserRepositoryInterface::class]);
        };
        $app[UserProviderInterface::class] = function () use ($app) {
            return new UserProvider($app[UserRepositoryInterface::class]);
        };

        $app[StringEncoderInterface::class] = function () use ($app) {
            return new StringEncoder($app['app-config']['jwt']['private_key'], $app['app-config']['jwt']['public_key']);
        };

        $app[UserChangeTimezoneService::class] = function () use ($app) {
            return new UserChangeTimezoneService($app[UserRepositoryInterface::class]);
        };
        $app[ChangeLocaleService::class] = function () use ($app) {
            return new ChangeLocaleService($app[UserRepositoryInterface::class]);
        };
        $app[NotifyUserValidationService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new NotifyUserValidationService(
                    $app[UserRepositoryInterface::class],
                    $app[MailerServiceInterface::class],
                    $app[StringEncoderInterface::class],
                    (object)$app['app-config']['email.options']
                ),
                $app['tx_session']
            );
        };
        $app[NotifyPasswordResetLinkService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new NotifyPasswordResetLinkService(
                    $app[UserResetPasswordRepositoryInterface::class],
                    $app[UserRepositoryInterface::class],
                    $app[MailerServiceInterface::class],
                    (object)$app['app-config']['email.options']
                ),
                $app['tx_session']
            );
        };
    }
}
