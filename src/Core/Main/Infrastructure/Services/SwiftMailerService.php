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
    ];

    /**
     * @var array List of all templates supported
     */
    public static $templates = [
        MailerServiceInterface::RESET_PASSWORD_EMAIL,
        MailerServiceInterface::ACTIVATION_EMAIL,
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
