<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_pairs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string("exch_id");
            $table->string("exch_name");
            $table->string("exch_code");
            $table->string("mkt_id");
            $table->string("mkt_name");
            $table->string("exchmkt_id");

            $table->string("exchange_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_pairs');
    }
}
