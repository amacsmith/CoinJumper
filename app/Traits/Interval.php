<?php

namespace App\Traits;

use App\Models\CoinPair;
use Exception;

Trait Interval
{

    protected $interval_types = array(
        '1m'    => 1,
        '5m'    => 5,
        '15m'   => 15,
        '30m'   => 30,
        '1h'    => 60,
        '6h'    => 360,
        '12h'   => 720,
        '1d'    => 1440,
        '1w'    => 10080
    );

    /**
     *
     * intervalTypeToInteger
     *
     * @param int|String $interval
     * @return mixed
     * @throws Exception
     */
    private function intervalTypeToInteger($interval)
    {
        if(is_int($interval)){

            return $interval;

        } elseif (array_key_exists(strtolower($interval), $this->interval_types)){

            return $this->interval_types[$interval];

        } else {

            throw new Exception("The interval, $interval is not a valid interval type or is not an integer.", '500');

        }

    }

    /**
     *
     * lastSetOfTickerByInterval
     *
     * @param $interval
     * @return mixed
     */
    public function lastSetOfTickerByInterval($interval)
    {
        return $this->getTickersByInterval($interval)->first();
    }

    /**
     *
     * getTickersByInterval
     *
     *
     * @param $interval
     * @return mixed
     */
    public function getTickersByInterval($interval)
    {


        /**
         * @var CoinPair $this
         */
        $interval_value = $this->intervalTypeToInteger($interval);

//        dump($interval_value);

        $tickers_collection = $this->tickers->sortByDesc('id');

//        dump($tickers_collection->count());

        $remainder_of_splits = $tickers_collection->count() % $interval_value;

        $number_of_splits = floor($tickers_collection->count() / $interval_value);

//        dd($remainder_of_splits);

        if($remainder_of_splits !== 0){

            $tickers_remainder_collection = $tickers_collection;

            $rest_tickers_collection = $tickers_remainder_collection->splice($remainder_of_splits);

            $interval_collection = $rest_tickers_collection->split($number_of_splits);

            $interval_collection->prepend($tickers_remainder_collection);

        } else {
            $interval_collection = $tickers_collection->split($number_of_splits);
        }


        return $interval_collection;

    }



    /**
     *
     * getPreviousTicker
     *
     * @return mixed
     */
    private function getLastTickersByInterval($interval_value)
    {

        $ticker_interval = $this->getTickersByInterval($interval_value)->first();

        dd($ticker_interval);

    }




}
