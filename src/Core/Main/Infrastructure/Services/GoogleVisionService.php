<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Vision\Annotation;
use Google\Cloud\Vision\VisionClient;

class GoogleVisionService
{
    /**
     * @var string Your Google Cloud Platform project ID
     */
    protected $projectId;

    /**
     * @var string The authentication key file path
     */
    protected $keyFilePath;

    /**
     * @var GoogleTranslateService
     */
    protected $googleTranslate;

    /**
     * GoogleVisionService constructor.
     * @param string $projectId
     * @param string $keyFilePath
     * @param GoogleTranslateService $googleTranslate
     */
    public function __construct(string $projectId, string $keyFilePath, GoogleTranslateService $googleTranslate)
    {
        $this->projectId = $projectId;
        $this->keyFilePath = $keyFilePath;
        $this->googleTranslate = $googleTranslate;
    }

    /**
     * @return GoogleTranslateService
     */
    protected function getGoogleTranslate(): GoogleTranslateService
    {
        return $this->googleTranslate;
    }

    /**
     * @param string $fileName The name of the image file to annotate
     * @return []
     */
    public function execute($fileName)
    {
        # Instantiates a client
        $vision = new VisionClient([
            'projectId' => $this->projectId,
            'keyFilePath' => $this->keyFilePath
        ]);

        # Prepare the image to be annotated
        $image = $vision->image(fopen($fileName, 'r'), [
            'LABEL_DETECTION'
        ]);

        # Performs label detection on the image file
        $labels = $vision->annotate($image)->labels();
        // translate the labels
        $translatedLabels = [];
        foreach ($labels as $entity) {
            $translatedLabels[] = $this->getGoogleTranslate()->execute($entity->description());
        }
        return $translatedLabels;
    }
}
