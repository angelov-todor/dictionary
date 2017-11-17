<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Console\Commands;

use Core\Main\Infrastructure\Ui\Web\Silex\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hateoas\Configuration\Metadata\Driver\YamlDriver;
use Metadata\Cache\FileCache;
use Metadata\Driver\FileLocator;
use Metadata\MetadataFactory;

class CacheWarmup extends Command
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * SendPaymentNotificationEmails constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->app = Application::bootstrap();
        Application::subscribeDomainEvents($this->app);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('cache:warmup')
            ->setDescription('Warmup the cache');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $metadataFactory = $this->buildMetadataFactory();
            foreach ($metadataFactory->getAllClassNames() as $className) {
                $metadataFactory->getMetadataForClass($className);
            }
            $output->writeln("Serializer cache has been created.");
        } catch (\RuntimeException $e) {
            $output->writeln(sprintf("An error occurred: ", $e->getMessage()));
            return 1;
        }
        return 0;
    }

    /**
     * @return MetadataFactory
     */
    private function buildMetadataFactory(): MetadataFactory
    {
        $cacheDir = $this->app['hateoas.config']['cacheDir'];
        $debug = $this->app['hateoas.config']['debug'];
        $this->createDir($cacheDir . '/annotations');
        $metadataDirs = ['' => $this->app['hateoas.config']['metadataDir']];
        $fileLocator = new FileLocator($metadataDirs);
        $metadataFactory = new MetadataFactory(new YamlDriver($fileLocator), null, $debug);
        $this->createDir($cacheDir . '/metadata');
        $metadataFactory->setCache(new FileCache($cacheDir . '/metadata'));
        return $metadataFactory;
    }

    /**
     * @param string $dir
     * @throws \RuntimeException
     * @return void
     */
    private function createDir($dir): void
    {
        if (is_dir($dir)) {
            return;
        }
        if (false === @mkdir($dir, 0777, true)) {
            throw new \RuntimeException(sprintf('Could not create directory "%s".', $dir));
        }
    }
}
