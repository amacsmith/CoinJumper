<?php

namespace App\Http\Controllers;

use App\Traits\Binance\AccountRequests;
use App\Traits\Binance\MarketDataRequests;

class BinanceController extends Controller
{

//    use AccountRequests, MarketDataRequests;
    use AccountRequests;


    protected $base = "https://www.binance.com/api/", $api_key, $api_secret;

    public $btc_value = 0.00;

    /**
     *
     * __construct
     *
     * BinanceController constructor.
     *
     */
    public function __construct()
    {
        $this->api_key = env('BINA_API_KEY');
        $this->api_secret = env('BINA_API_SECRET');

        return $this;
    }


    public function getAccountData()
    {


        $response = $this->account();

        return $response;


    }




}
