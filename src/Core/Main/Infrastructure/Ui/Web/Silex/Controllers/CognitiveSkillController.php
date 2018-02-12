<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Test\CognitiveSkill;
use Core\Main\Domain\Repository\CognitiveSkillRepositoryInterface;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CognitiveSkillController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return CognitiveSkillRepositoryInterface
     */
    protected function getRepository(): CognitiveSkillRepositoryInterface
    {
        return $this->app[CognitiveSkillRepositoryInterface::class];
    }

    /**
     * @return CognitiveTypeRepositoryInterface
     */
    protected function getCognitiveTypeRepository(): CognitiveTypeRepositoryInterface
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
        $factory->get('/cognitive-skill', [$this, 'getCognitiveSkills']);
        $factory->post('/cognitive-skill', [$this, 'addCognitiveSkill']);
        $factory->put('/cognitive-skill/{id}', [$this, 'updateCognitiveSkill']);
        $factory->get('/cognitive-skill/{id}', [$this, 'viewCognitiveSkill']);
        $factory->delete('/cognitive-skill/{id}', [$this, 'removeCognitiveSkill']);

        $factory->post('/cognitive-skill/{$id}/cognitive-types', [$this, 'addCognitiveType']);
        $factory->delete('/cognitive-skill/{$id}/cognitive-types/{$type}', [$this, 'removeCognitiveType']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getCognitiveSkills(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 100));

        $results = $this->getRepository()->viewBy($page, $limit);
        $count = $this->getRepository()->countBy();

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'cognitive_skills' // embedded rel
            ),
            'cognitive-skill', // route
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
    public function addCognitiveSkill(Request $request): Response
    {
        $name = $request->get('name');

        $cognitiveSkill = new CognitiveSkill(null, $name);
        $cognitiveSkill->setName($name);
        $this->getRepository()->add($cognitiveSkill);

        return $this->app['haljson']($cognitiveSkill, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewCognitiveSkill($id): Response
    {
        $cognitiveSkill = $this->getRepository()->ofId($id);
        return $this->app['haljson']($cognitiveSkill);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateCognitiveSkill($id, Request $request): Response
    {
        $name = $request->get('name');

        /** @var CognitiveSkill $cognitiveSkill */
        $cognitiveSkill = $this->getRepository()->ofId($id);
        $cognitiveSkill->setName($name);

        $this->getRepository()->update($cognitiveSkill);

        return $this->app['haljson']($cognitiveSkill, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function removeCognitiveSkill($id): Response
    {
        /** @var CognitiveSkill $cognitiveSkill */
        $cognitiveSkill = $this->getRepository()->ofId($id);
        if ($cognitiveSkill) {
            $this->getRepository()->remove($cognitiveSkill);
        }

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function addCognitiveType($id, Request $request): Response
    {
        $cognitiveSkill = $this->getRepository()->ofId($id);
        $cognitiveTypeId = $request->get('cognitive_type_id');

        $cognitiveType = $this->getCognitiveTypeRepository()->ofId($cognitiveTypeId);

        $cognitiveSkill->addCognitiveType($cognitiveType);
        $this->getRepository()->update($cognitiveSkill);

        return $this->app['haljson'](null);
    }

    /**
     * @param $id
     * @param $type
     * @return mixed
     */
    public function removeCognitiveType($id, $type)
    {
        $cognitiveSkill = $this->getRepository()->ofId($id);

        $cognitiveType = $this->getCognitiveTypeRepository()->ofId($type);

        $cognitiveSkill->removeCognitiveType($cognitiveType);
        $this->getRepository()->update($cognitiveSkill);

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }
}
