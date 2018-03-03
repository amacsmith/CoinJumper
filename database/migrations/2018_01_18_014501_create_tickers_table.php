<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('base_curr');
            $table->string('exch_code');
            $table->string('exch_name');

//            $table->string('display_name');

//            $table->string('mkt_name');
            $table->string('primary_curr');
            $table->integer('volume_24');
            $table->integer('mkt_id');
            $table->integer('exchmkt_id');
            $table->integer('exch_id');

            $table->decimal('btc_volume_24','30','8');
            $table->decimal('last_price','30','8');



            $table->string("coin_pair_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickers');
    }
}
