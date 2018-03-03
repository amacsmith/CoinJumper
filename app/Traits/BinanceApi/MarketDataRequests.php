<?php
/**
 * Created by PhpStorm.
 * User: amac
 * Date: 1/20/18
 * Time: 3:17 PM
 */

namespace App\Traits\Binance;


trait MarketDataRequests
{

    use Core;

    /**
     *
     * prices
     *
     * @return array
     */
    public function prices()
    {
        return $this->priceData($this->request("v1/ticker/allPrices"));
    }

    /**
     *
     * bookPrices
     *
     * @return array
     */
    public function bookPrices()
    {
        return $this->bookPriceData($this->request("v1/ticker/allBookTickers"));
    }


    /**
     *
     * candleSticks
     *
     * 1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M
     *
     * @param $symbol
     * @param string $interval
     * @return mixed
     */
    public function candleSticks($symbol, $interval = "5m")
    {
        return $this->request("v1/klines", ["symbol" => $symbol, "interval" => $interval]);
    }

    /**
     *
     * bookPriceData
     *
     * @param $array
     * @return array
     */
    private function bookPriceData($array)
    {
        $book_prices = [];
        foreach ($array as $obj) {
            $book_prices[$obj['symbol']] = [
                "bid" => $obj['bidPrice'],
                "bids" => $obj['bidQty'],
                "ask" => $obj['askPrice'],
                "asks" => $obj['askQty']
            ];
        }
        return $book_prices;
    }

    /**
     *
     * priceData
     *
     * @param $array
     * @return array
     */
    private function priceData($array)
    {
        $prices = [];
        foreach ($array as $obj) {
            $prices[$obj['symbol']] = $obj['price'];
        }
        return $prices;
    }

}