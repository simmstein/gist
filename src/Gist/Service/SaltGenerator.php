<?php

namespace Gist\Service;

use InvalidArgumentException;

/**
 * Class SaltGenerator.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class SaltGenerator
{
    /**
     * Generates a random salt.
     *
     * @param int $length
     *
     * @return string
     */
    public function generate($length = 32, $isApiKey = false)
    {
        if (!is_numeric($length)) {
            throw new InvalidArgumentException('Paramter length must be a valid integer.');
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $string = base64_encode(openssl_random_pseudo_bytes(256));
        }

        if (function_exists('mcrypt_create_iv')) {
            $string = base64_encode(mcrypt_create_iv(256, MCRYPT_DEV_URANDOM));
        }

        if (!empty($string)) {
            if (true === $isApiKey) {
                $string = str_replace(
                    array('+', '%', '/', '#', '&'),
                    '',
                    $string
                );
            }

            return substr($string, 0, $length);
        }

        throw new RuntimeException('You must enable openssl or mcrypt modules.');
    }
}
