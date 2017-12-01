<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Service\User\ChangeLocaleRequest;
use Core\Main\Application\Service\User\ChangeLocaleService;
use Core\Main\Application\Service\User\UserChangeTimezoneRequest;
use Core\Main\Application\Service\User\UserChangeTimezoneService;
use Core\Main\Application\Service\User\UserVerifyEmailRequest;
use Core\Main\Application\Service\User\UserVerifyEmailService;
use Core\Main\Infrastructure\Ui\Web\Silex\Encoders\StringEncoderInterface;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Core\Main\Application\Service\User\UserChangeEmailRequest;
use \Core\Main\Application\Service\User\UserChangeEmailService;
use \Core\Main\Application\Service\User\UserChangePasswordRequest;
use \Core\Main\Application\Service\User\UserChangePasswordService;
use \Core\Main\Domain\Model\User\User;

class IdentityController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;

        $factory = $this->app['controllers_factory'];

        // register routes
        $factory->get('/me', [$this, 'me']);
        $factory->put('/me/email', [$this, 'changeEmail']);
        $factory->put('/me/timezone', [$this, 'changeTimezone']);
        $factory->put('/me/password', [$this, 'changePassword']);
        $factory->put('/me/contact-name', [$this, 'contactName']);
        $factory->put('/email/verification/{checksum}', [$this, 'verifyEmail']);
        $factory->put('/me/locale', [$this, 'changeLocale']);

        return $factory;
    }

    /**
     * @return User
     */
    protected function getUserContext(): User
    {
        /* @var $userToken \Core\Main\Infrastructure\Ui\Web\Silex\User\User */
        $userToken = $this->app['security.token_storage']->getToken()->getUser();
        return $userToken->getDomainUser();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeTimezone(Request $request): Response
    {
        $user = $this->getUserContext();
        $timezone = $request->get('timezone', '');
        $user = $this->app[UserChangeTimezoneService::class]->execute(
            new UserChangeTimezoneRequest($user->getId(), $timezone)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeEmail(Request $request): Response
    {
        $user = $this->getUserContext();
        $email = $request->get('email', '');
        $user = $this->app[UserChangeEmailService::class]->execute(
            new UserChangeEmailRequest($user->getId(), $email)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        $user = $this->getUserContext();
        $currentPassword = $request->get('current_password', '');
        $newPassword = $request->get('new_password', '');
        $user = $this->app[UserChangePasswordService::class]->execute(
            new UserChangePasswordRequest($user->getId(), $currentPassword, $newPassword)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function me(Request $request): Response
    {
        $requestToken = $request->headers->get(
            $this->app['app-config']['jwt']['header'],
            $request->headers->get('Authorization')
        );
        $requestToken = trim(str_replace("Bearer", "", $requestToken));
        $tokenParts = explode('.', $requestToken);
        $header = json_decode(base64_decode(reset($tokenParts)));
        $payload = $this->app['security.jwt.encoder']->decode($requestToken);

        return $this->app->json(
            [
                'jwt' => [
                    'header' => $header,
                    'payload' => $payload
                ]
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param string $checksum
     * @return Response
     */
    public function verifyEmail(string $checksum): Response
    {
        $email = $this->app[StringEncoderInterface::class]->decode($checksum);
        $user = $this->app[UserVerifyEmailService::class]->execute(new UserVerifyEmailRequest($email));

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeLocale(Request $request): Response
    {
        $user = $this->getUserContext();
        $locale = $request->get('locale', '');
        $user = $this->app[ChangeLocaleService::class]->execute(
            new ChangeLocaleRequest($user->getId(), $locale)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }
}
