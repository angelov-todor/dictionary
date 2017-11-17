<?php
declare(strict_types=1);

namespace Core\Main\Infrastructure\Ui\Web\Silex\Encoders;

use Zend\Crypt\PublicKey\Rsa;

class StringEncoder implements StringEncoderInterface
{
    /**
     * @var Rsa
     */
    protected $rsa;

    /**
     * StringEncoder constructor.
     * @param string $private
     * @param string $public
     * @param string $passPhrase
     */
    public function __construct(string $private, string $public, string $passPhrase = '')
    {
        $this->rsa = Rsa::factory(array(
            'public_key' => $public,
            'private_key' => $private,
            'pass_phrase' => $passPhrase,
            'binary_output' => false
        ));
    }

    /**
     * @param string $encoded
     * @return string
     * @throws DecodeProblemException
     */
    public function decode(string $encoded): string
    {
        try {
            $plaintext = $this->rsa->decrypt($this->urlSafeB64Decode($encoded));
        } catch (Rsa\Exception\InvalidArgumentException $e) {
            throw  new DecodeProblemException($e->getMessage());
        } catch (\RuntimeException $e) {
            throw  new DecodeProblemException("String cannot be decoded.");
        }

        return $plaintext;
    }

    /**
     * @param string $plaintext
     * @return string
     * @throws EncodeProblemException
     */
    public function encode(string $plaintext): string
    {
        try {
            $encoded = $this->urlSafeB64Encode($this->rsa->encrypt($plaintext));
        } catch (Rsa\Exception\InvalidArgumentException $e) {
            throw  new EncodeProblemException($e->getMessage());
        } catch (\RuntimeException $e) {
            throw  new EncodeProblemException("String cannot be encoded.");
        }
        return $encoded;
    }

    /**
     * @param $string
     * @return string
     */
    protected function urlSafeB64Encode($string): string
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    /**
     * @param $string
     * @return string
     */
    protected function urlSafeB64Decode($string): string
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}
