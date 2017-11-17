<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

class StoredEvent extends \Ddd\Domain\Event\StoredEvent
{
    const MAX_LIMIT = 100;

    public function getBodyArray()
    {
        return ['body' => json_decode($this->eventBody(), true)];
    }
}
