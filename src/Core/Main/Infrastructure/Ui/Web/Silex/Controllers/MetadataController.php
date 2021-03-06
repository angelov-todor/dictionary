<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Domain\Model\DoctrineMetadataRepository;
use Doctrine\ORM\NonUniqueResultException;
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
        $factory->put('/metadata/{id}', [$this, 'updateMetadata']);
        $factory->get('/metadata/{id}', [$this, 'viewMetadata']);
        $factory->delete('/metadata/{id}', [$this, 'removeMetadata']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getMetadata(Request $request): Response
    {
        $nameFilter = $request->get('name');
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        // null - default - no filter, false - no parent, int - parent
        $parent = $request->get('parent');

        $results = $this->getRepository()->viewBy($nameFilter, $page, $limit, $parent);
        $count = $this->getRepository()->countBy($nameFilter, $parent);

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

    /**
     * @param $id
     * @return Response
     */
    public function viewMetadata($id): Response
    {
        $metadata = $this->getRepository()->find($id);
        return $this->app['haljson']($metadata, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addMetadata(Request $request): Response
    {
        $name = $request->get('name');
        $type = $request->get('type');
        $parent = $request->get('parent_id');
        $values = $request->get('values');
        $parentMetadata = null;
        if ($parent) {
            /** @var Metadata $parentMetadata */
            $parentMetadata = $this->getRepository()->find($parent);
        }
        $metadata = new Metadata();
        $metadata->setName($name)
            ->setType($type)
            ->setValues($values);
        if ($parentMetadata) {
            $metadata->setParent($parentMetadata);
        }
        $this->getRepository()->add($metadata);

        return $this->app['haljson']($metadata, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateMetadata($id, Request $request): Response
    {
        $name = $request->get('name');
        $type = $request->get('type');
        $parent = $request->get('parent_id');
        $values = $request->get('values');
        $parentMetadata = null;
        if ($parent) {
            /** @var Metadata $parentMetadata */
            $parentMetadata = $this->getRepository()->find($parent);
        }
        /** @var Metadata $metadata */
        $metadata = $this->getRepository()->find($id);
        $metadata->setName($name)
            ->setType($type)
            ->setValues($values);
        if ($metadata->getParent()) {
            $metadata->setParent(null);
        }
        if ($parentMetadata) {
            $metadata->setParent($parentMetadata);
        }

        $this->getRepository()->update($metadata);

        return $this->app['haljson']($metadata, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
