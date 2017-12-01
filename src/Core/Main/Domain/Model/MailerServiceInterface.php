<?php
declare(strict_types=1);

namespace Core\Main\Domain\Model;

interface MailerServiceInterface
{
    const ACTIVATION_EMAIL = 'activation_email';
    const RESET_PASSWORD_EMAIL = 'reset_password_email';

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
