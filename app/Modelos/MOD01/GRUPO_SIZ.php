<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class GRUPO_SIZ extends Model
{
    protected $table = 'dbo.Siz_Menu_Item';

    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){

   }

}
