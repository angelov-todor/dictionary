<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Service\StoredEvent\ViewStoredEventsRequest;
use Core\Main\Application\Service\StoredEvent\ViewStoredEventsResponse;
use Core\Main\Application\Service\StoredEvent\ViewStoredEventsService;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Domain\Model\StoredEvent;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StoredEventController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;

        /* @var $factory ControllerCollection */
        $factory = $this->app['controllers_factory'];
        $factory->get('/system/events', [$this, 'getEvents']);

        return $factory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getEvents(Request $request): Response
    {
        $page = intval($request->get('page', 1));
        $limit = intval($request->get('limit', StoredEvent::MAX_LIMIT));

        $eventsResponse = $this->app[ViewStoredEventsService::class]
            ->execute(new ViewStoredEventsRequest($limit, ($page - 1) * $limit));
        /* @var $eventsResponse ViewStoredEventsResponse */

        $paginatedCollection = new PaginatedCollection(
            new CollectionRepresentation(
                $eventsResponse->getStoredEvents(),
                'events' // embedded rel
            ),
            'stored-events', // route
            $request->query->all(), // route parameters
            $page,       // page number
            $limit,      // limit
            ceil($eventsResponse->getCount() / $limit), // total pages
            'page', // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false, // generate relative URIs, optional, defaults to `false`
            $eventsResponse->getCount(),       // total collection size, optional, defaults to `null`
            count($eventsResponse->getStoredEvents())//  current element count
        );
        return $this->app['haljson']($paginatedCollection);
    }
}
