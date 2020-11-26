<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class OP extends Model
{
    protected $table = 'dbo.@CP_OF';
    protected $primaryKey = 'Code';
    public $timestamps = false;


    public static function getRuta($docEntry){
    $rs = DB::select('select u_Ruta from OWOR where DocEntry ='. $docEntry);
    //dd($rs);
    // "100,106,109,112"  
    //[100,106,109]  
    foreach ($rs as $r) {
        $ruta = explode(",", str_replace(" ","",$r->u_Ruta));
        return $ruta;
    }
   }
   public static function ContieneRuta($docEntry, $ruta){
   return in_array ($ruta , self::getRuta($docEntry));
   }
   public static function getRutaNombres($docEntry){
    $rs = DB::select('select u_Ruta from OWOR where DocEntry ='. $docEntry);

    foreach ($rs as $r) {
        $ruta = explode(",",str_replace(array("\r","\n","\r\n"),'', $r->u_Ruta));

    }
   
    $data1= [];
    $i= 0;

foreach($ruta as $e){

    $irs =DB::table('@PL_RUTAS')->where('U_Orden', ($e))->value('Name');
           $data = array ( $e, $irs );     
           $data1 +=[$i=>$data];
           $i++;
}

return $data1;
   }
   
   public static function getTodasRutas(){

        $resu =DB::table('@PL_RUTAS')
        ->select('Code', 'Name')
        ->where('U_Estatus', 'A')          
        ->where('Code', '>','109')          
        ->where('U_Tipo','1')          
        ->where('U_Calidad', 'N');          

        return $resu->get();
   }

   public static function getStatus($docEntry){
    /*   select	OWOR.docentry, [@CP_OF].Code,
            [@CP_OF].U_CT, [@CP_OF].U_Orden,
            OWOR.Status, OriginNum,
            OITM.ItemName,[@CP_OF].U_Reproceso,
            OWOR.PlannedQty,[@CP_OF].U_Recibido,
            [@CP_OF].U_Procesado
        from OWOR
            left join OITM on OITM.ItemCode = OWOR.ItemCode
            left join [@CP_OF] on [@CP_OF].U_DocEntry = OWOR.DocEntry
        where OWOR.DocEntry = '70516'*/

       $order =  DB::table('OWOR')
           ->join('@CP_OF', '@CP_OF.U_DocEntry','=', 'OWOR.DocEntry')
           ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
           ->leftJoin('@PL_RUTAS', '@PL_RUTAS.U_Orden','=', '@CP_OF.U_Orden')
           ->select('OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_CT',
                    '@CP_OF.U_Orden','OWOR.Status', 'OWOR.OriginNum',
                    'OITM.ItemName', '@CP_OF.U_Reproceso', 'OWOR.PlannedQty',
                    '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
           ->where('OWOR.DocEntry', '70516')->get();


        return $order;
   }

   public static function getEstacionSiguiente ($Code, $option){
        //buscar indice en array de estaciones de la "estacion siguiente"
        $i = 1 +  array_search(OP::find($Code)->U_CT, self::getRuta(OP::find($Code)->U_DocEntry));
        $cantidad_rutas = count(self::getRuta(OP::find($Code)->U_DocEntry)); //cuantas hay?
       if ($i>=$cantidad_rutas){ //el indice rebasa la cantidad de rutas
            //obtener si la ultima ruta es de calidad
           $calidad = DB::table('@PL_RUTAS')->where('U_Orden', self::getRuta(OP::find($Code)->U_DocEntry)[$cantidad_rutas-1])->value('U_Calidad');
           
           if($calidad == 'S'){ // puede generar recibo de Produccion
               return "'".'Terminar OP'."'";
           }          
           else{ //si la ultima estacion de la ruta no es de Calidad entonces:
               return "'".'Error en ruta'."'";
           }
            
       }

       //el indice esta dentro de la cantidad de rutas
       $rs = DB::select('select * from [@PL_RUTAS] where U_Orden ='. self::getRuta(OP::find($Code)->U_DocEntry)[$i]);
    
       //devolver nombre o numero de la ruta segun la opcion.
       foreach ($rs as $r) {
           if ($option == 1){   
               return "'".$r->Name."'";
           }
           if ($option == 2){
               return $r->U_Orden;
           }

       }
   }

   public static function getEstacionActual ($Code){

        $i = array_search(OP::find($Code)->U_CT, self::getRuta(OP::find($Code)->U_DocEntry));

       if ($i>=count(self::getRuta(OP::find($Code)->U_DocEntry))){
           $i=$i-1;
       }

       $rs = DB::select('select * from [@PL_RUTAS] where U_Orden ='. self::getRuta(OP::find($Code)->U_DocEntry)[$i]);

       foreach ($rs as $r) {
           return "'".$r->Name."'";
       }
   }

   public static function onFirstEstacion($Code){
    $i = array_search(OP::find($Code)->U_CT, self::getRuta(OP::find($Code)->U_DocEntry));

    if ($i>=count(self::getRuta(OP::find($Code)->U_DocEntry))){
        $i=$i-1;
    }

    $rs = DB::select('select * from [@PL_RUTAS] where U_Orden ='. self::getRuta(OP::find($Code)->U_DocEntry)[$i]);
    $estacionActual = null;
    foreach ($rs as $r) {
        $estacionActual =  $r->Code;
    }

    $rs1 = DB::select('select u_Ruta from OWOR where DocEntry ='. OP::find($Code)->U_DocEntry);
    //dd($rs);
    foreach ($rs1 as $r) {
        $ruta = explode(",", $r->u_Ruta);
        if($ruta[0]==$estacionActual){
            return true;
        }
        else{
            return false;
        }
    }

   }

   public  static  function avanzarEstacion($Code, $estacionesUsuario){ // habilitar o desabilitar boton
       $rutas = explode(",", $estacionesUsuario);
     //El usuario tiene definidas las estaciones que puede avanzar
       if (array_search(OP::find($Code)->U_CT, $rutas) !== FALSE)
       {//si el usuario tiene permitida la estacion actual de la OP 
           return "'".'enabled'."'";//se habilita boton avanzar
       }else{
           return "'".'disabled'."'";
       }
   }

   public static function getInfoOwor($op){
   $rs = DB::table('OWOR')    
    ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
    ->leftJoin('OCRD', 'OCRD.CardCode','=', 'OWOR.CardCode')
    ->select('OWOR.ItemCode', 'OWOR.Status', 'OWOR.CardCode', 'OWOR.OriginNum as pedido',
    'OITM.ItemName', 'OCRD.CardName')
    ->where('OWOR.DocEntry', $op)->first();
   
    return $rs;

   }
   public static function getDescripcion($op){
   $rs = DB::table('OWOR')    
    ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')       
    ->where('OWOR.DocEntry', $op)->value('ItemName');
    return $rs;

   }
}
