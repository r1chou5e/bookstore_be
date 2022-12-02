<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tracking_num')->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('address_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->string('phone');
            $table->float('value');
            $table->date('shipping_on');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
};