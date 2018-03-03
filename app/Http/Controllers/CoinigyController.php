<?php

namespace App\Http\Controllers;

use App\Coinpair;
use App\Exchange;
use App\Ticker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CoinigyController extends Controller
{

    public $api_key = "340b717f71cdc7bd8621f13c64805e00";
    public $api_secret = "157948613018978b66ca89278edcece9";
    public $api_url = "https://api.coinigy.com/api/v1/";

    public function __construct()
    {

    }

    /*Function to send HTTP POST Requests*/
    /*Used by every function below to make HTTP POST call*/
    /**
     * @param $exchange_code
     * @return bool|mixed
     */
    public static function getPairs($exchange_code)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.coinigy.com/api/v1/markets");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $post_fields = json_encode(
            array("exchange_code" => "$exchange_code")
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-API-KEY: 340b717f71cdc7bd8621f13c64805e00",
            "X-API-SECRET: 157948613018978b66ca89278edcece9"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $pairs = json_decode($response);
        
        $pairs = $pairs->data;

        $real_pairs = array();

        foreach ($pairs as $pair){

            if($pair->exch_code === $exchange_code){
                $real_pairs[] = $pair;
            }

        }

        return $real_pairs;
    }


    /**
     * getExchanges
     */
    public function getExchanges()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.coinigy.com/api/v1/exchanges");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-API-KEY: 340b717f71cdc7bd8621f13c64805e00",
            "X-API-SECRET: 157948613018978b66ca89278edcece9"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $exchanges = json_decode($response);

        $exchanges = $exchanges->data;

       return response()->json($exchanges);
    }


    /**
     * getExchange
     * @param $exchange_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchange($exchange_code)
    {

        $exchange_code = json_decode($exchange_code);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.coinigy.com/api/v1/exchanges");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-API-KEY: 340b717f71cdc7bd8621f13c64805e00",
            "X-API-SECRET: 157948613018978b66ca89278edcece9"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $exchanges = json_decode($response);

        $exchanges = $exchanges->data;

        $messages_array = array();

        foreach ($exchanges as $exchange){

            Log::info($exchange->exch_code . " === " . $exchange_code);

            if($exchange->exch_code === $exchange_code){

                $exchange_object = Exchange::getExchangeByExchangeCode($exchange_code);

                if($exchange_object->count() === 0){
                    $exchange_object = new Exchange($exchange);

                    $exchange_object->save();

                } elseif ($exchange_object->count() > 1){
                    throw new Exception("exchange code returned more than one exchange");
                } else {
                    $exchange_object = $exchange_object[0];
                }

                $pairs = self::getPairs($exchange_code);

                foreach($pairs as $pair){

                    if(!$exchange_object->doesPairExist($pair->mkt_name, $pair->exch_code)) {

                        $pair_object = new Coinpair($pair);

                        $pair_object->exchange()->associate($exchange_object);

                        $pair_object->save();

                        $tc = new TwilioController();

                        $date = Carbon::now()->toDateTimeString();

                        $message = "$exchange_code: has had a new coin pair added called $pair->mkt_name at $date";

                        $messages_array[] = $message;

                        $tc->sendMessage("3173166329", $message);

                    }
                }

                $exchange_object->save();
            }

        }

        return response()->json($messages_array);
    }


    /**
     *
     * storeExchangePairs
     *
     * @param $exchange_code
     */
    public function storeExchangePairs($exchange_code)
    {

    }


    /**
     *
     * tickerByExchangeMarketId
     *
     * @param $options
     * @return mixed
     */
    public function tickerByExchangeMarketId($options)
    {

        $options = json_decode($options);

        $percent_of_increase = $options->percent_of_increase;

        /**
         * @var Coinpair $coin_pair
         */
        $coin_pair = Coinpair::getByExchangeMarkeyId($options->exchmkt_id);

        $ticker = new Ticker($options);

        $ticker->coinpair()->associate($coin_pair);

        $ticker->save();

        $ticker->checkVolumeForIncrease($percent_of_increase);

        return response()->json($coin_pair->tickers);
    }

}