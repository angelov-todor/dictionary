<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Unit\Position;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Model\Unit\UnitImage;
use Core\Main\Domain\Repository\CognitiveTypeRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\TestRepositoryInterface;
use Core\Main\Domain\Repository\UnitImageRepositoryInterface;
use Core\Main\Domain\Repository\UnitRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Doctrine\ORM\EntityManager;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UnitController implements ControllerProviderInterface
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
        $factory->post('/units', [$this, 'generateUnit']);
        $factory->put('/units/{id}', [$this, 'updateUnit']);
        $factory->get('/units', [$this, 'viewUnits']);
        $factory->get('/units/{id}', [$this, 'viewUnit']);
        $factory->delete('/units/{id}', [$this, 'deleteUnit']);
        $factory->put('/unit_images/{id}', [$this, 'updateUnitImage']);
        $factory->put('/unit_images/{id}/correct', [$this, 'changeCorrect']);

        return $factory;
    }

    /**
     * @return UnitRepositoryInterface
     */
    protected function getRepository(): UnitRepositoryInterface
    {
        return $this->app[UnitRepositoryInterface::class];
    }

    /**
     * @return CognitiveTypeRepositoryInterface
     */
    protected function getCognitiveTypeRepository(): CognitiveTypeRepositoryInterface
    {
        return $this->app[CognitiveTypeRepositoryInterface::class];
    }

    /**
     * @return ImageRepositoryInterface
     */
    protected function getImageRepository(): ImageRepositoryInterface
    {
        return $this->app[ImageRepositoryInterface::class];
    }

    /**
     * @return UnitImageRepositoryInterface
     */
    protected function getUnitImageRepository(): UnitImageRepositoryInterface
    {
        return $this->app[UnitImageRepositoryInterface::class];
    }

    /**
     * @return TestRepositoryInterface
     */
    protected function getTestRepository(): TestRepositoryInterface
    {
        return $this->app[TestRepositoryInterface::class];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function generateUnit(Request $request): Response
    {
        $name = $request->get('name');
        $text = $request->get('text');
        $columns = $request->get('cols', 0);
        $rows = $request->get('rows', 0);
        $criteria = $request->get('criteria');
        $timeToConduct = $request->get('time_to_conduct', 0);
        $type = $request->get('type');

        $cognitiveTypeId = $request->get('cognitive_type_id');
        $cognitiveType = $this->getCognitiveTypeRepository()->ofId($cognitiveTypeId);

        $unit = new Unit(
            null,
            strval($name),
            strval($text),
            strval($type),
            intval($rows),
            intval($columns),
            $cognitiveType,
            $timeToConduct
        );

        $this->getRepository()->add($unit);

        for ($i = 0; $i < $columns; $i++) {
            for ($j = 0; $j < $rows; $j++) {
                $image = $this->getImageRepository()->getImageByCriteria(strval($criteria));
                $unitImage = new UnitImage(
                    null,
                    $image,
                    new Position($i, $j),
                    $unit,
                    false
                );
                $this->getUnitImageRepository()->add($unitImage);
            }
        }

        $this->getRepository()->update($unit);

        return $this->app['haljson']($unit);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateUnit($id, Request $request): Response
    {
        $name = $request->get('name');
        $text = $request->get('text');
        $type = $request->get('type');

        $timeToConduct = $request->get('time_to_conduct');

        $cognitiveTypeId = $request->get('cognitive_type_id');
        $cognitiveType = $this->getCognitiveTypeRepository()->ofId($cognitiveTypeId);

        $cognitiveSubtypeId = $request->get('cognitive_subtype_id');
        $cognitiveSubtype = null;
        if ($cognitiveSubtypeId) {
            $cognitiveSubtype = $this->getCognitiveTypeRepository()->ofId($cognitiveSubtypeId);
        }

        $unit = $this->getRepository()->ofId($id);

        $unit->setName($name)
            ->setCognitiveType($cognitiveType)
            ->setCognitiveSubtype($cognitiveSubtype)
            ->setText($text)
            ->setTimeToConduct($timeToConduct)
            ->setType($type);

        $this->getRepository()->update($unit);

        return $this->app['haljson']($unit, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewUnit($id): Response
    {
        return $this->app['haljson']($this->getRepository()->ofId($id));
    }

    /**
     * @param $id
     * @return Response
     * @throws \Throwable
     */
    public function deleteUnit($id): Response
    {
        $unit = $this->getRepository()->ofId($id);
        $unitImageRepository = $this->getUnitImageRepository();
        $unitRepository = $this->getRepository();
        $this->getEntityManager()->transactional(function () use (
            $unit,
            $unitImageRepository,
            $unitRepository
        ) {
            foreach ($unit->getUnitImages() as $unitImage) {
                $unitImageRepository->remove($unitImage);
            }
            $unitRepository->remove($unit);
        });

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->app['em'];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function viewUnits(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));
        $testId = $request->get('test_id');

        $test = null;
        if ($testId) {
            $test = $this->getTestRepository()->ofId($testId);
        }

        $count = $this->getRepository()->countBy($test);
        $results = $this->getRepository()->viewBy($page, $limit, $test);

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'units' // embedded rel
            ),
            'units', // route
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
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateUnitImage($id, Request $request): Response
    {
        $image = $request->get('image');
        $imageId = $image['id'];
        $unitImage = $this->getUnitImageRepository()->ofId($id);

        $image = $this->getImageRepository()->ofId($imageId);

        $unitImage->setImage($image);
        $this->getUnitImageRepository()->update($unitImage);

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function changeCorrect($id, Request $request): Response
    {
        $correct = boolval($request->get('correct'));

        $unitImage = $this->getUnitImageRepository()->ofId($id);
        $unitImage->setIsCorrect($correct);
        $this->getUnitImageRepository()->update($unitImage);

        return $this->app['haljson']($unitImage);
    }
}
