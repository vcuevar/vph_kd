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
class Reportes_ProduccionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Mod00_Administrador.admin');
    }

    public function Produccion1(Request $request)
    {

        $enviado = $request->input('send');
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();

            if ($enviado == 'send') {
                $departamento = $request->input('dep');
                //  $fecha = explode(" - ",$request->input('date_range'));
                //$dt = date('d-m-Y H:i:s');
                $fechaI = Date('d-m-y', strtotime(str_replace('-', '/', $request->input('FechIn'))));
                $fechaF = Date('d-m-y', strtotime(str_replace('-', '/', $request->input('FechaFa'))));
                $fecha_desde = strtotime($request->input('FechIn'));
                $fecha_hasta = strtotime($request->input('FechaFa'));
                if ($fecha_hasta >= $fecha_desde) {
                    $clientes = DB::select('SELECT CardName from  "CP_ProdTerminada" WHERE  (fecha>=\'' . $fechaI . '\' AND
  fecha<=\'' . $fechaF . '\') AND
 (Name= (\'' . $departamento . '\')  OR Name= (CASE
 WHEN  \'' . $departamento . '\' like \'112%\' THEN N\'01 Corte de Piel\'
 WHEN  \'' . $departamento . '\' like \'115%\' THEN N\'02 Inspeccionar Piel\'
 WHEN  \'' . $departamento . '\' like \'118%\' THEN N\'02 Pegar.\'
 WHEN  \'' . $departamento . '\' like \'121%\' THEN N\'03 Anaquel Costura.\'
 WHEN  \'' . $departamento . '\' like \'133%\' THEN N\'03 Costura completa.\'
 WHEN  \'' . $departamento . '\' like \'136%\' THEN N\'04 Inspeccionar Costura\'
 WHEN  \'' . $departamento . '\' like \'139%\' THEN N\'139 Series Incompletas Costura\'
 WHEN  \'' . $departamento . '\' like \'145%\' THEN N\'05 Cojineria\'
 WHEN  \'' . $departamento . '\' like \'148%\' THEN N\'06 Funda Terminada\'
 WHEN  \'' . $departamento . '\' like \'151%\' THEN N\'07 Kitting\'
 WHEN  \'' . $departamento . '\' like \'157%\' THEN N\'07 Tapizar y Empaque\'
 WHEN  \'' . $departamento . '\' like \'175%\' THEN N\'08 Inspeccionar Empaque\'
 END))
 GROUP BY CardName, fecha, Name');
                    $produccion = DB::select('SELECT "CP_ProdTerminada"."orden", "CP_ProdTerminada"."Pedido", "CP_ProdTerminada"."Codigo",
 "CP_ProdTerminada"."modelo", "CP_ProdTerminada"."VS", "CP_ProdTerminada"."fecha",
 "CP_ProdTerminada"."CardName", 
 "CP_ProdTerminada"."Cantidad", "CP_ProdTerminada"."TVS"
 FROM   "CP_ProdTerminada" "CP_ProdTerminada"
 WHERE  ("CP_ProdTerminada"."fecha">=\'' . $fechaI . '\' AND
 "CP_ProdTerminada"."fecha"<=\'' . $fechaF . '\') AND
 ("CP_ProdTerminada"."Name"= (\'' . $departamento . '\')  OR "CP_ProdTerminada"."Name"= (CASE
 WHEN  \'' . $departamento . '\' like \'112%\' THEN N\'01 Corte de Piel\'
 WHEN  \'' . $departamento . '\' like \'115%\' THEN N\'02 Inspeccionar Piel\'
 WHEN  \'' . $departamento . '\' like \'118%\' THEN N\'02 Pegar.\'
 WHEN  \'' . $departamento . '\' like \'121%\' THEN N\'03 Anaquel Costura.\'
 WHEN  \'' . $departamento . '\' like \'133%\' THEN N\'03 Costura completa.\'
 WHEN  \'' . $departamento . '\' like \'136%\' THEN N\'04 Inspeccionar Costura\'
 WHEN  \'' . $departamento . '\' like \'139%\' THEN N\'139 Series Incompletas Costura\'
 WHEN  \'' . $departamento . '\' like \'145%\' THEN N\'05 Cojineria\'
 WHEN  \'' . $departamento . '\' like \'148%\' THEN N\'06 Funda Terminada\'
 WHEN  \'' . $departamento . '\' like \'151%\' THEN N\'07 Kitting\'
 WHEN  \'' . $departamento . '\' like \'157%\' THEN N\'07 Tapizar y Empaque\'
 WHEN  \'' . $departamento . '\' like \'175%\' THEN N\'08 Inspeccionar Empaque\'
 END))
 ORDER BY "CP_ProdTerminada"."CardName", "CP_ProdTerminada"."orden"');
                } else {
                    return redirect()->back()->withErrors(array('message' => 'de rango de Fechas'));
                }
                $result = json_decode(json_encode($produccion), true);
                $finalarray = [];
                foreach ($clientes as $client) {
                    $miarray = array_filter($result, function ($item) use ($client) {
                        return $item['CardName'] == $client->CardName;
                    });
                    $finalarray[$client->CardName] = $miarray;
                }
                //dd(($finalarray['CASTRO HERRERA ALEJANDRO ISAAC'][0]['orden']));
                $values = ['produccion' => $produccion, 'actividades' => $actividades, 'ultimo' => count($actividades), 'ofs' => $finalarray, 'departamento' => $departamento, 'fechaI' => $fechaI, 'fechaF' => $fechaF, 'tvs' => 0, 'cant' => 0];
                Session::flash('Ocultamodal', 1);
                //dd($produccion);
                $pdf_array = [
                    $produccion,
                    'del día ' . $fechaI . ' al ' . $fechaF,
                    $departamento
                ];
                Session::put('repP', $values);
                Session::put('pdf_array', $pdf_array);
                return view('Mod01_Produccion.produccionGeneral', $values);
                $compiled = view('Mod01_Produccion.produccionGeneral', $values)->render();
            } else {
                Session::flash('Ocultamodal', false);
                return view('Mod01_Produccion.produccionGeneral', ['actividades' => $actividades, 'ultimo' => count($actividades)]);
            }
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function ReporteProduccionPDF()
    {
        $pdf_array = Session::get('pdf_array');
        $valores = $pdf_array[0];
        $fecha = $pdf_array[1];
        $depto = $pdf_array[2];
        $pdf = \PDF::loadView('Mod01_Produccion.produccionGeneralPDF', compact('valores', 'fecha', 'depto'));
        //$pdf = new FPDF('L', 'mm', 'A4');
        $pdf->setOptions(['isPhpEnabled' => true]);
        //        Session::forget('values');
        return $pdf->stream('Siz_Reporte_Produccion ' . ' - ' .date("d/m/Y") . '.Pdf');
    }
    public function ReporteProduccionEXL()
    {
        if (Session::has('repP')) {
            $values = Session::get('repP');
            Excel::create('Siz_Reporte_Produccion_General' . ' - ' .date("d/m/Y") . '', function ($excel) use ($values) {
                $excel->sheet('Hoja 1', function ($sheet) use ($values) {
                    //$sheet->margeCells('A1:F5');     
                    $sheet->row(1, [
                        'Cliente', 'Fecha', 'Orden', 'Pedido', 'Código', 'Modelo', 'VS', 'Cantidad', 'Total VS'
                    ]);
                    //Datos    
                    $fila = 2;
                    foreach ($values['produccion'] as $produccion) {
                        //  $tvs= $tvs + $produccion->TVS;
                        //$cant = $cant + $produccion->Cantidad;
                        $sheet->row(
                            $fila,
                            [
                                $produccion->CardName,
                                substr($produccion->fecha, 0, 10),
                                $produccion->orden,
                                $produccion->Pedido,
                                $produccion->Codigo,
                                $produccion->modelo,
                                $produccion->VS,
                                $produccion->Cantidad,
                                $produccion->TVS,
                                //  $produccion->cant,
                                //$produccion->tvs,
                            ]
                        );
                        $fila++;
                    }
                });
            })->export('xlsx');
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function showModal(Request $request)
    {
        $nombre = str_replace('%20', ' ', explode('/', $request->path())[1]);
        $fechas = false;
        $unafecha = false;
        $fieldOtroNumber = '';
        $Text = '';
        $fieldText = '';
        $text_selUno = '';
        $data_selUno = [];
        $text_selDos = '';
        $data_selDos = [];
        $text_selTres = '';
        $data_selTres = [];
        $text_selCuatro = '';
        $data_selCuatro = [];
        $text_selCinco = '';
        $data_selCinco = [];
        $sizeModal = 'modal-sm';
        $data_table = '';
        $btn3 = '';
        $btnSubmitText = 'Generar';
        switch ($nombre) {
            case "HISTORIAL OP":
                $fieldOtroNumber = 'OP';
                break;
            case "MATERIALES OP":
                $fieldOtroNumber = 'OP';
                break;
            case "ENTRADAS ALMACEN":
                $fechas = true;
                break;
            case "PRODUCCION POR AREAS":
                $fechas = true;
                break;

            case "DATOS MAESTROS ARTICULOS":
                $Text = 'Para continuar, primero seleccione un artículo.';
                //$fieldText = 'Código';
                $sizeModal = 'modal-lg';
                $data_table = 'OITM.show';
                break;
            case "GENERACION ETIQUETAS":
                $Text = 'Para continuar, primero seleccione un artículo.';
                //$fieldText = 'Código';
                $sizeModal = 'modal-lg';
                $data_table = 'OITM.show';
                break;
            case "MRP":
                //Select
                        //$f->weekOfYear . '-' . $f->addWeek(2)->weekOfYear;
                // strftime('%V', strtotime( '+1 week', $f->fechaDeEjecucion));
                $text_selUno = 'Semana';
                $data_selUno = ['Producción', 'Compras'];
                $text_selDos = 'Tipo'; 
                $data_selDos = ['Completo', 'Con Orden', 'Proyección'];
                
                break;
            case "ACTUALIZAR MRP":
                $Text = "Esta a punto de actualizar el MRP. ¿Desea continuar?";
                break;
            case "TRASLADO ENTREGA":
                $almacenesOrigen = DB::table('SIZ_AlmacenesTransferencias')
                    ->select('Code as llave', 'Label as valor')
                    ->where('Dept', Auth::user()->dept)
                    ->whereIn('TrasladoDeptos', ['O', 'OD'])
                    ->get();
                $btn3 = ['btnName' => 'Omitir', 
                'route' => 'home/reporte2/TRASLADO ENTREGA'];
            
                   //    dd(array_pluck($almacenesOrigen, 'Label'));
                        
                $Text = "Elije Almacén Origen";
                $text_selTres = 'Almacén';
                $data_selTres = $almacenesOrigen;       
                break;
            case "ENTRADAS SALIDAS":
                $fechas = true;
                $text_selDos = 'Tipo de Material'; 
                $data_selDos = ['Cualquiera', 'MP', 'PT'];
                $almacenes = DB::table('OWHS')
                    ->select('WhsCode as llave', DB::raw('WhsCode + \' - \' + WhsName as valor'))
                    ->where('DataSource', 'I')
                    ->orderBy('WhsName')                    
                    ->get();                
                $text_selCuatro = 'Almacenes a Considerar';
                $data_selCuatro = $almacenes;
            break;
            
            case "CALIDAD CAPTURA DEFECTIVOS":
                $Text = "Ingresa a una OP para iniciar captura"; 
                $fieldOtroNumber = 'OP'; 
            break;

        }

        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            return view('Mod01_Produccion.modalParametros', 
            ['actividades' => $actividades, 
            'ultimo' => count($actividades), 
            'nombre' => $nombre, 
            'fieldOtroNumber' => $fieldOtroNumber, 
            'text' => $Text, 
            'fieldText' => $fieldText, 
            'fechas' => $fechas,
            'text_selUno' => $text_selUno,
            'data_selUno' => $data_selUno,
            'text_selDos' => $text_selDos,
            'data_selDos' => $data_selDos,
            'text_selTres' => $text_selTres,
            'data_selTres' => $data_selTres,
            'text_selCuatro' => $text_selCuatro,
            'data_selCuatro' => $data_selCuatro,
            'text_selCinco' => $text_selCinco,
            'data_selCinco' => $data_selCinco,
            'sizeModal' => $sizeModal,
            'data_table' => $data_table,
            'btn3' => $btn3,
            'btnSubmitText' => $btnSubmitText,
            'unafecha' => $unafecha
            ]);
        } else {
            return redirect()->route('auth/login');
        }
    }     
    public function historialOP(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();

            $op = $request->input('fieldOtroNumber');
            $info = OP::getInfoOwor($op);
            if(count($info)==0){
                return redirect()->back()->withErrors(array('message' => 'OP inválida'));
            }
            $consulta = DB::select(DB::raw("SELECT [@CP_LOGOF].U_idEmpleado, [@CP_LOGOF].U_CT ,[@PL_RUTAS].NAME,OHEM.firstName + ' ' + OHEM.lastName AS Empleado,
        DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOF].U_FechaHora)) AS FechaF ,
       [@CP_LOGOF].U_DocEntry  ,OWOR.ItemCode , OITM.ItemName ,
        SUM([@CP_LOGOF].U_Cantidad) AS U_CANTIDAD,
        sum(oitm.U_VS ) AS VS,
        (SELECT CompnyName FROM OADM ) AS CompanyName
        FROM [@CP_LOGOF] inner join [@PL_RUTAS] ON [@CP_LOGOF].U_CT = [@PL_RUTAS].Code
        left join OHEM ON [@CP_LOGOF].U_idEmpleado = OHEM.empID
        inner join OWOR ON [@CP_LOGOF].U_DocEntry = OWOR.DocNum
        inner join OITM ON OWOR.ItemCode = OITM.ItemCode
        WHERE U_DocEntry = $op
        GROUP BY [@CP_LOGOF].U_idEmpleado, [@CP_LOGOF].U_CT ,[@PL_RUTAS].NAME,
        OHEM.firstName + ' ' + OHEM.lastName ,
         DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOF].U_FechaHora)),[@CP_LOGOF].U_DocEntry
        ,OWOR.ItemCode , OITM.ItemName
        ORDER BY [@CP_LOGOF].U_CT, FechaF, Empleado"));
            Session::put('rephistorial', $consulta);

            switch ($info->Status) {
                case "P":
                    $status = 'Planificada';
                    break;
                case "R":
                    $status = 'Liberada';
                    break;
                case "L":
                    $status = 'Cerrada';
                    break;
                case "C":
                    $status = 'Cancelada';
                    break;
            }
            $data = array(
                'data' => $consulta,
                'op' => $op,
                'actividades' => $actividades,
                'ultimo' => count($actividades),
                'info' => $info,
                'status' => $status
            );
            return view('Mod01_Produccion.ReporteHistorialOP', $data);
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function historialOPXLS()
    {
        if (Session::has('rephistorial')) {
            $values = Session::get('rephistorial');
            
            Excel::create('Siz_Reporte_HistorialOP' . ' - ' .date("d/m/Y") . '', function ($excel) use ($values) {
                $excel->sheet('Hoja 1', function ($sheet) use ($values) {
                    //$sheet->margeCells('A1:F5');     
                    $sheet->row(1, [
                        'Código: ' . $values[0]->ItemCode, 'Descripción: ' . $values[0]->ItemName
                    ]);
                    $sheet->row(3, [
                        'Fecha', 'Estación', 'Empleado', 'Cantidad'
                    ]);
                    //Datos    
                    $fila = 4;
                    foreach ($values as $fil) {
                        //  $tvs= $tvs + $produccion->TVS;
                        //$cant = $cant + $produccion->Cantidad;
                        $sheet->row(
                            $fila,
                            [
                                date('d-m-Y', strtotime($fil->FechaF)),
                                $fil->NAME,
                                $fil->Empleado,
                                $fil->U_CANTIDAD
                            ]
                        );
                        $fila++;
                    }
                });
            })->export('xlsx');
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function materialesOP(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();

            $op = $request->input('fieldOtroNumber');
            $info = OP::getInfoOwor($op);
            if(count($info)==0){
                return redirect()->back()->withErrors(array('message' => 'OP inválida'));
            }
            $consulta = DB::select(DB::raw("SELECT b.DocNum AS DocNumOf,
        '*' + CAST(b.DocNum as varchar (50)) + '*' as CodBarras,
        b.ItemCode,
        c.ItemName,
        c.U_VS AS VS,
        d.CardCode,
        d.CardName,
        d.DocNum AS DocNumP,
        b.DueDate AS FechaEntrega,
        b.plannedqty,
        d.Comments as Comentario,
        b.Comments,
        c.UserText,
        f.InvntryUom,
        --f.U_estacion as CodEstacion,
         '*' + cast(f.u_estacion as varchar (3)) + '*' as BarrEstacion,
        ISNULL((SELECT Name FROM [@PL_RUTAS] WHERE Code=f.U_Estacion),'Sin Estacion') AS Estacion,
        a.ItemCode AS Codigo,
        f.ItemName as Descripcion,
        a.PlannedQty AS Cantidad,
        0 AS [Cant. Entregada],
        0 AS [Cant. Devolución],
        --g.Father,
        b.U_NoSerie,
        f.U_Metodo,
        b.U_OF as origen,
        (SELECT TOP 1 ItemName FROM OITM INNER JOIN OWOR ON OITM.ITEMCODE = OWOR.ItemCode  WHERE OWOR.DocNum = b.U_OF ) as Funda
    FROM (WOR1 a
         INNER JOIN OWOR b ON a.DocEntry=b.DocEntry
         INNER JOIN OITM c ON b.ItemCode=c.ItemCode
         INNER JOIN ORDR d ON b.OriginAbs=d.DocEntry
         INNER JOIN OITM f ON a.ItemCode=f.ItemCode)
         --inner join ITT1 g on a.ItemCode  = g.Code and b.ItemCode = g.Father
    WHERE a.DocEntry=CONVERT(Int,$op)
       AND NOT (f.InvntItem='N' AND f.SellItem='N' AND f.PrchseItem='N' AND f.AssetItem='N')
       AND f.ItemName  not like  '%Gast%'
    ORDER BY CONVERT(INT, f.U_Estacion)"));
            //dd($consulta);
            Session::put('repmateriales', $consulta);           
            Session::put('repinfo', $info);
            switch ($info->Status) {
                case "P":
                    $status = 'Planificada';
                    break;
                case "R":
                    $status = 'Liberada';
                    break;
                case "L":
                    $status = 'Cerrada';
                    break;
                case "C":
                    $status = 'Cancelada';
                    break;
            }
            $data = array(
                'data' => $consulta,
                'op' => $op,
                'actividades' => $actividades,
                'ultimo' => count($actividades),
                'db' => DB::getDatabaseName(),
                'info' => $info,
                'status' => $status
            );
            return view('Mod01_Produccion.ReporteMaterialesOP', $data);
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function materialesOPXLS()
    {
        if (Session::has('repmateriales')) {
            $values = Session::get('repmateriales');
            //dd($values);
            $info = Session::get('repinfo');
           
            Excel::create('Siz_Reporte_MaterialesOP' . ' - ' .date("d/m/Y") . '', function ($excel) use ($values, $info) {
                $excel->sheet('Hoja 1', function ($sheet) use ($values, $info) {
                    //$sheet->margeCells('A1:F5');     
                    $sheet->row(1, [
                        'Código: ' . $info->ItemCode, 'Descripción: ' . $info->ItemName
                    ]);
                    $sheet->row(3, [
                        'Fecha de Entrega', 'Estación', 'Código', 'Descripción', 'UM', 'Solicitada'
                    ]);
                    //Datos    
                    $fila = 4;
                    foreach ($values as $fil) {
                        //  $tvs= $tvs + $produccion->TVS;
                        //$cant = $cant + $produccion->Cantidad;
                        $sheet->row(
                            $fila,
                            [
                                date('d-m-Y', strtotime($fil->FechaEntrega)),
                                $fil->Estacion,
                                $fil->Codigo,
                                $fil->Descripcion,
                                $fil->InvntryUom,
                                number_format($fil->Cantidad, 2)
                            ]
                        );
                        $fila++;
                    }
                });
            })->export('xlsx');
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function backorder()
    {
        if (Auth::check()) {            
                $user = Auth::user();
                $actividades = $user->getTareas(); 
                $ultimo = count($actividades);
            return view('Mod01_Produccion.ReporteBackOrder', compact('user', 'actividades', 'ultimo'));
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function DataShowbackorder()
    {
        // $bo =  DB::table('Vw_BackOrderExcel')
        // ->select('OP', 'Pedido', 'fechapedido', 'OC', 'd_proc', 'no_serie', 'cliente', 'codigo1', 'codigo3', 'Descripcion',
		// 'Cantidad', 'VSind', 'VS', 'destacion', 'U_GRUPO', 'Secue', 'SecOT', 'SEMANA2', 'fentrega',
		// 'fechaentregapedido', 'SEMANA3', 'u_fproduccion', 'prioridad', 'comments', 'u_especial', 'modelo');        
        if (Auth::check()) {
        $rowsBo = DB::table('SIZ_View_ReporteBO');
         
        return Datatables::of($rowsBo)
        ->addColumn('Funda', function ($rowbo) {
            if(is_null ($rowbo->prefunda)){
                if(is_null ($rowbo->U_Entrega_Piel)){
                    if(is_null ($rowbo->U_Status)){
                        return '00 Por Programar';                    
                    }else{
                        $valuefunda = '00 Por Programar';
                        switch ($rowbo->U_Status) {
                            case "01":
                                $valuefunda = '00 Detenido Ventas';
                                break;
                            case "02":
                                $valuefunda = '00 Falta Inf.';
                                break;
                            case "03":
                                $valuefunda = '00 Falta Piel.';
                                break;
                            case "04":
                                $valuefunda = '00 Revision Piel';
                                break;
                            case "05":
                                $valuefunda = '00 Por Ordenar Mat.';
                                break;
                            case "06":
                                $valuefunda = '00 Proceso';
                                break;                       
                        }
                        return $valuefunda;
                    }
                }else {
                    return 'Sin liberar';
                } //end_2do_if           
            }else {
                if (($rowbo->CodFunda == 1 || $rowbo->CodFunda == 2) && (is_null ($rowbo->U_Entrega_Piel))) {
                    if(is_null ($rowbo->U_Status)){
                        return '00 Por Programar';    
                    }else{
                        $valuefunda = '00 Por Programar';
                        switch ($rowbo->U_Status) {
                            case "01":
                                $valuefunda = '00 Detenido Ventas';
                                break;
                            case "02":
                                $valuefunda = '00 Falta Inf.';
                                break;
                            case "03":
                                $valuefunda = '00 Falta Piel.';
                                break;
                            case "04":
                                $valuefunda = '00 Revision Piel';
                                break;
                            case "05":
                                $valuefunda = '00 Por Ordenar Mat.';
                                break;
                            case "06":
                                $valuefunda = '00 Proceso';
                                break;                       
                        }
                        return $valuefunda;
                    }
                }else{
                    return $rowbo->prefunda;
                }
            }//end_1er_if
        }
        )
        ->make(true);
        }else {
            return redirect()->route('auth/login');
        }
    }
    public function AjaxToSession($id) {
       
        if ($id == 'mrp') {
            Session::put( $id, Input::get('arr') );
            Session::put( 'cols', Input::get('cols'));
            Session::put('parameter', Input::get('parameter'));
        } else {
            Session::put( $id, Input::get('arr') );
        }
        
        
    }
    public function backOrderAjaxToSession(){
        //ajax nos envia los registros del datatable que el usuario filtro y los alamcenamos en la session
        //formato JSON
            Session::put('miarr',Input::get('arr')); 
    }
    public function ReporteBackOrderVentasPDF()
    {    
        if (Auth::check()) {       
        $data = json_decode((Session::get('miarr')));      
        $pdf = \PDF::loadView('Mod01_Produccion.ReporteBackOrderPDF_Ventas', compact('data'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Reporte_BackOrderV ' . ' - ' .date("d/m/Y") . '.Pdf');
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function ReporteBackOrderPlaneaPDF()
    {   
        if (Auth::check()) {    
            $data = json_decode((Session::get('miarr')));
         
            $pdf = \PDF::loadView('Mod01_Produccion.ReporteBackOrderPDF_Planea', compact('data'));
            $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Reporte_BackOrderP ' . ' - ' .date("d/m/Y") . '.Pdf');
        }else {
            return redirect()->route('auth/login');
        }
    }
    public function ReporteBackOrderXLS(){
          $path = public_path().'/assets/plantillas_excel/Mod_01/SIZ_bo.xlsx';
          $data = json_decode((Session::get('miarr')));     
           
           $excel=  Excel::load($path, function($excel) use($data){
                $excel->sheet('B.O.', function($sheet) use($data){
                
                $sheet->cell('A4', function ($cell) {
                    $cell->setValue((date("Y-m-d H:i:s")));
                });
                $sheet->cell('N4', function ($cell) {
                    $cell->setValue( date("H:i:s"));
                });
                    $index = 6;    
                foreach($data as $row) {
                    $sheet->row($index, [
                    $row->OP, $row->Pedido, $row->FechaPedido, $row->OC, $row->D_PROC, $row->NO_SERIE, $row->CLIENTE, $row->codigo1, $row->codigo3, $row->Descripcion, $row->Cant, $row->VSind, $row->VS, $row->Funda, $row->DEstacion, $row->U_Grupo, $row->Secue, $row->SecOT, $row->METAL, $row->SEMANA2, $row->fentrega, $row->fechaentregapedido, $row->SEMANA3, $row->u_fproduccion, $row->Prioridad, $row->Desv, $row->Comments, $row->U_Especial, $row->Modelo
                    ]);	
                    $index++;
                }         
            });
        })
        ->setFilename('SIZ Back Order Programado SALOTTO')
        ->export('xlsx', [ 'Set-Cookie' => 'xlscook=done; path=/' ]);

    }
    public function reporteProdxAreas(){
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();           
            $consulta = DB::select(DB::raw("           
            SELECT BIHR.Fecha,
            SUM(BIHR.VS100) AS VST100,
            SUM(BIHR.VS106) AS VST106,
            SUM(BIHR.VS109) AS VST109,
            SUM(BIHR.VS112) AS VST112,
            SUM(BIHR.VS115) AS VST115,
            SUM(BIHR.VS118) AS VST118,
            SUM(BIHR.VS121) AS VST121,
            SUM(BIHR.VS124) AS VST124,
            SUM(BIHR.VS127) AS VST127,
            SUM(BIHR.VS130) AS VST130,
            SUM(BIHR.VS133) AS VST133,
            SUM(BIHR.VS136) AS VST136,
            SUM(BIHR.VS139) AS VST139,
            SUM(BIHR.VS140) AS VST140,
            SUM(BIHR.VS142) AS VST142,
            SUM(BIHR.VS145) AS VST145,
            SUM(BIHR.VS148) AS VST148,
            SUM(BIHR.VS151) AS VST151,
            SUM(BIHR.VS154) AS VST154,
            SUM(BIHR.VS157) AS VST157,
            SUM(BIHR.VS160) AS VST160,
            SUM(BIHR.VS172) AS VST172,
            SUM(BIHR.VS175) AS VST175,
            SUM(BIHR.VS) AS VST
            FROM ( SELECT [@CP_LOGOF].U_DocEntry AS OP, [@CP_LOGOF].U_CT AS AREA, RUT.Name AS RUTA, CAST([@CP_LOGOF].U_FechaHora AS DATE) AS Fecha, CAST([@CP_LOGOF].U_FechaHora AS TIME) AS Hora, OP.ItemCode AS CODIGO, A3.ItemName AS ARTICULO, [@CP_LOGOF].U_Cantidad AS CANT, A3.U_VS * [@CP_LOGOF].U_Cantidad AS VS, CASE WHEN [@CP_LOGOF].U_CT=100 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS100, CASE WHEN [@CP_LOGOF].U_CT=106 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS106, CASE WHEN [@CP_LOGOF].U_CT=109 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS109, CASE WHEN [@CP_LOGOF].U_CT=112 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS112, CASE WHEN [@CP_LOGOF].U_CT=115 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS115, CASE WHEN [@CP_LOGOF].U_CT=118 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS118, CASE WHEN [@CP_LOGOF].U_CT=121 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS121, CASE WHEN [@CP_LOGOF].U_CT=124 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS124, CASE WHEN [@CP_LOGOF].U_CT=127 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS127, CASE WHEN [@CP_LOGOF].U_CT=130 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS130, CASE WHEN [@CP_LOGOF].U_CT=133 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS133, CASE WHEN [@CP_LOGOF].U_CT=136 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS136, CASE WHEN [@CP_LOGOF].U_CT=139 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS139, CASE WHEN [@CP_LOGOF].U_CT=140 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS140, CASE WHEN [@CP_LOGOF].U_CT=142 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS142, CASE WHEN [@CP_LOGOF].U_CT=145 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS145, CASE WHEN [@CP_LOGOF].U_CT=148 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS148, CASE WHEN [@CP_LOGOF].U_CT=151 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS151, CASE WHEN [@CP_LOGOF].U_CT=154 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS154, CASE WHEN [@CP_LOGOF].U_CT=157 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS157, CASE WHEN [@CP_LOGOF].U_CT=160 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS160, CASE WHEN [@CP_LOGOF].U_CT=172 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS172, CASE WHEN [@CP_LOGOF].U_CT=175 THEN A3.U_VS * [@CP_LOGOF].U_Cantidad ELSE 0 END AS VS175 FROM [@CP_LOGOF] INNER JOIN OWOR OP ON [@CP_LOGOF].U_DocEntry = OP.DocEntry inner join OITM A3 on OP.ItemCode=A3.ItemCode inner join [@PL_RUTAS] RUT on RUT.Code=[@CP_LOGOF].U_CT where  [@CP_LOGOF].U_FechaHora 
            BETWEEN '".date('d-m-Y', strtotime(Input::get('FechIn'))).' 00:00'."' and '".date('d-m-Y', strtotime(Input::get('FechaFa'))).' 23:59:59'."' ) BIHR Group by BIHR.Fecha order by BIHR.Fecha
            "));
           
            $consulta3 =  DB::select(DB::raw("
            Select BIHR.Fecha,
            SUM(BIHR.VS400) as VST400, 
            SUM(BIHR.VS403) as VST403, 
            SUM(BIHR.VS406) as VST406,
			SUM(BIHR.VS409) as VST409, 
            SUM(BIHR.VS415) as VST415, 
            SUM(BIHR.VS418) as VST418 
            from ( Select  [@CP_LOGOF].U_DocEntry as OP, [@CP_LOGOF].U_CT as AREA, RUT.Name AS RUTA, CAST([@CP_LOGOF].U_FechaHora as DATE) as Fecha, CAST([@CP_LOGOF].U_FechaHora as TIME) as Hora, OP.ItemCode as CODIGO, A3.ItemName as ARTICULO, [@CP_LOGOF].U_Cantidad as CANT, A3.U_VS as VS,
			 CASE When [@CP_LOGOF].U_CT=400 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS400, 
			 CASE When [@CP_LOGOF].U_CT=403 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS403, 
			 CASE When [@CP_LOGOF].U_CT=406 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS406, 
			 CASE When [@CP_LOGOF].U_CT=409 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS409, 
			 CASE When [@CP_LOGOF].U_CT=415 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS415, 
			 CASE When [@CP_LOGOF].U_CT=418 then A3.U_VS * [@CP_LOGOF].U_Cantidad else 0 end AS VS418 
			 from [@CP_LOGOF] inner join OWOR OP on [@CP_LOGOF].U_DocEntry = OP.DocEntry inner join OITM A3 on OP.ItemCode=A3.ItemCode inner join [@PL_RUTAS] RUT on RUT.Code=[@CP_LOGOF].U_CT where  CAST([@CP_LOGOF].U_FechaHora AS DATE) 
             BETWEEN '".date('d-m-Y', strtotime(Input::get('FechIn'))).' 00:00'."' and '".date('d-m-Y', strtotime(Input::get('FechaFa'))).' 23:59:59'."' ) BIHR Group by BIHR.Fecha order by BIHR.Fecha
            "));
            $consulta4 =  DB::select(DB::raw( "
           Select   CASE WHEN TRA_CAS.FECHA IS NULL THEN c.FECHA_CONSUMO ELSE TRA_CAS.FECHA END AS Fecha,
                    CASE WHEN SUM(TRA_CAS.ENT_CARP) IS NULL THEN 0 ELSE SUM(TRA_CAS.ENT_CARP) END AS S_CARP, 
                    CASE WHEN SUM(TRA_CAS.ENT_TRAS) IS NULL THEN 0 ELSE SUM(TRA_CAS.ENT_TRAS) END AS S_TRAS, 
                    CASE WHEN SUM(TRA_CAS.ENT_KITT) IS NULL THEN 0 ELSE SUM(TRA_CAS.ENT_KITT) END AS S_KITT, 
                    CASE WHEN SUM(TRA_CAS.ENT_TAPI) IS NULL THEN 0 ELSE SUM(TRA_CAS.ENT_TAPI) END AS S_TAPI, 
                    CASE WHEN c.Consumo IS NULL THEN 0 ELSE c.Consumo END AS Consumo,
                    CASE WHEN SUM(TRA_CAS.VST) IS NULL THEN 0 ELSE SUM(TRA_CAS.VST) END AS S_VST 
            From (Select    CAST(OWTR.DocDate AS DATE) AS FECHA, OWTR.DocEntry AS T_NUM, WTR1.ItemCode AS CODE, OITM.ItemName AS DESCRIPCION, OITM.U_TipoMat AS TIPO, WTR1.Quantity AS CANT, OITM.U_VS AS VS, (WTR1.Quantity * OITM.U_VS) AS VST, OWTR.Filler AS ALM_SALE, WTR1.WhsCode AS ALM_ENTR, OUSR.U_NAME AS REALIZO, CASE When OWTR.Filler = 'APT-PA' and  WTR1.WhsCode = 'APG-PA' then (WTR1.Quantity * OITM.U_VS) else 0 end AS ENT_CARP, CASE When OWTR.Filler = 'APG-PA' and  WTR1.WhsCode = 'AMP-TR' then (WTR1.Quantity * OITM.U_VS) else 0 end AS ENT_TRAS, CASE When OWTR.Filler = 'AMP-TR' and  WTR1.WhsCode = 'APP-ST' then (WTR1.Quantity * OITM.U_VS) else 0 end AS ENT_KITT, CASE When OWTR.Filler = 'APP-ST' and  WTR1.WhsCode = 'APG-ST' then (WTR1.Quantity * OITM.U_VS) else 0 end AS ENT_TAPI from OWTR Inner Join WTR1 on OWTR.DocEntry = WTR1.DocEntry Inner Join OITM on WTR1.ItemCode = OITM.ItemCode Inner join OUSR on OWTR.UserSign=OUSR.USERID  Where  OITM.U_TipoMat = 'CA' and CAST(OWTR.DocDate AS DATE) 
            BETWEEN '" . date('d-m-Y', strtotime(Input::get('FechIn'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime(Input::get('FechaFa'))) . ' 23:59:59' . "' 
             group by OWTR.DocDate, OWTR.DocEntry, WTR1.ItemCode, OITM.ItemName ,OITM.U_TipoMat, WTR1.Quantity, OITM.U_VS, OWTR.Filler, WTR1.WhsCode, OUSR.U_NAME) TRA_CAS
            FULL OUTER JOIN 
            (Select CASE WHEN SUM(OITM.U_VS) IS NULL THEN 0 ELSE SUM(OITM.U_VS) END Consumo, CAST(DocDate AS DATE) AS FECHA_CONSUMO from OINM inner join OITM on OINM.ItemCode=OITM.ItemCode where U_TipoMat = 'CA' and OINM.Warehouse = 'APG-ST' and (OINM.JrnlMemo like 'Emis%' or  OINM.JrnlMemo like 'Reci%') and CAST(DocDate AS DATE) 
            BETWEEN '" . date('d-m-Y', strtotime(Input::get('FechIn'))) . ' 00:00' . "' and '" . date('d-m-Y', strtotime(Input::get('FechaFa'))) . ' 23:59:59' . "' 
             group by DocDate) c on c.FECHA_CONSUMO = TRA_CAS.fecha
            group by TRA_CAS.FECHA, c.FECHA_CONSUMO, c.Consumo Order by FECHA
            "));
            if (strtotime(Input::get('FechaFa')) == strtotime(date("Y-m-d"))){
                $consulta2 = DB::select(DB::raw("           
                SELECT T2.SVS FROM ( SELECT Code FROM [@PL_RUTAS] WHERE U_Estatus = 'A' AND Code <= '175' ) T1 LEFT JOIN ( SELECT CodFunda, SUM( VS) as SVS FROM SIZ_View_ReporteBO WHERE u_status= '06' and CodFunda is not null Group By CodFunda ) T2 ON T1.Code = T2.CodFunda Order By Code 
                "));
                
                $consulta5 =  DB::select(DB::raw("
                Select SUM(AL_CAS.EX_CARP) AS T_CARP, 
                SUM(AL_CAS.EX_ALMA) AS T_ALMA, 
                SUM(AL_CAS.EX_CAMI) AS T_CAMI, 
                SUM(AL_CAS.EX_KITT) AS T_KITT, 
                SUM(AL_CAS.EX_TAPI) AS T_TAPIZ, 
                SUM(AL_CAS.EX_GUAD) AS T_GUAD, 
                SUM(AL_CAS.VST) AS T_TOTAL 
                From ( Select   OITW.ItemCode AS CODE, OITM.ItemName AS DESCRIPCION, OITM.U_TipoMat AS TIPO, OITW.OnHand AS CANT, OITM.U_VS AS VS, (OITW.OnHand * OITM.U_VS) AS VST, OITW.WhsCode AS ALMACEN, CASE When OITW.WhsCode = 'APT-PA' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_CARP, CASE When OITW.WhsCode = 'APG-PA' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_ALMA, CASE When OITW.WhsCode = 'AMP-TR' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_CAMI, CASE When OITW.WhsCode = 'APP-ST' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_KITT, 
                CASE When OITW.WhsCode = 'APG-ST' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_TAPI, CASE When OITW.WhsCode = 'AMG-FE' then (OITW.OnHand * OITM.U_VS) else 0 end AS EX_GUAD From OITW Inner Join OITM on OITW.ItemCode = OITM.ItemCode Where  OITM.U_TipoMat = 'CA' and OITW.OnHand > 0 ) AL_CAS
                "));

                $consulta7 = DB::select(DB::raw("
                SELECT SUM(PorIniciar * U_VS) as P400, SUM(Habilitado * U_VS) as H403,
                   SUM(Armado * U_VS) as A406, SUM(Tapado * U_VS) as T409, 
                   SUM(Preparado * U_VS) as PR415, SUM(Inspeccion * U_VS) as I418
                   FROM SIZ_View_ReporteBOCasco where proceso > 0 or PorIniciar > 0
                "));
            }else{
                $consulta2 = '';
                $consulta5 = '';
                $consulta7 = '';
            }                                     
     
            $data = array('data' => $consulta, 'data2' => $consulta2, 'data3' => $consulta3, 'data4' => $consulta4, 'data5' => $consulta5, 'data7' => $consulta7, 'actividades' => $actividades, 'ultimo' => count($actividades), 'db' => DB::getDatabaseName(), 'fi' => Input::get('FechIn'), 'ff' => Input::get('FechaFa') );
            $dataSesion = array(                
                'data' => $consulta,
                'data2' => $consulta2,         
                'data3' => $consulta3,         
                'data4' => $consulta4,         
                'data5' => $consulta5,                        
                                   
                'data7' => $consulta7,                        
                'fi' => Input::get('FechIn'),
                'ff' => Input::get('FechaFa')
            );
        
            Session::put('repprodxareas', $dataSesion);
            return view('Mod01_Produccion.reporteProdxAreas', $data);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function reporteProdxAreasPDF()
    {   
        if (Auth::check()) {    
        $repprodxareas = Session::get('repprodxareas');
                     
        $data = $repprodxareas['data'];
        $data2 = $repprodxareas['data2'];
        $data3 = $repprodxareas['data3'];
        $data4 = $repprodxareas['data4'];
        $data5 = $repprodxareas['data5'];
      
        $data7 = $repprodxareas['data7'];
        $fi = $repprodxareas['fi'];
        $ff = $repprodxareas['ff'];
       
        $pdf = \PDF::loadView('Mod01_Produccion.reporteProdxAreasPDF', compact('data', 'data2', 'data3', 'data4', 'data5', 'data7', 'fi', 'ff'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Reporte_ProdxAreas ' . ' - ' .date("d/m/Y") . '.Pdf');
        }else {
            return redirect()->route('auth/login');
        }
    }

    public function produccionxareasXLS()
    {
        if (Auth::check()) {
            $path = public_path() . '/assets/plantillas_excel/Mod_01/SIZ_resumenxareas.xlsx';
            $repprodxareas = Session::get('repprodxareas');    
            Excel::load($path, function($excel) use($repprodxareas){   
                $excel->sheet('Produccion', function ($sheet) use ($repprodxareas) { 
                    $count = 0;
                    $count2 = 0;
                    $data = $repprodxareas['data'];
                    $data2 = $repprodxareas['data2'];
                    $data3 = $repprodxareas['data3'];
                    $data4 = $repprodxareas['data4'];
                    $data5 = $repprodxareas['data5'];
                    $data7 = $repprodxareas['data7'];
                    $fi = $repprodxareas['fi'];
                    $ff = $repprodxareas['ff'];

                    $sheet->cell('C4', function ($cell) {//fecha
                        $cell->setValue(\AppHelper::instance()->getHumanDate(date("Y-m-d H:i:s")));
                    });
                    $sheet->cell('T4', function ($cell) {//hora
                        $cell->setValue(date("H:i:s"));
                    });
                    $sheet->cell('C5', function ($cell) use ($fi) {
                        $cell->setValue(\AppHelper::instance()->getHumanDate($fi));
                    });
                    $sheet->cell('L5', function ($cell) use ($ff){
                        $cell->setValue(\AppHelper::instance()->getHumanDate($ff));
                    });
                    
                    /*
                    INICIA REPORTE DE FUNDAS
                    */
                    $fila = 7;
                    foreach ($data as $rep) {
                        $sheet->row(
                            $fila,
                            [
                                \AppHelper::instance()->getHumanDate($rep->Fecha),
                                number_format($rep->VST100,2), number_format($rep->VST106,2), number_format($rep->VST109,2), number_format($rep->VST112,2), number_format($rep->VST115,2), number_format($rep->VST118,2), number_format($rep->VST121,2), number_format($rep->VST124,2), number_format($rep->VST127,2), number_format($rep->VST130,2), number_format($rep->VST133,2), number_format($rep->VST136,2), number_format($rep->VST139,2), number_format($rep->VST140,2), number_format($rep->VST142,2), number_format($rep->VST145,2), number_format($rep->VST148,2), number_format($rep->VST151,2), number_format($rep->VST154,2), number_format($rep->VST157,2), number_format($rep->VST160,2), number_format($rep->VST172,2), number_format($rep->VST175,2),
                            ]
                        );
                        $fila++;
                    }
                    $count = $fila-1; 
                    $sheet->row($fila++, [
                        'SUMA DE FUNDAS','=SUM(B7:B'.$count.')','=SUM(C7:C'.$count.')','=SUM(D7:D'.$count.')','=SUM(E7:E'.$count.')','=SUM(F7:F'.$count.')',
                        '=SUM(G7:G'.$count.')','=SUM(H7:H'.$count.')','=SUM(I7:I'.$count.')','=SUM(J7:J'.$count.')','=SUM(K7:K'.$count.')','=SUM(L7:L'.$count.')',
                        '=SUM(M7:M'.$count.')','=SUM(N7:N'.$count.')','=SUM(O7:O'.$count.')','=SUM(P7:P'.$count.')','=SUM(Q7:Q'.$count.')','=SUM(R7:R'.$count.')',
                        '=SUM(S7:S'.$count.')','=SUM(T7:T'.$count.')','=SUM(U7:U'.$count.')','=SUM(V7:V'.$count.')','=SUM(W7:W'.$count.')','=SUM(X7:X'.$count.')' ]);
                   
                    $sheet->cell('A7:A'.($count+1), function ($cells) { 
                        $cells
                        ->setFontColor('#ffffff')
                        ->setBackground('#333333');
                    });
                    $sheet->cell('B'.($count + 1).':X'.($count + 1), function ($cells) { 
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#78866B');
                    });
                        if (strtotime($ff) == strtotime(date("Y-m-d"))){             
                            $sheet->row($fila++,
                                \AppHelper::instance()->rebuiltArrayString('INVENTARIO', $data2, 'SVS')
                            );
                            $sheet->cell('A'.($fila - 1), function ($cells) { 
                                $cells
                                    ->setFontColor('#ffffff')
                                    ->setBackground('#333333');
                            });
                            $sheet->cell('B' . ($fila - 1) . ':X' . ($fila - 1), function ($cells) { 
                                $cells
                                    ->setFontColor('#ffffff')
                                    ->setBackground('#2352A0');
                            });
                  }
                  /*
                  INICIA REPORTE DE CASCO
                  */
                    $fila++;
                    $sheet->row($fila++, [
                        'REPORTE DE CASCOS', 'Planeación',	'Habilitado',	'Armado',	'Tapado',	'Pegado',	'Inspección Casco',
                    ]);
                    $sheet->cell('A' . ($fila - 1) . ':G' . ($fila - 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#333333');
                    });
                    $fila_ini = $fila;
                    foreach ($data3 as $rep) {
                        $sheet->row(
                            $fila,
                            [
                                \AppHelper::instance()->getHumanDate($rep->Fecha),
                                number_format($rep->VST400,2), number_format($rep->VST403,2), number_format($rep->VST406,2),
                                 number_format($rep->VST409,2), number_format($rep->VST415,2), number_format($rep->VST418,2),                                 
                            ]
                        );
                        $fila++;
                    }
                    $count = $fila-1; 
                    $sheet->row($fila++, [
                        'SUMA DE CASCOS','=SUM(B'.$fila_ini.':B'.$count.')','=SUM(C'.$fila_ini.':C'.$count.')','=SUM(D'.$fila_ini.':D'.$count.')','=SUM(E'.$fila_ini.':E'.$count.')','=SUM(F'.$fila_ini.':F'.$count.')',
                        '=SUM(G'.$fila_ini.':G'.$count.')',]);
                    $sheet->cell( 'A' . ($fila_ini) . ':A' . ($count + 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#333333');
                    });
                    $sheet->cell('B' . ($count + 1) . ':G' . ($count + 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#78866B');
                    });

                        if (strtotime($ff) == strtotime(date("Y-m-d"))){ 
                            foreach ($data7 as $rep) {            
                                    $sheet->row($fila++,                                    
                                    ['INVENTARIO', $rep->P400, $rep->H403, $rep->A406, $rep->T409, $rep->PR415, $rep->I418]                                    
                                    );
                                }
                        $sheet->cell('A' . ($fila - 1), function ($cells) {
                            $cells
                                ->setFontColor('#ffffff')
                                ->setBackground('#333333');
                        });
                        $sheet->cell('B' . ($fila - 1) . ':G' . ($fila - 1), function ($cells) {
                            $cells
                                ->setFontColor('#ffffff')
                                ->setBackground('#2352A0');
                        });
                          }
                        $fila++;
                   
                    $sheet->row($fila++, [
                        'MOVIMIENTOS DE CASCOS', 'Aduana Carpinteria',	'Almacén',	'Camión',	'Kitting',	'Tapiz',	'Ajuste',
                    ]);
                    $sheet->cell('A' . ($fila - 1) . ':G' . ($fila - 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#333333');
                    });
                    $fila_ini2 = $fila;
                    foreach ($data4 as $rep) {
                        $sheet->row(
                            $fila,
                            [
                                \AppHelper::instance()->getHumanDate($rep->Fecha),
                                number_format($rep->S_CARP,2), number_format($rep->S_TRAS,2), number_format($rep->S_KITT,2),
                                number_format($rep->S_TAPI,2), number_format($rep->Consumo,2),
                                number_format((($rep->S_TAPI + $rep->S_KITT + $rep->S_TRAS + $rep->S_CARP)*-1) + $rep->S_VST  ,2),                                 
                            ]
                        );
                        $fila++;                   
                    }
                    $count2 = $fila-1; 
                    $sheet->row($fila++, [
                        'SUMA DE CASCOS','=SUM(B'.$fila_ini2.':B'.$count2.')','=SUM(C'.$fila_ini2.':C'.$count2.')','=SUM(D'.$fila_ini2.':D'.$count2.')','=SUM(E'.$fila_ini2.':E'.$count2.')', '=SUM(F'.$fila_ini2.':F'.$count2.')',
                        '=SUM(G'.$fila_ini2.':G'.$count2.')',]);
                    $sheet->cell('A' . ($fila_ini2) . ':A' . ($count2 + 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#333333');
                    });
                    $sheet->cell('B' . ($count2 + 1) . ':G' . ($count2 + 1), function ($cells) {
                        $cells
                            ->setFontColor('#ffffff')
                            ->setBackground('#78866B');
                    });
                 if (strtotime($ff) == strtotime(date("Y-m-d"))){                    
                    foreach ($data5 as $rep) {
                        $sheet->row(
                            $fila,
                            [
                                'INVENTARIO DE CASCO',
                                number_format($rep->T_CARP,2), number_format($rep->T_ALMA,2), number_format($rep->T_CAMI,2),
                                 number_format($rep->T_KITT,2), number_format($rep->T_TAPIZ,2),                                  
                            ]
                        );
                        $fila++;
                    }
                        $sheet->cell('A' . ($fila - 1), function ($cells) {
                            $cells
                                ->setFontColor('#ffffff')
                                ->setBackground('#333333');
                        });
                        $sheet->cell('B' . ($fila - 1) . ':G' . ($fila - 1), function ($cells) {
                            $cells
                                ->setFontColor('#ffffff')
                                ->setBackground('#2352A0');
                        });  
                }
                });
               // $from = "A1"; // or any value
               // $to = "X6"; // or any value
               // $excel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );                
               // $excel->getActiveSheet()->setAutoFilter('A5:V5');
            })
                ->setFilename('SIZ Resumen de Producción por Areas')
                ->export('xlsx');
            }else {
                return redirect()->route('auth/login');
            }
    }
    public function backorderCasco()
    {                               
        if (Auth::check()) {            
                $user = Auth::user();
                $actividades = $user->getTareas(); 
                $ultimo = count($actividades);
            return view('Mod01_Produccion.ReporteBackOrderCasco', compact('user', 'actividades', 'ultimo'));
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function DataShowbackorderCasco()
    {             
        if (Auth::check()) {
        $rowsBo = DB::table('SIZ_View_ReporteBOCasco')->where('proceso', '>', 0)->orWhere('poriniciar', '>', 0);
         
        return Datatables::of($rowsBo)
        ->addColumn('diasproc', function ($rowbo) {
             $cDate = Carbon::parse($rowbo->U_Entrega_Piel);
             return $cDate->diffInDays();
        })
        ->addColumn('totalproc', function ($rowbo) {            
            return ($rowbo->PorIniciar + $rowbo->Habilitado + $rowbo->Armado + $rowbo->Preparado + $rowbo->Inspeccion);
        })
        ->addColumn('totalvs', function ($rowbo) {            
            return number_format(($rowbo->U_VS * 
            ($rowbo->PorIniciar + $rowbo->Habilitado + $rowbo->Armado + $rowbo->Preparado + $rowbo->Inspeccion)),2);
        })
        ->addColumn('uvs', function ($rowbo) {            
            return number_format($rowbo->U_VS,2);
        })
        ->addColumn('xiniciar', function ($rowbo) {            
            return  number_format($rowbo->PorIniciar,0);         
        })
        ->make(true);
        }else {
            return redirect()->route('auth/login');
        }
    }
    public function backOrderAjaxToSession_(){
        //ajax nos envia los registros del datatable que el usuario filtro y los alamcenamos en la session
        //formato JSON
        Session::put('miarr',Input::get('arr'));   
    }
    public function ReporteBackOrderCascoPDF()
    {    
        if (Auth::check()) {       
        $data = json_decode(stripslashes(Session::get('bocasco')));      
        
        $totales_pzas = array('Proceso'=>0, 'PorIniciar'=>0, 'Habilitado'=>0, 'Armado'=>0, 'Tapado'=>0, 'Preparado'=>0, 'Inspeccion'=>0, 'totalvs'=>0);   
        $totales_vs = array('Proceso'=>0, 'PorIniciar'=>0, 'Habilitado'=>0, 'Armado'=>0, 'Tapado'=>0, 'Preparado'=>0, 'Inspeccion'=>0, 'totalvs'=>0);   
        foreach($data as $item){            
                $totales_vs['Proceso'] += $item->Proceso * $item->uvs;
                $totales_vs['PorIniciar'] += $item->PorIniciar * $item->uvs; 
                $totales_vs['Habilitado'] += $item->Habilitado * $item->uvs;
                $totales_vs['Armado'] += $item->Armado * $item->uvs;
                $totales_vs['Tapado'] += $item->Tapado * $item->uvs;
                $totales_vs['Preparado'] += $item->Preparado * $item->uvs;
                $totales_vs['Inspeccion'] += $item->Inspeccion * $item->uvs;
                               
                $totales_pzas['Proceso'] += $item->Proceso; 
                $totales_pzas['PorIniciar'] += $item->PorIniciar; 
                $totales_pzas['Habilitado'] += $item->Habilitado; 
                $totales_pzas['Armado'] += $item->Armado; 
                $totales_pzas['Tapado'] += $item->Tapado; 
                $totales_pzas['Preparado'] += $item->Preparado; 
                $totales_pzas['Inspeccion'] += $item->Inspeccion; 
                $totales_pzas['totalvs'] += $item->totalvs;           
        }         
        $pdf = \PDF::loadView('Mod01_Produccion.ReporteBackOrderCascoPDF', compact('data', 'totales_pzas', 'totales_vs'));
        $pdf->setPaper('Letter','landscape')->setOptions(['isPhpEnabled'=>true]);             
        return $pdf->stream('Siz_Reporte_BackOrderCasco ' . ' - ' .date("d/m/Y") . '.Pdf');
        }else {
            return redirect()->route('auth/login');
        }
    }

    public function opcionesCatalogo(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            //se obtienen y guardan los departamentos elegidos
            $departamentos = "'".implode("', '", $request->get('data_selCuatro')). "'";
            $request->session()->put('selecciondeptos', $departamentos);
            $opciones = array();
                $opciones['1']='Empleados Activos';
                if ( count($request->get('data_selCuatro')) == 1) {
                    $opciones['2']='Empleados Activos CON FOTO';                  
                }
                $opciones['3']='Empleados No Activos';
                $opciones['4']='Todos los Empleados';
            //se manda a llamar el modal que muestre las opciones
           return redirect()->route('home/009 CATALOGO DE EMPLEADOS', ['opciones'=> $opciones]);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function R009(){
        if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();  
       // dd(Input::get('data_selCinco'));
        $data = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),  
            'option' => Input::get('data_selCinco')
        );
        return view('Mod01_Produccion.R009', $data);
    } else {
        return redirect()->route('auth/login');
    } 
    }
    public function DataShow009(Request $request)
    {
        $deptos = $request->session()->get('selecciondeptos');
        $opcionEmpleado = $request->get('empleados');
        $emp_activo = '1';
        $foto = false;
        switch ($opcionEmpleado) {
            case '1':
               
                break;
            case '2':
               $foto = true;
                break;
            case '3':
                $emp_activo ='0';
                break;
            case '4':
               $emp_activo ='%';
                break;
        }
            $data = DB::connection('miprueba')->select( "
            Select DEP_DeptoId, EMP_CodigoEmpleado AS CODIGO, EMP_Nombre + ' ' + 
            EMP_PrimerApellido + ' ' + EMP_SegundoApellido AS NOMBRE,
            ISNULL (DEP_Nombre, 'SIN DEPTO') AS DEPARTAMENTO,
            ISNULL (PUE_NombrePuesto, 'SIN PUESTO...') AS PUESTO, 
            EMP_FechaIngreso as FEC_INGR, EMP_FechaEgreso as FEC_BAJA, 
            EMP_Activo as ESTATUS, ISNULL (EMP_Fotografia, 'SIN FOTO') as FOTO 
            from Empleados left join Departamentos on DEP_DeptoId = EMP_DEP_DeptoId 
            left join Puestos on PUE_PuestoId = EMP_PUE_PuestoId
            where EMP_Eliminado = 0 
            and EMP_Activo = ?
            and ISNULL(DEP_Nombre, 'SIN DEPTO') in (".$deptos.")
            Order by DEPARTAMENTO, PUESTO, NOMBRE 
            ", [$emp_activo]); 
       // dd($data);
        $request->session()->put('opcionEmpleado', $opcionEmpleado);
        return Datatables::of(collect($data))
        ->addColumn('DTFOTO', function ($consulta) use ($foto){
            if ($foto) {
                if (\Storage::disk('nas')->has(''.$consulta->FOTO)) {
                     return 'data:image/jpeg;base64,'. base64_encode(\Storage::disk('nas')->get(''.$consulta->FOTO));
                } else {
                    return 'data:image/jpeg;base64,'. base64_encode(\Storage::disk('nas')->get('SIN_IMAGEN.jpg'));
                }

            } else {
                return $consulta->FOTO;
            }
        })
        ->addColumn('ESTADO', function ($consulta) {
            if ($consulta->ESTATUS == 1) {
                return 'ACTIVO';
            } else {
                return 'NO ACTIVO';
            }
        })
        ->make(true);
    }
}
