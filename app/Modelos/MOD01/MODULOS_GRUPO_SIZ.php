<?php

namespace App\Modelos\MOD01;

use Illuminate\Database\Eloquent\Model;
use DB;
class MODULOS_GRUPO_SIZ extends Model
{
    protected $table = 'dbo.Siz_Modulos_Grupo';
    protected $dateFormat = 'Y-m-d H:i';
    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){


   }

}
