<?php

namespace App\Models;

use \App\Traits\Interval;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use PHPUnit\Runner\StandardTestSuiteLoader;

/**
 * App\Models\Ticker
 *
 * @property-read \App\Models\CoinPair $coinpair
 * @property mixed volume_24
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $base_curr
 * @property string $exch_code
 * @property string $exch_name
 * @property string $primary_curr
 * @property int $volume_24
 * @property int $mkt_id
 * @property int $exchmkt_id
 * @property int $exch_id
 * @property float $btc_volume_24
 * @property float $last_price
 * @property string|null $coin_pair_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereBaseCurr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereBtcVolume24($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereCoinPairId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereExchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereExchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereExchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereExchmktId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereLastPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereMktId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker wherePrimaryCurr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticker whereVolume24($value)
 */
class Ticker extends Model
{

    use Interval;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'base_curr', 'display_name', 'exch_code', 'exch_name', 'mkt_name', 'primary_curr', 'volume_24', 'mkt_id', 'exchmkt_id', 'exch_id', 'btc_volume_24', 'last_price'
    ];


    //relationships//

    //many to many//

    /**
     * The exchange that the coinpair belongs to.
     */
    public function coinpair()
    {
        return $this->belongsTo('App\Models\CoinPair', 'coin_pair_id', 'id');
    }

    public function __construct($attributes = null)
    {

        parent::__construct();

        if($attributes != null){

//            $this->display_name = $attributes->display_name;
//            $this->mkt_name = $attributes->mkt_name;


            $this->base_curr = $attributes->base_curr;
            $this->exch_code = $attributes->exch_code;
            $this->exch_name = $attributes->exch_name;
            $this->primary_curr = $attributes->primary_curr;
            $this->volume_24 = $attributes->volume_24;
            $this->mkt_id = $attributes->mkt_id;
            $this->exchmkt_id = $attributes->exchmkt_id;
            $this->exch_id = $attributes->exch_id;
            $this->btc_volume_24 = $attributes->btc_volume_24;
            $this->last_price = $attributes->last_price;
        }

        return $this;


    }


    /**
     *
     * LastTickersInterval
     *
     * @param string $interval
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function LastTickersInterval($interval = '1m')
    {
        return $this->coinpair->lastSetOfTickerByInterval($interval);
    }

    /**
     *
     * LastTickers
     *
     * @param int $number_of_tickers
     * @param int $offset
     * @param bool $collection_method
     * @param bool $collection_method_parameter
     * @return string
     * @throws \Exception
     */
    public function LastTickers($number_of_tickers = 5,  $offset = 0, $collection_method = false, $collection_method_parameter = false)
    {

        if($offset % 1 !== 0){
            Throw new \Exception('offset must be an integer');
        } else {

            if($number_of_tickers > 0){
                $number_of_tickers = ($number_of_tickers + $offset) * -1;
            } else if ($number_of_tickers === 0){
                Throw new \Exception("The number of tickers must be Greater than or Less than 0");
            } else {
                $number_of_tickers = $number_of_tickers + $offset;
            }

            $tickers_collection = $this->coinpair->tickers->take($number_of_tickers)->reverse()->splice($offset);
            dump('<************************');
            dump('Ticker->id of set of tickers defined by the number of tickers that you want to compare the most recent one to.');
            dump('************************>');
            foreach($tickers_collection as $ticker){
                dump("ticker: " . $ticker->id);
            }
            dump('<========================>');

            if($collection_method === false){
                return $tickers_collection;
            } else {
                try {
                    if ($collection_method_parameter === false) {
                        return $tickers_collection->$collection_method();
                    } else {
                        return $tickers_collection->$collection_method($collection_method_parameter);
                    }
                } catch(\Exception $e){
                    return $e->getMessage();
                }

            }

        }

    }

    /**
     *
     * getPreviousTicker
     *
     * @return mixed
     */
    private function getPreviousTicker()
    {
        return $this->coinpair->tickers->sortByDesc('id')->shift()->first();
    }

    /**
     *
     * lastTicker
     *
     * @return mixed
     */
    public function lastTicker()
    {
        return $this->coinpair->tickers->sortByDesc('id')->first();
    }


    public function lastDayVolumeAverage()
    {
        dd($this);
    }

    /**
     *
     * createResult
     *
     * @param array $attributes
     * @return \stdClass
     * @throws \Exception
     */
    private function createResult($attributes)
    {
        if(count($attributes) < 1){
            Throw new \Exception("Cannot create an empty result object");
        } else {
            $result = new \stdClass();

            foreach ($attributes as $key => $value){

                $result->$key = $value;

            }
        }

        return $result;
    }

    /**
     *
     * checkVolumeForIncrease
     *
     * @param float $rate_of_increase_to_compare
     * @param int $number_of_tickers
     * @return \stdClass
     */
    public function checkVolumeForIncrease($rate_of_increase_to_compare = .05, $number_of_tickers = 5)
    {
        dump('<************************');
        dump('ticker->id of the ticker object you want to compare to a range of previous tickers');
        dump('************************>');
        dump("ticker: " . $this->id);
        dump('<========================>');
        $tickers_avg_volume_24 = $this->LastTickers($number_of_tickers, 0, 'avg', 'volume_24');

        $current_volume_24 = $this->volume_24;

        $compared_rate = $rate_of_increase_to_compare;

        $delta = $this->volume_24 - $tickers_avg_volume_24;

        $delta_decimal = round(($delta / $tickers_avg_volume_24) * 100, 4);

        if($delta_decimal >= $compared_rate){
            $increased_to_rate = true;
        } else {
            $increased_to_rate = false;
        }

        $delta_percent = strval($delta_decimal) . "%";

        return $this->createResult(compact('delta', 'delta_percent', 'delta_decimal', 'increased_to_rate', 'compared_rate', 'number_of_tickers', 'tickers_avg_volume_24', 'current_volume_24'));

    }

}
