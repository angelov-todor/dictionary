<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

interface MailerServiceInterface
{
    const WELCOME_TENANT_EMAIL = 'welcome_tenant_email';
    const WELCOME_TENANT_EMAIL_FAILED = 'welcome_tenant_email_failed';
    const PAYMENT_NOTIFICATION_EMAIL = 'payment_notification_email';
    const PAYMENT_SUCCEEDED_EMAIL = 'payment_succeeded_email';
    const PAYMENT_SUCCEEDED_TENANT_EMAIL = 'payment_succeeded_tenant_email';
    const PAYMENT_PENDING_TENANT_EMAIL = 'payment_pending_tenant_email';
    const ACTIVATION_EMAIL = 'activation_email';
    const RESET_PASSWORD_EMAIL = 'reset_password_email';
    const REFUND_TENANT_EMAIL = 'refund_tenant_email';
    const REFUND_LANDLORD_EMAIL = 'refund_landlord_email';
    const PAYMENT_FAILED_EMAIL = 'payment_failed_email';
    const PAYMENT_FAILED_TENANT_EMAIL = 'payment_failed_tenant_email';
    const NOT_PAID_PAYMENT_NOTIFICATION_EMAIL = 'not_paid_payment_notification_email'; // not used
    const PAYMENT_NOTIFICATION_REMINDER_EMAIL = 'payment_notification_reminder_email';
    const PAYMENT_NOTIFICATION_SECOND_REMINDER_EMAIL = 'payment_notification_second_reminder_email';
    const PAYMENT_NOTIFICATION_THIRD_REMINDER_EMAIL = 'payment_notification_third_reminder_email';
    const LEASE_TERMINATED_TENANT = 'lease_terminated_tenant';
    const LEASE_END_MODIFIED_TENANT = 'lease_end_modified_tenant';
    const LANDLORD_SUMMARY_EMAIL = 'landlord_summary_email';

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $charset
     * @return mixed
     */
    public function sendPlain(string $to, string $subject, string $body, string $charset);

    /**
     * @param string $to
     * @param string $template
     * @param array $data
     * @param null|string $locale
     * @param string $contentType
     * @param string $charset
     * @param array $metadata
     * @return mixed
     */
    public function sendTemplate(
        string $to,
        string $template,
        array $data,
        ?string $locale,
        string $contentType = 'text/html',
        string $charset = 'UTF-8',
        array $metadata = []
    );
}
