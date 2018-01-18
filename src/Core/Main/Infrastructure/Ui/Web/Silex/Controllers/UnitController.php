<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Unit\Position;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Model\Unit\UnitImage;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\UnitImageRepositoryInterface;
use Core\Main\Domain\Repository\UnitRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
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
        $factory->get('/units', [$this, 'viewUnits']);
        $factory->get('/units/{id}', [$this, 'viewUnit']);
        $factory->delete('/units/{id}', [$this, 'deleteUnit']);

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

    public function generateUnit(Request $request): Response
    {
        $text = $request->get('text');
        $cols = $request->get('cols');
        $rows = $request->get('rows');

        $unit = new Unit(null, $text, $rows, $cols);

        $this->getRepository()->add($unit);

        for ($i = 0; $i < $cols; $i++) {
            for ($j = 0; $j < $rows; $j++) {
                $image = $this->getImageRepository()->getRandomImage();
                $unitImage = new UnitImage(null, $image, new Position($i, $j), $unit);
                $this->getUnitImageRepository()->add($unitImage);
            }
        }

        $this->getRepository()->update($unit);

        return $this->app['haljson']($unit);
    }

    public function viewUnit($id): Response
    {
        return $this->app['haljson']($this->getRepository()->ofId($id));
    }

    public function deleteUnit($id): Response
    {
        $unit = $this->getRepository()->ofId($id);

        foreach ($unit->getUnitImages() as $unitImage) {
            $this->getUnitImageRepository()->remove($unitImage);
        }
        $this->getRepository()->remove($unit);
        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    public function viewUnits(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $count = $this->getRepository()->countBy();
        $results = $this->getRepository()->viewBy($page, $limit);

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
}