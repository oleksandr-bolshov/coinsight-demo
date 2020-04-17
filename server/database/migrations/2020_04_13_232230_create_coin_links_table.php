<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinLinksTable extends Migration
{
    public function up()
    {
        Schema::create('coin_links', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->string('type');
            $table->foreignId('coin_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coin_links');
    }
}
