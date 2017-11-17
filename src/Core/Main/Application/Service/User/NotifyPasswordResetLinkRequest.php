<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class NotifyPasswordResetLinkRequest
{
    /**
     * @var string
     */
    protected $resetPasswordId;

    /**
     * EmailPasswordResetRequest constructor.
     * @param string $resetPasswordId
     */
    public function __construct(string $resetPasswordId)
    {
        $this->resetPasswordId = $resetPasswordId;
    }

    /**
     * @return string
     */
    public function getResetPasswordId(): string
    {
        return $this->resetPasswordId;
    }
}
