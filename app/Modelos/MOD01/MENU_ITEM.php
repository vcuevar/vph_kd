<?php

namespace App\Modelos\MOD01;

use Illuminate\Database\Eloquent\Model;
use DB;
class MENU_ITEM extends Model
{
    protected $table = 'dbo.Siz_Menu_Item';

    public function tareas() {
        return $this->hasMany('TAREA_MENU');
    }

    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){


   }

}
