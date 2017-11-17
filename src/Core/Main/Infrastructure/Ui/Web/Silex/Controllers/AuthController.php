<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Exception\RequestUnprocessableException;
use Core\Main\Application\Service\User\AuthenticateRequest;
use Core\Main\Application\Service\User\AuthenticateService;
use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\JwtToken;
use Core\Main\Infrastructure\Ui\Web\Silex\User\User;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController implements ControllerProviderInterface
{
    /** @var Application */
    protected $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app
     *
     * @return ControllerCollection A ControllerCollection instance
     *
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;

        $factory = $this->app['controllers_factory'];

        // register routes
        $factory->post('/authenticate', [$this, 'authenticate']);
        $factory->post('/refresh-token', [$this, 'refreshToken']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws RequestUnprocessableException
     */
    public function authenticate(Request $request): Response
    {
        $email = $request->get('email', null);
        $password = $request->get('password', null);

        $user = $this->app[AuthenticateService::class]->execute(
            new AuthenticateRequest($email, $password)
        );

        try {
            $response = [
                'jwt' => $this->app['security.jwt.encoder']->encode(new User($user)),
            ];
        } catch (\DomainException $e) {
            throw new RequestUnprocessableException("Problem occurred while trying to create token.");
        }

        return $this->app->json(
            $response,
            array_key_exists('jwt', $response) ? Response::HTTP_OK : $response['status']
        );
    }

    /**
     * Return new token with refreshed life time.
     *
     * @return Response
     * @throws RequestUnprocessableException
     */
    public function refreshToken(): Response
    {
        /** @var $userToken JwtToken */
        $userToken = $this->app['security.token_storage']->getToken();

        try {
            $jwt = $this->app['security.jwt.encoder']->encode($userToken->getUser());
        } catch (\DomainException $e) {
            throw new RequestUnprocessableException("Problem occurred while trying to create token.");
        }

        return $this->app->json(
            ['jwt' => $jwt],
            Response::HTTP_OK
        );
    }
}
