<?php
declare(strict_types=1);

namespace Core\Main\Domain\Repository;

use \Core\Main\Domain\Model\User\User;

interface UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function add(User $user);

    /**
     * @param User $user
     */
    public function update(User $user);

    /**
     * @param string $email
     * @return User
     */
    public function ofEmail(string $email): ?User;

    /**
     * @param string $id
     * @return User
     */
    public function ofId(string $id): ?User;
}
