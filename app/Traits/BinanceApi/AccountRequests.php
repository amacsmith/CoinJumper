<?php
/**
 * Created by PhpStorm.
 * User: amac
 * Date: 1/20/18
 * Time: 3:01 PM
 */

namespace App\Traits\Binance;

Trait AccountRequests
{

    use Core;

    /**
     *
     * buy
     *
     * @param $symbol
     * @param $quantity
     * @param $price
     * @param string $type
     * @return mixed
     */
    public function buy($symbol, $quantity, $price, $type = "LIMIT")
    {
        return $this->order("BUY", $symbol, $quantity, $price, $type);
    }

    /**
     *
     * sell
     *
     * @param $symbol
     * @param $quantity
     * @param $price
     * @param string $type
     * @return mixed
     */
    public function sell($symbol, $quantity, $price, $type = "LIMIT")
    {
        return $this->order("SELL", $symbol, $quantity, $price, $type);
    }

    /**
     *
     * cancel
     *
     * @param $symbol
     * @param $orderid
     * @return mixed
     */
    public function cancel($symbol, $orderid)
    {
        return $this->signedRequest("v3/order", ["symbol" => $symbol, "orderId" => $orderid]);
    }

    /**
     *
     * orderStatus
     *
     * @param $symbol
     * @param $orderid
     * @return mixed
     */
    public function orderStatus($symbol, $orderid)
    {
        return $this->signedRequest("v3/order", ["symbol" => $symbol, "orderId" => $orderid]);
    }

    /**
     *
     * openOrders
     *
     * @param $symbol
     * @return mixed
     */
    public function openOrders($symbol)
    {
        return $this->signedRequest("v3/openOrders", ["symbol" => $symbol]);
    }

    /**
     *
     * orders
     *
     * @param $symbol
     * @param int $limit
     * @return mixed
     */
    public function orders($symbol, $limit = 500)
    {
        return $this->signedRequest("v3/allOrders", ["symbol" => $symbol, "limit" => $limit]);
    }

    /**
     *
     * trades
     *
     * @param $symbol
     * @return mixed
     */
    public function trades($symbol)
    {
        return $this->signedRequest("v3/myTrades", ["symbol" => $symbol]);
    }

    /**
     *
     * account
     *
     * @return mixed
     */
    public function account()
    {
        return $this->signedRequest("v3/account");
    }

    /**
     *
     * depth
     *
     * @param $symbol
     * @return mixed
     */
    public function depth($symbol)
    {
        return $this->request("v1/depth", ["symbol" => $symbol]);
    }

    /**
     *
     * balances
     *
     * @param bool $priceData
     * @return array
     */
    public function balances($priceData = false)
    {
        $balance = $this->signedRequest("v3/account");
        if (empty($balance['balances'])) {
            exit(json_encode($balance));
        }
        return $this->balanceData($balance, $priceData);
    }

    /**
     *
     * order
     *
     * @param $side
     * @param $symbol
     * @param $quantity
     * @param $price
     * @param string $type
     * @return mixed
     */
    private function order($side, $symbol, $quantity, $price, $type = "LIMIT")
    {
        $opt = [
            "symbol" => $symbol,
            "side" => $side,
            "type" => $type,
            "price" => $price,
            "quantity" => $quantity,
            "timeInForce" => "GTC",
            "recvWindow" => 60000
        ];
        return $this->signedRequest("v3/order", $opt);
    }


    /**
     *
     * balanceData
     *
     * @param $array
     * @param bool $priceData
     * @return array
     */
    private function balanceData($array, $priceData = false)
    {
        $btc_value = 0.00;

        if ($priceData){
            $btc_value = 0.00;
        }
        $balances = [];
        foreach ($array['balances'] as $obj) {
            $asset = $obj['asset'];
            $balances[$asset] = ["available" => $obj['free'], "onOrder" => $obj['locked'], "btcValue" => 0.00000000];
            if ($priceData) {
                if ($obj['free'] < 0.00000001) continue;
                if ($asset == 'BTC') {
                    $balances[$asset]['btcValue'] = $obj['free'];
                    $btc_value += $obj['free'];
                    continue;
                }
                $btcValue = number_format($obj['free'] * $priceData[$asset . 'BTC'], 8, '.', '');
                $balances[$asset]['btcValue'] = $btcValue;
                $btc_value += $btcValue;
            }
        }

        if ($priceData) {
            uasort($balances, function ($a, $b) {
                return $a['btcValue'] < $b['btcValue'];
            });
            $this->btc_value = $btc_value;
        }

        return $balances;
    }


}