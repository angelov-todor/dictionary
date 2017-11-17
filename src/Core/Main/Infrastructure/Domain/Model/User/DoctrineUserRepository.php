<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\User;

use Doctrine\ORM\EntityRepository;
use \Core\Main\Domain\Repository\UserRepositoryInterface;
use \Core\Main\Domain\Model\User\User;

class DoctrineUserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function ofId(string $id): ?User
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    /**
     * {@inheritdoc}
     */
    public function ofEmail(string $name): ?User
    {
        return $this->findOneBy(['email' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        $this->getEntityManager()->flush($user);
    }
}
