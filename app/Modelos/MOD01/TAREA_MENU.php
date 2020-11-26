<?php

namespace App\Modelos\MOD01;

use Illuminate\Database\Eloquent\Model;
use DB;
class TAREA_MENU extends Model
{
    protected $table = 'dbo.Siz_Tarea_menu';

    public function menus() {
        return $this->belongsTo('MENU_ITEM', 'id_menu_item');
    }

    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){


   }

}
