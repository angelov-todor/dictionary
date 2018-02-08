<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine\Type;

use Core\Main\Domain\Model\Answer\Answer;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Core\Main\Infrastructure\Application\Serialization\Jms\Serializer;
use Doctrine\DBAL\Types\JsonType;

class AnswerType extends JsonType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        $jsonEncoded = Serializer::instance()->getSerializer()->serialize($value, 'json');

        return $jsonEncoded;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }
        $value = (is_resource($value)) ? stream_get_contents($value) : $value;
        $data = json_decode($value, true);
        if (!isset($data['type'])) {
            throw ConversionException::conversionFailed($value, 'AnswerType');
        }
        $type = $data['type'];
        $obj = Serializer::instance()->getSerializer()->deserialize(
            $value,
            Answer::$typeToClassMap[$type],
            'json'
        );

        return $obj;
    }
}
