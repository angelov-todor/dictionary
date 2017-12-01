<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Repository\DictionaryRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Domain\Model\DoctrineDictionaryRepository;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DictionaryController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return DoctrineDictionaryRepository
     */
    protected function getRepository(): DoctrineDictionaryRepository
    {
        return $this->app[DictionaryRepositoryInterface::class];
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
        $factory->get('/dictionary', [$this, 'getDictionary']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getDictionary(Request $request): Response
    {
        $nameFilter = $request->get('name');
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $results = $this->getRepository()->viewBy($nameFilter, $page, $limit);
        $count = $this->getRepository()->countBy($nameFilter);

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'dictionary' // embedded rel
            ),
            'dictionary', // route
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
