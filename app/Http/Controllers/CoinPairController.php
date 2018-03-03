<?php

namespace App\Http\Controllers;

use App\Models\CoinPair;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use View;

class CoinPairController extends Controller
{


    public function index(Request $request)
    {

        if ($search = $request->get('search')) {

            $coin_pairs = CoinPair::search($search)->get();

        } else {

            $coin_pairs = CoinPair::all();

        }

        return View::make('coinpair.index', compact('coin_pairs'));

    }

}
