<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

class NotifyTenantPaymentSucceededRequest
{
    /**
     * @var string
     */
    protected $paymentId;

    /**
     * NotifyTenantPaymentSucceededRequest constructor.
     *
     * @param string $paymentId
     */
    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }
}
