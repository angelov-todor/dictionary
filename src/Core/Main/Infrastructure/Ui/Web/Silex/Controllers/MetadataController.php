<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Domain\Model\DoctrineMetadataRepository;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MetadataController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return DoctrineMetadataRepository
     */
    protected function getRepository(): DoctrineMetadataRepository
    {
        return $this->app[MetadataRepositoryInterface::class];
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
        $factory->get('/metadata', [$this, 'getMetadata']);
        $factory->post('/metadata', [$this, 'addMetadata']);
        $factory->delete('/metadata/{id}', [$this, 'removeMetadata']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getMetadata(Request $request): Response
    {
        $nameFilter = $request->get('name');
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $results = $this->getRepository()->viewBy($nameFilter, $page, $limit);
        $count = $this->getRepository()->countBy($nameFilter);

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'metadata' // embedded rel
            ),
            'metadata', // route
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

    public function addMetadata(Request $request): Response
    {
        $name = $request->get('name');
        $type = $request->get('type');
        $parent = $request->get('parent');
        $parentMetadata = null;
        if ($parent) {
            /** @var Metadata $parentMetadata */
            $parentMetadata = $this->getRepository()->find($parent);
        }
        $metadata = new Metadata();
        $metadata->setName($name)->setType($type);
        if ($parentMetadata) {
            $metadata->setParent($parentMetadata);
        }
        $this->getRepository()->add($metadata);

        return $this->app['haljson']($metadata, Response::HTTP_CREATED);
    }

    public function removeMetadata($id): Response
    {
        /** @var Metadata $metadata */
        $metadata = $this->getRepository()->find($id);
        if ($metadata) {
            // TODO: this might have children
            $this->getRepository()->remove($metadata);
        }

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }
}
