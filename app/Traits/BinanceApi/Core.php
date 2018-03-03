<?php
/**
 * Created by PhpStorm.
 * User: amac
 * Date: 1/20/18
 * Time: 3:23 PM
 */

namespace App\Traits\Binance;


trait Core
{

    /**
     *
     * request
     *
     * @param $url
     * @param array $params
     * @return mixed
     */
    private function request($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\n";
        $query = http_build_query($params, '', '&');
        return json_decode($this->http_request($this->base . $url . '?' . $query, $headers), true);
    }

    /**
     *
     * http_request
     *
     * @param $url
     * @param $headers
     * @param array $data
     * @return bool|mixed
     */
    public function http_request($url, $headers, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            $content = false;
        }
        curl_close($ch);
        return $content;
    }

    /**
     *
     * signedRequest
     *
     * @param $url
     * @param array $params
     * @return mixed
     */
    private function signedRequest($url, $params = [])
    {
        $headers[] = "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\nX-MBX-APIKEY: {$this->api_key}\r\n";
        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');
        $query = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query, $this->api_secret);
        $endpoint = "{$this->base}{$url}?{$query}&signature={$signature}";
        return json_decode($this->http_request($endpoint, $headers), true);
    }

}