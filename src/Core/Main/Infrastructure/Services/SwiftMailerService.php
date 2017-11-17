<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

use Core\Main\Application\Helper\Locale;
use Core\Main\Domain\Model\MailerServiceInterface;
use Pimple\Container;

class SwiftMailerService implements MailerServiceInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $senderEmail;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var array
     */
    protected $templateMap = [
        MailerServiceInterface::RESET_PASSWORD_EMAIL => 'reset_password_email.twig',
        MailerServiceInterface::ACTIVATION_EMAIL => 'activation_email.twig',
        MailerServiceInterface::PAYMENT_SUCCEEDED_EMAIL => 'payment_succeeded_email.twig',
        MailerServiceInterface::PAYMENT_SUCCEEDED_TENANT_EMAIL => 'payment_succeeded_tenant_email.twig',
        MailerServiceInterface::PAYMENT_NOTIFICATION_EMAIL => 'payment_notification_email.twig',
        MailerServiceInterface::WELCOME_TENANT_EMAIL => 'welcome_tenant_email.twig',
        MailerServiceInterface::WELCOME_TENANT_EMAIL_FAILED => 'welcome_tenant_email_failed.twig',
        MailerServiceInterface::REFUND_TENANT_EMAIL => 'refund_tenant_email.twig',
        MailerServiceInterface::REFUND_LANDLORD_EMAIL => 'refund_landlord_email.twig',
        MailerServiceInterface::PAYMENT_FAILED_EMAIL => 'payment_failed_email.twig',
        MailerServiceInterface::PAYMENT_FAILED_TENANT_EMAIL => 'payment_failed_tenant_email.twig',
        MailerServiceInterface::NOT_PAID_PAYMENT_NOTIFICATION_EMAIL => 'not_paid_payment_notification_email.twig',
        MailerServiceInterface::PAYMENT_NOTIFICATION_REMINDER_EMAIL => 'payment_notification_reminder_email.twig',
        MailerServiceInterface::PAYMENT_NOTIFICATION_SECOND_REMINDER_EMAIL =>
            'payment_notification_second_reminder_email.twig',
        MailerServiceInterface::PAYMENT_NOTIFICATION_THIRD_REMINDER_EMAIL =>
            'payment_notification_third_reminder_email.twig',
        MailerServiceInterface::LEASE_TERMINATED_TENANT => 'lease_terminated_tenant.twig',
        MailerServiceInterface::LANDLORD_SUMMARY_EMAIL => 'landlord_summary_email.twig',
        MailerServiceInterface::PAYMENT_PENDING_TENANT_EMAIL => 'payment_pending_tenant_email.twig',
        MailerServiceInterface::LEASE_END_MODIFIED_TENANT => 'lease_end_modified_tenant.twig',
        'encourage_users' => 'encourage_users.twig'
    ];

    /**
     * @var array List of all templates supported
     */
    public static $templates = [
        MailerServiceInterface::RESET_PASSWORD_EMAIL,
        MailerServiceInterface::ACTIVATION_EMAIL,
        MailerServiceInterface::PAYMENT_SUCCEEDED_EMAIL,
        MailerServiceInterface::PAYMENT_SUCCEEDED_TENANT_EMAIL,
        MailerServiceInterface::PAYMENT_NOTIFICATION_EMAIL,
        MailerServiceInterface::WELCOME_TENANT_EMAIL,
        MailerServiceInterface::WELCOME_TENANT_EMAIL_FAILED,
        MailerServiceInterface::REFUND_TENANT_EMAIL,
        MailerServiceInterface::REFUND_LANDLORD_EMAIL,
        MailerServiceInterface::PAYMENT_FAILED_EMAIL,
        MailerServiceInterface::PAYMENT_FAILED_TENANT_EMAIL,
        MailerServiceInterface::NOT_PAID_PAYMENT_NOTIFICATION_EMAIL,
        MailerServiceInterface::PAYMENT_NOTIFICATION_REMINDER_EMAIL,
        MailerServiceInterface::PAYMENT_NOTIFICATION_SECOND_REMINDER_EMAIL,
        MailerServiceInterface::PAYMENT_NOTIFICATION_THIRD_REMINDER_EMAIL,
        MailerServiceInterface::LEASE_TERMINATED_TENANT,
        MailerServiceInterface::LANDLORD_SUMMARY_EMAIL,
        MailerServiceInterface::PAYMENT_PENDING_TENANT_EMAIL,
        MailerServiceInterface::LEASE_END_MODIFIED_TENANT,
        'encourage_users'
    ];

    /**
     * SwiftMailerService constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param string $senderEmail
     * @param string $senderName
     * @param \Twig_Environment $twig
     * @param Container $app
     */
    public function __construct(
        \Swift_Mailer $mailer,
        string $senderEmail,
        string $senderName,
        \Twig_Environment $twig,
        $app
    ) {
        $this->mailer = $mailer;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
        $this->twig = $twig;
        $this->app = $app;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        return $this->mailer;
    }

    /**
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    /**
     * @return string
     */
    public function senderName(): string
    {
        return $this->senderName;
    }


    /**
     * @param string $sender
     * @return SwiftMailerService
     */
    public function setSender(string $sender): SwiftMailerService
    {
        $this->senderEmail = $sender;
        return $this;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig(): \Twig_Environment
    {
        return $this->twig;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $charset
     * @return int
     * @deprecated Use `sendTemplate`
     */
    public function sendPlain(string $to, string $subject, string $body, string $charset = 'UTF-8')
    {
        $contentType = 'text/plain';
        $message = new \Swift_Message($subject, $body, $contentType, $charset);
        $message->addTo($to);
        $message->addFrom($this->getSenderEmail(), $this->senderName());

        return $this->getMailer()->send($message);
    }

    /**
     * @param string $to
     * @param string $template
     * @param array $data
     * @param string $locale
     * @param string $contentType
     * @param string $charset
     * @param array $metadata
     * @return int
     */
    public function sendTemplate(
        string $to,
        string $template,
        array $data,
        ?string $locale = Locale::DEFAULT_LOCALE,
        string $contentType = 'text/html',
        string $charset = 'UTF-8',
        array $metadata = []
    ): int {
        $locale = $locale ?? Locale::DEFAULT_LOCALE;

        $language = Locale::getLanguageFromLocale($locale);

        if (isset($this->app['translator'])) {
            $this->app['translator']->setLocale($language);
        }

        $templateObj = $this->getTwig()->load($this->getTemplateFile($template));

        $subject = $templateObj->renderBlock('subject', $data);
        $body = $templateObj->renderBlock('body', $data);

        $message = new \Swift_Message($subject, $body, $contentType, $charset);
        $message->addTo($to)
            ->addFrom($this->getSenderEmail(), $this->senderName());
        //  additional headers to sent with the message
        if (!empty($metadata)) {
            $headers = $message->getHeaders();
            $metadata['environment'] = $this->app['app-config']['environment'];
            $headers->addTextHeader('X-MC-Metadata', json_encode($metadata));
        }

        return $this->getMailer()->send($message);
    }

    /**
     * @param string $template
     * @return string
     */
    public function getTemplateFile(string $template): string
    {
        return $this->templateMap[$template];
    }
}
