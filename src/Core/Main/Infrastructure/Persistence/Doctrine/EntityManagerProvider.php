<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine;

use Core\Main\Infrastructure\Persistence\Doctrine\Type\AnswerType;
use Core\Main\Infrastructure\Persistence\Doctrine\Type\PositionType;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\ORMException;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Core\Main\Infrastructure\Persistence\Doctrine\Type\UTCDateTimeType;
use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Tools\Setup;
use Core\Main\Infrastructure\Domain\Model\User\Password;
use Syslogic\DoctrineJsonFunctions\Query\AST\Functions\Mysql as DqlFunctions;

class EntityManagerProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app['em.options'] = array_replace_recursive([
            'proxiesDir' => __DIR__ . '/../../../../../../var/cache/doctrine',
            'debug' => false,
            'echosql' => false
        ], $app['app-config']['em.options'] ?? []);

        $app['em'] = function () use ($app) {
            return $this->build($app['db'], $app['em.options']);
        };
    }

    /**
     * @param $conn
     * @param array $entityManagerOptions
     * @return EntityManager
     * @throws DBALException
     * @throws ORMException
     */
    protected function build($conn, array $entityManagerOptions): EntityManager
    {
        $types = [
            'password' => Password::class,
            'position' => PositionType::class,
            'answer' => AnswerType::class
        ];
        foreach ($types as $type => $className) {
            if (!Type::hasType($type)) {
                Type::addType($type, $className);
            }
        }

        Type::overrideType('datetime', UTCDateTimeType::class);

        $em = EntityManager::create(
            $conn,
            Setup::createYAMLMetadataConfiguration(
                [__DIR__ . '/Mapping'], //  paths
                $entityManagerOptions['debug'],   //  debug mode
                $entityManagerOptions['proxiesDir'] //  proxies dir
            )
        );
        if ($entityManagerOptions['echosql']) {
            $em->getConnection()
                ->getConfiguration()
                ->setSQLLogger(new FileSQLLogger(__DIR__ . '/../../../../../../var/log/sql.log'));
        }

        $platform = $em->getConnection()->getDatabasePlatform();
        $em->getConfiguration()
            ->addCustomStringFunction(DqlFunctions\JsonExtract::FUNCTION_NAME, DqlFunctions\JsonExtract::class);
        $em->getConfiguration()
            ->addCustomStringFunction(DqlFunctions\JsonUnquote::FUNCTION_NAME, DqlFunctions\JsonUnquote::class);

        $em->getConfiguration()->addCustomStringFunction('MONTH', Month::class);
        $em->getConfiguration()->addCustomStringFunction('YEAR', Year::class);
        $platform->registerDoctrineTypeMapping('json', 'array');
        $platform->registerDoctrineTypeMapping('enum', 'string');

        return $em;
    }
}
