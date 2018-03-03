<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Exchange
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Coinpair[] $pairs
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $exch_balance_enabled
 * @property string $exch_code
 * @property float $exch_fee
 * @property int $exch_id
 * @property string $exch_name
 * @property int $exch_trade_enabled
 * @property string $exch_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchBalanceEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchTradeEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereExchUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Exchange whereUpdatedAt($value)
 */
class Exchange extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exch_balance_enabled', 'exch_code', 'exch_fee', 'exch_id', 'exch_name', 'exch_trade_enabled', 'exch_url'
    ];

    protected $table = "exchanges";





    /**
     * The pairs that belong to the user.
     */
    public function pairs()
    {
        return $this->hasMany('App\Models\CoinPair');
    }

    public function __construct($attributes = null)
    {

        parent::__construct();

        if($attributes != null){

            $this->exch_balance_enabled = $attributes->exch_balance_enabled;
            $this->exch_code = $attributes->exch_code;
            $this->exch_fee = $attributes->exch_fee;
            $this->exch_id = $attributes->exch_id;
            $this->exch_name = $attributes->exch_name;
            $this->exch_trade_enabled = $attributes->exch_trade_enabled;
            $this->exch_url = $attributes->exch_url;
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
            $query->where('exch_balance_enabled', 'LIKE', "%$search%")
                ->orWhere('exch_code', 'LIKE', "%$search%")
                ->orWhere('exch_fee', 'LIKE', "%$search%")
                ->orWhere('exch_id', 'LIKE', "%$search%")
                ->orWhere('exch_name', 'LIKE', "%$search%")
                ->orWhere('exch_trade_enabled', 'LIKE', "%$search%")
                ->orWhere('exch_url', 'LIKE', "%$search%");
        });
    }

    public static function getExchangeByExchangeCode($exchange_code)
    {
        return Exchange::where("exch_code", "=", $exchange_code)->get();
    }

    public function doesPairExist($mkt_name, $exchange_code)
    {

        foreach($this->pairs as $p){

            if($p->mkt_name === $mkt_name && $p->exch_code === $exchange_code){

                return true;

            }
        }

        return false;

    }

}
