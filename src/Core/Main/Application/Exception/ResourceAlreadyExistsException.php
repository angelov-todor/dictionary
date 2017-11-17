<?php
declare(strict_types=1);

namespace Core\Main\Application\Exception;

class ResourceAlreadyExistsException extends \Exception implements ResourceConflictExceptionInterface
{

}
