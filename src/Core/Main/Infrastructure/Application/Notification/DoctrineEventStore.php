<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Application\Notification;

use Core\Main\Domain\Repository\StoredEventRepositoryInterface;
use Core\Main\Infrastructure\Application\Serialization\Jms\Serializer;
use Ddd\Application\EventStore;
use Doctrine\ORM\EntityRepository;
use Core\Main\Domain\Model\StoredEvent;

class DoctrineEventStore extends EntityRepository implements EventStore, StoredEventRepositoryInterface
{
    /**
     * @param \Ddd\Domain\DomainEvent $aDomainEvent
     * @return void
     */
    public function append($aDomainEvent)
    {
        $storedEvent = new StoredEvent(
            get_class($aDomainEvent),
            $aDomainEvent->occurredOn(),
            Serializer::instance()->getSerializer()->serialize($aDomainEvent, 'json')
        );

        $this->getEntityManager()->persist($storedEvent);
        $this->getEntityManager()->flush($storedEvent);
    }

    /**
     * @param $anEventId
     * @return StoredEvent[]
     */
    public function allStoredEventsSince($anEventId)
    {
        $query = $this->createQueryBuilder('e');
        if ($anEventId) {
            $query->where('e.eventId > :eventId');
            $query->setParameters(array('eventId' => $anEventId));
        }
        $query->orderBy('e.eventId');

        return $query->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function findOfCount(): int
    {
        $count = $this->createQueryBuilder("e")
            ->select('count(distinct e)')
            ->getQuery()
            ->getSingleScalarResult();

        return intval($count);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findOf(int $limit, int $offset): array
    {
        $events = $this->createQueryBuilder("e")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('e.occurredOn', 'DESC')
            ->getQuery()
            ->getResult();

        return $events;
    }
}
