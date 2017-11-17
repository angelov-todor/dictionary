<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Controllers;

use Core\Main\Application\Service\Unit\ViewPaymentSummaryRequest;
use Core\Main\Application\Service\Unit\ViewPaymentSummaryService;
use Core\Main\Application\Service\User\ChangeContactNameRequest;
use Core\Main\Application\Service\User\ChangeContactNameService;
use Core\Main\Application\Service\User\ChangeLocaleRequest;
use Core\Main\Application\Service\User\ChangeLocaleService;
use Core\Main\Application\Service\User\UserChangeTimezoneRequest;
use Core\Main\Application\Service\User\UserChangeTimezoneService;
use Core\Main\Application\Service\User\UserVerifyEmailRequest;
use Core\Main\Application\Service\User\UserVerifyEmailService;
use Core\Main\Domain\Model\Unit\Unit;
use Core\Main\Infrastructure\DataTransformer\PaginatedCollection;
use Core\Main\Infrastructure\Helpers\DateTimeHelper;
use Core\Main\Infrastructure\Ui\Web\Silex\Encoders\StringEncoderInterface;
use Hateoas\Representation\CollectionRepresentation;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Core\Main\Application\Service\User\UserChangeEmailRequest;
use \Core\Main\Application\Service\User\UserChangeEmailService;
use \Core\Main\Application\Service\User\UserChangePasswordRequest;
use \Core\Main\Application\Service\User\UserChangePasswordService;
use \Core\Main\Domain\Model\User\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IdentityController implements ControllerProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app): ControllerCollection
    {
        $this->app = $app;

        $factory = $this->app['controllers_factory'];

        // register routes
        $factory->get('/me', [$this, 'me']);
        $factory->put('/me/email', [$this, 'changeEmail']);
        $factory->put('/me/timezone', [$this, 'changeTimezone']);
        $factory->put('/me/password', [$this, 'changePassword']);
        $factory->put('/me/contact-name', [$this, 'contactName']);
        $factory->put('/email/verification/{checksum}', [$this, 'verifyEmail']);
        $factory->put('/me/locale', [$this, 'changeLocale']);
        $factory->get('/me/payments-summary', [$this, 'paymentsSummary']);

        return $factory;
    }

    /**
     * @return User
     */
    protected function getUserContext(): User
    {
        /* @var $userToken \Core\Main\Infrastructure\Ui\Web\Silex\User\User */
        $userToken = $this->app['security.token_storage']->getToken()->getUser();

        return $userToken->getDomainUser();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeTimezone(Request $request): Response
    {
        $user = $this->getUserContext();
        $timezone = $request->get('timezone', '');
        $user = $this->app[UserChangeTimezoneService::class]->execute(
            new UserChangeTimezoneRequest($user->getId(), $timezone)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeEmail(Request $request): Response
    {
        $user = $this->getUserContext();
        $email = $request->get('email', '');
        $user = $this->app[UserChangeEmailService::class]->execute(
            new UserChangeEmailRequest($user->getId(), $email)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        $user = $this->getUserContext();
        $currentPassword = $request->get('current_password', '');
        $newPassword = $request->get('new_password', '');
        $user = $this->app[UserChangePasswordService::class]->execute(
            new UserChangePasswordRequest($user->getId(), $currentPassword, $newPassword)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function me(Request $request): Response
    {
        $requestToken = $request->headers->get(
            $this->app['app-config']['jwt']['header'],
            $request->headers->get('Authorization')
        );
        $requestToken = trim(str_replace("Bearer", "", $requestToken));
        $tokenParts = explode('.', $requestToken);
        $header = json_decode(base64_decode(reset($tokenParts)));
        $payload = $this->app['security.jwt.encoder']->decode($requestToken);

        return $this->app->json(
            [
                'jwt' => [
                    'header' => $header,
                    'payload' => $payload
                ]
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param string $checksum
     * @return Response
     */
    public function verifyEmail(string $checksum): Response
    {
        $email = $this->app[StringEncoderInterface::class]->decode($checksum);
        $user = $this->app[UserVerifyEmailService::class]->execute(new UserVerifyEmailRequest($email));

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeLocale(Request $request): Response
    {
        $user = $this->getUserContext();
        $locale = $request->get('locale', '');
        $user = $this->app[ChangeLocaleService::class]->execute(
            new ChangeLocaleRequest($user->getId(), $locale)
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function paymentsSummary(Request $request): Response
    {
        $json = true;
        $accept = $request->headers->get('Accept');
        if ($accept == 'text/csv') {
            $json = false;
        }
        $from = DateTimeHelper::createDateFromRequest($request, 'from');
        $to = DateTimeHelper::createDateFromRequest($request, 'to');

        $summary = $this->app[ViewPaymentSummaryService::class]->execute(
            new ViewPaymentSummaryRequest(
                $this->getUserContext()->getId(),
                $from,
                $to
            )
        );
        if (!$json) {
            return $this->paymentSummaryAsCsv($from, $summary);
        }
        return $this->app['haljson'](new PaginatedCollection(
            new CollectionRepresentation(
                $summary,
                'payments-summary' // embedded rel
            ),
            'me/payments-summary', // route
            $request->query->all(), // route parameters
            1,       // page number
            100,      // limit
            ceil(count($summary) / 100), // total pages
            'page', // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false, // generate relative URIs, optional, defaults to `false`
            count($summary),       // total collection size, optional, defaults to `null`
            count($summary)//  current element count
        ));
    }

    /**
     * @param \DateTime $from
     * @param array $summary
     * @return Response
     */
    protected function paymentSummaryAsCsv(\DateTime $from, array $summary): Response
    {
        // TODO: date formats regarding Excel
        $response = new StreamedResponse();
        $response->setCallback(function () use ($summary) {
            $headers = [
                'payment_id',
                'status',
                'payment_due_date',
                'payment_paid_date',
                'amount',
                'currency',
                'property',
                'room',
                'tenant',
                'tenant_email',
                'lease_start',
                'lease_end'
            ];
            $delimiter = ';';
            $handle = fopen('php://output', 'w+');
            // Add the header of the CSV file
            fputcsv($handle, $headers, $delimiter);

            $unitsWithPayments = array_filter($summary, function ($leasePaymentUnit) {
                return isset($leasePaymentUnit['leases']) &&
                    isset($leasePaymentUnit['leases'][0]['lease_payments']);
            });

            foreach ($unitsWithPayments as $unitWithPayments) {
                foreach ($unitWithPayments['leases'] as $lease) {
                    if (!isset($lease['lease_payments'])) {
                        continue;
                    }
                    foreach ($lease['lease_payments'] as $leasePayment) {
                        fputcsv(
                            $handle,
                            [
                                $leasePayment['id'],
                                $leasePayment['status'],
                                $leasePayment['expected_at']->format('Y-m-d'),
                                $leasePayment['paid_at'] ? $leasePayment['paid_at']->format('Y-m-d') : null,
                                number_format(($leasePayment['amount'] / 100), 2),
                                $leasePayment['currency'],
                                $unitWithPayments['property_name'],
                                $unitWithPayments['unit_type'] == Unit::UNIT_TYPE_WHOLE ?
                                    null :
                                    $unitWithPayments['unit_name'],
                                $lease['tenant_name'],
                                $lease['tenant_email'],
                                $lease['start']->format('Y-m-d'),
                                $lease['end']->format('Y-m-d')
                            ],
                            $delimiter
                        );
                    }
                }
            }
            fclose($handle);
        });
        $filename = sprintf('localhost-payment-summary-%s-%s', $from->format('Y'), $from->format('m'));
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function contactName(Request $request): Response
    {
        $contactName = $request->get('contact_name', '');
        $user = $this->app[ChangeContactNameService::class]->execute(
            new ChangeContactNameRequest($this->getUserContext()->getId(), strval($contactName))
        );

        return $this->app['haljson']($user, Response::HTTP_NO_CONTENT);
    }
}
