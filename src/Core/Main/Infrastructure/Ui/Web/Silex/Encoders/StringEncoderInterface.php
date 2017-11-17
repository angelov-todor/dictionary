<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Encoders;

interface StringEncoderInterface
{
    /**
     * @param string $plaintext
     * @return string
     */
    public function encode(string $plaintext): string;

    /**
     * @param string $encoded
     * @return string
     */
    public function decode(string $encoded): string;
}
