<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex;

use Core\Main\Application\Service\Metadata\AddSourceMetadataService;
use Core\Main\Application\Service\Metadata\GenerateAdditionalMetadataService;
use Core\Main\Application\Service\Metadata\GenerateMetadataService;
use Core\Main\Application\Service\User\NotifyPasswordResetLinkService;
use Core\Main\Application\Service\User\NotifyUserValidationService;
use Core\Main\Domain\Event\AppendEventStoreSubscriber;
use Core\Main\Domain\Model\StoredEvent;
use Core\Main\Domain\Model\User\User;
use Core\Main\Infrastructure\Application\Serialization\Jms\Serializer;
use Core\Main\Infrastructure\DataTransformer\Provider\HALServiceProvider;
use Core\Main\Infrastructure\Persistence\Doctrine\EntityManagerProvider;
use Core\Main\Infrastructure\Ui\DomainEventSubscriber\ImageCreatedSubscriber;
use Core\Main\Infrastructure\Ui\DomainEventSubscriber\ImageMetadataAddedSubscriber;
use Core\Main\Infrastructure\Ui\DomainEventSubscriber\PasswordResetEmailSubscriber;
use Core\Main\Infrastructure\Ui\DomainEventSubscriber\UserValidateEmailSubscriber;
use Core\Main\Infrastructure\Ui\ProblemDetails\ProblemDetailsProvider;
use Core\Main\Infrastructure\Ui\Rollbar\RollbarProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\AuthController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\CognitiveSkillController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\CognitiveTypeController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\DictionaryController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\IdentityController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\ImageController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\MetadataController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\MethodologyController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\ResetPasswordController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\StoredEventController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\TestController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\UnitController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\UserController;
use Core\Main\Infrastructure\Ui\Web\Silex\Controllers\WordController;
use Core\Main\Infrastructure\Ui\Web\Silex\Jwt\Provider\JwtServiceProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\ImageServicesProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\ImagineServiceProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\StoredEventsServicesProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\SwiftMailerServiceProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\TestServicesProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\TwigServiceProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\UnitServicesProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\UserServicesProvider;
use Core\Main\Infrastructure\Ui\Web\Silex\Provider\WordServicesProvider;
use Ddd\Domain\DomainEventPublisher;
use Ddd\Infrastructure\Application\Service\DoctrineSession;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class Application
{
    public static function bootstrap(array $bootstrapValues = [])
    {
        // Timezone
        date_default_timezone_set('UTC');

        // Application
        $app = new \Silex\Application($bootstrapValues);
        // Error handling
        // It converts all errors to exceptions,
        // and exceptions are then caught by Silex - some of them!
        ErrorHandler::register();

        $app['app-path'] = realpath(__DIR__ . '/../../../../../../../');
        $app['app-config'] = include __DIR__ . '/../../../../../../../config/app-config.php';

        $app['debug'] = isset($app['app-config']['debug']) ? $app['app-config']['debug'] : false;
        error_reporting($app['app-config']['php']['error_reporting']);
        ini_set('display_errors', strval($app['app-config']['php']['display_errors']));

        $app->register(new RollbarProvider());

        // TODO: remove this at some point. This fails the Hateoas annotations at the moment
        AnnotationRegistry::registerLoader('class_exists');

        $app->register(new EntityManagerProvider());

        $app->register(
            new DoctrineServiceProvider(),
            ['db.options' => $app['app-config']['db.options']]
        );

        $app['tx_session'] = function () use ($app) {
            return new DoctrineSession($app['em']);
        };

        $app['event_store'] = function () use ($app) {
            return $app['em']->getRepository(StoredEvent::class);
        };

        $app->before(function (Request $request) use ($app) {
            if (0 === strpos(strval($request->headers->get('Content-Type')), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
            }
        });

        $app->register(new UserServicesProvider());
        $app->register(new ImageServicesProvider());
        $app->register(new StoredEventsServicesProvider());
        $app->register(new WordServicesProvider());
        $app->register(new UnitServicesProvider());
        $app->register(new TestServicesProvider());

        $app['hateoas.config'] = $app['app-config']['hateoas.options'];

        $app->register(new SwiftMailerServiceProvider());

        $app['security.firewalls'] = [
            'login' => [
                'pattern' => 'authenticate|reset-password|^/email|^/image/',
                'anonymous' => true,
                'stateless' => true,
            ],
            'users' => [
                'pattern' => new RequestMatcher('^/users$', null, ['POST']),
                'anonymous' => true,
                'stateless' => true,
            ],
            'options' => [
                'pattern' => new RequestMatcher('^.*$', null, ['OPTIONS']),
                'anonymous' => true,
                'stateless' => true,
            ],
            'secured' => [
                'pattern' => '^.*$',
                'jwt' => [
                    'use_forward' => true,
                    'require_previous_session' => false,
                    'stateless' => true,
                    'header' => $app['app-config']['jwt']['header'],
                    'life_time' => $app['app-config']['jwt']['life_time'],
                    'public_key' => $app['app-config']['jwt']['public_key'],
                    'private_key' => $app['app-config']['jwt']['private_key'],
                ],
                'stateless' => true,
            ],
        ];

        Serializer::instance()->setSerializer(
            SerializerBuilder::create()
                ->addMetadataDir(__DIR__ . '/../../../../Infrastructure/DataTransformer/Jms')
                ->setCacheDir(__DIR__ . '/../../../../../../../var/cache/jms-serializer')
                ->build()
        );

        $domainSubscribers = [];
        $app->before(function () use ($app, &$domainSubscribers) {
            $domainSubscribers = self::subscribeDomainEvents($app);
        });
        $app->after(function () use (&$domainSubscribers) {
            foreach ($domainSubscribers as $k => $subscriber) {
                DomainEventPublisher::instance()->unsubscribe($subscriber);
                unset($domainSubscribers[$k]);
            }
        });

        $app->register(new HALServiceProvider());
        $app->register(new SecurityServiceProvider(), [
            'security.access_rules' => [
                ['authenticate|reset-password|^/email', 'IS_AUTHENTICATED_ANONYMOUSLY'],
                ['^/image/', 'IS_AUTHENTICATED_ANONYMOUSLY'],
                [new RequestMatcher('^/users$', null, ['POST']), 'IS_AUTHENTICATED_ANONYMOUSLY'],
                [new RequestMatcher('^.*$', null, ['OPTIONS']), 'IS_AUTHENTICATED_ANONYMOUSLY'],
                ['^/tests', User::ROLE_USER],
                ['^/users', User::ROLE_ADMIN],
                ['^.*$', [User::ROLE_CREATOR]],
            ],
            'security.role_hierarchy' => [
                User::ROLE_ADMIN => [User::ROLE_CREATOR, User::ROLE_USER],
                User::ROLE_CREATOR => [User::ROLE_USER]
            ]
        ]);
        $app->register(new JwtServiceProvider());
        $app->register(new ProblemDetailsProvider());
        $app->register(new ImagineServiceProvider(), ['imagine.driver' => 'Imagick']);
        $app->register(new LocaleServiceProvider());
        $app->register(new TranslationServiceProvider(), array(
            'locale_fallbacks' => array('en'),
        ));

        $app->extend('translator', function ($translator) {
            /** @var $translator Translator */
            $translator->addLoader('yaml', new YamlFileLoader());
            $translator->addResource('yaml', __DIR__ . '/../../../../../../../locales/en.yml', 'en');
            return $translator;
        });
        $app->register(new TwigServiceProvider());

        //  match all option requests
        $app->options('/{section}', function () {
            return new JsonResponse();
        })->assert('section', '.*');

        $app->mount('/', new AuthController());
        $app->mount('/', new ResetPasswordController());
        $app->mount('/', new UserController());
        $app->mount('/', new IdentityController());
        $app->mount('/', new StoredEventController());
        $app->mount('/', new ImageController());
        $app->mount('/', new MetadataController());
        $app->mount('/', new WordController());
        $app->mount('/', new DictionaryController());
        $app->mount('/', new UnitController());
        $app->mount('/', new CognitiveTypeController());
        $app->mount('/', new CognitiveSkillController());
        $app->mount('/', new MethodologyController());
        $app->mount('/', new TestController());

        // cors requests
        $app->after(function (Request $request, Response $response) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set(
                'Access-Control-Allow-Headers',
                'Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, Authorization, Origin'
            );
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, OPTIONS, PATCH, DELETE');
        });

        return $app;
    }

    /**
     * @param \Silex\Application $app
     * @return array
     */
    public static function subscribeDomainEvents(\Silex\Application $app): array
    {
        $domainSubscribers = [];
        $domainSubscribers[] = DomainEventPublisher::instance()->subscribe(
            new AppendEventStoreSubscriber($app['event_store'])
        );

        $domainSubscribers[] = DomainEventPublisher::instance()->subscribe(
            new PasswordResetEmailSubscriber($app[NotifyPasswordResetLinkService::class])
        );
        $domainSubscribers[] = DomainEventPublisher::instance()->subscribe(
            new UserValidateEmailSubscriber($app[NotifyUserValidationService::class])
        );
        $domainSubscribers[] = DomainEventPublisher::instance()->subscribe(
            new ImageCreatedSubscriber(
                $app[GenerateMetadataService::class],
                $app[AddSourceMetadataService::class]
            )
        );
        $domainSubscribers[] = DomainEventPublisher::instance()->subscribe(
            new ImageMetadataAddedSubscriber(
                $app[GenerateAdditionalMetadataService::class]
            )
        );

        return $domainSubscribers;
    }
}
