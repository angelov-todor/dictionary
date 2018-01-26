<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Provider;

use Core\Main\Application\Service\Metadata\AddSourceMetadataService;
use Core\Main\Application\Service\Metadata\GenerateAdditionalMetadataService;
use Core\Main\Application\Service\Metadata\GenerateMetadataService;
use Core\Main\Application\Service\WordTools;
use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\Services\GoogleVisionService;
use Ddd\Application\Service\TransactionalApplicationService;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ImageServicesProvider implements ServiceProviderInterface
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
        $app[ImageRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Image::class);
        };

        $app[MetadataRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(Metadata::class);
        };
        $app[ImageMetadataRepositoryInterface::class] = function () use ($app) {
            return $app['em']->getRepository(ImageMetadata::class);
        };
        $app[GenerateMetadataService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new GenerateMetadataService(
                    $app[GoogleVisionService::class],
                    $app[ImageRepositoryInterface::class],
                    $app[ImageMetadataRepositoryInterface::class],
                    $app[MetadataRepositoryInterface::class]
                ),
                $app['tx_session']
            );
        };

        $app[GoogleVisionService::class] = function () use ($app) {
            return new GoogleVisionService(
                $app['app-config']['google']['google_project_id'],
                $app['app-config']['google']['google_key_file_path']
            );
        };

        $app[AddSourceMetadataService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new AddSourceMetadataService(
                    $app[MetadataRepositoryInterface::class],
                    $app[ImageRepositoryInterface::class],
                    $app[ImageMetadataRepositoryInterface::class]
                ),
                $app['tx_session']
            );
        };

        $app[GenerateAdditionalMetadataService::class] = function () use ($app) {
            return new TransactionalApplicationService(
                new GenerateAdditionalMetadataService(
                    $app[WordTools::class],
                    $app[MetadataRepositoryInterface::class],
                    $app[ImageRepositoryInterface::class],
                    $app[ImageMetadataRepositoryInterface::class]
                ),
                $app['tx_session']
            );
        };
    }
}
