<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Application\Serialization\Jms;

use JMS\Serializer\Serializer as JmsSerializer;

class Serializer
{
    /**
     * @var Serializer
     */
    private static $instance = null;

    /**
     * @var JmsSerializer
     */
    protected $serializer;

    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @return JmsSerializer
     * @throws \Exception
     */
    public function getSerializer(): JmsSerializer
    {
        if (null === $this->serializer) {
            throw new SerializerNotSetException("Serializer is not set.");
        }

        return $this->serializer;
    }

    /**
     * @param JmsSerializer $serializer
     * @return Serializer
     */
    public function setSerializer(JmsSerializer $serializer): Serializer
    {
        $this->serializer = $serializer;
        return $this;
    }
}
