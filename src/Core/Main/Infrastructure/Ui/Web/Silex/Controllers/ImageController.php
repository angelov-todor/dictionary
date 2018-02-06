<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Model\ImageCreated;
use Core\Main\Domain\Model\ImageMetadataAdded;
use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Model\User\User;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Domain\Model\DoctrineImageMetadataRepository;
use Core\Main\Infrastructure\Domain\Model\DoctrineImageRepository;
use Core\Main\Infrastructure\Domain\Model\DoctrineMetadataRepository;
use Ddd\Domain\DomainEventPublisher;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Hateoas\Representation\CollectionRepresentation;
use Imagine\Filter\Transformation;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    protected $filters = [
        'thumb' => [
            'filter' => 'thumbnail',
            'width' => 120,
            'height' => 90,
            'mode' => ImageInterface::THUMBNAIL_OUTBOUND,
            'allow_upscale' => true
        ],
        'large' => [
            'filter' => 'thumbnail',
            'width' => 640,
            'height' => 480,
            'mode' => ImageInterface::THUMBNAIL_OUTBOUND,
            'allow_upscale' => true
        ],
    ];

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
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;
        /* @var $factory ControllerCollection */
        $factory = $this->app['controllers_factory'];
        $factory->get('/images-enrich', [$this, 'getImageForEnrichment']);
        $factory->get('/images/search', [$this, 'searchImage']);

        $factory->get('/images', [$this, 'getImages']);

        $factory->post('/images-upload', [$this, 'imageUpload']);
        $factory->post('/images-upload-external', [$this, 'uploadExternalImage']);

        $factory->get('/images/{id}', [$this, 'viewImage']);
        $factory->delete('/images/{id}', [$this, 'deleteImage']);
        $factory->post('/images/{id}/metadata', [$this, 'createMetadata']);
        $factory->delete('/images/{id}/metadata', [$this, 'deleteMetadata']);

        $factory->get('/image/{filter}/{resource}', [$this, 'getImage']);

        return $factory;
    }

    /**
     * @return DoctrineImageRepository
     */
    protected function getRepository()
    {
        return $this->app[ImageRepositoryInterface::class];
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->app['em'];
    }

    /**
     * @return DoctrineMetadataRepository
     */
    protected function getMetadataRepository()
    {
        return $this->app[MetadataRepositoryInterface::class];
    }

    /**
     * @return DoctrineImageMetadataRepository
     */
    protected function getImageMetadataRepository()
    {
        return $this->app[ImageMetadataRepositoryInterface::class];
    }

    /**
     * @return Response
     */
    public function getImageForEnrichment(): Response
    {
        $imageCount = $this->app[ImageRepositoryInterface::class]->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomImage = null;
        if ($imageCount) {
            $randomImage = $this->app[ImageRepositoryInterface::class]->createQueryBuilder('i')
                ->select('i')
                ->setMaxResults(1)
                ->setFirstResult(rand(0, intval($imageCount) - 1))
                ->getQuery()
                ->getSingleResult();
        }

        $metadataCount = $this->app[MetadataRepositoryInterface::class]->createQueryBuilder('m')
            ->select('count(m)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomMetadata = null;
        if ($metadataCount) {
            $randomMetadata = $this->app[MetadataRepositoryInterface::class]->createQueryBuilder('m')
                ->select('m')
                ->setMaxResults(1)
                ->setFirstResult(rand(0, intval($metadataCount) - 1))
                ->getQuery()
                ->getSingleResult();
        }

        $r = new \stdClass();
        $r->image = $randomImage;
        $r->metadata = $randomMetadata;
        $r->question = 'The big question?';


        return $this->app['haljson']($r, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getImages(Request $request): Response
    {
        $termFilter = $request->get('term');
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $results = $this->getRepository()->viewBy($page, $limit, $termFilter);
        $count = $this->getRepository()->countBy($termFilter);

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'images' // embedded rel
            ),
            'images', // route
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

    public function viewImage($id): Response
    {
        return $this->app['haljson']($this->getRepository()->find($id));
    }

    /**
     * @param $id
     * @return Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function deleteImage($id): Response
    {
        /** @var Image $image */
        $image = $this->getRepository()->find($id);
        $path = Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . $image->getSrc();

        file_exists($path) && is_writable($path) && unlink($path);

        $imageMetadataRepository = $this->getImageMetadataRepository();
        $imageRepository = $this->getRepository();

        $this->getEntityManager()->transactional(function () use (
            $image,
            $imageRepository,
            $imageMetadataRepository
        ) {
            $imageMetadata = $image->getImageMetadata();
            foreach ($imageMetadata as $imageMetadatum) {
                $imageMetadataRepository->remove($imageMetadatum);
            }
            $imageRepository->remove($image);
        });

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function imageUpload(Request $request): Response
    {
        $requestContent = $request->getContent();
        $json = json_decode($requestContent);

        $filename = $json->filename;
        $data = $json->data;

        if (!$filename || !$data) {
            return $this->app['haljson']([
                'filename' => $filename,
                'data' => $data,
                'content' => $request->getContent()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $uuidName = $this->getToken(40) . '.' . $ext;
        $location = Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . $uuidName;
        $file = getcwd() . DIRECTORY_SEPARATOR . $location;

        $image = new Image($this->getUserContext());
        $this->base64ToJpeg($data, $file);
        $image->setSrc($uuidName);
        $this->getRepository()->add($image);

        DomainEventPublisher::instance()->publish(new ImageCreated(
            $image->getId(),
            null
        ));


        return $this->app['haljson']($image, Response::HTTP_CREATED);
    }

    /**
     * @param string $base64
     * @param string $filename
     * @return string
     */
    protected function base64ToJpeg(string $base64, string $filename): string
    {
        // open the output file for writing
        $ifp = fopen($filename, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $filename;
    }

    /**
     * @param int $length
     * @return string
     */
    protected function getToken(int $length): string
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }

        return $token;
    }


    public function createMetadata($id, Request $request): Response
    {
        /** @var Image $image */
        $image = $this->getRepository()->find($id);
        /** @var Metadata $metadata */
        $metadata = $this->getMetadataRepository()->find($request->get('metadata'));
        $imageMetadata = new ImageMetadata();
        $imageMetadata->setImage($image)
            ->setMetadata($metadata)
            ->setValue(strval($request->get('value')));

        $this->getImageMetadataRepository()->add($imageMetadata);
        DomainEventPublisher::instance()->publish(new ImageMetadataAdded(
            $image->getId(),
            $imageMetadata->getId(),
            $imageMetadata->getMetadata()->getId(),
            $imageMetadata->getMetadata()->getName(),
            $imageMetadata->getValue()
        ));

        return $this->app['haljson']($imageMetadata, Response::HTTP_CREATED);
    }

    public function deleteMetadata($id): Response
    {
        /** @var ImageMetadata $imageMetadata */
        $imageMetadata = $this->getImageMetadataRepository()->find($id);
        $this->getImageMetadataRepository()->remove($imageMetadata);

        return $this->app['haljson'](null, Response::HTTP_NO_CONTENT);
    }

    public function getImage(Request $request)
    {
        $image = $request->get('resource');
        $filter = $request->get('filter');

        $path = Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . $image;
        if (!file_exists($path)) {
            $path = Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . 'image_not_found.jpg';
            if (!file_exists($path)) {
                return $this->app['haljson']([
                    'location' => $path,
                    'cwd' => getcwd(),
                    'error' => 'Not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        if ($filter == 'large') {
            return $this->dynamicImage('large', $path);
        }

        return $this->dynamicImage('thumb', $path);
    }

    /**
     * @param string $name
     * @param string $file
     * @return RedirectResponse
     */
    protected function dynamicImage(string $name, string $file): RedirectResponse
    {
        $options = $this->filters[$name];
        $filter = $options['filter'];
        $assets = '/var/cache/assets/';
        $cacheDir = $this->app['app-path'] . $assets;

        $basename = pathinfo($file, PATHINFO_BASENAME);
        $cacheImage = $cacheDir . $name . '-' . $basename;
        if (!file_exists($cacheImage)) {
            if (!file_exists($cacheDir)) {
                @mkdir($cacheDir);
            }
            $format = pathinfo($file, PATHINFO_EXTENSION);
            $image = $this->app['imagine']->open($file);

            $transformation = new Transformation();
            $transformation->$filter(new Box($options['width'], $options['height']), $options['mode']);
            $image = $transformation->apply($image);

            $imageBlob = $image->get($format);
            file_put_contents($cacheImage, $imageBlob);
        }
        return new RedirectResponse($assets . $name . '-' . $basename, 301);
    }

// TODO: move variables to proper place

    /**
     * @var string
     */
    protected $cx = '009818310687169508712:jsxa4kza75m';
    /**
     * @var string
     */
    protected $key = 'AIzaSyAKX55B8g6DBfDfkJ-nzhqj-hDUUKcLqLc';
    /**
     * @var string
     */
    protected $uri = 'https://www.googleapis.com/customsearch/v1?key=%s&cx=%s&q=%s&searchType=image&start=%s';

    public function searchImage(Request $request): Response
    {
        $term = $request->get('term');
        $page = $request->get('page', 1);
        $start = (($page - 1) * 10) + 1;
        $url = sprintf($this->uri, $this->key, $this->cx, $term, $start);
        $response = file_get_contents($url);

        return new JsonResponse(json_decode($response));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function uploadExternalImage(Request $request): Response
    {
        $imageLink = $request->get('link');
        // TODO: check for valid extensions
        $ext = pathinfo($imageLink, PATHINFO_EXTENSION);
        $uuidName = $this->getToken(40) . '.' . $ext;
        $location = Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . $uuidName;
        $file = getcwd() . DIRECTORY_SEPARATOR . $location;

        file_put_contents($file, file_get_contents($imageLink));
        $image = new Image($this->getUserContext());
        $image->setSrc($uuidName);
        $this->getRepository()->add($image);

        DomainEventPublisher::instance()->publish(new ImageCreated(
            $image->getId(),
            $imageLink
        ));

        return $this->app['haljson']($image, Response::HTTP_CREATED);
    }
}
