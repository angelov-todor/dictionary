<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User\Notifications;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Model\Payment\Payment;
use Ddd\Application\Service\ApplicationService;

class NotifyTenantChargeFailedService implements ApplicationService
{
    use NotificationTrait;

    /**
     * @param NotifyTenantChargeFailedRequest $request
     * @return int
     */
    public function execute($request = null): int
    {
        /** @var Payment $payment */
        $payment = $this->getPaymentRepository()->findOfLeaseIdAndSerie($request->getLeaseId(), $request->getSerie());
        if (is_null($payment)) {
            return 0;
        }

        return $this->send(
            $payment->getLeasePayment()->getLease()->getTenant()->getEmail(),
            MailerServiceInterface::PAYMENT_FAILED_TENANT_EMAIL,
            $this->getNotificationData($payment),
            $payment->getLeasePayment()->getLease()->getTenant()->getLocale()
        );
    }
}
