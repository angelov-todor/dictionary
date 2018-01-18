<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine\Type;

use Core\Main\Domain\Model\Unit\Position;
use Core\Main\Infrastructure\Application\Serialization\Jms\Serializer;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class PositionType extends JsonType
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
        $obj = Serializer::instance()->getSerializer()->deserialize(
            $value,
            Position::class,
            'json'
        );

        return $obj;
    }
}
