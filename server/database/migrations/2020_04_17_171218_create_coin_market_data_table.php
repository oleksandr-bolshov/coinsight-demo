<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinMarketDataTable extends Migration
{
    public function up()
    {
        Schema::create('coin_market_data', function (Blueprint $table) {
            $table->id();
            $table->float('price');
            $table->float('market_cap');
            $table->float('volume');
            $table->float('circulating_supply');
            $table->foreignId('coin_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coin_market_data');
    }
}
