<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class NotifyLandlordPaymentFailedRequest
{
    /** @var string */
    private $leaseId;

    /** @var int */
    private $serie;

    /** @var string */
    private $landlordId;

    /**
     * NotifyUserPaymentFailedRequest constructor.
     *
     * @param string $leaseId
     * @param int $serie
     * @param string $landlordId
     */
    public function __construct(string $leaseId, int $serie, string $landlordId)
    {
        $this->leaseId = $leaseId;
        $this->serie = $serie;
        $this->landlordId = $landlordId;
    }

    /**
     * @return string
     */
    public function getLeaseId(): string
    {
        return $this->leaseId;
    }

    /**
     * @return int
     */
    public function getSerie(): int
    {
        return $this->serie;
    }

    /**
     * @return string
     */
    public function getLandlordId(): string
    {
        return $this->landlordId;
    }
}
