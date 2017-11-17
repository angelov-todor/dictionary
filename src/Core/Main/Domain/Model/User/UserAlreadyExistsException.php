<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Core\Main\Application\Exception\ResourceConflictExceptionInterface;

class UserAlreadyExistsException extends \Exception implements ResourceConflictExceptionInterface
{

}
