<?php

namespace Gist\Service;

use InvalidArgumentException;

/**
 * Class SaltGenerator
 * @author Simon Vieille <simon@deblan.fr>
 */
class SaltGenerator
{
    public function generate($length = 32)
    {
        if (!is_numeric($length)) {
            throw new InvalidArgumentException('Paramter length must be a valid integer.');
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return substr(base64_encode(openssl_random_pseudo_bytes($length)), 0, $length);
        }

        if (function_exists('mcrypt_create_iv')) {
            return substr(base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)), 0, $length);
        }

        throw new RuntimeException('You must enable openssl or mcrypt modules.');
    }
}
