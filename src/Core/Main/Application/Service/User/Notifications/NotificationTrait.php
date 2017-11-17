<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User\Notifications;

use Core\Main\Domain\Model\Payment\Payment;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Repository\PaymentRepositoryInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;

trait NotificationTrait
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
     * NotificationTrait constructor.
     *
     * @param SwiftMailerService $mailService
     * @param PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(
        SwiftMailerService $mailService,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->mailService = $mailService;
        $this->paymentRepository = $paymentRepository;
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
     * @param Payment $payment
     * @return array
     */
    protected function getNotificationData(Payment $payment)
    {
        $leasePayment = $payment->getLeasePayment();
        $lease = $leasePayment->getLease();
        $unit = $lease->getUnit();
        $landlordName = $unit->getProperty()->getLandlord()->getEmailTemplateSettings()->getContactName();
        $data = [
            'tenant' => $lease->getTenant()->getName(),
            'serie' => $leasePayment->getSerie(),
            'total_series' => $lease->getTotalSeries(),
            'property' => $unit->getProperty()->getName(),
            'currency' => $payment->getCurrency(),
            'amount' => number_format(($payment->getAmount() / 100), 2),
            'landlord_name' => $landlordName
        ];
        if ($unit->getProperty()->getFormattedAddress()) {
            $data['address'] = $unit->getProperty()->getFormattedAddress();
        }
        if ($leasePayment->getNote()) {
            $data['note'] = $leasePayment->getNote();
        }
        if ($unit->getName() != Unit::WHOLE_PROPERTY_UNIT_NAME) {
            $data['unit'] = $unit->getName();
        }
        return $data;
    }

    /**
     * @param string $to
     * @param string $template
     * @param array $data
     * @param string|null $locale
     * @return int
     */
    protected function send(string $to, string $template, array $data, ?string $locale)
    {
        return $this->getMailService()->sendTemplate(
            $to,
            $template,
            $data,
            $locale
        );
    }
}
