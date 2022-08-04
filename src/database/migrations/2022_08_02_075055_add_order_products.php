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
        //
        /*
        Schema::dropIfExists('Orders');
        Schema::create('Orders', function (Blueprint $table) {
          $table->unsignedBigInteger('OrderId');
          $table->integer('CustomerId');
          $table->float('Total');
       });

       /*/
       Schema::dropIfExists('OrderProduct');
       Schema::create('OrderProduct', function (Blueprint $table) {
         $table->integer('ProductId');
         $table->integer('Quantity');
         $table->float('Unitprice');
         $table->float('Total');
         $table->integer('OrderId');


      });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
