<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('coin_profiles', function (Blueprint $table) {
            $table->id();

            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('genesis_date')->nullable();
            $table->string('consensus_mechanism')->nullable();
            $table->string('hashing_algorithm')->nullable();

            $table->foreignId('coin_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coin_profiles');
    }
}
