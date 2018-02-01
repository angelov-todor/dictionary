<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Test\Methodology;
use Core\Main\Domain\Repository\MethodologyRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MethodologyController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return MethodologyRepositoryInterface
     */
    protected function getRepository(): MethodologyRepositoryInterface
    {
        return $this->app[MethodologyRepositoryInterface::class];
    }

    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;
        /* @var $factory ControllerCollection */
        $factory = $this->app['controllers_factory'];
        $factory->get('/methodologies', [$this, 'getMethodologies']);
        $factory->post('/methodologies', [$this, 'addMethodology']);
        $factory->put('/methodologies/{id}', [$this, 'updateMethodology']);
        $factory->delete('/methodologies/{id}', [$this, 'removeMethodology']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getMethodologies(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 100));

        $results = $this->getRepository()->viewBy($page, $limit);
        $count = $this->getRepository()->countBy();

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'methodologies' // embedded rel
            ),
            'methodologies', // route
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

    /**
     * @param Request $request
     * @return Response
     */
    public function addMethodology(Request $request): Response
    {
        $name = $request->get('name');

        $methodology = new Methodology(null, $name);
        $methodology->setName($name);
        $this->getRepository()->add($methodology);

        return $this->app['haljson']($methodology, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateMethodology($id, Request $request): Response
    {
        $name = $request->get('name');

        /** @var Methodology $methodology */
        $methodology = $this->getRepository()->ofId($id);
        $methodology->setName($name);

        $this->getRepository()->update($methodology);

        return $this->app['haljson']($methodology, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function removeMethodology($id): Response
    {
        /** @var Methodology $methodology */
        $methodology = $this->getRepository()->ofId($id);
        if ($methodology) {
            $this->getRepository()->remove($methodology);
        }

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }
}
