<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Core\Main\Infrastructure\Ui\Web\Silex\Application;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

// replace with file to your own project bootstrap
require_once __DIR__ . '/../vendor/autoload.php';


/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = Application::bootstrap(['exception-handler-register' => false])['em'];

$helperSet = ConsoleRunner::createHelperSet($entityManager);
$helperSet->set(new QuestionHelper(), 'dialog');

/** Migrations setup */
$configuration = new Configuration($entityManager->getConnection());
$configuration->setMigrationsDirectory('migrations');
$configuration->setMigrationsNamespace('migrations');
$configuration->setMigrationsTableName('doctrine_migration_versions');
$configuration->setName('Doctrine Migrations');

$diffCommand = new DiffCommand();
$diffCommand->setMigrationConfiguration($configuration);

$statusCommand = new StatusCommand();
$statusCommand->setMigrationConfiguration($configuration);

$executeCommand = new ExecuteCommand();
$executeCommand->setMigrationConfiguration($configuration);

$versionCommand = new VersionCommand();
$versionCommand->setMigrationConfiguration($configuration);

$generateCommand = new GenerateCommand();
$generateCommand->setMigrationConfiguration($configuration);

$migrateCommand = new class($entityManager->getConnection()) extends MigrateCommand
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(\Doctrine\DBAL\Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct(null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = (boolean)$input->getOption('dry-run');
        $writeSql = (boolean)$input->getOption('write-sql');
        $shouldLock = !$dryRun && !$writeSql;

        $hasLock = true;
        if ($shouldLock) {
            $hasLock = $this->connection->query("SELECT GET_LOCK('migrations', 300);")->fetchColumn();
        }
        if ($shouldLock && !$hasLock) {
            // should have locked, but no lock obtained
            return 1;
        }

        $status = parent::execute($input, $output);

        if ($shouldLock) {
            $this->connection->query("SELECT RELEASE_LOCK('migrations');")->fetchColumn();
        }
        return $status;
    }
};
$migrateCommand->setMigrationConfiguration($configuration);

// Add Doctrine Migration commands
$cli = ConsoleRunner::createApplication($helperSet, [
    $diffCommand,
    $executeCommand,
    $generateCommand,
    $migrateCommand,
    $statusCommand,
    $versionCommand,
]);

return $cli->run();
