<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Repository\LeaseRepositoryInterface;
use Core\Main\Domain\Repository\PaymentRepositoryInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;
use Ddd\Application\Service\ApplicationService;

class NotifyTenantPaymentSucceededService implements ApplicationService
{
    /**
     * @var SwiftMailerService
     */
    protected $mailService;

    /**
     * @var PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var LeaseRepositoryInterface
     */
    protected $leaseRepository;

    /**
     * NotifyTenantPaymentSucceededService constructor.
     * @param SwiftMailerService $mailService
     * @param PaymentRepositoryInterface $paymentRepository
     * @param LeaseRepositoryInterface $leaseRepository
     */
    public function __construct(
        SwiftMailerService $mailService,
        PaymentRepositoryInterface $paymentRepository,
        LeaseRepositoryInterface $leaseRepository
    ) {
        $this->mailService = $mailService;
        $this->paymentRepository = $paymentRepository;
        $this->leaseRepository = $leaseRepository;
    }

    /**
     * @return SwiftMailerService
     */
    protected function getMailService(): SwiftMailerService
    {
        return $this->mailService;
    }

    /**
     * @return PaymentRepositoryInterface
     */
    protected function getPaymentRepository(): PaymentRepositoryInterface
    {
        return $this->paymentRepository;
    }

    /**
     * @return LeaseRepositoryInterface
     */
    protected function getLeaseRepository(): LeaseRepositoryInterface
    {
        return $this->leaseRepository;
    }

    /**
     * @param NotifyTenantPaymentSucceededRequest $request
     * @return int
     */
    public function execute($request = null): int
    {
        $payment = $this->getPaymentRepository()->ofId($request->getPaymentId());

        $lease = $this->getLeaseRepository()->ofId(
            $payment->getLeaseId(),
            $payment->getLandlordId()
        );

        $data = [
            'tenant' => $lease->getTenant()->getName(),
            'property' => $lease->getUnit()->getProperty()->getName(),
            'serie' => $payment->getSerie(),
            'amount' => number_format($payment->getAmount() / 100, 2),
            'total_series' => $lease->getTotalSeries(),
            'currency' => $payment->getCurrency(),
        ];
        if ($lease->getUnit()->getProperty()->getFormattedAddress()) {
            $data['address'] = $lease->getUnit()->getProperty()->getFormattedAddress();
        }
        if ($lease->getUnit()->getType() != Unit::UNIT_TYPE_WHOLE) {
            $data['unit'] = $lease->getUnit()->getName();
        }

        return $this->getMailService()->sendTemplate(
            $lease->getTenant()->getEmail(),
            MailerServiceInterface::PAYMENT_SUCCEEDED_TENANT_EMAIL,
            $data,
            $lease->getTenant()->getLocale()
        );
    }
}
