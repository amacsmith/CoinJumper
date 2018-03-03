<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\CoinPair;
use App\Models\Ticker;
use App\CoinJumper\Search\Search;
use Illuminate\Http\Request;

class TestController extends Controller
{


    public function search(Request $request)
    {
        $search = new Search;

        return $search->coinpairs($request->get('search'));

    }

    public function test()
    {

        $bina = new BinanceController();

        $balances = $bina->getAccountData();

        foreach ($balances['balances'] as $balance){

//            if($balance['locked'] > 0){
//                dd($balance);
//            }

            dump("coinpair: " . $balance['asset']);
            dump("available: " . $balance['free']);
            dump("locked: " . $balance['locked']);

        }

        exit();


//        $test_collection = collect([1,2,3,4,5]);
//
//        dd($test_collection->splice(1));

        /**
         * @var Ticker $ticker
         */
        $ticker = Ticker::all()->take(-1)->first();

        dd($ticker->checkVolumeForIncrease(.05, 11));


        /**
         * @var CoinPair $bina_xrp
         */
        $bina_xrp = CoinPair::find(229);



        $tickers = $bina_xrp->getTickersByInterval('1h');

        dd($tickers[0]);


        /**
         * @var Exchange $binance
         */
//        $binance = Exchange::getExchangeByExchangeCode('BINA');
//
//        $pair = CoinPair::find(1);
//
//        dump($pair->mkt_name);
//        dump($pair->exch_code);
//        $result = $binance->doesPairExist($pair->mkt_name, $pair->exch_code);
//
//        dd($result);

//        dd(Exchange::doesPairExist())

        dd(CoinigyController::getExchange("BINA"));




    }

}
