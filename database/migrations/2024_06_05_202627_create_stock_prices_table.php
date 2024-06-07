<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockPricesTable extends Migration
{
    public function up()
    {
        Schema::create('stock_market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->date('date');
            $table->decimal('open', 8, 2);
            $table->decimal('high', 8, 2);
            $table->decimal('low', 8, 2);
            $table->decimal('close', 8, 2);
            $table->unsignedBigInteger('volume');
            $table->decimal('after_hours', 8, 2);
            $table->decimal('pre_market', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_prices');
    }
}
