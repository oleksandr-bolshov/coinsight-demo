<?php

use App\Domain\Portfolios\Enums\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', TransactionType::getValues());
            $table->float('price_per_coin');
            $table->float('quantity');
            $table->float('fee');
            $table->datetime('datetime');
            $table->foreignId('portfolio_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('coin_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
