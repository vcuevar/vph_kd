<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SizPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Siz_Pedidos_Csv', function (Blueprint $table) {
            $table->increments('id');
            $table->string('usuario',50);
            $table->integer('pedido'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Siz_Pedidos_Csv');
    }
}
