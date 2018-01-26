<?php
declare(strict_types=1);

namespace Core\Main\Application\Service\Metadata;

use Core\Main\Application\Exception\ResourceNotFoundException;
use Core\Main\Application\Service\WordTools;
use Core\Main\Domain\Model\ImageMetadata;
use Core\Main\Domain\Repository\ImageMetadataRepositoryInterface;
use Core\Main\Domain\Repository\ImageRepositoryInterface;
use Core\Main\Domain\Repository\MetadataRepositoryInterface;
use Ddd\Application\Service\ApplicationService;
use Rollbar\Rollbar;

class GenerateAdditionalMetadataService implements ApplicationService
{

    /**
     * @var WordTools
     */
    protected $wordTools;

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
     * GenerateAdditionalMetadataService constructor.
     * @param WordTools $wordTools
     * @param MetadataRepositoryInterface $metadataRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param ImageMetadataRepositoryInterface $imageMetadataRepository
     */
    public function __construct(
        WordTools $wordTools,
        MetadataRepositoryInterface $metadataRepository,
        ImageRepositoryInterface $imageRepository,
        ImageMetadataRepositoryInterface $imageMetadataRepository
    ) {
        $this->wordTools = $wordTools;
        $this->metadataRepository = $metadataRepository;
        $this->imageRepository = $imageRepository;
        $this->imageMetadataRepository = $imageMetadataRepository;
    }

    /**
     * @return MetadataRepositoryInterface
     */
    protected function getMetadataRepository(): MetadataRepositoryInterface
    {
        return $this->metadataRepository;
    }

    /**
     * @return ImageMetadataRepositoryInterface
     */
    protected function getImageMetadataRepository(): ImageMetadataRepositoryInterface
    {
        return $this->imageMetadataRepository;
    }

    /**
     * @return WordTools
     */
    protected function getWordTools(): WordTools
    {
        return $this->wordTools;
    }

    /**
     * @return ImageRepositoryInterface
     */
    protected function getImageRepository(): ImageRepositoryInterface
    {
        return $this->imageRepository;
    }

    /**
     * @param GenerateAdditionalMetadataRequest $request
     * @return void
     * @throws ResourceNotFoundException
     */
    public function execute($request = null): void
    {
        $processors = [
            [
                'name' => 'syllables',
                'metadata' => 'Сричкоделение'
            ],
            [
                'name' => 'phonemes',
                'metadata' => 'Фонеми'
            ],
            [
                'name' => 'rhymeform',
                'metadata' => 'Римоформа'
            ],
        ];
        $image = $this->getImageRepository()->ofId($request->getImageId());
        if (!$image) {
            throw new ResourceNotFoundException("Image not found");
        }
        foreach ($processors as $processor) {
            $metadata = $this->getMetadataRepository()->byName($processor['metadata']);
            if (!$metadata) {
                Rollbar::info(sprintf("Metadata `%s` not found", $processor['metadata']));
                continue;
            }

            $metadataValue = $this->getWordTools()->{$processor['name']}($request->getImageMetadataValue());
            if (!$metadataValue) {
                Rollbar::info(sprintf("Metadata value for `%s` not generated", $processor['metadata']));
                continue;
            }
            $imageMetadata = new ImageMetadata();
            $imageMetadata->setImage($image);
            $imageMetadata->setMetadata($metadata);
            $imageMetadata->setValue($metadataValue);
            $this->getImageMetadataRepository()->add($imageMetadata);
        }
    }
}
