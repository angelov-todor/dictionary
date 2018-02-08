<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Helper\Locale;
use Core\Main\Domain\Model\User\User;
use Core\Main\Domain\Repository\UserRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Hateoas\Representation\CollectionRepresentation;
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
        $factory->put('/users/{id}', [$this, 'updateUser']);
        $factory->get('/users/{id}', [$this, 'getUser']);
        $factory->get('/users', [$this, 'getUsers']);

        return $factory;
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getRepository(): UserRepositoryInterface
    {
        return $this->app[UserRepositoryInterface::class];
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

    public function updateUser(string $id, Request $request): Response
    {
        /** @var User $user */
        $user = $this->app[UserViewService::class]->execute(new UserViewRequest($id));

        $user->setRoles((array)$request->get('role'));
        $this->getRepository()->update($user);
        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
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
                $request->get('locale', Locale::DEFAULT_LOCALE)
            )
        );

        return $this->app['haljson']($user, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getUsers(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $results = $this->getRepository()->viewBy($page, $limit);
        $count = $this->getRepository()->countBy();

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'users' // embedded rel
            ),
            'users', // route
            $request->query->all(), // route parameters
            $page,       // page number
            $limit,      // limit
            ceil($count / $limit), // total pages
            'page', // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false, // generate relative URIs, optional, defaults to `false`
            $count,       // total collection size, optional, defaults to `null`
            count($results)//  current element count
        );
        return $this->app['haljson']($paginatedCollection);
    }

}
