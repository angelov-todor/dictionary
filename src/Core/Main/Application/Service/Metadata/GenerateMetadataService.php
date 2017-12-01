<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Core\Main\Infrastructure\Services\GoogleVisionService;
use Ddd\Application\Service\ApplicationService;

class GenerateMetadataService implements ApplicationService
{
    /**
     * @var GoogleVisionService
     */
    protected $visionService;

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var ImageMetadataRepositoryInterface
     */
    protected $imageMetadataRepository;

    /**
     * @var MetadataRepositoryInterface
     */
    protected $metadataRepository;

    /**
     * GenerateMetadataService constructor.
     * @param GoogleVisionService $visionService
     * @param ImageRepositoryInterface $imageRepository
     * @param ImageMetadataRepositoryInterface $imageMetadataRepository
     * @param MetadataRepositoryInterface $metadataRepository
     */
    public function __construct(
        GoogleVisionService $visionService,
        ImageRepositoryInterface $imageRepository,
        ImageMetadataRepositoryInterface $imageMetadataRepository,
        MetadataRepositoryInterface $metadataRepository
    ) {
        $this->visionService = $visionService;
        $this->imageRepository = $imageRepository;
        $this->imageMetadataRepository = $imageMetadataRepository;
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * @return GoogleVisionService
     */
    protected function getVisionService(): GoogleVisionService
    {
        return $this->visionService;
    }

    /**
     * @return ImageRepositoryInterface
     */
    protected function getImageRepository(): ImageRepositoryInterface
    {
        return $this->imageRepository;
    }

    /**
     * @return ImageMetadataRepositoryInterface
     */
    protected function getImageMetadataRepository(): ImageMetadataRepositoryInterface
    {
        return $this->imageMetadataRepository;
    }

    /**
     * @return MetadataRepositoryInterface
     */
    protected function getMetadataRepository(): MetadataRepositoryInterface
    {
        return $this->metadataRepository;
    }

    /**
     * @param GenerateMetadataRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        try {
            $metadata = $this->findMetadataByName('label');
        } catch (\Exception $e) {
            // TODO: should we
            return;
        }
        /** @var Image $image */
        $image = $this->getImageRepository()->find($request->getImageId());

        // TODO: resolve path properly
        $file = getcwd() . DIRECTORY_SEPARATOR . Image::IMAGE_LOCATION . DIRECTORY_SEPARATOR . $image->getSrc();

        $labelDetection = $this->getVisionService()->execute($file);
        foreach ($labelDetection as $entity) {
            $imageMetadata = new ImageMetadata();
            $imageMetadata->setImage($image);
            $imageMetadata->setMetadata($metadata);
            $imageMetadata->setValue($entity->description());
            $this->getImageMetadataRepository()->add($imageMetadata);
        }
    }

    /**
     * @param string $name
     * @return Metadata
     * @throws \Exception
     */
    protected function findMetadataByName(string $name): Metadata
    {
        $meta = $this->getMetadataRepository()->byName($name);
        if (null == $meta) {
            throw new \Exception("No metadata with name $name");
        }
        return $meta;
    }
}
