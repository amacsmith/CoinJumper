<?php

namespace App\Http\Controllers;

use App\CoinJumper\Search\Search;
use App\Models\CoinPair;
use App\Models\Exchange;
use Illuminate\Http\Request;
use View;

class SearchController extends Controller
{


    public function search(Request $request)
    {

//        $request_type = $request->get('type');


        $search = $request->get('search');

        $coin_pairs = Search::coinpairs($search)->get();




        return View::make('coinpair.index', compact('coin_pairs'));


//        switch ($request_type){
//            case "coinpair":
//                if ($search = $request->get('search')) {
//                    $coin_pairs = Search::coinpairs($search)->get();
//                }else {
//                    $coin_pairs = CoinPair::all();
//                }
//
//                return View::make('coinpair.index', compact('coin_pairs'));
//
//                break;
//            case "exchange":
//                if ($search = $request->get('search')) {
//                    $exchanges = Search::exchanges($search)->get();
//                }else {
//                    $exchanges = Exchange::all();
//                }
//
//
//                $exchanges_attributes = $exchanges->toArray()[0];
//
//                $exchanges_object = new \stdClass();
//
//                foreach ($exchanges_attributes as $key => $value){
//
//                    $exchanges_object->$key = $value;
//
//                }
//
//                return View::make('exchange.index', ['exchanges' => $exchanges, 'exchanges_object' => $exchanges_object]);
//
//                break;
//            case "coinjumper":
//                if ($search = $request->get('search')) {
//                    $coin_pairs = Search::coinpairs($search)->get();
//                }else {
//                    $coin_pairs = CoinPair::all();
//                }
//                break;
//        }


    }
    
    
}
