<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Service\User\UserPasswordResetClaimRequest;
use Core\Main\Application\Service\User\UserPasswordResetClaimService;
use Core\Main\Application\Service\User\UserPasswordResetService;
use Core\Main\Application\Service\User\ViewResetPasswordExpirationRequest;
use Core\Main\Application\Service\User\ViewResetPasswordExpirationService;
use Core\Main\Application\Service\User\UserPasswordResetRequest;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;

        $factory = $this->app['controllers_factory'];

        // register routes
        $factory->post('/reset-password', [$this, 'resetPasswordRequest']);
        $factory->put('/reset-password/{hash}/password', [$this, 'resetPasswordAction']);
        $factory->get('/reset-password/{hash}', [$this, 'resetPasswordTokenInfo']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resetPasswordRequest(Request $request): Response
    {
        $email = $request->get('email', null);
        $resetHash = $this->app[UserPasswordResetClaimService::class]->execute(
            new UserPasswordResetClaimRequest($email)
        );

        return $this->app->json(['hash' => $resetHash], Response::HTTP_CREATED);
    }

    /**
     * @param string $hash
     * @param Request $request
     * @return Response
     */
    public function resetPasswordAction(string $hash, Request $request): Response
    {
        $password = $request->get('password', null);

        $this->app[UserPasswordResetService::class]->execute(
            new UserPasswordResetRequest($hash, $password)
        );

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $hash
     *
     * @return Response
     * @throws RequestUnprocessableException
     */
    public function resetPasswordTokenInfo(string $hash): Response
    {
        /** @var \DateTime $date */
        $date = $this->app[ViewResetPasswordExpirationService::class]->execute(
            new ViewResetPasswordExpirationRequest($hash)
        );

        return $this->app->json(
            ["expiration_date" => $date->format(DATE_ATOM)],
            Response::HTTP_OK
        );
    }
}
