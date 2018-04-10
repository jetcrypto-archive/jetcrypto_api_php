<?php

namespace jetcrypto\api;

/**
 * JetCrypto API Wrapper.
 *
 * @category JetCrypto API
 * @author Alexander Mochkin <amochkin@jetcrypto.com>
 * @copyright (c) 2018, JetCrypto LLC
 *
 * @git https://github.com/jetcrypto/jetcrypto_api_php
 * @license http://opensource.org/licenses/MIT
 */
class JetCrypto extends JetCryptoAPITrading {
    /**
     * API version number.
     *
     * @var int
     */
    protected $apiVersion = 3;

    /**
     * JetCrypto public API object.
     *
     * @var JetCryptoAPIPublic
     */
    private $publicAPI = null;

    /**
     * Initiates JetCrypto API functionality. If API keys are not provided
     * then only public API methods will be available.
     *
     * @param string $apiKey JetCrypto API key
     * @param string $apiSecret JetCrypto API secret
     * @param int $apiVersion API version number.
     *
     * @return
     */
    public function __construct($apiKey = null, $apiSecret = null, $apiVersion = 3) {
        if (is_null($apiKey) || is_null($apiSecret)) {
            return;
        }

        $this->apiVersion = $apiVersion;
        $this->publicAPI = new JetCryptoAPIPublic($this->apiVersion);

        return parent::__construct($apiKey, $apiSecret);
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->publicAPI, $method], $args);
    }

}
