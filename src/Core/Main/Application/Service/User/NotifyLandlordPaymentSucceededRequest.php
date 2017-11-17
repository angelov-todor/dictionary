<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class NotifyLandlordPaymentSucceededRequest
{
    /**
     * @var string
     */
    protected $leasePaymentId;

    /**
     * NotifyLandlordPaymentSucceededRequest constructor.
     *
     * @param string $leasePaymentId
     */
    public function __construct(string $leasePaymentId)
    {
        $this->leasePaymentId = $leasePaymentId;
    }

    /**
     * @return string
     */
    public function getLeasePaymentId(): string
    {
        return $this->leasePaymentId;
    }
}
