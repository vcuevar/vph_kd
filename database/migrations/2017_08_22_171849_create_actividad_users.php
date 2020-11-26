<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('Siz_Modulos_Grupo', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_grupo');
                $table->integer('id_modulo')->nullable();
                $table->integer('id_tarea')->nullable();
                $table->timestamps();
            });
        Schema::create('Siz_Menu_Item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('id_modulo');
            $table->timestamps();
        });
        Schema::create('Siz_Tarea_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('id_menu_item');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     /*   Schema::drop('Siz_Modulos_Grupo');
        Schema::drop('Siz_Menu_Item');
        Schema::drop('Siz_Tarea_menu');*/
    }
}
