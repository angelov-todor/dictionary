<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Helper\Locale;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Core\Main\Application\Service\User\UserCreateService;
use \Core\Main\Application\Service\User\UserCreateRequest;
use \Core\Main\Application\Service\User\UserViewRequest;
use \Core\Main\Application\Service\User\UserViewService;

class UserController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;
        /* @var $factory ControllerCollection */
        $factory = $this->app['controllers_factory'];
        $factory->post('/users', [$this, 'createUser']);
        $factory->get('/users/{id}', [$this, 'getUser']);

        return $factory;
    }

    /**
     * @param string $id
     * @return Response
     */
    public function getUser(string $id): Response
    {
        $user = $this->app[UserViewService::class]->execute(new UserViewRequest($id));

        return $this->app['haljson']($user);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createUser(Request $request)
    {
        $user = $this->app[UserCreateService::class]->execute(
            new UserCreateRequest(
                $request->get('email', ''),
                $request->get('password', ''),
                $request->get('locale', Locale::DEFAULT_LOCALE),
                $request->get('currency', Locale::DEFAULT_CURRENCY)
            )
        );

        return $this->app['haljson']($user, Response::HTTP_CREATED);
    }
}
