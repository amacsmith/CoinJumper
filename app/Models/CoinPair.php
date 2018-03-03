<?php

namespace App\Models;

use App\Traits\Interval;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CoinPair
 *
 * @property-read \App\Models\Exchange $exchange
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticker[] $tickers
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $exch_id
 * @property string $exch_name
 * @property string $exch_code
 * @property string $mkt_id
 * @property string $mkt_name
 * @property string $exchmkt_id
 * @property string|null $exchange_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereExchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereExchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereExchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereExchangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereExchmktId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereMktId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereMktName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CoinPair whereUpdatedAt($value)
 */
class CoinPair extends Model
{

    use Interval;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exch_id', 'exch_name', 'exch_code', 'mkt_id', 'mkt_name', 'exchmkt_id', 'exchange_id'
    ];

    public $table = "coin_pairs";

    //relationships//

    //many to many//

    /**
     * The exchange that the coinpair belongs to.
     */
    public function exchange()
    {
        return $this->belongsTo('App\Models\Exchange');
    }

    public function tickers()
    {
        return $this->hasMany('App\Models\Ticker');
    }

    public function __construct($attributes = null)
    {

        parent::__construct();

        if ($attributes != null) {
            $this->exch_id = $attributes->exch_id;
            $this->exch_name = $attributes->exch_name;
            $this->exch_code = $attributes->exch_code;
            $this->mkt_id = $attributes->mkt_id;
            $this->mkt_name = $attributes->mkt_name;
            $this->exchmkt_id = $attributes->exchmkt_id;
        }

        return $this;

    }

    /**
     *
     * Query search scope
     *
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {

        return $query->where(function ($query) use ($search)
        {

            $query->where('exch_id', 'LIKE', "%$search%")
                ->orWhere('exch_name', 'LIKE', "%$search%")
                ->orWhere('exch_code', 'LIKE', "%$search%")
                ->orWhere('mkt_id', 'LIKE', "%$search%")
                ->orWhere('mkt_name', 'LIKE', "%$search%")
                ->orWhere('exchmkt_id', 'LIKE', "%$search%")
                ->orWhere('exchange_id', 'LIKE', "%$search%");

        });

    }


    public static function getByExchangeMarkeyId($exchmkt_id)
    {

        $result = CoinPair::where('exchmkt_id', '=', $exchmkt_id)->first();

        return $result;

    }


    public function hasVolumeIncreased()
    {


    }

    public function getLastTicker()
    {

    }


}
