<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\StoredEvent;

use Core\Main\Domain\Repository\StoredEventRepositoryInterface;
use Ddd\Application\Service\ApplicationService;

class ViewStoredEventsService implements ApplicationService
{
    /**
     * @var StoredEventRepositoryInterface
     */
    protected $storedEventsRepository;

    /**
     * ViewStoredEventsService constructor.
     * @param StoredEventRepositoryInterface $storedEventsRepository
     */
    public function __construct(StoredEventRepositoryInterface $storedEventsRepository)
    {
        $this->storedEventsRepository = $storedEventsRepository;
    }

    /**
     * @return StoredEventRepositoryInterface
     */
    protected function getStoredEventsRepository(): StoredEventRepositoryInterface
    {
        return $this->storedEventsRepository;
    }

    /**
     * @param ViewStoredEventsRequest $request
     * @return ViewStoredEventsResponse
     */
    public function execute($request = null): ViewStoredEventsResponse
    {
        $count = $this->getStoredEventsRepository()->findOfCount();

        if ($count == 0) {
            return new ViewStoredEventsResponse([], 0);
        }

        $storedEvents = $this->getStoredEventsRepository()->findOf($request->getLimit(), $request->getOffset());

        return new ViewStoredEventsResponse($storedEvents, $count);
    }
}
