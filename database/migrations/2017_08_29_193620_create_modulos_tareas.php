<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulosTareas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
       /* Schema::table('Siz_Modulos_Grupo', function (Blueprint $table) {
            $table->integer('id_menu')->nullable();
            $table->char('privilegio_modulo')->nullable();;
            $table->char('privilegio_tarea')->nullable();;
        });*/
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       /* Schema::table('Siz_Modulos_Grupo', function (Blueprint $table) {
            $table->dropColumn('nombre');
            $table->dropColumn('privilegio_modulo');
            $table->dropColumn('privilegio_tarea');
        });*/
    }
}

