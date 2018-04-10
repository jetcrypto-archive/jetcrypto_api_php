<?php

namespace jetcrypto\api\tools;

use jetcrypto\api\JetCryptoAPIConf;

/**
 * HTTP requests support class.
 *
 * @category JetCrypto API
 * @author Alexander Mochkin <amochkin@jetcrypto.com>
 * @copyright (c) 2018, JetCrypto LLC
 *
 * @git https://github.com/jetcrypto/jetcrypto_api_php
 * @license http://opensource.org/licenses/MIT
 */
class Request {

    /**
     * JetCrypto API Key value.
     *
     * @var string
     */
    private $apiKey = "";

    /**
     * JetCrypto API Secret value.
     *
     * @var string
     */
    private $apiSecret = "";

    /**
     * API version number.
     *
     * @var int
     */
    private $apiVersion = 3;

    /**
     * Initiates JetCrypto API object for trading methods.
     *
     * @param string $apiKey JetCrypto API Key value
     * @param string $apiSecret JetCrypto API Secret value
     * @param int $apiVersion API version number.
     * @param bool $isDemoAPI Demo API flag
     */
    public function __construct($apiKey, $apiSecret, $apiVersion = 3) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->apiVersion = $apiVersion;
    }

    /**
     * Executes curl request to the JetCrypto API.
     *
     * @param array $params Request parameters list.
     * @param string $method HTTP method (default: 'POST').
     *
     * @return array JSON data.
     * @throws \Exception If Curl error or JetCrypto API error occurred.
     */
    public function exec(array $params = [], $method = "POST") {
        usleep(100000);

        // generate the POST data string
        $params['nonce'] = self::getNonce();
        $params = http_build_query($params);

        // curl handle (initialize if required)
        $ch = curl_init();

        curl_setopt(
            $ch, CURLOPT_URL,
            JetCryptoAPIConf::getAPIUrl(JetCryptoAPIConf::API_TYPE_TRADING, $this->apiVersion)
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Key: ' . $this->apiKey,
            'Sign: ' . hash_hmac('sha512', "$params", $this->apiSecret)
        ]);

        // run the query
        $res = curl_exec($ch);
        if ($res === false) {
            $e = curl_error($ch);
            curl_close($ch);

            throw new \Exception("Curl error: " . $e);
        }
        curl_close($ch);

        $json = json_decode($res, true);

        // Check for the JetCrypto API error
        if (isset($json['error'])) {
            throw new \Exception(
                "JetCrypto API error ({$json['code']}): {$json['error']}"
            );
        }

        return $json;
    }

    /**
     * Executes simple GET request to the JetCrypto public API.
     *
     * @param string $request API entrypoint method.
     * @param int $apiVersion API version number.
     *
     * @return array JSON data.
     */
    public static function json($request, $apiVersion = 3) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,
           JetCryptoAPIConf::getAPIUrl(JetCryptoAPIConf::API_TYPE_PUBLIC, $apiVersion)
          . $request
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);

        switch ($output) {
            case ("Not implemented"):
                $json = [
                    'error' =>  [
                        'message' => $output
                    ]
                ];
                break;
            default:
                $json = json_decode($output, true);
        }

        return $json;
    }

    private static function getNonce() {
        return time();
    }

}
