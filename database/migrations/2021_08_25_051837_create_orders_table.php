<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 16);
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['1', '2', '3'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa');
            $table->string('snap_token', 36)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->delete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->delete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
