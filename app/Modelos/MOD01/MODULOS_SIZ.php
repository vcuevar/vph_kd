<?php

namespace App\Modelos\MOD01;

use Illuminate\Database\Eloquent\Model;
use DB;
class MODULOS_SIZ extends Model
{
    protected $table = 'dbo.Siz_Modulo';

    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){


   }

}
