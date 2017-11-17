<?php

namespace Core\Main\Domain\Repository;

use Core\Main\Domain\Model\User\ResetPassword;

interface UserResetPasswordRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ResetPassword|null
     */
    public function ofId(string $id): ?ResetPassword;

    /**
     * @param string $id
     *
     * @return array|null
     */
    public function findOfUserId(string $id): ?array;

    /**
     * @param ResetPassword $resetPassword
     *
     * @return string
     */
    public function add(ResetPassword $resetPassword): string;

    /**
     * @param ResetPassword $resetPassword
     *
     * @return void
     */
    public function update(ResetPassword $resetPassword);

    /**
     * @param ResetPassword $resetPassword
     *
     * @return void
     */
    public function delete(ResetPassword $resetPassword);
}
