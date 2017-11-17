<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;

abstract class JsonType extends JsonArrayType
{
    /**
     * @return string
     */
    abstract public function getTargetClass(): string;

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'JSON';
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }
        $jsonEncoded = json_encode($value);
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
        $targetClass = $this->getTargetClass();

        return new $targetClass(...array_values($data));
    }
}
