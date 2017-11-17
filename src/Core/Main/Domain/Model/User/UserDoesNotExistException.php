<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model\User;

use Core\Main\Application\Exception\ResourceNotFoundExceptionInterface;

class UserDoesNotExistException extends \Exception implements ResourceNotFoundExceptionInterface
{

}
