<?php
/**
 * Created by PhpStorm.
 * User: amac
 * Date: 1/21/18
 * Time: 5:54 AM
 */

namespace App\CoinJumper\Search;

use App\Models\CoinPair;
use App\Models\Exchange;
use Illuminate\Support\Collection;

class Search
{

    /**
     *
     * coinpairs
     *
     * search coinpairs
     *
     * @param $search
     * @return mixed
     */
    public static function coinpairs($search)
    {
        return CoinPair::search($search);
    }


    /**
     *
     * exchanges
     *
     * search exchnages
     * @param $search
     * @return mixed
     */
    public static function exchanges($search)
    {

        return Exchange::search($search);

    }

    /**
     *
     * coinjumper
     *
     * search coinjumper
     * @return Collection
     */
    public static function coinjumper($search)
    {

        return CoinPair::search($search);

//        return new Collection(['Killers', 'in', 'Disguise']); //temp
    }
}