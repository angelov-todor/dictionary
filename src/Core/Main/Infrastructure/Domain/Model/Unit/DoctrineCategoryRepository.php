<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\Unit;

use Core\Main\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineCategoryRepository extends EntityRepository implements CategoryRepositoryInterface
{

}
