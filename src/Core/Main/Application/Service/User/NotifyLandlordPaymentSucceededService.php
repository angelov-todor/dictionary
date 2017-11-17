<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\User;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Domain\Repository\LeasePaymentRepositoryInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;
use Ddd\Application\Service\ApplicationService;

class NotifyLandlordPaymentSucceededService implements ApplicationService
{
    /**
     * @var SwiftMailerService
     */
    protected $mailService;

    /**
     * @var LeasePaymentRepositoryInterface
     */
    protected $leasePaymentRepository;

    /**
     * NotifyLandlordPaymentSucceededService constructor.
     * @param SwiftMailerService $mailService
     * @param LeasePaymentRepositoryInterface $leasePaymentRepository
     */
    public function __construct(
        SwiftMailerService $mailService,
        LeasePaymentRepositoryInterface $leasePaymentRepository
    ) {
        $this->mailService = $mailService;
        $this->leasePaymentRepository = $leasePaymentRepository;
    }

    /**
     * @return SwiftMailerService
     */
    protected function getMailService(): SwiftMailerService
    {
        return $this->mailService;
    }

    /**
     * @return LeasePaymentRepositoryInterface
     */
    protected function getLeasePaymentRepository(): LeasePaymentRepositoryInterface
    {
        return $this->leasePaymentRepository;
    }

    /**
     * @param NotifyLandlordPaymentSucceededRequest $request
     * @return int
     */
    public function execute($request = null): int
    {
        $leasePayment = $this->getLeasePaymentRepository()->ofId($request->getLeasePaymentId());

        $lease = $leasePayment->getLease();

        $data = [
            'tenant' => $lease->getTenant()->getName(),
            'property' => $lease->getUnit()->getProperty()->getName(),
            'currency' => $leasePayment->getPrice()->getCurrency(),
            'amount' => number_format($leasePayment->getPrice()->getAmount() / 100, 2),
            'serie' => $leasePayment->getSerie(),
            'total_series' => $lease->getTotalSeries()
        ];
        if ($lease->getUnit()->getType() != Unit::UNIT_TYPE_WHOLE) {
            $data['unit'] = $lease->getUnit()->getName();
        }
        if ($lease->getUnit()->getProperty()->getFormattedAddress()) {
            $data['address'] = $lease->getUnit()->getProperty()->getFormattedAddress();
        }
        $user = $leasePayment->getLease()->getUnit()->getProperty()->getLandlord();

        return $this->getMailService()->sendTemplate(
            $user->getEmail(),
            MailerServiceInterface::PAYMENT_SUCCEEDED_EMAIL,
            $data,
            $user->getLocale()
        );
    }
}
