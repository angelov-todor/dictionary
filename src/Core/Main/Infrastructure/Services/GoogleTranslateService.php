<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Services;

use Google\Cloud\Translate\TranslateClient;
use Google\Cloud\Vision\Annotation;

class GoogleTranslateService
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
     * @param string $text
     * @param string $target
     * @return Annotation\Entity[]|null
     */
    public function execute($text, string $target = 'bg')
    {
        # Instantiates a client
        $translate = new TranslateClient([
            'projectId' => $this->projectId,
            'keyFilePath' => $this->keyFilePath
        ]);

        # Prepare the image to be annotated
        $translation = $translate->translate($text, [
            'target' => $target
        ]);

        return $translation['text'];
    }
}
