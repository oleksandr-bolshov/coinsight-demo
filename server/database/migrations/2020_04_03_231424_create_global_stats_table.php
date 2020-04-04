<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalStatsTable extends Migration
{
    public function up()
    {
        Schema::create('global_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('market_cap');
            $table->integer('volume');
            $table->float('bitcoin_dominance');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('global_stats');
    }
}
