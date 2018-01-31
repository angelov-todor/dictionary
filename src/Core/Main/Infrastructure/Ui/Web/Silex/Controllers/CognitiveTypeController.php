<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Test\CognitiveType;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CognitiveTypeController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return CognitiveTypeRepositoryInterface
     */
    protected function getRepository(): CognitiveTypeRepositoryInterface
    {
        return $this->app[CognitiveTypeRepositoryInterface::class];
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
        $factory->get('/cognitive-type', [$this, 'getCognitiveTypes']);
        $factory->post('/cognitive-type', [$this, 'addCognitiveType']);
        $factory->put('/cognitive-type/{id}', [$this, 'updateCognitiveType']);
        $factory->delete('/cognitive-type/{id}', [$this, 'removeCognitiveType']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getCognitiveTypes(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 100));

        $results = $this->getRepository()->viewBy($page, $limit);
        $count = $this->getRepository()->countBy();

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'cognitive_types' // embedded rel
            ),
            'cognitive-type', // route
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
    public function addCognitiveType(Request $request): Response
    {
        $name = $request->get('name');
        $parentId = $request->get('parent');

        $parent = null;
        if ($parentId) {
            /** @var CognitiveType $parent */
            $parent = $this->getRepository()->ofId($parentId);
        }
        $cognitiveType = new CognitiveType(null, $name, $parent);
        $cognitiveType->setName($name);
        $this->getRepository()->add($cognitiveType);

        return $this->app['haljson']($cognitiveType, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateCognitiveType($id, Request $request): Response
    {
        $name = $request->get('name');
        $parentId = $request->get('parent');

        $parent = null;
        if ($parentId) {
            /** @var CognitiveType $parent */
            $parent = $this->getRepository()->ofId($parentId);
        }
        /** @var CognitiveType $cognitiveType */
        $cognitiveType = $this->getRepository()->ofId($id);
        $cognitiveType->setName($name);
        $cognitiveType->setParent($parent);

        $this->getRepository()->update($cognitiveType);

        return $this->app['haljson']($cognitiveType, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function removeCognitiveType($id): Response
    {
        /** @var CognitiveType $cognitiveType */
        $cognitiveType = $this->getRepository()->ofId($id);
        if ($cognitiveType) {
            // TODO: this might have children
            $this->getRepository()->remove($cognitiveType);
        }

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }
}
