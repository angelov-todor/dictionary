<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Domain\Model\User;

use Core\Main\Domain\Model\User\ResetPassword;
use Core\Main\Domain\Repository\UserResetPasswordRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineUserResetPasswordRepository extends EntityRepository implements UserResetPasswordRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function ofId(string $id): ?ResetPassword
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOfUserId(string $id): ?array
    {
        return $this->findBy(['userId' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResetPassword $resetPassword): string
    {
        $this->getEntityManager()->persist($resetPassword);
        $this->getEntityManager()->flush($resetPassword);

        return $resetPassword->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update(ResetPassword $resetPassword)
    {
        $this->getEntityManager()->flush($resetPassword);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ResetPassword $resetPassword)
    {
        $this->getEntityManager()->remove($resetPassword);
    }
}
