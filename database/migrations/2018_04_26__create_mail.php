<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      /*  Schema::create('Siz_Email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('No_Nomina');
            $table->integer('gurpoEnvioCorreo');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
    {
       // Schema::drop('Siz_Email');
    }
}
