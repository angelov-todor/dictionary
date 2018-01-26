<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Service\WordTools;
use Core\Main\Domain\Model\Dictionary\Word;
use Core\Main\Domain\Repository\DictionaryRepositoryInterface;
use Core\Main\Domain\Repository\WordRepositoryInterface;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Domain\Model\DoctrineWordRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WordController implements ControllerProviderInterface
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
        $factory->get('/words', [$this, 'getWords']);
        $factory->get('/words/{id}', [$this, 'getWord']);

        $factory->get('/tools/syllables', [$this, 'getSyllables']);
        $factory->get('/tools/transcription', [$this, 'getTranscription']);
        $factory->get('/tools/phonemes', [$this, 'getPhonemes']);
        $factory->get('/tools/rhymeform', [$this, 'getRhymeforms']);
        $factory->get('/tools/reduction', [$this, 'getReduction']);
        $factory->get('/tools/rhymes', [$this, 'getRhymes']);

        return $factory;
    }

    /**
     * @return DoctrineWordRepository
     */
    protected function getRepository()
    {
        return $this->app[WordRepositoryInterface::class];
    }

    /**
     * @param $id
     * @return Response
     */
    public function getWord($id): Response
    {
        /** @var Word $word */
        $word = $this->getRepository()->find($id);
        return $this->app['haljson']($word);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getWords(Request $request): Response
    {
        $nameFilter = $request->get('name');
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', 20));

        $count = $this->getRepository()->countBy($nameFilter);
        $results = $this->getRepository()->viewBy($nameFilter, $page, $limit);

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $results,
                'words' // embedded rel
            ),
            'words', // route
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
    public function getSyllables(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $response = $tools->syllables($request->get('word'));

        return $this->app['haljson']($response);
    }

    /**
     * TODO: fix the algorithm usage
     * @param Request $request
     * @return Response
     */
    public function getTranscription(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $response = $tools->transcription($request->get('word'));

        return $this->app['haljson']($response);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getPhonemes(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $response = $tools->phonemes($request->get('word'));

        return $this->app['haljson']($response);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getRhymeforms(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $response = $tools->rhymeform($request->get('word'));

        return $this->app['haljson']($response);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getReduction(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $response = $tools->reduction($request->get('word'));

        return $this->app['haljson']($response);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getRhymes(Request $request): Response
    {
        /** @var WordTools $tools */
        $tools = $this->app[WordTools::class];

        $rhymeform = $tools->rhymeform($request->get('word'));

        /** @var EntityRepository $repository */
        $repository = $this->app[DictionaryRepositoryInterface::class];

        $words = $repository->findBy(['rhymeform' => $rhymeform], null, 30);

        return $this->app['haljson']($words);
    }
}
