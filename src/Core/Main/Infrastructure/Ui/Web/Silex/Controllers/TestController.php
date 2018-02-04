<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Test\Test;
use Core\Main\Domain\Repository\CognitiveSkillRepositoryInterface;
use Core\Main\Domain\Repository\MethodologyRepositoryInterface;
use Core\Main\Domain\Repository\TestRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Domain\Model\User\User;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController implements ControllerProviderInterface
{
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
     * @var Application
     */
    protected $app;

    /**
     * @return TestRepositoryInterface
     */
    protected function getRepository(): TestRepositoryInterface
    {
        return $this->app[TestRepositoryInterface::class];
    }

    /**
     * @return CognitiveSkillRepositoryInterface
     */
    protected function getCognitiveSkillRepository(): CognitiveSkillRepositoryInterface
    {
        return $this->app[CognitiveSkillRepositoryInterface::class];
    }

    /**
     * @return MethodologyRepositoryInterface
     */
    protected function getMethodologyRepository(): MethodologyRepositoryInterface
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
        $factory->get('/tests', [$this, 'getMethodologies']);
        $factory->post('/tests', [$this, 'addTest']);
        $factory->put('/tests/{id}', [$this, 'updateTest']);
        $factory->delete('/tests/{id}', [$this, 'removeTest']);
        $factory->get('/tests/{id}', [$this, 'viewTest']);

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
                'tests' // embedded rel
            ),
            'tests', // route
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
    public function addTest(Request $request): Response
    {
        $name = $request->get('name');
        $cognitiveSkillId = $request->get('cognitive_skill_id');
        $cognitiveSkill = $this->getCognitiveSkillRepository()->ofId($cognitiveSkillId);

        $gradingScale = $request->get('grading_scale');
        $maxAge = $request->get('max_age');
        $minAge = $request->get('min_age');

        $methodologyId = $request->get('methodology_id');
        $methodology = null;
        if ($methodologyId) {
            $methodology = $this->getMethodologyRepository()->ofId($methodologyId);
        }
        $pointsRequired = $request->get('points_required');
        $timeToConduct = $request->get('time_to_conduct');
        $notes = $request->get('notes');

        $test = new Test(
            null,
            $name,
            [],
            $cognitiveSkill,
            $gradingScale,
            $minAge,
            $maxAge,
            $methodology,
            $pointsRequired,
            $timeToConduct,
            $notes,
            $this->getUserContext()
        );

        $this->getRepository()->add($test);

        return $this->app['haljson']($test, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateTest($id, Request $request): Response
    {
        $name = $request->get('name');

        /** @var Test $test */
        $test = $this->getRepository()->ofId($id);
        $test->setName($name);

        $this->getRepository()->update($test);

        return $this->app['haljson']($test, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function removeTest($id): Response
    {
        /** @var Test $test */
        $test = $this->getRepository()->ofId($id);
        if ($test) {
            $this->getRepository()->remove($test);
        }

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewTest($id): Response
    {
        $test = $this->getRepository()->ofId($id);
        return $this->app['haljson']($test);
    }
}