<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

use Core\Main\Domain\Model\Image;
use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Model\Metadata;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Ddd\Application\Service\ApplicationService;

class AddSourceMetadataService implements ApplicationService
{
    /**
     * @var MetadataRepositoryInterface
     */
    protected $metadataRepository;

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var ImageMetadataRepositoryInterface
     */
    protected $imageMetadataRepository;

    /**
     * AddSourceMetadataService constructor.
     * @param MetadataRepositoryInterface $metadataRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param ImageMetadataRepositoryInterface $imageMetadataRepository
     */
    public function __construct(
        MetadataRepositoryInterface $metadataRepository,
        ImageRepositoryInterface $imageRepository,
        ImageMetadataRepositoryInterface $imageMetadataRepository
    ) {
        $this->metadataRepository = $metadataRepository;
        $this->imageRepository = $imageRepository;
        $this->imageMetadataRepository = $imageMetadataRepository;
    }

    /**
     * @return ImageMetadataRepositoryInterface
     */
    protected function getImageMetadataRepository(): ImageMetadataRepositoryInterface
    {
        return $this->imageMetadataRepository;
    }

    /**
     * @return ImageRepositoryInterface
     */
    protected function getImageRepository(): ImageRepositoryInterface
    {
        return $this->imageRepository;
    }

    /**
     * @return MetadataRepositoryInterface
     */
    protected function getMetadataRepository(): MetadataRepositoryInterface
    {
        return $this->metadataRepository;
    }

    /**
     * @param AddSourceMetadataRequest $request
     * @return null|ImageMetadata
     */
    public function execute($request = null): ?ImageMetadata
    {
        try {
            $metadata = $this->findMetadataByName('source');
        } catch (\Exception $e) {
            // TODO: should we
            return null;
        }
        /** @var Image $image */
        $image = $this->getImageRepository()->find($request->getImageId());
        $imageMetadata = new ImageMetadata();
        $imageMetadata->setImage($image);
        $imageMetadata->setMetadata($metadata);
        $imageMetadata->setValue($request->getSource());
        $this->getImageMetadataRepository()->add($imageMetadata);

        return $imageMetadata;
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
