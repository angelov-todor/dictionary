<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Domain\Model\MailerServiceInterface;
use Core\Main\Infrastructure\Services\SwiftMailerService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SwiftMailerServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        if ($app->offsetExists(MailerServiceInterface::class)) {
            return;
        }
        $options = $app['app-config']['mailer.options'];
        $app[MailerServiceInterface::class] = function () use ($app, $options) {
            $transport = \Swift_SmtpTransport::newInstance($options['host'], $options['port'], $options['encryption']);
            $transport->setUsername($options['username']);
            $transport->setPassword($options['password']);
            return new SwiftMailerService(
                \Swift_Mailer::newInstance($transport),
                $options['sender_email'],
                $options['sender_name'],
                $app['twig'],
                $app
            );
        };
    }
}
