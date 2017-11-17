<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class ViewResetPasswordExpirationRequest
{
    /** @var string */
    private $hash;

    /**
     * ViewResetPasswordExpirationRequest constructor.
     *
     * @param null|string $hash
     */
    public function __construct(?string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return null|string
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }
}
