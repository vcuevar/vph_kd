<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\OP;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Datatables;
use Carbon\Carbon;

ini_set("memory_limit", '512M');
ini_set('max_execution_time', 0);
class GuestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Mod_Invitado.index');
    }

    public function getArticulo($itemCode, $proveedor, $cantXbulto)
    {
            $cardName = DB::table('OCRD')->where('CardCode', $proveedor)
            ->value('CardName'); 
            $proveedor = $proveedor.' '.$cardName;
            $param = self::getParam_DM_Articulos($itemCode);
            $param['proveedor'] = $proveedor;
            $param['cantXbulto'] = $cantXbulto;
            return view('Mod04_Materiales.Visualiza_ArticuloQR', $param);
            
    }
    public static function getParam_DM_Articulos($item){
 $data = DB::select( "
                    select OITM.ItemCode, ItemName, oitm.CardCode, ocrd.CardName,ALM.*,
                    Costo1.Price as CostoEstandar, Costo1.Currency as MonedaEstandar,
                    Costo10.Price as CostoL10, Costo10.Currency as MonedaL10, 
                    Costo9.Price as CostoACompras, Costo9.Currency as MonedaACompras,
                    CostoUltima.Price as CostoU, CostoUltima.Currency as MonedaU, CostoUltima.DocDate as FechaUltimaCompra, 
                    OITM.InvntryUom as UM, OITM.BuyUnitMsr as UM_Com, OITM.PurPackUn as Factor,
                    UFD1.Descr as Grupo_Pla, tb.ItmsGrpNam as Grupo,
                    UF.Descr as Comprador, OITM.U_ReOrden AS Reorden, OITM.U_Minimo AS Minimo,
                    OITM.U_Maximo AS Maximo, OITM.LeadTime AS TE,OITM.NumInBuy Conversion,
                    (SELECT Descr from UFD1 WHERE TableID = 'OITM' AND FieldID = '18' AND FldValue = OITM.U_Metodo) Metodo, 
                    (SELECT Descr FROM UFD1 WHERE TableID = 'OITM' AND FieldID = '16' AND FldValue = OITM.U_Linea) as Linea,
                     rutas.Name AS Ruta, ordenes.oc as OC 
                    from oitm 
                    left join OCRD on OCRD.CardCode = oitm.CardCode
                    left JOIN
                    (SELECT        ItemCode, SUM(CASE WHEN 
                                                WhsCode = 'AMP-ST' OR
                                                WhsCode = 'AMP-CC' OR
                                                WhsCode = 'AMP-TR' OR
                                                WhsCode = 'AXL-TC' OR
                                                WhsCode = 'APG-PA' 
                                                THEN OnHand ELSE 0 END) AS A_Lerma, 
                                                SUM(CASE WHEN 
                                                WhsCode = 'AMG-ST' 
                                                --OR WhsCode = 'AMG-CC' 
                                                THEN OnHand ELSE 0 END) AS A_Gdl, 
                                                SUM(CASE WHEN 
                                                WhsCode = 'APP-ST' OR
                                                WhsCode = 'APT-PA' OR
                                                WhsCode = 'APG-ST'
                                                THEN OnHand ELSE 0 END) AS WIP,
                                                SUM(CASE WHEN 
                                                WhsCode = 'AMP-CO' OR
                                                WhsCode = 'ARF-ST' OR 
                                                WhsCode = 'AMP-FE'
                                                THEN OnHand ELSE 0 END) AS ALM_OTROS
                    FROM            dbo.OITW
                    GROUP BY ItemCode) AS ALM ON oitm.ItemCode = ALM.ItemCode
                    left join ITM1 Costo1 on Costo1.ItemCode = OITM.ItemCode
                    AND Costo1.PriceList = 1
                    left join ITM1 Costo10 on Costo10.ItemCode = OITM.ItemCode
                    AND Costo10.PriceList = 10
                    left join ITM1 Costo9 on Costo9.ItemCode = OITM.ItemCode
                    AND Costo9.PriceList = 9
                    left join UFD1 on UFD1.FldValue = OITM.U_GrupoPlanea AND UFD1.TableID = 'OITM'
                        AND UFD1.FieldID = 19
                    LEFT OUTER JOIN dbo.UFD1 AS UF ON OITM.U_Comprador = UF.FldValue
                    AND UF.TableID = 'OITM' 
                    left join OITB tb on tb.ItmsGrpCod = OITM.ItmsGrpCod
                    left join [@PL_RUTAS] rutas on rutas.Code = OITM.U_estacion
                    left join (SELECT P.DocEntry, P.ItemCode, P.Price, P.DocDate, P.Currency
                                    FROM PDN1 P 
                                    ) CostoUltima on CostoUltima.ItemCode = OITM.ItemCode
                                    AND CostoUltima.DocEntry = (Select max(DocEntry) from PDN1 where PDN1.ItemCode = OITM.ItemCode)
                    left join (SELECT  POR1.itemCode, SUM( OITM.NumInBuy * POR1.OpenQty ) as oc
                    FROM OPOR INNER JOIN POR1 ON OPOR.DocEntry = POR1.DocEntry LEFT JOIN OITM ON POR1.ItemCode = OITM.ItemCode 
                    
                    WHERE POR1.LineStatus <> 'C'  
                    group by POR1.ItemCode)as ordenes on ordenes.ItemCode = OITM.ItemCode
                    where oitm.ItemCode =  ?            
                ",[$item]); 
        
                
                try { 
                    $semanas = DB::select('exec SIZ_SP_Art ?, ?', ['semana', $item]);
                  } catch(\Illuminate\Database\QueryException $ex){ 
                    $semanas = array();                   
                  }
         $columns = array();
         $sem = '';
         if (count($semanas) > 0) {
            $sem = json_decode(json_encode($semanas[0]), true);
            if ( array_key_exists('ant', $semanas[0]) ) {
                array_push($columns,["data" => "ant", "name" => "Anterior"]);
            } 
               $numerickeys = array_where(array_keys((array)$semanas[0]), function ($key, $value) {
                    return is_numeric($value);
                });
        //Antes de agregar hay que ordenar las columnas numericas obtenidas
        sort($numerickeys);
        //agregar columnas...  hasta 2099 usar 20, para 2100 a 2199 usar 21...
        $string_comienzo_anio = '20';
        foreach ($numerickeys as $value) {
            //averiguamos cuando inicia la semana
            $num_semana = substr($value, 2, 2);
            $year = $string_comienzo_anio. substr($value, 0, 2);
            $StartAndEnd=\AppHelper::instance()->getStartAndEndWeek($num_semana, $year);
            
            //preparamos el nombre
            $name = 'Sem-'.$num_semana.' '.$StartAndEnd['week_start'];
            array_push($columns,["data" => $value, "name" => $name]);        
         }
         } 
        $metodos = DB::select( 'SELECT FldValue, Descr FROM UFD1 WHERE TableID = ? AND FieldID = ? ORDER BY Descr', ['OITM',18]);
        $compradores = DB::select( 'SELECT FldValue, Descr FROM UFD1 WHERE TableID = ? AND FieldID = ? ORDER BY Descr', ['OITM',10]);
        $gruposPlaneacion = DB::select( 'SELECT FldValue, Descr FROM UFD1 WHERE TableID = ? AND FieldID = ? ORDER BY Descr', ['OITM',19]);
                 
         
        $proveedores = DB::select('SELECT CardCode, CardName FROM OCRD WHERE CardType = ? ORDER BY CardName', ['S']);
        
       
        $param = array( 
            'actividades' => [],
            'ultimo' => 0,       
            'data' => $data,          
            'semanas' => $sem,
            'proveedores' => $proveedores,
            'columns' => $columns,
            'metodos' => $metodos,
            'compradores' => $compradores,
            'gruposPlaneacion' => $gruposPlaneacion,
           
        );
        return $param;
}
}
