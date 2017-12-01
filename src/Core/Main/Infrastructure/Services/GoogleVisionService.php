<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

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
     * GoogleVisionService constructor.
     * @param string $projectId
     * @param string $keyFilePath
     */
    public function __construct(string $projectId, string $keyFilePath)
    {
        $this->projectId = $projectId;
        $this->keyFilePath = $keyFilePath;
    }

    /**
     * @param string $fileName The name of the image file to annotate
     * @return Annotation\Entity[]|null
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
        return $labels;
    }
}
