<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer("exch_balance_enabled");
            $table->string("exch_code");
            $table->decimal("exch_fee", 20, 8);
            $table->integer("exch_id");
            $table->string("exch_name");
            $table->integer("exch_trade_enabled");
            $table->string("exch_url");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}
