<?php

namespace jetcrypto\api;

use jetcrypto\api\tools\Request;

/**
 * JetCrypto Trading API Methods.
 *
 * This API allows to trade on the exchange and receive information about the account.
 *
 * @category JetCrypto API
 * @author Alexander Mochkin <amochkin@jetcrypto.com>
 * @copyright (c) 2018, JetCrypto LLC
 *
 * @git https://github.com/jetcrypto/jetcrypto_api_php
 * @license http://opensource.org/licenses/MIT
 */
class JetCryptoAPITrading {

    private $apiKey = "";
    private $apiSecret = "";

    private $request = null;

    /**
     * Constructor of the class.
     *
     * @param string $apiKey JetCrypto API key
     * @param string $apiSecret JetCrypto API secret
     */
    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->request = new Request($this->apiKey, $this->apiSecret, $this->apiVersion);
    }

    /**
     * Returns information about the user’s current balance, API-key privileges,
     * the number of open orders and Server Time.
     * To use this method you need a privilege of the key info.
     *
     * @return array
     * @throws \Exception
     */
    public function getInfo() {
        return $this->_request('getInfo');
    }

    /**
     * Returns all positive balances.
     *
     * @return array
     * @throws \Exception
     */
    public function getBalances() {
        $info = $this->getInfo();
        if ($info['success'] == 1) {
            return array_filter($info['return']['funds'], function ($amount) {
                return $amount > 0.0;
            });
        }

        return [];
    }

    public function trade() {
        //TODO: implement
    }

    /**
     * Returns the list of your active orders and additional response data.
     * To use this method you need a privilege of the info key. If the order
     * disappears from the list, it was either executed or canceled.
     *
     * @param string $pair Currency trade pair (ex: 'eth_btc').
     *
     * @return array
     * @throws \Exception
     */
    public function activeOrders($pair = null) {
        $params = [];
        if (!is_null($pair)) {
            $params['pair'] = $pair;
        }

        return $this->_request('ActiveOrders', $params);
    }

    /**
     * Returns only the list of your active orders. To use this method you need
     * a privilege of the info key. If the order disappears from the list,
     * it was either executed or canceled.
     *
     * @param string $pair Currency trade pair (ex: 'eth_btc').
     *
     * @return array
     * @throws \Exception
     */
    public function getActiveOrders($pair = null) {
        $orders = $this->activeOrders($pair);
        if ($orders['success'] == 1) {
            return $orders['return'];
        }

        return [];
    }

    public function orderInfo() {
        //TODO: implement
    }

    public function cancelOrder() {
        //TODO: implement
    }

    public function tradeHistory() {
        //TODO: implement
    }

    public function withdrawCoin() {
        //TODO: implement
    }

    /**
     * JSON request functionality wrapper.
     *
     * @param string $method API method name
     *
     * @param array $params
     * @return array
     * @throws \Exception
     */
    private function _request($method, $params = []) {
        $params['method'] = $method;

        $response = $this->request->exec($params);

        return $response;
    }

}
