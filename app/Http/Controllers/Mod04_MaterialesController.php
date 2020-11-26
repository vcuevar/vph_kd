<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use App\OP;
use Dompdf\Dompdf;
//excel
use Illuminate\Http\Request;
//DOMPDF
use Illuminate\Support\Facades\Input;
use App\SAP;
use Session;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Datatables;
use Validator;
use QrCode;
ini_set("memory_limit", '512M');
ini_set('max_execution_time', 0);

class Mod04_MaterialesController extends Controller
{
public function reporteEntradasAlmacen()
{
    if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();  
        $data = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),
            'db' => DB::getDatabaseName(),          
            'fi' => Input::get('FechIn'),
            'ff' => Input::get('FechaFa')
        );
        return view('Mod04_Materiales.reporteEntradasAlmacen', $data);
    } else {
        return redirect()->route('auth/login');
    }
}
    public function DataShowEntradasMP(Request $request)
    {
        if (Auth::check()) {
            $consulta = DB::select(DB::raw( "
          SELECT * FROM(
            SELECT 'ENTRADA G' as TIPO, PDN1.ItemCode, OPDN.DocNum, OPDN.DocDate, OPDN.CardCode, OPDN.CardName, PDN1.Price, PDN1.LineTotal, PDN1.VatSum, OPDN.DocCur, PDN1.Dscription, OPDN.DocRate, PDN1.WhsCode, PDN1.Quantity, PDN1.NumPerMsr, OPDN.NumAtCard
            ,PDN1.TotalFrgn, PDN1.VatSumFrgn 
            FROM   OPDN OPDN INNER JOIN dbo.PDN1 PDN1 ON OPDN.DocEntry=PDN1.DocEntry
            WHERE  (CAST( OPDN.DocDate as DATE) 
                        BETWEEN '" . date('d-m-Y', strtotime($request->get('fi'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime($request->get('ff'))) . ' 23:59:59' . "'
            )AND (PDN1.WhsCode=N'AMG-CC' OR PDN1.WhsCode=N'AMG-ST' OR PDN1.WhsCode=N'AMG-FE' OR PDN1.WhsCode=N'AGG-RE' OR PDN1.WhsCode=N'AMG-KU' OR PDN1.WhsCode=N'AMP-BL' OR PDN1.WhsCode=N'APG-ST' OR PDN1.WhsCode=N'APG-PA' OR PDN1.WhsCode=N'ATG-ST' OR PDN1.WhsCode=N'ATG-FX' OR PDN1.WhsCode=N'AMP-TR' OR PDN1.WhsCode=N'ARG-ST')
UNION ALL
            SELECT 'ENTRADA L' as TIPO, PDN1.ItemCode, OPDN.DocNum, OPDN.DocDate, OPDN.CardCode, OPDN.CardName, PDN1.Price, PDN1.LineTotal, PDN1.VatSum, OPDN.DocCur, PDN1.Dscription, OPDN.DocRate, PDN1.WhsCode, PDN1.Quantity, PDN1.NumPerMsr, OPDN.NumAtCard
            ,PDN1.TotalFrgn, PDN1.VatSumFrgn 
            FROM   OPDN OPDN INNER JOIN PDN1 PDN1 ON OPDN.DocEntry=PDN1.DocEntry
            WHERE  (CAST( OPDN.DocDate as DATE) 
            BETWEEN '" . date('d-m-Y', strtotime($request->get('fi'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime($request->get('ff'))) . ' 23:59:59' . "'
            ) 
            AND (PDN1.WhsCode IS  NULL  OR  NOT (PDN1.WhsCode=N'AGG-RE' OR PDN1.WhsCode=N'AMG-CC' OR PDN1.WhsCode=N'AMG-FE' OR PDN1.WhsCode=N'AMG-KU' OR PDN1.WhsCode=N'AMG-ST' OR PDN1.WhsCode=N'AMP-BL' OR PDN1.WhsCode=N'AMP-TR' OR PDN1.WhsCode=N'APG-PA' OR PDN1.WhsCode=N'APG-ST' OR PDN1.WhsCode=N'ARG-ST' OR PDN1.WhsCode=N'ATG-FX' OR PDN1.WhsCode=N'ATG-ST'))
UNION ALL
            SELECT 'NOTA CREDITO' as TIPO, RPC1.ItemCode, ORPC.DocNum, ORPC.DocDate, ORPC.CardCode, ORPC.CardName, RPC1.Price, RPC1.LineTotal, RPC1.VatSum, ORPC.DocCur, RPC1.Dscription, ORPC.DocRate, RPC1.WhsCode, RPC1.Quantity, RPC1.NumPerMsr, ORPC.NumAtCard
            ,RPC1.TotalFrgn, RPC1.VatSumFrgn 
            FROM   ORPC ORPC INNER JOIN RPC1 RPC1 ON ORPC.DocEntry=RPC1.DocEntry
            WHERE  (CAST( ORPC.DocDate as DATE) 
            BETWEEN '" . date('d-m-Y', strtotime($request->get('fi'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime($request->get('ff'))) . ' 23:59:59' . "'
            ) 
            AND (RPC1.WhsCode IS  NULL  OR  NOT (RPC1.WhsCode=N'AGG-RE' OR RPC1.WhsCode=N'AMG-CC' OR RPC1.WhsCode=N'AMG-FE' OR RPC1.WhsCode=N'AMG-KU' OR RPC1.WhsCode=N'AMG-ST' OR RPC1.WhsCode=N'AMP-BL' OR RPC1.WhsCode=N'AMP-TR' OR RPC1.WhsCode=N'APG-PA' OR RPC1.WhsCode=N'APG-ST' OR RPC1.WhsCode=N'ARG-ST' OR RPC1.WhsCode=N'ATG-FX' OR RPC1.WhsCode=N'ATG-ST'))
UNION ALL
            SELECT 'DEVOLUCION' AS TIPO, RPD1.ItemCode, ORPD.DocNum, ORPD.DocDate, ORPD.CardCode, ORPD.CardName, RPD1.Price, RPD1.LineTotal, RPD1.VatSum, ORPD.DocCur, RPD1.Dscription, ORPD.DocRate, RPD1.WhsCode, RPD1.Quantity, RPD1.NumPerMsr, ORPD.NumAtCard
            ,RPD1.TotalFrgn, RPD1.VatSumFrgn 
            FROM   ORPD ORPD INNER JOIN RPD1 RPD1 ON ORPD.DocEntry=RPD1.DocEntry
            WHERE  (CAST( ORPD.DocDate as DATE) 
            BETWEEN '" . date('d-m-Y', strtotime($request->get('fi'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime($request->get('ff'))) . ' 23:59:59' . "'
            ) 
            AND (RPD1.WhsCode IS  NULL  OR  NOT (RPD1.WhsCode=N'AGG-RE' OR RPD1.WhsCode=N'AMG-CC' OR RPD1.WhsCode=N'AMG-FE' OR RPD1.WhsCode=N'AMG-KU' OR RPD1.WhsCode=N'AMG-ST' OR RPD1.WhsCode=N'AMP-BL' OR RPD1.WhsCode=N'AMP-TR' OR RPD1.WhsCode=N'APG-PA' OR RPD1.WhsCode=N'APG-ST' OR RPD1.WhsCode=N'ARG-ST' OR RPD1.WhsCode=N'ATG-FX' OR RPD1.WhsCode=N'ATG-ST'))
            
            ) T
            ORDER BY T.TIPO, T.DocNum, T.DocDate
        "));
        
        $request->session()->put( 'fechas_entradas', array(
                'fi' => $request->get('fi'),
                'ff' => $request->get('ff')
            ));
        
        $consulta = collect($consulta);
            return Datatables::of($consulta)
                ->addColumn('Cant', function ($consulta) {
                    return ($consulta->Quantity * $consulta->NumPerMsr);
                })
                ->addColumn('LineaTotal', function ($consulta) {
                    if ($consulta->DocCur == 'MXP') {
                        return $consulta->LineTotal;
                    } 
                    elseif ($consulta->DocCur == 'USD') {
                        return $consulta->TotalFrgn;
                    }
                })
                ->addColumn('Iva', function ($consulta) {
                    if ($consulta->DocCur == 'MXP') {
                        return $consulta->VatSum;
                    } 
                    elseif ($consulta->DocCur == 'USD') {
                        return $consulta->VatSumFrgn;
                    }
                })
                ->addColumn('TotalConIva', function ($consulta) {
                    if ($consulta->DocCur == 'MXP') {
                        return ($consulta->LineTotal + $consulta->VatSum);
                    } 
                    elseif ($consulta->DocCur == 'USD') {
                        return ($consulta->TotalFrgn + $consulta->VatSumFrgn);
                    }
                })
                
                ->make(true);
        } else {
            return redirect()->route('auth/login');
        }
    }
   
public function entradasPDF()
{
    $a = json_decode(Session::get('entradas'));
      
        $entradasL = array_filter($a, function ($value) {
            return $value->TIPO == 'ENTRADA L';
        });
        $entradasG = array_filter($a, function ($value) {
            return $value->TIPO == 'ENTRADA G';
        });
        $devoluciones = array_filter($a, function ($value) {
            return $value->TIPO == 'DEVOLUCION';
        });
        $notascredito = array_filter($a, function ($value) {
            return $value->TIPO == 'NOTA CREDITO';
        });
        
    // dd(\AppHelper::instance()->getHumanDate(array_get( Session::get('fechas_entradas'), 'ff')));
    $data = array('notascredito' => $notascredito, 'entradasL' => $entradasL, 'entradasG' => $entradasG, 'devoluciones' => $devoluciones, 'fechas_entradas' => Session::get('fechas_entradas'));
    $pdf = \PDF::loadView('Mod04_Materiales.ReporteEntradasPDF', $data);
        $pdf->setPaper('Letter', 'landscape')->setOptions(['isPhpEnabled' => true]);  
    return $pdf->stream('SIZ Entradas' . ' - ' . date("d/m/Y") . '.Pdf');
}
public function iowhsPDF()
{
    $a = json_decode(Session::get('entradasysalidas'));
         
    // dd(\AppHelper::instance()->getHumanDate(array_get( Session::get('fechas_entradas'), 'ff')));
    $data = array('data' => $a, 'fechas_entradas' => Session::get('param_entradasysalidas'));
    $pdf = \PDF::loadView('Mod04_Materiales.ReporteIOWhsPDF', $data);
        $pdf->setPaper('Letter', 'landscape')->setOptions(['isPhpEnabled' => true]);  
    return $pdf->stream('SIZ Entradas - Salidas' . ' ' . date("d/m/Y") . '.Pdf');
}
    public function entradasXLS()
    {
        $path = public_path() . '/assets/plantillas_excel/Mod_01/SIZ_entradas.xlsx';
        $data = json_decode(Session::get('entradas'));
        $fechas_entradas = Session::get('fechas_entradas');
        $fecha = 'Del: '. \AppHelper::instance()->getHumanDate(array_get($fechas_entradas, 'fi')).' al: '.
                \AppHelper::instance()->getHumanDate(array_get($fechas_entradas, 'ff'));

                Excel::load($path, function ($excel) use ($data, $fecha) {
            $excel->sheet('MP', function ($sheet) use ($data, $fecha) {

                $sheet->cell('C4', function ($cell) {
                    $cell->setValue(\AppHelper::instance()->getHumanDate(date("Y-m-d H:i:s")).' '. date("H:i:s"));
                });
                $sheet->cell('C5', function ($cell) use ($fecha) {
                    $cell->setValue($fecha);
                });
                $index = 7;
                foreach ($data as $row) {
                    $sheet->row($index, [
                     $row->DocNum, 
                     $row->TIPO,
                     $row->DocDate,
                     $row->CardCode,
                     $row->CardName,
                     $row->NumAtCard,
                     $row->ItemCode,
                     $row->Dscription,
                     $row->Cant,
                     $row->Price,
                     $row->LineaTotal,
                     $row->VatSum,
                     $row->TotalConIva,
                     $row->DocCur
                     
                    ]);
                    $index++;
                }
            });
        })
            ->setFilename('SIZ Reporte de Materia Prima')
            ->export('xlsx');
    }
    public function EntradasSalidas(Request $request)
    {
        if (Auth::check()) {
            $fi = strtotime($request->get('FechIn'). ' 00:00:00');
            $ff = strtotime($request->get('FechaFa'). ' 23:59:59');
            if ($fi > $ff) {
                Session::flash('error', 'Rango de fechas no válido');
                return redirect()->back()->withInput($request->input());
            }
            $rules = [
                // 'fieldText' => 'required|exists:OITM,ItemCode',
                'FechIn' => 'required|date|before:tomorrow',
                'FechaFa' => 'required|date',              
            ];
            $customMessages = [
                'FechIn.before' => 'La Fecha de Inicio no es válida',               
                //'fieldText.exists' => 'El Código no existe.'
            ];
            $valid = Validator::make( $request->all(), $rules, $customMessages);
            
            if ($valid->fails()) {
                return redirect()->back()
                    ->withErrors($valid)
                    ->withInput($request->input());
            }
           
            $tipomat = Input::get('text_selDos');
            $almacenes = "'".implode("', '", Input::get('data_selCuatro')). "'"; //alamacenes separados por comas            
           // dd($almacenes);            
            $fechai = date('d-m-Y' , $fi);
            $fechaf = date('d-m-Y' , $ff);
                   
            $user = Auth::user();
            $actividades = $user->getTareas();

            $param = array(
                'actividades' => $actividades,
                'ultimo' => count($actividades),
                'fi' => $fechai,
                'ff' => $fechaf,
                'tipomat' => $tipomat,
                'almacenes' => $almacenes
            );
            return view('Mod04_Materiales.ReporteEntradasSalidas', $param);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function DM_Articulos(Request $request){
        if (Auth::check()) {
           
            $rules = [
                // 'fieldText' => 'required|exists:OITM,ItemCode',
                'pKey' => 'required',
                'costocompras' => 'min:0',

            ];
            $customMessages = [
                'pKey.required' => 'Ningun código seleccionado',
                'costocompras.min' => 'El costo de A-COMPRAS debe ser igual/mayor a cero',
                //'fieldText.exists' => 'El Código no existe.'
            ];
            $valid = Validator::make( $request->all(), $rules, $customMessages);
            
            if ($valid->fails()) {
                return redirect()->back()
                    ->withErrors($valid)
                    ->withInput();
            }
            
            $param = self::getParam_DM_Articulos($request, Input::get('pKey'));
            
            return view('Mod04_Materiales.DM_Articulos', $param);
            
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function QR_Articulos(Request $request){
        if (Auth::check()) {
           
            $rules = [
                // 'fieldText' => 'required|exists:OITM,ItemCode',
                'pKey' => 'required',
                'costocompras' => 'min:0',

            ];
            $customMessages = [
                'pKey.required' => 'Ningun código seleccionado',
                'costocompras.min' => 'El costo de A-COMPRAS debe ser igual/mayor a cero',
                //'fieldText.exists' => 'El Código no existe.'
            ];
            $valid = Validator::make( $request->all(), $rules, $customMessages);
            
            if ($valid->fails()) {
                return redirect()->back()
                    ->withErrors($valid)
                    ->withInput();
            }
            
            $param = self::getParam_DM_Articulos($request, Input::get('pKey'));
            
            return view('Mod04_Materiales.Etiqueta_Articulo', $param);
            
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function articuloToSap(Request $request){
        //monedacompras
        //grupop
        //metodo
        //proveedor
        //code
        //costocompras --
        //comprador
//dd($request->all());
         $rules = [
                // 'fieldText' => 'required|exists:OITM,ItemCode',
                'costocompras' => 'required|numeric',
            ];
            $customMessages = [
                'costocompras.required' => 'El costo de A-COMPRAS debe capturarse',
                'costocompras.numeric' => 'El costo de A-COMPRAS debe ser numérico'
            ];
            $valid = Validator::make($request->all(), $rules, $customMessages);
            
            if ($valid->fails()) {
                
                //$errors = new \Illuminate\Support\MessageBag();

                // add your error messages:
               // $errors->add('Error', 'El costo A-COMPRAS debe contener un número');
               $param = self::getParam_DM_Articulos($request, Input::get('pKey'));
               return view('Mod04_Materiales.DM_Articulos', $param)->withErrors($valid);
                    
            }else {
                 $result = SAP::SaveArticulo(Input::all());
                 if ($result != 'ok') {                    
                    Session::flash('error', $result);
                 } else {
                    Session::flash('mensaje','Artículo guardado.');
                 }
                 $param = self::getParam_DM_Articulos($request, Input::get('pKey'));
               return view('Mod04_Materiales.DM_Articulos', $param);
            }
       
    }
public static function getParam_DM_Articulos($request, $item){
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
                 
        $user = Auth::user();
        $actividades = $user->getTareas();  
        
        $proveedores = DB::select('SELECT CardCode, CardName FROM OCRD WHERE CardType = ? ORDER BY CardName', ['S']);
        
        $tareas = json_decode(json_encode($actividades), true);
        foreach ($tareas as $tarea) {
            $arrayurl = explode('/', $request->path());
            $ruta = str_replace('%20', ' ', $arrayurl[count($arrayurl)-1]);
            $ruta = str_replace('%C3%B7', '&#247;', $ruta);
            $privilegioTarea = array_search($ruta, $tarea);
           
            if ($privilegioTarea != false) {
                $privilegioTarea = $tarea['privilegio_tarea'];
                break;
            }
       }
       if (strpos($privilegioTarea, 'checked') !== false) {
        $privilegioTarea = '';            
       } else {
         $privilegioTarea = 'disabled';
       }
        $param = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),
            'data' => $data,          
            'semanas' => $sem,
            'proveedores' => $proveedores,
            'columns' => $columns,
            'metodos' => $metodos,
            'compradores' => $compradores,
            'gruposPlaneacion' => $gruposPlaneacion,
            'privilegioTarea' => $privilegioTarea
        );
        return $param;
}

public function solicitudMateriales(){
     if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();
        $almacenesDestino = DB::table('SIZ_AlmacenesTransferencias')
                            ->where('Dept', Auth::user()->dept)
                            ->where('SolicitudMateriales', 'D')->get();
    

        $param = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),
            'almacenesDestino' => $almacenesDestino,  
        );
        return view('Mod04_Materiales.solicitudMateriales', $param);
    } else {
         return redirect()->route('auth/login');
    }
    
}
public function pickingArticulos(){
     if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();  

        $param = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),          
        );
        return view('Mod04_Materiales.ShowSolicitudes', $param);
    } else {
         return redirect()->route('auth/login');
    } 
}
public function TrasladosArticulos(){
     if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();  

        $param = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),          
        );
        return view('Mod04_Materiales.ShowTraslados', $param);
    } else {
         return redirect()->route('auth/login');
    } 
}
public function AutorizacionSolicitudes(){
     if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();         
      
        $param = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),          
        );
        return view('Mod04_Materiales.AutorizarSolicitudes', $param);
    } else {
         return redirect()->route('auth/login');
    }
    
}
public function DataSolicitudes(){
     $consulta = DB::table('SIZ_SolicitudesMP')
                    ->join('SIZ_MaterialesSolicitudes', 'SIZ_MaterialesSolicitudes.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
                    ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                    ->leftjoin('OUDP', 'OUDP.Code', '=', 'dept')
                    ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'firstName', 'lastName', 'dept', 'Name')
                    ->select('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 
                    'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'OHEM.firstName',
                     'OHEM.lastName', 'OHEM.dept', 'OUDP.Name as depto')
                    ->whereIn('SIZ_SolicitudesMP.Status', ['Pendiente', 'Regresada', 'En Proceso'])
                    ->whereIn('SIZ_MaterialesSolicitudes.EstatusLinea', ['S', 'P', 'N']);
   
                    //$consulta = collect($consulta);
            return Datatables::of($consulta)             
                 ->addColumn('folio', function ($item) {                     
                       return  '<a href="' . url('home/2 PICKING ARTICULOS/solicitud/'.$item->Id_Solicitud).'"><i class="fa fa-hand-o-right"></i> '.$item->Id_Solicitud.'</a>';                
                    }
                    )
                    ->addColumn('user_name', function ($item) {
                       return  $item->firstName.' '.$item->lastName;           
                    }
                    )
                    ->addColumn('area', function ($item) {                      
                       return  $item->depto;
                    }
                    )
                    ->addColumn('statusbadge', function ($item) {
                      if ($item->Status == 'Pendiente') {
                          return '<a href="2 PICKING ARTICULOS/solicitud/'.$item->Id_Solicitud.'"><span class="badge badge-warning" style="background:#FFC107">'.$item->Status.'</span></a>';
                      } else {
                            return '<a href="2 PICKING ARTICULOS/solicitud/'.$item->Id_Solicitud.'"><span class="badge badge-primary" style="background:#007BFF">'.$item->Status.'</span></a>';
                      }                                                                    
                    }
                    )
                ->make(true);
}
public function DataTraslados(){
     $consulta = DB::table('SIZ_SolicitudesMP')
                    ->join('SIZ_MaterialesSolicitudes', 'SIZ_MaterialesSolicitudes.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
                    ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                    ->leftjoin('OUDP', 'OUDP.Code', '=', 'dept')
                    ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'firstName', 'lastName', 'dept', 'Name')
                    ->select('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 
                    'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'OHEM.firstName',
                     'OHEM.lastName', 'OHEM.dept', 'OUDP.Name as depto')
                    ->where('SIZ_MaterialesSolicitudes.Cant_PendienteA', '>', 0)
                    ->where('SIZ_SolicitudesMP.Status', 'Traslado');
     //$consulta = collect($consulta);
            return Datatables::of($consulta)             
                 ->addColumn('folio', function ($item) {                     
                       return  '<a href="TRASLADOS/solicitud/'.$item->Id_Solicitud.'"><i class="fa fa-hand-o-right"></i> '.$item->Id_Solicitud.'</a>';           
                    }
                    )
                    ->addColumn('user_name', function ($item) {
                       return  $item->firstName.' '.$item->lastName;           
                    }
                    )
                    ->addColumn('area', function ($item) {                      
                       return  $item->depto;
                    }
                    )
                   
                ->make(true);
}
public function DataEntregaslotes(){
     $consulta = DB::table('SIZ_SolicitudesMP')
                    ->join('SIZ_MaterialesTraslados', 
                    'SIZ_MaterialesTraslados.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
                    ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                    ->join('SIZ_AlmacenesTransferencias', function ($join) {
                        $join->on('SIZ_AlmacenesTransferencias.Code', '=', 'SIZ_SolicitudesMP.AlmacenOrigen')
                            ->where('SIZ_AlmacenesTransferencias.TrasladoDeptos', '<>', 'D')
                            ->whereNotNull('TrasladoDeptos');
                    })
                    ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 
                    'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'firstName', 'lastName', 'AlmacenOrigen')
                    ->select('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 
                        'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'OHEM.firstName',
                        'OHEM.lastName', 'SIZ_SolicitudesMP.AlmacenOrigen')
                    ->where('SIZ_MaterialesTraslados.Cant_PendienteA', '>', 0)
                    ->whereNotIn('SIZ_MaterialesTraslados.EstatusLinea', ['T', 'S', 'C']) //solicitudes que no tengan lineas terminadas, listas para ser recibidas y canceladas.
                    ->where('SIZ_SolicitudesMP.Status', 'Pendiente')
                    //->whereNull('AlmacenOrigen')
                    ->where('SIZ_AlmacenesTransferencias.dept', Auth::user()->dept)
                    //->where('SIZ_SolicitudesMP.Usuario', Auth::user()->U_EmpGiro)
                    ;
     //$consulta = collect($consulta);
            return Datatables::of($consulta)             
                 ->addColumn('folio', function ($item) {                     
                       return  '<a href="'.url('lotesdeptos/'.$item->Id_Solicitud).'"><i class="fa fa-hand-o-right"></i> '.$item->Id_Solicitud.'</a>';           
                    }
                    )
                    ->addColumn('user_name', function ($item) {
                       return  $item->firstName.' '.$item->lastName;           
                    }
                    )
                   
                ->make(true);
}
public function DataSolicitudes_Auht(){
     $consulta = DB::table('SIZ_SolicitudesMP')
                  //  ->join('SIZ_MaterialesSolicitudes', 'SIZ_MaterialesSolicitudes.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
            ->join('SIZ_MaterialesSolicitudes', function ($join) {
                $join->on('SIZ_MaterialesSolicitudes.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
                    //->where('SIZ_MaterialesSolicitudes.EstatusLinea', '<>', 'A');
                    ->whereIn('EstatusLinea', ['S', 'A']);
            })
                    ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                    ->leftjoin('OUDP', 'OUDP.Code', '=', 'dept')
                    ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'firstName', 'lastName', 'dept', 'Name')
                    ->select('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 
                    'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'OHEM.firstName',
                     'OHEM.lastName', 'OHEM.dept', 'OUDP.Name as depto')
                    ->where('SIZ_SolicitudesMP.Status', 'Autorizacion');
     //$consulta = collect($consulta);
            return Datatables::of($consulta)             
                 ->addColumn('folio', function ($item) {                     
                       return  '<a href="AUTORIZACION/solicitud/'.$item->Id_Solicitud.'"><i class="fa fa-hand-o-right"></i> '.$item->Id_Solicitud.'</a>';           
                    }
                    )
                    ->addColumn('user_name', function ($item) {
                       return  $item->firstName.' '.$item->lastName;           
                    }
                    )
                    ->addColumn('area', function ($item) {                      
                       return  $item->depto;
                    }
                    )                                     
                    
                ->make(true);
}
  public function ShowArticulosWH(Request $request)
    {
        $consulta= DB::select('
        SELECT OITM.ItemCode, ItemName, InvntryUom AS UM, (ALMACENES.stock - (COALESCE(PROCESO.CantProceso, 0) + COALESCE(PROCESOT.CantProcesoT, 0))) AS Existencia FROM OITM
        LEFT JOIN 
        (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\' OR WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) 
		AS stock
        FROM dbo.OITW
        GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
		LEFT JOIN
		(select ItemCode, sum (Cant_PendienteA) CantProceso
		 from SIZ_MaterialesSolicitudes mat
		 where mat.EstatusLinea in (\'S\', \'P\', \'N\')
         group by ItemCode) AS PROCESO ON OITM.ItemCode = PROCESO.ItemCode
         LEFT JOIN
         (select mat.ItemCode, sum (Cant_PendienteA) CantProcesoT
from SIZ_MaterialesTraslados mat
LEFT JOIN SIZ_SolicitudesMP sol on sol.Id_Solicitud = mat.Id_Solicitud
where mat.EstatusLinea in (\'S\', \'P\', \'I\', \'E\', \'N\')
AND (sol.AlmacenOrigen = \'AMP-ST\' OR sol.AlmacenOrigen = \'APG-PA\' )
group by ItemCode) AS PROCESOT ON OITM.itemCode = PROCESOT.ItemCode
        WHERE PrchseItem = \'Y\' AND InvntItem = \'Y\' AND U_TipoMat <> \'PT\' AND U_TipoMat IS NOT NULL
        AND OITM.frozenFor = \'N\'
        AND (ALMACENES.stock - (COALESCE(PROCESO.CantProceso, 0) + COALESCE(PROCESOT.CantProcesoT, 0))) >= 0
        ');
               $columns = array(
                ["data" => "ItemCode", "name" => "Código"],
                ["data" => "ItemName", "name" => "Descripción"],
                ["data" => "UM", "name" => "UM"],            
                ["data" => "Existencia", "name" => "Existencia", "defaultContent" => "0.00"],            
            );          

            return response()->json(array('data' => $consulta, 'columns' => $columns));
    }
public function saveArt(Request $request){   
     
            DB::beginTransaction();
        $err = false;
        $id = 0;
        $arts = $request->get('arts');
        $usercomment = mb_strtoupper($request->get('comentario'));
                $dt = new \DateTime();
                $id = DB::table('SIZ_SolicitudesMP')->insertGetId(
                    ['FechaCreacion' => $dt, 'Usuario' => Auth::id(), 'Status' => 'Autorizacion', 
                    'ComentarioUsuario' => $usercomment]
                );
                
                foreach ($arts as $art) {
                    DB::table('SIZ_MaterialesSolicitudes')->insert(
                        ['Id_Solicitud' => $id, 'ItemCode' => $art['pKey'], 
                        'Cant_Requerida' => $art['cant'], 'Destino' => $art['labelDestino'], 
                        'Cant_Autorizada' =>  $art['cant'], 'Cant_scan' =>  0, 
                        'Cant_PendienteA' =>  $art['cant'],
                        'EstatusLinea' => 'S', 'Cant_ASurtir_Origen_A' => 0, 
                        'Cant_ASurtir_Origen_B' => 0]
                    );
                }
                if (!($id > 0) || is_null($arts) || is_null($id)) {
                    $err =true;
                }
        
        if ($err) {
            DB::rollBack();       
            return 'Error: No se guardo la solicitud, favor de notificar a Sistemas';
        }else{
                DB::commit();
                $N_Emp = User::where('position', 4)
                    ->select(DB::raw('case when email like \'%@%\' then email else email + cast(\'@zarkin.com\' as varchar)  end AS correo'))
                    ->where('dept', Auth::user()->dept)
                    ->where('status', 1)
                    ->value('correo'); 
                $correos_db = DB::select("
                    SELECT 
                    CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                    FROM OHEM
                    INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                    WHERE se.SolicitudesMP in (1,2)
                    GROUP BY email
                ");                  
                $correos =array_pluck($correos_db, 'correo'); 
                if (!in_array($N_Emp, $correos) && $N_Emp !== null) {
                    $correos[] = $N_Emp;
                }    
                if (count($correos) > 0) {                                        
                    Mail::send('Emails.SolicitudMP', [
                        'arts' => $arts, 'id' => $id, 'comentario' => $usercomment
                    ], function ($msj) use ($correos, $id) {
                        $msj->subject('SIZ Solicitud de Material #'.$id); //ASUNTO DEL CORREO
                        $msj->to($correos); //Correo del destinatario
                    });                    
                }               
                return 'Mensaje: Tu Solicitud ha sido enviada (#'.$id.')';
        }
        DB::rollBack();  
        return 'reload';
    
    
}
public function ShowDetalleSolicitud($id, $qr_itemcode = null, $qr_cant = null, $showmodalqr = false){
   
    if (Auth::check()) {
    $user = Auth::user();
    $actividades = $user->getTareas();  
    Session::put('solicitud_picking', $id);
      // $solicitudes = DB::table('SIZ_SolicitudesMP');
       $step = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->first();
      if (Session::has('noAsignarAlm')) {

                             }else{
                                 if ($step->Status != 'Autorizacion') {  
    $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                    mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_scan, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, mat.Cant_ASurtir_Origen_B,
                     ALMACENES.APGPA, ALMACENES.AMPST, (APGPA + AMPST) AS Disponible, mat.EstatusLinea  from SIZ_MaterialesSolicitudes mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    LEFT JOIN 
                    (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
					SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                    WHERE mat.EstatusLinea <> \'C\' AND Id_Solicitud = ? AND Cant_PendienteA > 0', [$id]);
                   
                                          
                        if (!is_null($qr_itemcode) ){
                            self::asignaAlmacenesOrigen($articulos, 0);   
                            //     se rige por cant escaneada
                         } else {                             
                            self::asignaAlmacenesOrigen($articulos, 1); // se asigna por Cant_PendienteA                                                                             
                         }
                    }
                }
                        $articulos = DB::select(
                    'select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                                        mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_scan, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, mat.Cant_ASurtir_Origen_B,
                                        ALMACENES.APGPA, ALMACENES.AMPST, (APGPA + AMPST) AS Disponible, 
                                        mat.EstatusLinea, LOTES.BatchNum, CASE WHEN (mat.Cant_ASurtir_Origen_A + mat.Cant_ASurtir_Origen_B) = L.Asignado THEN \'Y\' ELSE \'N\' END AS Preparado
                                        from SIZ_MaterialesSolicitudes mat
                                        LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                                        LEFT JOIN 
                                        (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
                                        SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST
                                        FROM dbo.OITW
                                        GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                                        LEFT JOIN (					
                                            SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
                                            GROUP BY ItemCode
                                        )AS LOTES on LOTES.ItemCode = OITM.ItemCode
                                        LEFT JOIN (					
                                           select
                        T2.ItemCode, sum(COALESCE(lotesa.Cant, 0)) AS Asignado
                    from
                        OBTN T0
                        inner join OBTQ T1 on T0.ItemCode = T1.ItemCode and T0.SysNumber = T1.SysNumber
                        inner join OITM T2 on T0.ItemCode = T2.ItemCode
                        Left join (
                            select sum(matl.Cant) Cant, matt.Id_Solicitud, matt.ItemCode, matl.lote from SIZ_MaterialesLotes matl
                            inner join SIZ_MaterialesSolicitudes matt on matt.Id = matl.Id_Item
                            group by matt.Id_Solicitud, matt.ItemCode, matl.lote
                            ) as lotesa on lotesa.Id_Solicitud = ? AND lotesa.ItemCode = T1.ItemCode AND lotesa.lote = T0.DistNumber
                            where
                        T1.Quantity > 0 AND  (WhsCode = \'AMP-ST\' OR WhsCode = \'APG-PA\')						
                    group by T2.ItemCode
                                        )AS L on L.ItemCode = OITM.ItemCode
                                        WHERE mat.EstatusLinea <> \'A\' AND Id_Solicitud = ?', [$id, $id]);
                    
    
    //$step = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->value('Status');
    $showmodal = false;
    $qr_item = null;
    $todosCantScan = 0;
    if ($step->Status == 'Autorizacion') {
        $itemsConLotes = 0;
        $articulos_validos = array_where($articulos, function ($key,$item) {
            return ($item->EstatusLinea == 'S' || $item->EstatusLinea == 'N');
        });
        $articulos_novalidos = array_where($articulos, function ($key,$item) {
            return $item->EstatusLinea == 'A';
        });       
    } else {    //para Picking
        $articulos_validos = array_where($articulos, function ($key,$item) {
            return $item->EstatusLinea == 'S' || $item->EstatusLinea == 'P';
        });    
        //verificamos si hay algun articulo que no tenga cantidad Scanner 
         $scan_item = array_where($articulos_validos, function ($key,$item){
            return $item->Cant_scan == 0;
            });     
         if (count($scan_item) == 0) {
             $todosCantScan = 1;
         } 
                         
        if (!is_null($qr_itemcode) && count($articulos_validos) > 0) { 
            //verificar que venimos de escanear qr 
            //y que el material escaneado sea de los articulos validos           
            $qr_item_search = array_where($articulos_validos, function ($key,$item) use ($qr_itemcode) {
            return $item->ItemCode == $qr_itemcode;
            });
            $qr_item = array_values($qr_item_search);
            if (count($qr_item) !== 1) {
                //si el material no esta en los validos entonces esta en los que no se surtiran
              
                $qr_item = null;
                Session::flash('error', 'El material escaneado esta en la lista de materiales que no se surtirán');
            }else{
                if ($showmodalqr) {
                   $showmodal = true;
                }
               
            }
            if (is_null($qr_cant)) {
                $showmodal = 0;
            }
        }else{
            if (Session::has('notfound')) {
            Session::flash('error', Session::pull('notfound'));
        } 
        } 
        $itemsConLotes = array_where($articulos_validos, function ($key, $item) {
            return $item->BatchNum > 0 && $item->Preparado == 'N';
        });
        
        $itemsConLotes = count($itemsConLotes);
        $articulos_novalidos = array_where($articulos, function ($key,$item) {
            return $item->EstatusLinea == 'N';
        });   
    }
        
    if (count($articulos_novalidos) > 0) {
        Session::flash('solicitud_err','Esta Solicitud tiene artículos que no se surtirán (fueron quitados o no hay material disponible)');
    }

    $param = array(        
        'actividades' => $actividades,
        'ultimo' => count($actividades),    
        'id' => $id,
        'itemsConLotes' => $itemsConLotes,
        'articulos_validos' => $articulos_validos,
        'articulos_novalidos' => $articulos_novalidos,
        'comentario' => $step->ComentarioUsuario,
        'qr_item' => $qr_item,
        'qr_cant' => $qr_cant,
        'showmodal' => $showmodal,
        'todosCantScan' => $todosCantScan
    );
    
    if ($step->Status == 'Autorizacion') {
        return view('Mod04_Materiales.Autorizacion', $param);
    } else {
        return view('Mod04_Materiales.Picking', $param);
    }
          
    } else {
        return redirect()->route('auth/login');
    }
}
public function ShowDetalleTraslado($id){ 
    if (Auth::check()) {
    $user = Auth::user();
    $actividades = $user->getTareas();  
      // $solicitudes = DB::table('SIZ_SolicitudesMP');                

    $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                    mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_scan, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, mat.Cant_ASurtir_Origen_B,
                     ALMACENES.APGPA, ALMACENES.AMPST, (APGPA + AMPST) AS Disponible, 
                     CASE WHEN ((APGPA + AMPST)  < (mat.Cant_ASurtir_Origen_A + mat.Cant_ASurtir_Origen_B)) and mat.EstatusLinea = \'S\' THEN \'N\' ELSE mat.EstatusLinea END AS EstatusLinea
                      from SIZ_MaterialesSolicitudes mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    LEFT JOIN 
                    (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
					SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                    WHERE Id_Solicitud = ? AND mat.Cant_PendienteA > 0', [$id]);

                    $articulos_validos = array_where($articulos, function ($key,$item) {
                        return ($item->EstatusLinea == 'S') && (($item->Cant_ASurtir_Origen_A + $item->Cant_ASurtir_Origen_B) > 0);
                    });
                   $articulos_novalidos = array_where($articulos, function ($key,$item) {
                                        return $item->EstatusLinea == 'N';});     
                    
                    $pdf_solicitud = DB::table('OWTR')->where('FolioNum', $id)->get();
    if (count($articulos_novalidos) > 0) {
        Session::flash('solicitud_err','Esta Solicitud tiene artículos que no se surtirán (fueron quitados o no hay material disponible)');
    }
    $param = array(        
        'actividades' => $actividades,
        'ultimo' => count($actividades),    
        'id' => $id,
        'articulos_validos' => $articulos_validos,
        'articulos_novalidos' => $articulos_novalidos,
        'pdf_solicitud' => $pdf_solicitud
    );
        Session::put('transfer1', 0);
        Session::put('transfer2', 0);
        return view('Mod04_Materiales.Traslado', $param);    
          
    } else {
        return redirect()->route('auth/login');
    }
}
public function ShowDetallePdf($id){ 
    if (Auth::check()) {
    $user = Auth::user();
    $actividades = $user->getTareas();  
      // $solicitudes = DB::table('SIZ_SolicitudesMP');                
      $almacenOrigen = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->value('AlmacenOrigen');    
      if (is_null($almacenOrigen)) {
          $t = 'SIZ_MaterialesSolicitudes';
      } else {
          $t = 'SIZ_MaterialesTraslados';
      }    
    $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                    mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, mat.Cant_ASurtir_Origen_B,                    
                    mat.EstatusLinea, mat.Destino 
                      from '.$t.' mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode                  
                    WHERE Id_Solicitud = ? AND mat.Cant_PendienteA > 0', [$id]);
  
                    $articulos_validos = array_where($articulos, function ($key,$item) {
                        return $item->EstatusLinea == 'S';
                    });
                    $articulos_novalidos = array_where($articulos, function ($key,$item) {
                                        return $item->EstatusLinea == 'N';});     
                    
                    $pdf_solicitud = DB::table('OWTR')->where('FolioNum', $id)
                    ->orWhere('Comments', 'like', '%SIZ VALE #'.$id.'%')
                    ->get();
                    

    if (count($articulos_novalidos) > 0) {
        Session::flash('solicitud_err','Esta Solicitud tiene artículos que no se surtirán (fueron quitados o no hay material disponible)');
    }
    $param = array(        
        'actividades' => $actividades,
        'ultimo' => count($actividades),    
        'id' => $id,
        'articulos_validos' => $articulos_validos,
        'articulos_novalidos' => $articulos_novalidos,
        'pdf_solicitud' => $pdf_solicitud
    );
       
        return view('Mod04_Materiales.PDFs_Solicitud', $param);    
          
    } else {
        return redirect()->route('auth/login');
    }
}
public function asignaAlmacenesOrigen($articulos, $opcion){ 
  //  dd('asignacion');
    foreach ($articulos as $art) {
        if (true) {
            if ($opcion == 1) {
                $Cant = $art->Cant_PendienteA; 
                $P = $art->Cant_PendienteA; 
            } else { //0
                $Cant = $art->Cant_scan; 
                $P = $art->Cant_scan; 
            }
           
                $A = $art->APGPA; 
                $B = $art->AMPST;
                $ALMA = 0;      
                $ALMB = 0;      
                if(($A + $B) >= $Cant){
                    if ($A >= $B) {
                        if ($A >= $P) {
                            $ALMA = $P;
                            $ALMB = 0;
                        
                        } else {
                            $P = $P - $A;
                            $ALMA = $Cant - $P;
                            if ($P <= $B) {
                            $ALMB = $P;
                            
                            }
                        }                
                    } else {
                        if ($B >= $P) {
                            $ALMA = 0;
                            $ALMB = $P;
                        
                        } else { 
                            $P = $P - $B;
                            $ALMB = $Cant - $P;
                            if ($P <= $A) {
                                $ALMA = $P;

                            }
                        }
                        
                    }
                    self::updateAlmacenesArticulo([$ALMA, $ALMB, 'S', $art->Id]);               
                }else{
                    if ($art->EstatusLinea == 'S') {
                        self::updateAlmacenesArticulo([0, 0, 'N', $art->Id]);
                    } else {
                        self::updateAlmacenesArticulo([0, 0, $art->EstatusLinea, $art->Id]);
                    }
                }
        }        
    }
}
public function updateAlmacenesArticulo($parametros){
    DB::update('UPDATE SIZ_MaterialesSolicitudes SET Cant_ASurtir_Origen_A = ? , Cant_ASurtir_Origen_B = ?, EstatusLinea = ? WHERE Id = ?', $parametros);
}
public function removeArticuloSolicitud(){
 // dd(strpos(Input::get('reason'), 'Material') !== false);
// dd(Input::all());
    if (Auth::check()) {
        if (strpos(Input::get('reason'), 'Material') !== false) {
            DB::update('UPDATE SIZ_MaterialesSolicitudes SET EstatusLinea = ? , Razon_Picking = ?
            WHERE Id = ?', ['C', Input::get('reason'), Input::get('articulo')]);
        } else {
            DB::update('UPDATE SIZ_MaterialesSolicitudes SET EstatusLinea = ? , Razon_Picking = ?
            WHERE Id = ?', ['N', Input::get('reason'), Input::get('articulo')]);
        }
         Session::flash('noAsignarAlm', 1);
        return redirect()->back();
    } else {
        return redirect()->route('auth/login');
    }
}
public function removeArticuloNoAutorizado(){
    if (Auth::check()) {
            $item = DB::table('SIZ_MaterialesSolicitudes')->where('Id', Input::get('articulo'))->first();
            $id_sol = $item->Id_Solicitud;
            $estatus_item = $item->EstatusLinea;
           
        if (strpos(Input::get('reason'), 'Pendiente') !== false) {
            if ($estatus_item <> 'C') {
               DB::update('UPDATE SIZ_MaterialesSolicitudes SET EstatusLinea = ? , 
                Razon_NoAutorizado = ? WHERE Id = ?', 
                ['A', Input::get('reason'), Input::get('articulo')]);
            } else {
                Session::flash('mensaje', 'Este artículo ya esta cancelado');
            }
                        
        } else {
          
            DB::update('UPDATE SIZ_MaterialesSolicitudes SET EstatusLinea = ? , 
            Razon_NoAutorizado = ? WHERE Id = ?',
            ['C', Input::get('reason'), Input::get('articulo')]);
                
                $articulosvalidos = DB::table('SIZ_MaterialesSolicitudes')
                    ->whereIn('EstatusLinea', ['S', 'A'])
                    ->where('Id_Solicitud', $id_sol)->count();

                if ($articulosvalidos == 0) {
                    DB::update(
                        'UPDATE SIZ_SolicitudesMP SET Status = ? 
                             WHERE Id_Solicitud = ?',
                        ['Cancelada', $id_sol]
                    );
                    // si el Solicitante tiene correo se le avisa
                    $solicitante = DB::table('SIZ_SolicitudesMP')
                        ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                        ->select('SIZ_SolicitudesMP.Status', 'OHEM.firstName', 'OHEM.lastName', DB::raw('case when email like \'%@%\' then email else email + cast(\'@zarkin.com\' as varchar)  end AS correo'))
                        ->where('SIZ_SolicitudesMP.Id_Solicitud', $id_sol)->first();
                    $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));                        
                    $nombreCompleto = explode(' ',$solicitante->firstName)[0].' '.$apellido;

                    $correos = array();
                    if ( $solicitante->correo !== null ) {
                        $correos[] = $solicitante->correo;
                    }
                    $arts = DB::table('SIZ_MaterialesSolicitudes')
                        ->join('OITM', 'OITM.ItemCode', '=', 'SIZ_MaterialesSolicitudes.ItemCode')
                        ->select('SIZ_MaterialesSolicitudes.*', 'OITM.ItemName', 'OITM.InvntryUom')
                        ->where('Id_Solicitud', $id_sol)->get();

                    if ((count($correos) > 0) && ($solicitante->Status === 'Cancelada')) {
                        Mail::send('Emails.AutorizacionMP', [
                            'arts' => $arts, 'id' => $id_sol, 'nombreCompleto' => $nombreCompleto
                        ], function ($msj) use ($correos, $id_sol) {
                            $msj->subject('SIZ No se Autorizo Material #' . $id_sol); //ASUNTO DEL CORREO
                            $msj->to($correos); //Correo del destinatario
                        });
                    }
                    Session::flash('mensaje', 'Solicitud #' . $id_sol . ' Cancelada');
                    return redirect('/home/2 AUTORIZACION');
                }
        }
        return redirect()->back();
    } else {
        return redirect()->route('auth/login');
    }
}
public function editArticulo(){
    if (Auth::check()) {
        DB::update('UPDATE SIZ_MaterialesSolicitudes SET Cant_Autorizada = ? , Cant_PendienteA = ?, Razon_AutorizaCantMenor = ? WHERE Id = ?', 
        [Input::get('canta'), Input::get('canta'), 
        Input::get('reason'), Input::get('articulo')]);
        return redirect()->back();
    } else {
        return redirect()->route('auth/login');
    }
}
public function editArticuloPicking(){
 
    if (Auth::check()) {
        if (Session::has('solicitud_picking')) {
            Session::forget('solicitud_picking');
        }
    if (Input::get('pendiente') < (Input::get('canta')+Input::get('cantb'))) {
        Session::flash('error', 'La Cantidad a Surtir debe ser igual o menor a '.Input::get('pendiente'));
        return redirect()->back();
    }
    $art = DB::table('SIZ_MaterialesSolicitudes')
            ->join('OITM', 'OITM.ItemCode', '=' , 'SIZ_MaterialesSolicitudes.ItemCode')
            ->select('SIZ_MaterialesSolicitudes.*', 'OITM.ItemName', 'OITM.InvntryUom')        
            ->where('Id', Input::get('articulo'))->first();
             if (Input::has('qrinput')) {
                $cant_scanner = '';
            }else{
                $cant_scanner = 'Cant_scan = 0,';   
            }
    if (Input::get('canta') + Input::get('cantb') == Input::get('pendiente')) {
        DB::update('UPDATE SIZ_MaterialesSolicitudes SET  Cant_ASurtir_Origen_A = ?, Cant_ASurtir_Origen_B = ?, Cant_scan = 0, Razon_PickingCantMenor = ? WHERE Id = ?', [Input::get('canta'), Input::get('cantb'), '', Input::get('articulo')]);
    } elseif(Input::get('canta') + Input::get('cantb') < Input::get('pendiente')){
        DB::update('UPDATE SIZ_MaterialesSolicitudes SET  Cant_ASurtir_Origen_A = ?, Cant_ASurtir_Origen_B = ?, Cant_scan = 0, Razon_PickingCantMenor = ? WHERE Id = ?', [Input::get('canta'), Input::get('cantb'), Input::get('reason'), Input::get('articulo')]);
        
        if (strpos(Input::get('reason'), 'existencia') !== false) {
           
           
                    
            $correos_db = DB::select("
                SELECT 
                CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                FROM OHEM
                INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                WHERE se.SolicitudesErrExistencias = 1
                GROUP BY email
            ");
            $correos = array_pluck($correos_db, 'correo');
                if (count($correos) > 0) {                    
                    Mail::send('Emails.Err_existencias', 
                    ['art' => $art], 
                    function ($msj) use ($correos, $art) {
                        $msj->subject('SIZ Error de Existencias ('.$art->ItemCode.')'); //ASUNTO DEL CORREO
                        $msj->to($correos); //Correo del destinatario
                    });
                }
        }
        
    } else{
          Session::flash('error', 'No se pudo actualizar...');
    }
    $id_sol =Input::get('idsol');
    if (Input::has('qrinput')) {
       $qr_itemcode = $art->ItemCode;
    } else {
       $qr_itemcode = null;
    }
    $cant = null;
    Session::flash('noAsignarAlm', 1);
    $showmodalqr = true;
     return redirect()
                ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'qr_itemcode', 'cant', 'showmodalqr'));
    } else {
        return redirect()->route('auth/login');
    }
}
public function returnArticuloSolicitud($id){
    if (Auth::check()) {   
        DB::update('UPDATE SIZ_MaterialesSolicitudes SET EstatusLinea = ? , Razon_Picking = ? , Razon_NoAutorizado = ? WHERE Id = ?', ['S', '', '', $id]);
        return redirect()->back();
    } else {
        return redirect()->route('auth/login');
    }
}
public function SolicitudPDF($id){
    if (Auth::check()) {   
        $comment = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->value('ComentarioUsuario');
        DB::update('UPDATE SIZ_SolicitudesMP SET Status = ? WHERE Id_Solicitud = ?', ['En Proceso', $id]);
        $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino,
          mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_PendienteA, (mat.Cant_ASurtir_Origen_A + mat.Cant_ASurtir_Origen_B) AS Cant_Surtir,
         ALMACENES.APGPA, ALMACENES.AMPST, (APGPA + AMPST) AS Disponible from SIZ_MaterialesSolicitudes mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    LEFT JOIN 
                    (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
					SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                    WHERE Id_Solicitud = ? AND mat.EstatusLinea = \'S\'', [$id]);       
  
       //haz el PDF para "Picking"
        $pdf = \PDF::loadView('Mod04_Materiales.SolicitudPDF', compact('articulos', 'id', 'comment'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Picking_'.$id . ' - ' .date("d/m/Y") . '.Pdf');
    } else {
        return redirect()->route('auth/login');
    }
}
public function SolicitudPDF_Traslados($id){  
    $solicitud = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->first();
    $almacenOrigen = $solicitud->AlmacenOrigen;
    $transfer = 'Por Definir ';
     $solicitante = DB::table('OHEM')
                     ->where('U_EmpGiro', $solicitud->Usuario)->first();
    $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));
    $UsuarioSolicitud = explode(' ',$solicitante->firstName)[0].' '.$apellido;
    
    if (is_null($almacenOrigen)) {
        $t = 'SIZ_MaterialesSolicitudes';
        $tipoDoc = 'Solicitud';
        $almacenOrigen = "Materia Prima";
        $recibe = $UsuarioSolicitud;
        $entrega = $solicitud->SOLentrega_TRASrecibe_Usuario;
    } else {
        $t = 'SIZ_MaterialesTraslados';
        $tipoDoc = 'Traslado';
        $entrega = $UsuarioSolicitud;
        $recibe = $solicitud->SOLentrega_TRASrecibe_Usuario;
    }
$transfer1 = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as unitMsr, 
OITM.ItemName as Dscription, mat.Destino as WhsCode,
      mat.Cant_Requerida, mat.Cant_Autorizada,
      mat.Cant_PendienteA, (mat.Cant_ASurtir_Origen_A + mat.Cant_ASurtir_Origen_B) AS Quantity
      from '.$t.' mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    
                    WHERE Id_Solicitud = ? AND mat.EstatusLinea = \'S\'', [$id]); 
       if (count($transfer1) <= 0) {
        Session::flash('error', 'No tenemos registros de esa Solicitud');
        return redirect()->back();
       }         
                               
        $total1 = array_sum(array_pluck($transfer1, 'Quantity'));
        
        //$info1 = DB::select('select FolioNum, Filler, Printed, Comments  from OWTR where DocEntry = ? ', [$transfer]);
        $comentario = $solicitud->ComentarioUsuario;
        $fechaSol = $solicitud->FechaCreacion;
        $pdf = \PDF::loadView('Mod04_Materiales.TrasladoPDF_SinPrecio', compact('id', 'transfer1', 'fechaSol',
         'total1',  'transfer', 'comentario', 'almacenOrigen', 'recibe', 'tipoDoc', 'entrega'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Traslado_'.$id . ' - ' .date("d/m/Y") . '.Pdf');
  

    if (Auth::check()) {   
        $comment = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->value('ComentarioUsuario');
        DB::update('UPDATE SIZ_SolicitudesMP SET Status = ? WHERE Id_Solicitud = ?', ['En Proceso', $id]);
        $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino,
          mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_PendienteA, (mat.Cant_ASurtir_Origen_A + mat.Cant_ASurtir_Origen_B) AS Cant_Surtir,
          from SIZ_Traslados mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    LEFT JOIN 
                    (SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
					SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                    WHERE Id_Solicitud = ? AND mat.EstatusLinea = \'S\'', [$id]);       
  
       //haz el PDF para "Picking"
        $pdf = \PDF::loadView('Mod04_Materiales.SolicitudPDF', compact('articulos', 'id', 'comment'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Picking_'.$id . ' - ' .date("d/m/Y") . '.Pdf');
    } else {
        return redirect()->route('auth/login');
    }
}
public function Solicitud_A_Traslados($id){
 if (Auth::check()) {

        $cantLineas = DB::table('SIZ_MaterialesSolicitudes')
        ->where('EstatusLinea', 'S')
        ->where('Id_Solicitud', $id)
        ->count();
        $cantcheckLineas = DB::table('SIZ_MaterialesSolicitudes')
        ->where('Id_Solicitud', $id)
        ->where('Cant_PendienteA', '>=', DB::raw('([Cant_ASurtir_Origen_A] + [Cant_ASurtir_Origen_B])'))
        ->where('EstatusLinea', 'S')
        ->count();
      
        if ($cantLineas == $cantcheckLineas ) {
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',Auth::user()->lastName));
            DB::update('UPDATE SIZ_SolicitudesMP SET Status = ?, PickingUsuario = ?  WHERE Id_Solicitud = ?', ['Traslado', explode(' ',Auth::user()->firstName)[0].' '.$apellido, $id]);
            Session::flash('mensaje','Solicitud #'.$id.' Enviada a Traslados');
            return redirect()->action('Mod04_MaterialesController@pickingArticulos');
        } else {
            Session::flash('error','Hay cantidades a surtir incorrectas');
            return redirect()->back();
        }
        
       
       
    } else {
        return redirect()->route('auth/login');
    }
}
public function Solicitud_A_Picking($id){
 if (Auth::check()) {       
        $solicitante = DB::table('SIZ_SolicitudesMP')       
        ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')        
        ->select('SIZ_SolicitudesMP.Status','OHEM.firstName','OHEM.lastName', DB::raw('case when email like \'%@%\' then email else email + cast(\'@zarkin.com\' as varchar)  end AS correo'))
        ->where('SIZ_SolicitudesMP.Id_Solicitud', $id)->first();
        $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));
        $nombreCompleto = explode(' ',$solicitante->firstName)[0].' '.$apellido;
         
        $correos_db = DB::select("
            SELECT 
            CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
            FROM OHEM
            INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
            WHERE se.SolicitudesMP in (1,3)
            GROUP BY email
        ");
        $correos =array_pluck($correos_db, 'correo'); 
        if (!in_array($solicitante->correo, $correos) && $solicitante->correo !== null) {
            $correos[] = $solicitante->correo;
        }
        $arts = DB::table('SIZ_MaterialesSolicitudes')
        ->join('OITM', 'OITM.ItemCode', '=' , 'SIZ_MaterialesSolicitudes.ItemCode')
        ->select('SIZ_MaterialesSolicitudes.*', 'OITM.ItemName', 'OITM.InvntryUom')        
        ->where('Id_Solicitud', $id)->get(); 
        
        if ((count($correos) > 0) && ($solicitante->Status === 'Autorizacion')) {                                        
            Mail::send('Emails.AutorizacionMP', [
                'arts' => $arts, 'id' => $id, 'nombreCompleto' => $nombreCompleto
            ], function ($msj) use ($correos, $id) {
                $msj->subject('SIZ Autorización Material #'.$id); //ASUNTO DEL CORREO
                $msj->to($correos); //Correo del destinatario
            });
        }
        DB::update('UPDATE SIZ_SolicitudesMP SET Status = ? WHERE Id_Solicitud = ?', 
            ['Pendiente', $id]);
        Session::flash('mensaje','La Solicitud  #'.$id.' se ha enviado a Picking Almacén');
        return redirect()->action('Mod04_MaterialesController@AutorizacionSolicitudes');
    } else {
        return redirect()->route('auth/login');
    }
}
public function Solicitud_A_PickingTraslados($id){
 if (Auth::check()) {   
    
        DB::update('UPDATE SIZ_SolicitudesMP SET Status = ? WHERE Id_Solicitud = ?', 
        ['Pendiente', $id]);
        Session::flash('mensaje','La Solicitud  #'.$id.' se ha enviado a Picking Almacén');
        return redirect()->action('Mod04_MaterialesController@TrasladosArticulos');
       
    } else {
        return redirect()->route('auth/login');
    }
}
public function DM_Articulo(Request $request, $ItemCode){
    if (Auth::check()) {
        $param = self::getParam_DM_Articulos($request, $ItemCode);
        
        $param['privilegioTarea'] = 'disabled';
        $param['oculto'] = true;

        
        return view('Mod04_Materiales.DM_Articulos', $param);
    } else {
        return redirect()->route('auth/login');
    }
}
public function Etiqueta_Articulo($ItemCode){
    if (Auth::check()) {
        $param = self::getInfo_Etiqueta_Articulos($ItemCode);
        
       // return view('Mod04_Materiales.DM_Articulos', $param);
    } else {
        return redirect()->route('auth/login');
    }
}

public function HacerTraslados($id){
   //dd(Session::get('transfer1'));
    if (Auth::check()) {
        $rates = DB::table('ORTT')->where('RateDate', date('d-m-Y'))->get();
        if (count($rates) >= 3) {
            //GUARDAR USUARIO QUE HACE MOVIMIENTO
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',Auth::user()->lastName));
             DB::table('SIZ_SolicitudesMP')
                    ->where('Id_Solicitud', $id)
                    ->update(['SOLentrega_TRASrecibe_Usuario' => explode(' ',Auth::user()->firstName)[0].' '.$apellido]);
            //PERSONA QUE SOLICITA
            $solicitante = DB::table('SIZ_SolicitudesMP')       
                ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')        
                ->select('OHEM.firstName','OHEM.lastName', 'SIZ_SolicitudesMP.ComentarioUsuario',
                'SIZ_SolicitudesMP.PickingUsuario', 'SIZ_SolicitudesMP.SOLentrega_TRASrecibe_Usuario')
                ->where('SIZ_SolicitudesMP.Id_Solicitud', $id)->first();
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));
            $nombreCompleto = explode(' ',$solicitante->firstName)[0].' '.$apellido;
            //articulos validos para transferencia
            $articulos = DB::select('select mat.Id, mat.ItemCode, mat.Destino,
                mat.Cant_ASurtir_Origen_A as CA, mat.Cant_ASurtir_Origen_B AS CB, mat.Cant_PendienteA,
                ALMACENES.APGPA, ALMACENES.AMPST, mat.Cant_Autorizada, LOTES.BatchNum from SIZ_MaterialesSolicitudes mat                                
                LEFT JOIN(SELECT ItemCode, SUM(CASE WHEN WhsCode = \'APG-PA\'  THEN OnHand ELSE 0 END) AS APGPA,
                SUM(CASE WHEN WhsCode = \'AMP-ST\'  THEN OnHand ELSE 0 END) AS AMPST                
                FROM dbo.OITW
                GROUP BY ItemCode) AS ALMACENES ON mat.ItemCode = ALMACENES.ItemCode
                LEFT JOIN (					
                                            SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
                                            GROUP BY ItemCode
                                        )AS LOTES on LOTES.ItemCode = mat.ItemCode
                WHERE Id_Solicitud = ? AND mat.EstatusLinea = \'S\'
                AND mat.Cant_ASurtir_Origen_A <= APGPA AND mat.Cant_ASurtir_Origen_B <= AMPST ', [$id]); 

            $primer_origen = array_where($articulos, function ($key,$item) {
                return $item->CA > 0;
            });

            $stat1 = 0;
            $stat2 = 0;
            $transfer1 = 0;
            $transfer2 = 0;
            $total1 = 0;
            $total2 = 0;
            $t1 = 0;
            $t2 = 0;
            $info1 = 0;
            $info2 = 0;
            if (count($primer_origen) > 0) {
                $observacionesComplemento = (!is_null($solicitante->PickingUsuario)) ? ", Surtió: ". $solicitante->PickingUsuario. ", Entrega: ". $solicitante->SOLentrega_TRASrecibe_Usuario : "";
                $data =  array(
                    'id_solicitud' => $id,
                    'pricelist' => '10',
                    'almacen_origen' => 'APG-PA',
                    'items' => $primer_origen,
                    'observaciones' => utf8_decode("SIZ VALE #".$id .", Solicitó: ". $nombreCompleto . $observacionesComplemento . ". ".$solicitante->ComentarioUsuario )
                                        

                );
                if (Session::has('transfer1')) {   
                    if (Session::get('transfer1') > 0) {
                        $t1 = Session::get('transfer1');
                    } else {
                        $t1 = SAP::Transfer($data);
                    }
                } else {
                    $t1 = SAP::Transfer($data);
                }
                
                if (is_numeric($t1) && $t1 > 0 ) {
                    Session::put('transfer1', $t1);
                    Session::flash('mensaje', 'Transferencia '.$t1.' realizada.');
                    
                }else{
                    $stat1 = strlen($t1);
                    $transfer1 = array();
                    Session::flash('error', $t1);
                }
               
                }else{
                    if (false) {
                       if (Session::has('transfer1') && (Session::get('transfer1') > 0)) {                        
                        $t1 = Session::get('transfer1');
                            if (is_numeric($t1) && $t1 > 0 ) {
                                Session::put('transfer1', $t1);
                                $transfer1 = DB::select('select LineNum +1 As lineNum, WTR1.ItemCode, Dscription, unitMsr, COALESCE( Destino, WhsCode ) as WhsCode,
                                                Quantity, Price, Currency, LineTotal 
                                                from WTR1 
                                                left join SIZ_TransferSolicitudesMP as t on t.DocEntry_Transfer = WTR1.DocEntry
                                                left join SIZ_MaterialesSolicitudes as s on s.Id_Solicitud = t.Id_Solicitud and s.ItemCode = WTR1.ItemCode
                                                where DocEntry = ? ', [$t1]);                                
                                    $total1 = DB::select('select sum(LineTotal) as Total from WTR1 where DocEntry = ? ', [$t1]);
                                    $info1 = DB::select('select Printed, Comments  from OWTR where DocEntry = ? ', [$t1]);
                            }else{
                                $stat1 = strlen($t1);
                                $transfer1 = array();
                            }
                        }
                    }
                   
                }
              
            $segundo_origen = array_where($articulos, function ($key,$item) {
                return $item->CB > 0;
            });
            
                if (count($segundo_origen) > 0) {
                     $observacionesComplemento = (!is_null($solicitante->PickingUsuario)) ? ", Surtió: ". $solicitante->PickingUsuario. ", Entrega: ". $solicitante->SOLentrega_TRASrecibe_Usuario : "";
                    $data =  array(
                        'id_solicitud' => $id,
                        'pricelist' => '10',
                        'almacen_origen' => 'AMP-ST',
                        'items' => $segundo_origen,
                        'observaciones' => utf8_decode("SIZ VALE #".$id .", Solicitó: ". $nombreCompleto . $observacionesComplemento.". ". $solicitante->ComentarioUsuario )
                    );
                        
                    if (Session::has('transfer2')) {                        
                        if (Session::get('transfer2') > 0) {
                            $t2 = Session::get('transfer2');
                        } else {
                            $t2 = SAP::Transfer($data);
                        }
                        } else {
                            $t2 = SAP::Transfer($data);
                           
                    }

                    if (is_numeric($t2) && $t2 > 0 ) {
                        
                        Session::put('transfer2', $t2);
                        Session::flash('mensaje2', 'Transferencia '.$t2.' realizada.');
                    }else{
                        $stat2 = strlen($t2);
                        $transfer2 = array();
                        Session::flash('error', $t2);
                    }
            }else{
                if (false) {
                    if (Session::has('transfer2') && (Session::get('transfer2') > 0)) {                        
                        $t2 = Session::get('transfer2');
                        if (is_numeric($t2) && $t2 > 0 ) {
                           // Session::put('transfer2', $t2);
                            $transfer2 = DB::select('select LineNum +1 As lineNum, WTR1.ItemCode, Dscription, unitMsr, COALESCE( Destino, WhsCode ) as WhsCode,
                                                Quantity, Price, Currency, LineTotal 
                                                from WTR1 
                                                left join SIZ_TransferSolicitudesMP as t on t.DocEntry_Transfer = WTR1.DocEntry
                                                left join SIZ_MaterialesSolicitudes as s on s.Id_Solicitud = t.Id_Solicitud and s.ItemCode = WTR1.ItemCode
                                                where DocEntry = ? ', [$t2]);                                
                                $total2 = DB::select('select sum(LineTotal) as Total from WTR1 where DocEntry = ? ', [$t2]);
                                $info2 = DB::select('select Printed, Comments  from OWTR where DocEntry = ? ', [$t2]);
                        }else{
                            $stat2 = strlen($t2);
                            $transfer2 = array();
                        }
                    }
                }
            }
            if ($stat1 > 0 && $stat2 > 0) {
               Session::flash('error', 'Transferencia APG-PA '.$t1);
               Session::flash('error', 'Transferencia AMP-ST '.$t2); 
                return redirect()->back();
            }

            $filas = DB::table('SIZ_MaterialesSolicitudes')
            ->where('Id_Solicitud', $id)
            ->whereIn('EstatusLinea', ['S', 'P', 'N'])
            ->count();            
            if ($filas > 0) {
                DB::table('SIZ_SolicitudesMP')
                ->where('Id_Solicitud', $id)
                ->update(['Status' => 'Regresada']);
            } elseif($filas == 0) {
                DB::table('SIZ_SolicitudesMP')
                ->where('Id_Solicitud', $id)
                ->update(['Status' => 'Cerrada', 'FechaFinalizada' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
            }
            return redirect()->action('Mod04_MaterialesController@ShowDetalleTraslado', [$id]);
           // return redirect()->route('home/TRASLADOS/solicitud', [$id]);
        } else {
            Session::flash('error', 'No estan capturados todos los "Tipos de Cambio" en SAP.');        
            return redirect()->back();
        }
    } else {
        return redirect()->route('auth/login');
    }
}
public function getPdfSolicitud(){
    $sol = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', Input::get('sol'))->exists();
    if ($sol) {
        return redirect('home/pdf/solicitud/'.Input::get('sol'));
    } else {
        Session::flash('error', 'No tenemos registros de esa Solicitud');
        return redirect()->back();
    }    
}
  public function getPdfTraslado(Request $request){
    if (isset($request->transfer)) {
        $transfer = $request->transfer;
        Session::forget('lotesdeptos');
    }else{
        $transfer = $request->input('transfer');
    }
      
    $transfer1 = DB::select('select LineNum +1 As lineNum, WTR1.ItemCode, Dscription, unitMsr, COALESCE( Destino, WhsCode ) as WhsCode,
                Quantity, Price, Currency, LineTotal
                from WTR1 
                left join SIZ_TransferSolicitudesMP as t on t.DocEntry_Transfer = WTR1.DocEntry
                left join SIZ_MaterialesSolicitudes as s on s.Id_Solicitud = t.Id_Solicitud and s.ItemCode = WTR1.ItemCode
                where DocEntry = ? ', [$transfer]); 
       if (count($transfer1) <= 0) {
        Session::flash('error', 'No tenemos registros de ese Traslado');
        return redirect()->back();
       }         
                               
        $total1 = DB::select('select sum(LineTotal) as Total from WTR1 where DocEntry = ? ', [$transfer]);
        $info1 = DB::select('select COALESCE(FolioNum, t.Id_Solicitud) FolioNum, CreateDate, Filler, Printed, Comments  from OWTR 
        left join SIZ_TransferSolicitudesMP as t on t.DocEntry_Transfer = OWTR.DocEntry
        where OWTR.DocEntry = ? ', [$transfer]);
        $solicitud = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $info1[0]->FolioNum)->first();
        $solicitante = DB::table('OHEM')
                     ->where('U_EmpGiro', $solicitud->Usuario)->first();

        $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));      
        
        
        $UsuarioSolicitud = explode(' ',$solicitante->firstName)[0].' '.$apellido;
    if (is_null($solicitud->AlmacenOrigen)) {
        $tipoDoc = 'Solicitud';
       // $almacenOrigen = "Materia Prima";
        $recibe = $UsuarioSolicitud;
        $entrega = $solicitud->SOLentrega_TRASrecibe_Usuario;
    } else {
        $tipoDoc = 'Traslado';
        $entrega = $UsuarioSolicitud;
        $recibe = $solicitud->SOLentrega_TRASrecibe_Usuario;
    }
    
        $comentario = $solicitud->ComentarioUsuario;
        $fechaSol = $solicitud->FechaCreacion;
        $pdf = \PDF::loadView('Mod04_Materiales.TrasladoPDF_SinPrecio', 
        compact('info1', 'transfer1', 'fechaSol', 
         'total1',  'transfer', 'comentario','recibe', 'tipoDoc', 'entrega'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Traslado_'.$info1[0]->FolioNum . ' - ' .date("d/m/Y") . '.Pdf');
  }
    public function trasladoEntrega()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            $almacenesDestino = DB::table('SIZ_AlmacenesTransferencias')
                ->where('Dept', Auth::user()->dept)
                ->whereIn('TrasladoDeptos', ['D', 'OD'])->get();
            //quitar almacen origen de los almacenes destino si existe.
            $almacenOrigen = '';
            if (Input::has('text_selTres')) {
                $almacenOrigen = Input::get('text_selTres');
            } 
            
            $almacenesDestino = array_where($almacenesDestino, function ($key, $item) use($almacenOrigen){            
                    return $item->Code != $almacenOrigen;
            });
 
            $param = array(
                'actividades' => $actividades,
                'ultimo' => count($actividades),
                'almacenOrigen' => $almacenOrigen,
                'almacenesDestino' => array_values($almacenesDestino)
            );
            return view('Mod04_Materiales.showListMateriales', $param);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function ShowArticulosWHTraslados(Request $request)
    {    
        //AND U_TipoMat = \'PT\' 
        if ($request->get('almacen') !== 'AMP-ST' && $request->get('almacen') !== 'APG-PA') {
            $consulta = DB::select('
            SELECT  OITM.ItemCode, ItemName, InvntryUom AS UM, (ALMACENES.stock - COALESCE(PROCESO.CantProceso, 0)) AS Existencia FROM OITM
            LEFT JOIN 
            (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''. $request->get('almacen'). '\'   THEN OnHand ELSE 0 END) AS stock
            FROM dbo.OITW 
            GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
            LEFT JOIN
            (select ItemCode, sum (Cant_PendienteA) CantProceso
            from SIZ_MaterialesTraslados mat
            inner join SIZ_SolicitudesMP sol on sol.Id_Solicitud = mat.Id_Solicitud 
            AND sol.AlmacenOrigen =\''. $request->get('almacen'). '\'
            where mat.EstatusLinea in (\'S\', \'P\', \'I\', \'E\' , \'N\')
            group by ItemCode) AS PROCESO ON OITM.ItemCode = PROCESO.ItemCode 
            WHERE PrchseItem = \'Y\' AND InvntItem = \'Y\' 
            AND OITM.frozenFor = \'N\'
            AND (ALMACENES.stock - COALESCE(PROCESO.CantProceso, 0)) > 0
            ');
        } else {
            $consulta = DB::select('
            SELECT  OITM.ItemCode, ItemName, InvntryUom AS UM, (ALMACENES.stock - (COALESCE(PROCESO.CantProceso, 0) + COALESCE(PROCESOSOL.CantProcesosol, 0))) AS Existencia FROM OITM
            LEFT JOIN 
            (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''. $request->get('almacen'). '\'   THEN OnHand ELSE 0 END) AS stock
            FROM dbo.OITW 
            GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
            LEFT JOIN
            (select ItemCode, sum (Cant_PendienteA) CantProceso
            from SIZ_MaterialesTraslados mat
            inner join SIZ_SolicitudesMP sol on sol.Id_Solicitud = mat.Id_Solicitud 
            AND sol.AlmacenOrigen =\''. $request->get('almacen'). '\'
            where mat.EstatusLinea in (\'S\', \'P\', \'I\', \'E\', \'N\')
            group by ItemCode) AS PROCESO ON OITM.ItemCode = PROCESO.ItemCode 
            LEFT JOIN (
            select ItemCode, sum(Cant_PendienteA)AS CantProcesosol
                        from SIZ_MaterialesSolicitudes mat
                        where mat.EstatusLinea in (\'S\', \'P\', \'N\')
                        group by ItemCode
                        ) AS PROCESOSOL ON PROCESOSOL.ItemCode = OITM.ItemCode
            WHERE PrchseItem = \'Y\' AND InvntItem = \'Y\' 
            AND OITM.frozenFor = \'N\'
            AND (ALMACENES.stock - (COALESCE(PROCESO.CantProceso, 0) + COALESCE(PROCESOSOL.CantProcesosol, 0))) > 0
            ');
        }
        
        
        $columns = array(
            ["data" => "ItemCode", "name" => "Código"],
            ["data" => "ItemName", "name" => "Descripción"],
            ["data" => "UM", "name" => "UM"],
            ["data" => "Existencia", "name" => "Existencia", "defaultContent" => "0.00"],
        );

        return response()->json(array('data' => $consulta, 'columns' => $columns));
    }
    public function HacerEntrega($almacen_origen, $id){
        //sreturn redirect()->back();
        $todosArticulos = DB::select('select mat.Id, mat.ItemCode, OITM.ItemName,
        mat.Destino, mat.Cant_ASurtir_Origen_A as CA, mat.Cant_PendienteA,
        ALMACENES.AlmacenOrigen, LOTES.BatchNum, mat.EstatusLinea, OITM.InvntryUom as UM,
        CASE WHEN (mat.Cant_ASurtir_Origen_A ) = L.Asignado THEN \'Y\' ELSE \'N\' END AS Preparado
        from SIZ_MaterialesTraslados mat 
        LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode                                
        LEFT JOIN 
        (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''.$almacen_origen.'\' THEN 
        OnHand ELSE 0 END) AS AlmacenOrigen
            FROM dbo.OITW
            GROUP BY ItemCode) AS ALMACENES ON mat.ItemCode = ALMACENES.ItemCode
        LEFT JOIN (					
            SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
            GROUP BY ItemCode
        )AS LOTES on LOTES.ItemCode = mat.ItemCode
        LEFT JOIN (					
            select
                T2.ItemCode, sum(COALESCE(lotesa.Cant, 0)) AS Asignado
            from
                OBTN T0
                inner join OBTQ T1 on T0.ItemCode = T1.ItemCode and T0.SysNumber = T1.SysNumber
                inner join OITM T2 on T0.ItemCode = T2.ItemCode
                Left join (
                    select sum(matl.Cant) Cant, matt.Id_Solicitud, matt.ItemCode, matl.lote from SIZ_MaterialesLotes matl
                            inner join SIZ_MaterialesTraslados matt on matt.Id = matl.Id_Item
                            group by matt.Id_Solicitud, matt.ItemCode, matl.lote
                            ) as lotesa on lotesa.Id_Solicitud = ? AND lotesa.ItemCode = T1.ItemCode AND lotesa.lote = T0.DistNumber
                            where
                T1.Quantity > 0 AND  WhsCode = \''.$almacen_origen.'\'
            group by T2.ItemCode
        )AS L on L.ItemCode = mat.ItemCode			
        WHERE Id_Solicitud = ?
        AND mat.Cant_ASurtir_Origen_A <= AlmacenOrigen AND mat.EstatusLinea <> \'T\'', [$id, $id]);
            //dd($todosArticulos);
        $itemsConLotes = array_where($todosArticulos, function ($key, $item) {
            return $item->BatchNum > 0 && $item->Preparado == 'N';
        });
        if (count($itemsConLotes) > 0) {
            Session::flash('error', 'Termina de asignar Lotes');
            return redirect()->back();
        }else{
            DB::table('SIZ_MaterialesTraslados')
            ->where('Id_Solicitud', $id)
            ->where('EstatusLinea', 'E')
            ->update(['EstatusLinea' => 'S']);  
            DB::commit();
        }
       
        $traslado_interno =  array_where($todosArticulos, function ($key, $item) {            
            return $item->EstatusLinea == 'I';
        });
        $traslado_externo = array_where($todosArticulos, function ($key, $item){            
            return $item->EstatusLinea == 'E';
        });
        $usercomment = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)
        ->value('ComentarioUsuario');
        
        if (count($traslado_externo) > 0) {
            $correos_db = DB::select("
                SELECT 
                CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                FROM OHEM
                INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                WHERE se.Traslados in (1,3)
                GROUP BY email
            ");
            $correos = array_pluck($correos_db, 'correo');
            if (count($correos) > 0) {                    
                Mail::send('Emails.TrasladosDeptos', [
                    'arts' => $traslado_externo, 'id' => $id, 'comentario' => $usercomment, 'origen' => $almacen_origen
                ], function ($msj) use ($correos, $id) {
                    $msj->subject('SIZ Traslado #' . $id); //ASUNTO DEL CORREO
                    $msj->to($correos); //Correo del destinatario
                });                        
            }
        }

if (count($traslado_interno) > 0) {
$rates = DB::table('ORTT')->where('RateDate', date('d-m-Y'))->get();
        if (count($rates) >= 3) {
       // if (true) {
            //PERSONA QUE SOLICITA
            $solicitud = DB::table('SIZ_SolicitudesMP')       
                ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')        
                ->select('OHEM.firstName','OHEM.lastName', 'SIZ_SolicitudesMP.*')
                ->where('SIZ_SolicitudesMP.Id_Solicitud', $id)->first();
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitud->lastName));
            $nombreCompleto = explode(' ',$solicitud->firstName)[0].' '.$apellido;   
                          
            $stat3 = 0;
            $transfer3 = 0;                 
            $t3 = 0;
            $info3 = 0;
             $observacionesComplemento =  (!is_null($solicitud->SOLentrega_TRASrecibe_Usuario)) ? ", Recibe: ". $solicitud->SOLentrega_TRASrecibe_Usuario : "";
                $data =  array(
                    'id_solicitud' => $id,
                    'pricelist' => '10',
                    'almacen_origen' => $solicitud->AlmacenOrigen,
                    'items' => $traslado_interno,
                    'observaciones' => utf8_decode("SIZ VALE #".$id .", Envió: ". $nombreCompleto.$observacionesComplemento.". ". $solicitud->ComentarioUsuario )
                );
                // dd($data);   
                
                        $t3 = SAP::Transfer2($data);                                               

                if (is_numeric($t3) && $t3 > 0 ) {                                             
                    $mensaje_traslado_interno = $t3;
                }else{
                    $stat3 = strlen($t3);
                    $transfer3 = array();
                }
            
            if ( $stat3 > 0 ) {
                $mensaje_traslado_interno = 'Error Transferencia: '.$t3;                                        
            }
            $filas = DB::table('SIZ_MaterialesTraslados')
            ->where('Id_Solicitud', $id)
            ->whereNotIn('EstatusLinea', ['T', 'C'])
            ->count();            
            if ($filas > 0) {
               
            } elseif($filas == 0) {
                DB::table('SIZ_SolicitudesMP')
                ->where('Id_Solicitud', $id)
                ->update(['Status' => 'Cerrada', 'FechaFinalizada' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
            }
           
        }else {
            $mensaje_traslado_interno = 'Error. No estan capturados todos los "Tipos de Cambio" en SAP.';                    
        }        
}

if (count($traslado_interno) > 0 && count($traslado_externo) > 0) { 
        Session::flash('ambos', 'true'); 
        Session::flash('interno', $mensaje_traslado_interno);   
        Session::flash('externo','Entrega #' . $id ); 
        return redirect()->back(); 
        } else if(count($traslado_interno) > 0) {
            if (strpos($mensaje_traslado_interno, 'Error') !== false) {
                Session::flash('error', $mensaje_traslado_interno);
                 return redirect()->back();
            }else{
                Session::flash('interno', $mensaje_traslado_interno);
                 return redirect()->back();
            }
        } else if(count($traslado_externo) > 0) {
            Session::flash('externo','Entrega #' . $id );
             return redirect()->back();                
        }
        ///
    }
    public function saveTraslado(Request $request)
    {
        
        DB::beginTransaction();
        $err = false;
        $id = 0;
        $arts = $request->get('arts');
        $almacen_origen = $request->get('almacen');
        $usercomment = mb_strtoupper($request->get('comentario'));
        $dt = new \DateTime();
        
        $id = DB::table('SIZ_SolicitudesMP')->insertGetId(
            [
                'FechaCreacion' => $dt, 'Usuario' => Auth::id(), 'Status' => 'Pendiente',
                'ComentarioUsuario' => $usercomment, 'AlmacenOrigen' => $almacen_origen
            ]
        );
        $almacenesDestino = DB::table('SIZ_AlmacenesTransferencias')                        
                        ->where('Dept', Auth::user()->dept)
                        ->where('TrasladoDeptos', 'OD')->lists('Code');
                        
        foreach ($arts as $art) {            
            $statusL = 'E';
            if (is_numeric(array_search(trim($art['destino']), $almacenesDestino))) {
                $statusL = 'I';
            } 
            DB::table('SIZ_MaterialesTraslados')->insert(
                [
                    'Id_Solicitud' => $id, 'ItemCode' => $art['pKey'],
                    'Cant_Requerida' => $art['cant'], 'Destino' => trim($art['labelDestino']),
                    'Cant_Autorizada' =>  $art['cant'], 
                    'Cant_PendienteA' =>  $art['cant'],
                    'EstatusLinea' => $statusL, 'Cant_ASurtir_Origen_A' => $art['cant'], 'Cant_ASurtir_Origen_B' => 0
                ]
            );                                                 
        }        
        if (!($id > 0) || is_null($arts) || is_null($id)) {
            $err = true;
        }
        if ($err) {
            DB::rollBack();
            return 'Error: No se guardo la solicitud, favor de notificar a Sistemas';
        } 
        $todosArticulos = DB::select('select mat.Id, mat.ItemCode, OITM.ItemName,
        mat.Destino, mat.Cant_ASurtir_Origen_A as CA, mat.Cant_PendienteA, 
        ALMACENES.AlmacenOrigen, LOTES.BatchNum, mat.EstatusLinea, OITM.InvntryUom as UM,
         CASE WHEN (mat.Cant_ASurtir_Origen_A ) = L.Asignado THEN \'Y\' ELSE \'N\' END AS Preparado
        from SIZ_MaterialesTraslados mat  
        LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode                               
        LEFT JOIN 
        (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''.$almacen_origen.'\' THEN 
        OnHand ELSE 0 END) AS AlmacenOrigen
            FROM dbo.OITW
            GROUP BY ItemCode) AS ALMACENES ON mat.ItemCode = ALMACENES.ItemCode
        LEFT JOIN (					
            SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
            GROUP BY ItemCode
        )AS LOTES on LOTES.ItemCode = mat.ItemCode
        LEFT JOIN (					
            select
                T2.ItemCode, sum(COALESCE(lotesa.Cant, 0)) AS Asignado
            from
                OBTN T0
                inner join OBTQ T1 on T0.ItemCode = T1.ItemCode and T0.SysNumber = T1.SysNumber
                inner join OITM T2 on T0.ItemCode = T2.ItemCode
                Left join (
                    select sum(matl.Cant) Cant, matt.Id_Solicitud, matt.ItemCode, matl.lote from SIZ_MaterialesLotes matl
                    inner join SIZ_MaterialesTraslados matt on matt.Id = matl.Id_Item
                    group by matt.Id_Solicitud, matt.ItemCode, matl.lote
                    ) as lotesa on lotesa.Id_Solicitud = ? AND lotesa.ItemCode = T1.ItemCode AND lotesa.lote = T0.DistNumber
                     where
                T1.Quantity > 0 AND  WhsCode = \''.$almacen_origen.'\'
            group by T2.ItemCode
        )AS L on L.ItemCode = mat.ItemCode			
        WHERE Id_Solicitud = ?
        AND mat.Cant_ASurtir_Origen_A <= AlmacenOrigen AND mat.EstatusLinea <> \'T\'', [$id, $id]);
            
        $itemsConLotes = array_where($todosArticulos, function ($key, $item) {
            return $item->BatchNum > 0 && $item->Preparado == 'N';
        });
        if (count($itemsConLotes) > 0) {
            DB::commit();
            Session::put('lotesdeptos', $id);
            return 'lotesdeptos';
        }else{
            DB::table('SIZ_MaterialesTraslados')
            ->where('Id_Solicitud', $id)
            ->where('EstatusLinea', 'E')
            ->update(['EstatusLinea' => 'S']);  
            DB::commit();
        }
       
        $traslado_interno =  array_where($todosArticulos, function ($key, $item) {            
            return $item->EstatusLinea == 'I';
        });
        $traslado_externo = array_where($todosArticulos, function ($key, $item){            
            return $item->EstatusLinea == 'E';
        });
        
        if (count($traslado_externo) > 0) {
           
            $correos_db = DB::select("
                SELECT 
                CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                FROM OHEM
                INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                WHERE se.Traslados in (1,3)
                GROUP BY email
            ");
            $correos = array_pluck($correos_db, 'correo');
            if (count($correos) > 0) {                    
                Mail::send('Emails.TrasladosDeptos', [
                    'arts' => $traslado_externo, 'id' => $id, 'comentario' => $usercomment, 'origen' => $almacen_origen
                ], function ($msj) use ($correos, $id) {
                    $msj->subject('SIZ Traslado #' . $id); //ASUNTO DEL CORREO
                    $msj->to($correos); //Correo del destinatario
                });                        
            }
        }


if (count($traslado_interno) > 0) {
$rates = DB::table('ORTT')->where('RateDate', date('d-m-Y'))->get();
        if (count($rates) >= 3) {
       // if (true) {
            //PERSONA QUE SOLICITA
            $solicitud = DB::table('SIZ_SolicitudesMP')       
                ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')        
                ->select('OHEM.firstName','OHEM.lastName', 'SIZ_SolicitudesMP.*')
                ->where('SIZ_SolicitudesMP.Id_Solicitud', $id)->first();
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitud->lastName));
            $nombreCompleto = explode(' ',$solicitud->firstName)[0].' '.$apellido;   
                          
            $stat3 = 0;
            $transfer3 = 0;                 
            $t3 = 0;
            $info3 = 0;
            $observacionesComplemento = (!is_null($solicitud->SOLentrega_TRASrecibe_Usuario)) ? ", Recibe: ". $solicitud->SOLentrega_TRASrecibe_Usuario : "";
                $data =  array(
                    'id_solicitud' => $id,
                    'pricelist' => '10',
                    'almacen_origen' => $solicitud->AlmacenOrigen,
                    'items' => $traslado_interno,
                    'observaciones' => utf8_decode("SIZ VALE #".$id .", Envió: ". $nombreCompleto. $observacionesComplemento.". ". $solicitud->ComentarioUsuario )
                                        
                );
               //  dd($data);   
                
                        $t3 = SAP::Transfer2($data);                                               

                if (is_numeric($t3) && $t3 > 0 ) {                                             
                    $mensaje_traslado_interno = $t3;
                }else{
                    $stat3 = strlen($t3);
                    $transfer3 = array();
                }
            
            if ( $stat3 > 0 ) {
                $mensaje_traslado_interno = 'Error Transferencia: '.$t3;                                        
            }
            $filas = DB::table('SIZ_MaterialesTraslados')
            ->where('Id_Solicitud', $id)
            ->whereNotIn('EstatusLinea', ['T', 'C'])
            ->count();            
            if ($filas > 0) {
               
            } elseif($filas == 0) {
                DB::table('SIZ_SolicitudesMP')
                ->where('Id_Solicitud', $id)
                ->update(['Status' => 'Cerrada', 'FechaFinalizada' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
            }
           
        }else {
        $mensaje_traslado_interno = 'Error. No estan capturados todos los "Tipos de Cambio" en SAP.';                    
        }        
}
DB::commit();
if (count($traslado_interno) > 0 && count($traslado_externo) > 0) {                
            return  $id .';'.$mensaje_traslado_interno;
        } else if(count($traslado_interno) > 0) {
            if (strpos($mensaje_traslado_interno, 'Error') !== false) {
                 return $mensaje_traslado_interno;
            }else{
                return 'Mensaje:'.$mensaje_traslado_interno; //link pdf
            }
        } else if(count($traslado_externo) > 0) {
            return ' Ha sido enviada la Entrega #' . $id;    
        }                      
        
    }
    public function lotesdeptos(Request $request){

       // dd( $request->session()->get('lotesdept')) ;
        if (Auth::check()) {
           // dd($request->id);
                
                if (isset($request->id)) {
                    $id = $request->id;
                }else{
                    if (Session::has('lotesdeptos')) {
                        $id = Session::get('lotesdeptos');
                    }else{
                        Session::flash('error', 'No hemos podido definir el número de solicitud');
                        return redirect()->back();
                    }
                }
                
                $user = Auth::user();
                $actividades = $user->getTareas();  
               
                $almacen_origen = DB::table('SIZ_SolicitudesMP')
                ->where('Id_Solicitud', $id)
                ->value('AlmacenOrigen');
            //consulta validada
                $todosArticulos = DB::select('
                select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                                        mat.Cant_Requerida, mat.Cant_Autorizada,
                                         mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A AS CA,
                ALMACENES.AlmacenOrigen,
                CASE WHEN (mat.Cant_ASurtir_Origen_A > AlmacenOrigen) THEN \'N\' ELSE mat.EstatusLinea END EstatusLinea, LOTES.BatchNum, 
                CASE WHEN (mat.Cant_ASurtir_Origen_A ) = L.Asignado THEN \'Y\' ELSE \'N\' END AS Preparado
                from SIZ_MaterialesTraslados mat 
                LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode                               
                LEFT JOIN 
                (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''.$almacen_origen.'\' THEN 
                OnHand ELSE 0 END) AS AlmacenOrigen
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON mat.ItemCode = ALMACENES.ItemCode
                LEFT JOIN (					
                    SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
                    GROUP BY ItemCode
                )AS LOTES on LOTES.ItemCode = mat.ItemCode
                LEFT JOIN (					
                    select
                        T2.ItemCode, sum(COALESCE(lotesa.Cant, 0)) AS Asignado
                    from
                        OBTN T0
                        inner join OBTQ T1 on T0.ItemCode = T1.ItemCode and T0.SysNumber = T1.SysNumber
                        inner join OITM T2 on T0.ItemCode = T2.ItemCode
                        Left join (
                            select sum(matl.Cant) Cant, matt.Id_Solicitud, matt.ItemCode, matl.lote from SIZ_MaterialesLotes matl
                            inner join SIZ_MaterialesTraslados matt on matt.Id = matl.Id_Item
                            group by matt.Id_Solicitud, matt.ItemCode, matl.lote
                            ) as lotesa on lotesa.Id_Solicitud = ? AND lotesa.ItemCode = T1.ItemCode AND lotesa.lote = T0.DistNumber
                            where
                        T1.Quantity > 0 AND  WhsCode = \''.$almacen_origen.'\'
                    group by T2.ItemCode
                )AS L on L.ItemCode = mat.ItemCode			
                WHERE Id_Solicitud = ?
                 AND mat.EstatusLinea in (\'E\', \'I\')', [$id, $id]);
                    //dd($todosArticulos);
                $itemsConLotes = array_where($todosArticulos, function ($key, $item) {
                    return $item->BatchNum > 0 && $item->Preparado == 'N';
                });
               
                $articulos_validos = array_where($todosArticulos, function ($key,$item) {
                    return $item->EstatusLinea == 'S' || $item->EstatusLinea == 'I' || $item->EstatusLinea == 'E';
                });
                $articulos_novalidos = array_where($todosArticulos, function ($key,$item) {
                    return $item->EstatusLinea == 'N' ;
                });
              //  dd($itemsConLotes);
                $param = array(        
                    'actividades' => $actividades,
                    'ultimo' => count($actividades),    
                    'id' => $id,
                    'itemsConLotes' => count($itemsConLotes),
                    'articulos_validos' => $articulos_validos,
                    'articulos_novalidos' => $articulos_novalidos,
                    'almacen_origen' => $almacen_origen
                );
                return view('Mod04_Materiales.LotesDeptos', $param);
            
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function Entregaslotes(){
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();  
    
            $param = array(        
                'actividades' => $actividades,
                'ultimo' => count($actividades),          
            );
            return view('Mod04_Materiales.EntregasLotes', $param);
        } else {
             return redirect()->route('auth/login');
        } 
    }
    public function TrasladosDeptos(){
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
             Session::forget('transfer3');   
            $param = array(
                'actividades' => $actividades,
                'ultimo' => count($actividades),
            );
            return view('Mod04_Materiales.ShowTrasladosDeptos', $param);
        } else {
            return redirect()->route('auth/login');
        } 
    }
    public function DataTrasladosDeptos(){
        $consulta = DB::table('SIZ_SolicitudesMP')
            ->join('SIZ_MaterialesTraslados', 'SIZ_MaterialesTraslados.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
            ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
            ->leftjoin('OUDP', 'OUDP.Code', '=', 'dept')
            ->join('SIZ_AlmacenesTransferencias', function ($join) {
                $join->on('SIZ_AlmacenesTransferencias.Code', '=', DB::raw('SUBSTRING(Destino, 1, 6)'))
                    ->where('SIZ_AlmacenesTransferencias.TrasladoDeptos', '<>', 'D')
                    ->whereNotNull('TrasladoDeptos');
            })
            ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 'SIZ_SolicitudesMP.FechaCreacion', 'SIZ_SolicitudesMP.Usuario', 'SIZ_SolicitudesMP.Status', 'firstName', 'lastName', 'OHEM.dept', 'Name', 'SIZ_SolicitudesMP.AlmacenOrigen')
            ->select(
                'SIZ_SolicitudesMP.Id_Solicitud',
                'SIZ_SolicitudesMP.FechaCreacion',
                'SIZ_SolicitudesMP.Usuario',
                'SIZ_SolicitudesMP.Status',
                'SIZ_SolicitudesMP.AlmacenOrigen',
                'OHEM.firstName',
                'OHEM.lastName',
                'OHEM.dept',
                'OUDP.Name as depto'
            )
            ->whereNotNull('SIZ_SolicitudesMP.AlmacenOrigen')
            ->where('SIZ_MaterialesTraslados.Cant_PendienteA', '>', 0)
            ->where('SIZ_AlmacenesTransferencias.dept', Auth::user()->dept)
            ->whereIn('SIZ_MaterialesTraslados.EstatusLinea', ['S', 'P', 'N'])
            ->where('SIZ_SolicitudesMP.Status', 'Pendiente');
       
        return Datatables::of($consulta)
            ->addColumn(
                'folio',
                function ($item) {
                    return  '<a href="TRASLADO RECEPCION/solicitud/' . $item->Id_Solicitud . '"><i class="fa fa-hand-o-right"></i> ' . $item->Id_Solicitud . '</a>';
                }
            )
            ->addColumn(
                'user_name',
                function ($item) {
                    return  $item->firstName . ' ' . $item->lastName;
                }
            )
            ->addColumn(
                'area',
                function ($item) {
                    return  $item->depto;
                }
            )

            ->make(true);
    }
    public function ShowDetalleTrasladoDeptos($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            $solicitud = DB::table('SIZ_SolicitudesMP')->where('Id_Solicitud', $id)->first();
                     
            $articulos = DB::select('select mat.Id, mat.ItemCode, OITM.InvntryUom as UM, OITM.ItemName, mat.Destino, 
                    mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, 
                    ALMACENES.AlmacenOrigen, CASE WHEN (mat.Cant_ASurtir_Origen_A > AlmacenOrigen) AND mat.EstatusLinea <> \'C\'  THEN \'N\'  ELSE mat.EstatusLinea END as EstatusLinea  
                    from SIZ_MaterialesTraslados mat
                    LEFT JOIN OITM on OITM.ItemCode = mat.ItemCode
                    LEFT JOIN 
                    (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''.$solicitud->AlmacenOrigen.'\' THEN OnHand ELSE 0 END) AS AlmacenOrigen
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON OITM.ItemCode = ALMACENES.ItemCode
                    inner join SIZ_AlmacenesTransferencias on SIZ_AlmacenesTransferencias.Code = SUBSTRING(Destino, 1, 6) 
                    and SIZ_AlmacenesTransferencias.Dept = '.Auth::user()->dept.' and SIZ_AlmacenesTransferencias.TrasladoDeptos <> \'D\' and TrasladoDeptos is not null 
                    WHERE Id_Solicitud = ? AND Cant_PendienteA > 0
                    group by Id, mat.ItemCode, OITM.InvntryUom, OITM.ItemName, mat.Destino,  mat.Cant_Requerida, mat.Cant_Autorizada, mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, 
                    ALMACENES.AlmacenOrigen, EstatusLinea', [$id]);
              
                $articulos_validos = array_where($articulos, function ($key, $item) {
                    return $item->EstatusLinea == 'S' || $item->EstatusLinea == 'P';
                });
                $articulos_novalidos = array_where($articulos, function ($key, $item) {
                    return $item->EstatusLinea == 'N';
                });
                $pdf_solicitud = DB::table('OWTR')->where('FolioNum', $id)->get();                        
            if (count($articulos_novalidos) > 0) {
                Session::flash('solicitud_err', 'Esta Solicitud tiene artículos que no se surtirán (fueron quitados o no hay material disponible)');
            }

            $param = array(
                'actividades' => $actividades,
                'ultimo' => count($actividades),
                'id' => $id,
                'almacen_origen' => $solicitud->AlmacenOrigen,
                'articulos_validos' => $articulos_validos,
                'articulos_novalidos' => $articulos_novalidos,
                'comentario' => $solicitud->ComentarioUsuario,
                'pdf_solicitud' => $pdf_solicitud
            );
            
                return view('Mod04_Materiales.TrasladosDeptos', $param);
            
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function removeArticuloTrasladoDepto()
    {
        $item = DB::table('SIZ_MaterialesTraslados')->where('Id', Input::get('articulo'))->first();
        $id_sol = $item->Id_Solicitud;
        if (Auth::check()) {
            if (strpos(Input::get('reason'), 'Material') !== false) {
     
                DB::update('UPDATE SIZ_MaterialesTraslados SET EstatusLinea = ? , Razon = ?
            WHERE Id = ?', ['C', Input::get('reason'), Input::get('articulo')]);

                $articulosvalidos = DB::table('SIZ_MaterialesTraslados')
                    ->whereIn('EstatusLinea', ['S', 'N', 'I'])
                    ->where('Id_Solicitud', $id_sol)->count();
                
                if ($articulosvalidos == 0) {
                    DB::update(
                        'UPDATE SIZ_SolicitudesMP SET Status = ? 
                             WHERE Id_Solicitud = ?',
                        ['Cancelada', $id_sol]
                    );
                    // si el Solicitante tiene correo se le avisa
                    $solicitante = DB::table('SIZ_SolicitudesMP')
                        ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
                        ->select('SIZ_SolicitudesMP.Status', 'OHEM.firstName', 'OHEM.lastName', DB::raw('case when email like \'%@%\' then email else email + cast(\'@zarkin.com\' as varchar)  end AS correo'))
                        ->where('SIZ_SolicitudesMP.Id_Solicitud', $id_sol)->first();
                    $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitante->lastName));
                    $nombreCompleto = explode(' ',$solicitante->firstName)[0].' '.$apellido;
                    $correos_db = DB::select("
                    SELECT 
                    CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                    FROM OHEM
                    INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                    WHERE se.Traslados in (1, 3)
                    GROUP BY email
                    ");
                    $correos = array_pluck($correos_db, 'correo');
                    if (!in_array($solicitante->correo, $correos) && $solicitante->correo !== null) {
                        $correos[] = $solicitante->correo;
                    }   
                    $arts = DB::table('SIZ_MaterialesTraslados')
                        ->join('OITM', 'OITM.ItemCode', '=', 'SIZ_MaterialesTraslados.ItemCode')
                        ->select('SIZ_MaterialesTraslados.*', 'OITM.ItemName', 'OITM.InvntryUom')
                        ->where('EstatusLinea', '<>', 'T')
                        ->where('Id_Solicitud', $id_sol)->get();

                    if ((count($correos) > 0) && ($solicitante->Status === 'Cancelada')) {
                        Mail::send('Emails.TrasladoDeptosMaterialesCancelacion', [
                            'arts' => $arts, 'id' => $id_sol, 'nombreCompleto' => $nombreCompleto
                        ], function ($msj) use ($correos, $id_sol) {
                            $msj->subject('SIZ Traslado Cancelación #' . $id_sol); //ASUNTO DEL CORREO
                            $msj->to($correos); //Correo del destinatario
                        });
                    }
                    Session::flash('mensaje', 'Solicitud #' . $id_sol . ' Cerrada');
                }
            } else {
                DB::update('UPDATE SIZ_MaterialesTraslados SET EstatusLinea = ? , Razon= ?
            WHERE Id = ?', ['N', Input::get('reason'), Input::get('articulo')]);
            }
            return redirect()->back();
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function returnArticuloTrasladosDepto($id)
    {
        if (Auth::check()) {
            DB::update('UPDATE SIZ_MaterialesTraslados SET EstatusLinea = ? , Razon = ?  WHERE Id = ?', ['S', '', $id]);
            return redirect()->back();
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function editArticuloTrasladosDepto()
    {
        if (Auth::check()) {
            if (Input::get('cantp')  == Input::get('pendiente')) {
                DB::update('UPDATE SIZ_MaterialesTraslados SET  Cant_ASurtir_Origen_A = ?, Razon_CantMenor = ? WHERE Id = ?', [Input::get('cantp'), '', Input::get('articulo')]);
            } elseif (Input::get('cantp') < Input::get('pendiente')) {
                DB::update('UPDATE SIZ_MaterialesTraslados SET  Cant_ASurtir_Origen_A = ?, Razon_CantMenor = ? WHERE Id = ?', [Input::get('cantp'), Input::get('reason'), Input::get('articulo')]);

                if (strpos(Input::get('reason'), 'Material') !== false) {

                    $art = DB::table('SIZ_MaterialesTraslados')
                        ->join('OITM', 'OITM.ItemCode', '=', 'SIZ_MaterialesTraslados.ItemCode')
                        ->select('SIZ_MaterialesTraslados.*', 'OITM.ItemName', 'OITM.InvntryUom')
                        ->where('Id', Input::get('articulo'))->first();

                    $correos_db = DB::select("
                        SELECT 
                        CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                        FROM OHEM
                        INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                        WHERE se.Traslados in (1,3)
                        GROUP BY email
                    ");
                    $correos =array_pluck($correos_db, 'correo');

                    if (count($correos) > 0) {                        
                        Mail::send('Emails.Err_TrasladoDeptos', [
                            'art' => $art
                        ], function ($msj) use ($correos, $art) {
                            $msj->subject('SIZ Traslado - Articulo  (' . $art->ItemCode . ')'); //ASUNTO DEL CORREO
                            $msj->to($correos); //Correo del destinatario
                        });                        
                    }
                }
            } else {
                Session::flash('error', 'No se pudo actualizar...');
            }
            Session::flash('mensaje', 'Cantidad Actualizada...');
            return redirect()->back();
        } else {
            return redirect()->route('auth/login');
        }
    }
    Public function updateArticuloTrasladoDepto($id){
        if (Auth::check()) {
             $rates = DB::table('ORTT')->where('RateDate', date('d-m-Y'))->get();
            if (count($rates) >= 3) {
           // if (true) {
               //GUARDAR EL USUARIO QUE HACE EL MOVIMIENTO
               $apellido = Self::getApellidoPaternoUsuario(explode(' ',Auth::user()->lastName));
             DB::table('SIZ_SolicitudesMP')
                    ->where('Id_Solicitud', $id)
                    ->update(['SOLentrega_TRASrecibe_Usuario' => explode(' ',Auth::user()->firstName)[0].' '.$apellido]);
                //PERSONA QUE SOLICITA
                $solicitud = DB::table('SIZ_SolicitudesMP')       
                    ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')        
                    ->select('OHEM.firstName','OHEM.lastName', 'SIZ_SolicitudesMP.*')
                    ->where('SIZ_SolicitudesMP.Id_Solicitud', $id)->first();  
                $apellido = Self::getApellidoPaternoUsuario(explode(' ',$solicitud->lastName));
                $nombreCompleto = explode(' ',$solicitud->firstName)[0].' '.$apellido;   
               
                $articulos = DB::select('select mat.Id, mat.ItemCode, mat.Destino, 
                mat.Cant_ASurtir_Origen_A as CA, mat.Cant_PendienteA,
                ALMACENES.AlmacenOrigen, LOTES.BatchNum from SIZ_MaterialesTraslados mat                                
                LEFT JOIN (SELECT ItemCode, SUM(CASE WHEN WhsCode = \''.$solicitud->AlmacenOrigen.'\' THEN OnHand ELSE 0 END) AS AlmacenOrigen
                    FROM dbo.OITW
                    GROUP BY ItemCode) AS ALMACENES ON mat.ItemCode = ALMACENES.ItemCode
                    LEFT JOIN (					
                        SELECT ItemCode, COUNT (DistNumber) AS BatchNum FROM OBTN 
                        GROUP BY ItemCode
                    )AS LOTES on LOTES.ItemCode = mat.ItemCode
                     inner join SIZ_AlmacenesTransferencias on SIZ_AlmacenesTransferencias.Code = SUBSTRING(Destino, 1, 6) 
                    and SIZ_AlmacenesTransferencias.Dept = '.Auth::user()->dept.' and SIZ_AlmacenesTransferencias.TrasladoDeptos <> \'D\' and TrasladoDeptos is not null 
                WHERE Id_Solicitud = ? AND mat.EstatusLinea = \'S\' 
                AND mat.Cant_ASurtir_Origen_A <= AlmacenOrigen 
                 group by Id, mat.ItemCode, mat.Destino, 
                mat.Cant_PendienteA, mat.Cant_ASurtir_Origen_A, 
                ALMACENES.AlmacenOrigen, BatchNum', [$id]);
                
                $stat3 = 0;
                $transfer3 = 0;              
                $t3 = 0;
                $info3 = 0;
                if (count($articulos) > 0) {
                    $observacionesComplemento = (!is_null($solicitud->SOLentrega_TRASrecibe_Usuario)) ? ", Recibe: ". $solicitud->SOLentrega_TRASrecibe_Usuario : "";
                    $data =  array(
                        'id_solicitud' => $id,
                        'pricelist' => '10',
                        'almacen_origen' => $solicitud->AlmacenOrigen,
                        'items' => $articulos,
                         'observaciones' => utf8_decode("SIZ VALE #".$id .", Envió: ". $nombreCompleto.$observacionesComplemento.". ". $solicitud->ComentarioUsuario)
                                        
                    );
                   //  dd($data);   
                    if (Session::has('transfer3')) {   
                                        
                        if (Session::get('transfer3') > 0) {
                            $t3 = Session::get('transfer3');
                        } else {
                            $t3 = SAP::Transfer2($data);
                        }
                    } else {
                            $t3 = SAP::Transfer2($data);                           
                    }

                    if (is_numeric($t3) && $t3 > 0 ) {
                         
                        Session::put('transfer3', $t3);
                        Session::put('mensaje2', 'Transferencia '.$t3.' realizada.');
                    }else{
                        $stat3 = strlen($t3);
                        $transfer3 = array();                       
                    }
                }
                if ( $stat3 > 0 ) {
                    Session::flash('error', 'Transferencia '.$t3);                    
                    return redirect()->back();
                }
                $filas = DB::table('SIZ_MaterialesTraslados')
                ->where('Id_Solicitud', $id)
                ->whereNotIn('EstatusLinea', ['T', 'C'])
                ->count();            
                if ($filas > 0) {
                    DB::table('SIZ_SolicitudesMP')
                    ->where('Id_Solicitud', $id)
                    ->update(['Status' => 'Pendiente']);
                } elseif($filas == 0) {
                    DB::table('SIZ_SolicitudesMP')
                    ->where('Id_Solicitud', $id)
                    ->update(['Status' => 'Cerrada', 'FechaFinalizada' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
                }
                return redirect()->action('Mod04_MaterialesController@ShowDetalleTrasladoDeptos', [$id]);
            }else {
            Session::flash('error', 'No estan capturados todos los "Tipos de Cambio" en SAP.');        
            return redirect()->back();
        }                       
        }else {
        return redirect()->route('auth/login');
        }
    }
    public function vistaLotes($tabla, $alm, $id){
        $cant = 'mat.Cant_ASurtir_Origen_A';
        if ($alm == 'AMP-ST' && ($tabla == 'solicitudes')) {
            $cant = 'mat.Cant_ASurtir_Origen_B';
        } else { //para Traslados
            $cant = 'mat.Cant_ASurtir_Origen_A';
        }
        if ($tabla == 'solicitudes') {
            $t = 'SIZ_MaterialesSolicitudes';
        } else if ($tabla == 'traslados') {
            $t = 'SIZ_MaterialesTraslados';
        }
        
        $articulo = collect (DB::select('select mat.Id, mat.ItemCode, o.ItemName, o.InvntryUom AS UM,
                 '.$cant.' AS Cant, mat.Id_Solicitud
                from '.$t.' mat                                
                LEFT JOIN OITM o ON mat.ItemCode = o.ItemCode
                WHERE mat.Id = ? 
                and mat.EstatusLinea in (\'S\', \'P\', \'I\', \'E\', \'N\')', [$id]))->first();
        //return $articulo->ItemCode;
       // dd($cant);
        $lotes = DB::select('select
                    T0.DistNumber as NumLote,
                    T1.Quantity  AS Disponible,
                     COALESCE((PROCESO.CantProceso - '.$articulo->Cant.'), 0) Proceso
                from
                    OBTN T0
                    inner join OBTQ T1 on T0.ItemCode = T1.ItemCode and T0.SysNumber = T1.SysNumber
                    inner join OITM T2 on T0.ItemCode = T2.ItemCode
                    LEFT JOIN
                        (select ItemCode, sum (Cant_PendienteA) CantProceso
                        from '.$t.' mat
                        LEFT JOIN SIZ_SolicitudesMP sol on sol.Id_Solicitud = mat.Id_Solicitud
                        where mat.EstatusLinea in (\'S\', \'P\', \'I\', \'E\', \'N\')
                        group by ItemCode) AS PROCESO ON T2.ItemCode = PROCESO.ItemCode
                     where
                    T1.Quantity > 0 AND T0.ItemCode = ? AND WhsCode = ? 
				group by T0.DistNumber, T1.Quantity, CantProceso
				order by T0.DistNumber', [$articulo->ItemCode, $alm]);
        $arrayNumLotes = array_pluck($lotes, 'NumLote');
        //dd($arrayNumLotes);
        $lotesAsignados = DB::table('SIZ_MaterialesLotes')
        ->where('Id_Item', $id)                  
        ->where('alm', $alm)                  
        ->get();

       foreach ($lotesAsignados as $l) {
           if (($key = array_search($l->lote, $arrayNumLotes)) !== false) {
           // dd($key);    
            unset($lotes[$key]);                
            }
       }
       $lotes = array_values($lotes);
      // dd($lotes);
        $sumLotesAsignados = array_sum(array_pluck($lotesAsignados, 'Cant'));
       // dd(array_pluck($lotesAsignados, 'Disponible'));
        
        $user = Auth::user();
        $actividades = $user->getTareas();
        $ultimo = count($actividades);
        return view('Mod04_Materiales.Lotes', compact('tabla', 'sumLotesAsignados',
         'lotes', 'lotesAsignados', 'articulo', 'alm', 'actividades', 'ultimo'));
        
    }
    public function insertLotes(Request $request){

            $existe = DB::table('SIZ_MaterialesLotes')
            ->where('Id_Item', Input::get('articulo'))
            ->where('alm', Input::get('alm'))
            ->where('lote',Input::get('lote'))
            ->first();
           // dd($existe);
            $existe = count($existe);
      
            
            if ($existe == 0) {
                
                //$errors = new \Illuminate\Support\MessageBag();

                // add your error messages:
               // $errors->add('Error', 'El costo A-COMPRAS debe contener un número');
                DB::table('SIZ_MaterialesLotes')->insert(
                    ['Id_Item' => Input::get('articulo'), 'Cant' => Input::get('cant'), 'lote' => Input::get('lote'), 'alm' => Input::get('alm')]
                );
               return redirect()->back();
                    
            }else if($existe > 0){
                DB::table('SIZ_MaterialesLotes')
                ->where('Id_Item', Input::get('articulo'))
                ->update(
                    ['Cant' => Input::get('cant'), 'lote' => Input::get('lote'), 'alm' => Input::get('alm')]
                );
               return redirect()->back();
            }
    }
    public function removeLote($id, $lote, $alm){     
        DB::table('SIZ_MaterialesLotes')
        ->where('Id_Item', $id)
        ->where('lote', $lote)
        ->where('alm', $alm)
        ->delete();
        return redirect()->back();
    }
    public function iowhsXLS()
    {
        $path = public_path() . '/assets/plantillas_excel/Mod_04/SIZ_entradas_salidas.xlsx';
        $data = json_decode(Session::get('entradasysalidas'));
        $fechas_entradas = Session::get('param_entradasysalidas');
        $fecha = 'Del: '. \AppHelper::instance()->getHumanDate(array_get($fechas_entradas, 'fi')).' al: '.
                \AppHelper::instance()->getHumanDate(array_get($fechas_entradas, 'ff'));

                Excel::load($path, function ($excel) use ($data, $fecha) {
            $excel->sheet('Detalles', function ($sheet) use ($data, $fecha) {

                $sheet->cell('C4', function ($cell) {
                    $cell->setValue(\AppHelper::instance()->getHumanDate(date("Y-m-d H:i:s")).' '. date("H:i:s"));
                });
                $sheet->cell('C5', function ($cell) use ($fecha) {
                    $cell->setValue($fecha);
                });
                $index = 7;
                foreach ($data as $row) {
                    $dateA = date_create($row->DocDate);
                    $dateB = date_create($row->CreateDate);

                    $sheet->row($index, [
                     $row->BASE_REF, 
                     date_format($dateA,'d-m-Y'),
                     date_format($dateB,'d-m-Y'),
                     $row->JrnlMemo,
                     $row->ItemCode,

                     $row->Dscription,
                     $row->Movimiento,
                     $row->STDVAL,
                     $row->U_TipoMat,
                     $row->U_NAME,
                     
                     $row->ALM_ORG,
                     $row->VSala,
                     $row->NUMOPER,
                     $row->TIPO,
                     $row->Comments,
                     
                     $row->CardName,
                     $row->DocTime
                     
                    ]);
                    $index++;
                }
            });
        })
            ->setFilename('SIZ M04 Reporte de Entradas - Salidas Articulos')
            ->export('xlsx');
    }

    public function DataShowEntradasSalidas(Request $request)
    {
        if (Auth::check()) {
            $fi = $request->get('fi').' 00:00';
            $ff = $request->get('ff').' 23:59:59';
            $tipomat = $request->get('tipomat');
            $almacenes = $request->get('almacenes');
            
            if ($tipomat == 'Cualquiera') {
                $tipomat = '%';
            }

    $consulta = DB::select("
        Select OINM.CardName, OINM.BASE_REF, OINM.AppObjAbs, 
        OINM.DocDate, OINM.CreateDate, OINM.JrnlMemo, OINM.ItemCode, OINM.Dscription, ITM1.Price as COST01, 
        OINM.RevalTotal,(OINM.InQty-OINM.OutQty) as Movimiento, OINM.UserSign, OUSR.U_NAME AS UNAME, OINM.Warehouse AS ALM_ORG, 
        ISNULL(OINM.Ref2, 'N/A') AS ALM_DES, OITM.U_VS, OINM.Comments, OITM.U_TipoMat, OINM.DocTime, 
        OWOR.ItemCode as OPModelo 
        from OINM  inner join OUSR on OINM.UserSign=OUSR.USERID 
        inner join OITM on OINM.ItemCode=OITM.ItemCode left join OWOR on OINM.AppObjAbs = OWOR.DocEntry 
        inner join ITM1 on OINM.ItemCode= ITM1.ItemCode and ITM1.PriceList = '10'  
        Where Cast (OINM.CreateDate as DATE) between  '".$fi."' and '".$ff."' 
        and U_TipoMat like '".$tipomat."' and OINM.Warehouse in (".$almacenes.")
    " );

$consultaj = collect($consulta);
foreach($consultaj as $item)
{        
    $almacenO = self::defAlmacen($item->ALM_ORG);
    $almacenD = self::defAlmacen($item->ALM_DES);
    $item->AMLORIG = $almacenO;
    $item->AMLDEST = $almacenD;
    $tipo = 'SIN REGISTRO';
    $item->NUMOPER =  '';
    $item->VSala = floatval($item->U_VS) * floatval($item->Movimiento);
    $item->STDVAL =(strpos($item->JrnlMemo, 'Reval') !== false) ? $item->RevalTotal : $item->COST01;
    if ((strpos($item->JrnlMemo, 'Pedido') !== false) || ( strpos($item->JrnlMemo, 'Fact.') !== false )){
        $item->NUMOPER = "01 COMPRA";
        $tipo = "PROVEEDOR -> ". $almacenO; 
    }
    //1
    if ( ((strpos($item->JrnlMemo, 'Emisi') !== false) || ( strpos($item->JrnlMemo, 'Recibo.') !== false )) 
    && $item->Movimiento < 0 ){
       //2
        if ((strpos($item->Dscription, 'PIEL 0') !== false) && ($item->U_TipoMat == 'MP')) {
            $item->NUMOPER = "02 SALIDA PIEL";
            $tipo = "ENV. " . $almacenO . " -> PROCESO";
        } else {
            $item->NUMOPER = "02 SALIDA CON";
            if (strlen($item->OPModelo) < 6) {
                $tipo = $almacenO . " -> CONS-SUB";
            } else {
                if (strlen($item->OPModelo) > 10) {
                    if ((strpos($item->OPModelo, '3581-42') !== false) || (strpos($item->OPModelo, '3581-32') !== false) ||
                        (strpos($item->OPModelo, '3774-42') !== false) || (strpos($item->OPModelo, '3778-42') !== false) ) {
                        $tipo = $almacenO . " -> CONS-REF";
                    } else {
                        $tipo = $almacenO . " -> CONS-PT";
                    }
                } else {
                    if ((strpos($item->OPModelo, '-H') !== false)) {
                        $tipo = $almacenO . " -> CONS-HB";
                    } else {
                        $tipo = $almacenO . " -> CONS-CA";
                    }
                }
            }
        } //2
    } //1
    if (( strpos($item->JrnlMemo, 'Recibo.') !== false ) && $item->Movimiento > 0 ){
        $item->NUMOPER = "04 DEV PIEL";
        $tipo = "PROCESO -> " . $almacenO; 
    } else {
        if ($item->U_TipoMat == 'CA') {
            $item->NUMOPER = "03 PROD KASCO";
            $tipo = "FAB-CASCO -> " . $almacenO;
        } else {
            if ($item->U_TipoMat == 'PT') {
                $item->NUMOPER = "03 PROD SALAS";
                $tipo = "FAB-PT -> " . $almacenO;
            } else {
                if (strpos($item->ItemCode, '-H') !== false) {
                    $item->NUMOPER = "03 PROD HABIL";
                    $tipo = "FAB-HAB -> " . $almacenO;
                } else {
                    $item->NUMOPER = "03 PROD SUB";
                    $tipo = "FAB-SUB -> " . $almacenO;
                }
                
            }
            
        }
    } //1
    if (((strpos($item->JrnlMemo, 'dito proveedore') !== false) || ( strpos($item->JrnlMemo, 'Devolución de merc') !== false ))) {
        $item->NUMOPER = "04 DEV CANCELA";
        $tipo = $almacenO . " -> PROVEEDOR";
    }
    if (((strpos($item->JrnlMemo, 'dito clientes') !== false) || ( strpos($item->JrnlMemo, 'Credit Me') !== false ))) {
        $item->NUMOPER = "04 DEV CLIENTE";
        $tipo = "CLIENTE -> " . $almacenO;
    }
    if (strpos($item->JrnlMemo, 'Devoluciones') !== false) {
        $item->NUMOPER = "04 DEV CLIENTE";
        $tipo = "CLIENTE -> " . $almacenO;
    }
    if (((strpos($item->JrnlMemo, 'Entreg') !== false) || ( strpos($item->JrnlMemo, 'Facturas clie') !== false ))) {
        $tipo = $almacenO . " -> CLIENTE";
        if ($item->ALM_ORG == 'AXL-CI') {
            $item->NUMOPER = "08 S-SERVICIOS";            
        } else {
            $item->NUMOPER = "05 FACTURA";
        }
    }
    if (strpos($item->JrnlMemo, 'Traslado') !== false) {
        $item->NUMOPER = "07 TRASLADO";
        if ($item->Movimiento < 0) {
           $tipo = "ENV. " . $almacenO . " -> " . $almacenD;           
        } else {
           $tipo = "REC. " . $almacenO . " <- " . $almacenD; 
        }
    }
    if (($item->NUMOPER ==  '') && (strpos($item->CardName, 'RECLASIFI') !== false)) {
        $item->NUMOPER = "06 AJUSTE REC";
        $tipo = "RECLA. -> " . $almacenO;
    }
    if (($item->NUMOPER ==  '') && (strpos($item->CardName, 'GVN') !== false)) {
        $item->NUMOPER = "02 SALIDA GAS";
        $tipo = $almacenO . " -> CONS-GRAL";
    }
    if (($item->NUMOPER ==  '') && (strpos($item->CardName, 'DIVER') !== false)) {
        $item->NUMOPER = "02 SALIDA CON";
        $tipo = $almacenO . " -> CONS " . $almacenO;
    }
    if (($item->NUMOPER ==  '') && (strpos($item->CardName, 'SALDOS INICIALES CONTABLES') !== false)) {
        $item->NUMOPER = "05 FACTURA";
        $tipo = $almacenO . " -> CLIENTE FUS";
    }
    $item->TIPO = $tipo;
}
    $request->session()->put( 'param_entradasysalidas', array(
        'fi' => $request->get('fi'),
        'ff' => $request->get('ff'),
        'tipomat' => $request->get('tipomat')
    ));
    return
    Datatables::of($consultaj)
    ->addColumn('U_NAME', function ($consultaj) {
        if (strpos($consultaj->Comments, 'SIZ VALE') !== false) {
           $cadena = explode(':', $consultaj->Comments);
           $nombre = explode(',', $cadena[1]);
           if ( strlen($nombre[0]) > 5 ){
                return $nombre[0];
           } else {
                return $consultaj->UNAME;
           }
           
        } else {
           return $consultaj->UNAME;
        }
    })
    ->make(true);
    } else {
        return redirect()->route('auth/login');
    }
}

    public function defAlmacen($alm){
        $grpAlmacen = ["AMG-FE", "AMG-ST", "AMP-BL","AMP-CC", "AMP-CO", "AMP-KU", "AMP-ST", "APG-PA", 
                         "ARF-ST" , "ATG-SE", "AMP-FE", "AMG-KU", "APT-FX", "AMG-CC", "AXL-TC" ];
        $grpTerminado = ["APT-ST", "ATG-ST", "AXL-CA", "APT-CO" ];
        $grpProceso = [ "APG-ST", "APP-ST", "APT-PA"];
        if ( in_array($alm, $grpAlmacen) ) {
            return 'ALMACEN';
        } else if ( in_array($alm, $grpTerminado) ){
            return 'TERMINADO';
        } else if (  in_array($alm, $grpProceso) ){
            return 'PROCESO';
        } else if ( $alm == "APT-PR" ){
            return 'DIRECCION';
        } else if ( $alm == "ATL-DS" ){
            return 'DISEÑO';
        } else if ( $alm == "APT-SE" ){
            return 'SERVICIOS';
        } else if ( $alm == "AMP-TR"){
            return 'CAMION';
        } else if ( $alm == "APT-TR" ){
            return 'CONSUMOS';
        }
         return 'SIN DEFINIR';               
    }
    public function TransferenciasPendientes(){
       if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();   
            $param = array(
                'actividades' => $actividades,
                'ultimo' => count($actividades),
            );
            return view('Mod04_Materiales.ShowTransferenciasP', $param);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function DataShowTransferenciasPendientes(){
        $consulta = DB::select("
       Select      'SOLICITUD' AS TIPO_DOC,
             SIZ_SolicitudesMP.Id_Solicitud AS NUMERO, COALESCE(SIZ_SolicitudesMP.AlmacenOrigen, 'ALMACEN MATERIA PRIMA') AS ORIGEN, 
             SIZ_SolicitudesMP.FechaCreacion AS FECHA,
             (Select OHEM.firstName +'  '+ OHEM.lastName  + ' - '+ D.Name from OHEM inner join OUDP D on D.Code = OHEM.dept Where OHEM.U_EmpGiro = SIZ_SolicitudesMP.Usuario) AS USUARIO,
             Destino AS DESTINO,
             SIZ_SolicitudesMP.Status AS ST_SOL,
             SIZ_MaterialesSolicitudes.ItemCode AS CODIGO,
             OITM.ItemName AS DESCRIPCION,
             OITM.InvntryUom AS UDM,
             SIZ_MaterialesSolicitudes.Cant_PendienteA AS PENDIENTE,
             SIZ_MaterialesSolicitudes.EstatusLinea AS ST_LIN
From SIZ_SolicitudesMP
inner join SIZ_MaterialesSolicitudes on SIZ_MaterialesSolicitudes.Id_Solicitud = SIZ_SolicitudesMP.Id_Solicitud
 inner join OITM on SIZ_MaterialesSolicitudes.ItemCode = OITM.ItemCode
Where SIZ_MaterialesSolicitudes.EstatusLinea in ('S', 'P', 'N')
  UNION ALL
 
Select      'TRASLADOS' AS TIPO_DOC,
             SIZ_SolicitudesMP.Id_Solicitud AS NUMERO, COALESCE(SIZ_SolicitudesMP.AlmacenOrigen, 'ALMACEN MATERIA PRIMA') AS ORIGEN,
             SIZ_SolicitudesMP.FechaCreacion AS FECHA,
             (Select OHEM.firstName +'  '+ OHEM.lastName  + ' - '+ D.Name from OHEM inner join OUDP D on D.Code = OHEM.dept Where OHEM.U_EmpGiro = SIZ_SolicitudesMP.Usuario) AS USUARIO,
             Destino AS DESTINO,
             SIZ_SolicitudesMP.Status AS ST_SOL,
             SIZ_MaterialesTraslados.ItemCode AS CODIGO,
             OITM.ItemName AS DESCRIPCION,
             OITM.InvntryUom AS UDM,
             SIZ_MaterialesTraslados.Cant_PendienteA AS PENDIENTE,
             SIZ_MaterialesTraslados.EstatusLinea AS ST_LIN
From SIZ_SolicitudesMP
inner join SIZ_MaterialesTraslados on SIZ_MaterialesTraslados.Id_Solicitud = SIZ_SolicitudesMP.Id_Solicitud
 inner join OITM on SIZ_MaterialesTraslados.ItemCode = OITM.ItemCode
Where SIZ_MaterialesTraslados.EstatusLinea in ('S', 'P', 'I', 'E')
  Order By SIZ_SolicitudesMP.Id_Solicitud
    " );

$consultaj = collect($consulta);
       
        return Datatables::of($consultaj)
            ->addColumn(
                'STATUS_LIN',
                function ($item) {
                    switch ($item->ST_LIN) {
                        case 'E':
                            return 'Traslado Externo';
                            break;
                        case 'I':
                            return 'Traslado Interno';
                            break;
                        case 'S':
                           if ($item->TIPO_DOC == 'TRASLADOS') {
                               return 'Esperando Recepción';
                           } else {
                               return 'En Proceso';
                           }
                           
                            break;
                        case 'P':
                            return 'Cant. Pendiente';
                            break;
                        
                        default:
                            return 'Indefinido';
                            break;
                    }
                    
                }
            )           
            /*
            ->addColumn(
                'area',
                function ($item) {
                    return  $item->depto;
                }
            )
            */
            ->make(true);
    }
    public function getApellidoPaternoUsuario($apellido){
        $preposiciones = ["DE", "LA", "LAS", "D", "LOS", "DEL"]; 
        if (in_array($apellido[0], $preposiciones) && count($apellido)>1 ) {
            if (in_array($apellido[1], $preposiciones) && count($apellido)>2 ) {
               return $apellido[0].' '.$apellido[1].' '.$apellido[2];
            } else {
                return $apellido[0].' '.$apellido[1];
            }            
        } else {
            return $apellido[0];
        }
    }
    public function generaEtiquetaQR(){
        
        $itemName = Input::get('itemName');
        $pKey = Input::get('pKey');
        $cardCode = Input::get('proveedor');
        $cardName = DB::table('OCRD')->where('CardCode', Input::get('proveedor'))
        ->value('CardName');
        $um = Input::get('um');
        $fechar = Input::get('date');
        $fechar = \AppHelper::instance()->getHumanDateFromFormat($fechar);
        $cant = Input::get('cantx_bulto');
        $separador = ' - ';
        $CodigoQR = QrCode::margin(1)->format('png')->size(100)
        ->generate("http://187.189.177.39:8081/siz/public/qr/".
        $pKey."/".$cardCode."/".$cant);

        $pdf = \PDF::loadView('Mod04_Materiales.etiquetaQrPDF', 
        compact('separador', 'itemName', 'pKey', 'cardCode',  'cardName', 'um','cant', 'CodigoQR', 'fechar'));
        //  $pdf->setPaper([0, 0, 90, 144], 'landscape')->setOptions(['isPhpEnabled'=>true]);
        $pdf->setPaper([0, 0, 90, 144],'landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_QR_'.$pKey . ' - ' .date("d/m/Y") . '.Pdf');
    }
    public function getArticulo($itemCode, $proveedor, $cantXbulto, Request $request)
    {
        
        if (!Auth::check()) {
             $cardName = DB::table('OCRD')->where('CardCode', $proveedor)
            ->value('CardName'); 
            $proveedor = $proveedor.' '.$cardName;
            $param = self::getParam_DM_Articulos1($itemCode);
            $param['proveedor'] = $proveedor;
            $param['cantXbulto'] = $cantXbulto;
            return view('Mod04_Materiales.Visualiza_ArticuloQR', $param);
        }
       if (Session::has('solicitud_picking')) {
               $id_sol = Session::get('solicitud_picking');
            $items = DB::table('SIZ_MaterialesSolicitudes')
            ->where('id_solicitud', $id_sol)
            ->lists('itemCode');
            $values = array();
            if (in_array($itemCode ,$items)) {                                            
                $mi_material = DB::table('SIZ_MaterialesSolicitudes')
                ->where('Id_Solicitud', $id_sol)
                ->where('ItemCode', $itemCode)
                ->first();
                if ($mi_material->Cant_Autorizada >= ($cantXbulto + $mi_material->Cant_scan)) {
                    Session::put('qrfound', $id_sol);
                    Session::flash('qrmsg','Cantidad agregada: '. $cantXbulto);
                    DB::update('UPDATE SIZ_MaterialesSolicitudes SET Cant_scan = ? WHERE Id_Solicitud = ? AND ItemCode=?', [($cantXbulto + $mi_material->Cant_scan), $id_sol, $itemCode]);
                    $cantXbulto =(floatval($cantXbulto) + floatval($mi_material->Cant_scan)) * 1; 
                    $showmodalqr = true;
                    return redirect()                     
                ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'itemCode', 'cantXbulto', 'showmodalqr')); 
                } else {
                  /*  if ($mi_material->Cant_Autorizada == $mi_material->Cant_scan) {
                        Session::flash('info','El material '.$itemCode.' está completo');              
                        $item = null;
                        $cant = null;
                        return redirect()
                        ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'item', 'cant'));
                    } else {
                        */


                        // creo que esto de que reinicie el escaneo cuando rebase la cantidad confunde a los usuarios
                            //por eso se mejor se quita
                        //         if ($mi_material->Cant_Autorizada >= $cantXbulto) {
                //             Session::flash('mensaje','Cantidad escaneada: '. $cantXbulto);
                //             Session::put('qrfound', $id_sol);
                //             DB::update('UPDATE SIZ_MaterialesSolicitudes SET Cant_scan = ? WHERE Id_Solicitud = ? AND ItemCode=?', [$cantXbulto, $id_sol, $itemCode]);
                //              return redirect()
                // ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'itemCode', 'cantXbulto')); 
                       // } else {
                            Session::flash('error','No se agregó la cantidad.
                            Razón: se rebasaría la Cantidad Autorizada, intente con otra etiqueta'); 
                            $showmodalqr = false;             
                            return redirect()
                            ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'itemCode', 'cantXbulto', 'showmodalqr')); 
                        }                        
                   // }                    
                }                                
                             
            } else {
                Session::put('notfound','El material escaneado no esta en la solicitud #'.$id_sol);
              
                $item = null;
                $cant = null;
                $showmodalqr = false;
                return redirect()
                ->action('Mod04_MaterialesController@ShowDetalleSolicitud', compact('id_sol', 'item', 'cant', 'showmodalqr'));        
            }
            
        

            $cardName = DB::table('OCRD')->where('CardCode', $proveedor)
            ->value('CardName'); 
            $proveedor = $proveedor.' '.$cardName;
            $param = self::getParam_DM_Articulos($request, $itemCode);
            $param['proveedor'] = $proveedor;
            $param['cantXbulto'] = $cantXbulto;
            return view('Mod04_Materiales.Visualiza_ArticuloQR', $param);
            
    }
     public static function getParam_DM_Articulos1($item){
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