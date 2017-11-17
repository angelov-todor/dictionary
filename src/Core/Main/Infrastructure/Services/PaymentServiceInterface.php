<?php

namespace Core\Main\Infrastructure\Services;

use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Application\Service\Payment\ChangeStripeAccountRequest;
use Core\Main\Application\Service\Payment\CreateStripeAccountRequest;
use Core\Main\Domain\Model\Payment\StripeAccount;
use Stripe\Account;
use Stripe\BalanceTransaction;
use Stripe\Charge;

interface PaymentServiceInterface
{
    /**
     * @param StripeAccount $stripeAccount
     * @return string
     */
    public function generateAuthorizationLink(StripeAccount $stripeAccount): string;

    /**
     * @param string $authorizationCode
     * @return array
     */
    public function authorize(string $authorizationCode): array;

    /**
     * @return string
     */
    public function getPublicKey(): string;

    /**
     * The real charge method that deducts money from tenant and moves it to our account
     *
     * @param string $source
     * @param string $destinationAccount
     * @param int $amount
     * @param string $currency
     * @param string $description
     * @param array|null $metadata
     *
     * @return Charge
     */
    public function charge(
        string $source,
        string $destinationAccount,
        int $amount,
        string $currency,
        string $description,
        array $metadata = null
    );

    /**
     * The real charge method that deducts money from tenant and moves it to our account
     *
     * @param string $subscriptionId
     * @param string $destinationAccount
     * @param int $amount
     * @param string $currency
     * @param string $description
     * @param array|null $metadata
     *
     * @return Charge
     */
    public function chargeSubscription(
        string $subscriptionId,
        string $destinationAccount,
        int $amount,
        string $currency,
        string $description,
        array $metadata = null
    );

    /**
     * @param string $transactionId
     * @return BalanceTransaction
     * @throws ResourceNotFoundException
     */
    public function getBalanceTransaction(string $transactionId): BalanceTransaction;

    /**
     * @param string $userId
     * @return Account
     * @throws \Stripe\Error\Permission
     */
    public function getAccount(string $userId): Account;

    /**
     * @param CreateStripeAccountRequest $request
     * @return Account
     * @throws \Stripe\Error\InvalidRequest
     */
    public function createAccount(CreateStripeAccountRequest $request): Account;

    /**
     * @param string $accountId
     * @param ChangeStripeAccountRequest $request
     * @return Account
     * @throws \Stripe\Error\InvalidRequest
     */
    public function updateAccount(string $accountId, ChangeStripeAccountRequest $request): Account;

    /**
     * @param string $email
     * @param string $source
     * @return string Subscription Id
     */
    public function createSubscription(string $email, string $source): string;

    /**
     * @param string $subscriptionId
     */
    public function removeSubscription(string $subscriptionId): void;
}
