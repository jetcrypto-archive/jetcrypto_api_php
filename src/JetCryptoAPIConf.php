<?php

namespace jetcrypto\api;

/**
 * JetCrypto API Configuration constants.
 *
 * @category JetCrypto API
 * @author Alexander Mochkin <amochkin@jetcrypto.com>
 * @copyright (c) 2018, JetCrypto LLC
 *
 * @git https://github.com/jetcrypto/jetcrypto_api_php
 * @license http://opensource.org/licenses/MIT
 */
class JetCryptoAPIConf {

    const URL_PUBLIC  = "https://api.jetcrypto.com/api";
    const URL_TRADING  = "https://api.jetcrypto.com/tapi";

    const API_TYPE_PUBLIC = 'public';
    const API_TYPE_TRADING = 'trading';

    /**
     * Returns JetCrypto API URL.
     *
     * @param string $apiType
     * @param int $apiVersion
     *
     * @return string JetCrypto API URL
     */
    public static function getAPIUrl($apiType = self::API_TYPE_PUBLIC, $apiVersion = 3) {
        switch ($apiType) {
            case (self::API_TYPE_PUBLIC):
                return (self::URL_PUBLIC . "/{$apiVersion}/");
            case (self::API_TYPE_TRADING):
                return self::URL_TRADING;
        }
    }

}
