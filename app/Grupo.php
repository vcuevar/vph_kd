<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Grupo extends Model
{
    protected $table = 'dbo.OHTY';
    protected $primaryKey = 'typeID';
    public $timestamps = false;


    public static function getInfo($id_grupo){
        dd(self::find($id_grupo));
   }

   public static function getStatus($docEntry){

   }

   public static function getEstacionSiguiente ($docEntry){


   }

}
