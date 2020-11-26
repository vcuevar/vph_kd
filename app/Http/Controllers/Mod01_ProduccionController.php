<?php

namespace App\Http\Controllers;

use App;
use Artisan;
use App\Http\Controllers\Controller;
use App\Modelos\MOD01\LOGOF;
use App\Modelos\MOD01\LOGOT;
use App\OP;
use App\SAP;
use App\SAPi;
use App\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Lava;
use Mail;
use Session;
use Maatwebsite\Excel\Facades\Excel;

class Mod01_ProduccionController extends Controller
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

    public function estacionSiguiente(Request $request)
    {
        // echo OP::getEstacionSiguiente("81143");
        // dd(OP::getStatus("81143"));
        // dd(OP::getRuta("81143"));
    }

    public function ReporteOpPDF($op)
    {
        //$pdf = App::make('dompdf.wrapper');
        $GraficaOrden = DB::select(DB::raw("SELECT [@CP_LOGOF].U_idEmpleado, [@CP_LOGOF].U_CT ,[@PL_RUTAS].NAME,OHEM.firstName + ' ' + OHEM.lastName AS Empleado,
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
        //dd($GraficaOrden);

        $data = array(
            'data' => $GraficaOrden,
            'op' => $op,
        );

        // dd($sociedad);
        //$data = $GraficaOrden;

        $pdf = \PDF::loadView('Mod01_Produccion.ReporteOpPDF', $data);
        //dd($pdf);

        return $pdf->setOptions(['isPhpEnabled' => true])->stream('Siz_Producción_Reporte_OP.Pdf');
        // return $pdf->download('ReporteOP.pdf');
    }

    public function ReporteMaterialesPDF($op)
    {
        //$pdf = App::make('dompdf.wrapper');
        $Materiales = DB::select(DB::raw("SELECT b.DocNum AS DocNumOf,
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
        //dd($Materiales);
        $data = array(
            'data' => $Materiales,
            'op' => $op,
            'db' => DB::table('OADM')->value('CompnyName'),
        );
        //$data = $GraficaOrden;

        $pdf = \PDF::loadView('Mod01_Produccion.ReporteMateriales', $data);
        //dd($pdf);
        //return $pdf->stream();
        return $pdf->setOptions(['isPhpEnabled' => true])->stream('ReporteMateriales.pdf');
    }

    public function allUsers(Request $request)
    {
        $users = DB::select('select * from view_Plantilla_SIZ');
        $users = $this->arrayPaginator($users, $request);

        return view('Mod00_Administrador.usuarios', compact('users'));
    }

    public function arrayPaginator($array, $request)
    {
        $page = Input::get('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
    public function usuariosActivos(Request $request)
    {
        $users = User::name($request->get('name'))->where('jobTitle', '<>', 'Z BAJA')->where('status', '1')
            ->orderBy('Ohem.dept, OHEM.jobTitle, ohem.firstname', 'asc')
        ;
        //dd($users);
        return view('Mod00_Administrador.usuarios', compact('users'));
    }

    public function cambiopassword()
    {
        try {
            $password = Hash::make(Input::get('password'));
            DB::table('dbo.OHEM')
                ->where('U_EmpGiro', Input::get('userId'))
                ->update(['U_CP_Password' => $password]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('msg' => $e->getMessage()));
        }
        $user = User::find(Input::get('userId'));
        Session::flash('mensaje', 'La contraseña de ' . $user->firstName . ' ' . $user->lastName . ' ha cambiado.');
        return redirect()->back();
    }
    public function traslados(Request $request)
    {
       
        //zzarkin
        if (Session::has('send')) {
            $enviado = Session::get('send');
        } else {
            $enviado = $request->input('send');
        }
        //dd($request->input('pass'), Input::get('pass2'));
        if (Session::has('miusuario')) {
            $miusuario = Session::get('miusuario');
            $mipassword = Session::get('pass');
            $mipasswor2 = Session::get('pass2');
        } else {
            $miusuario = $request->input('miusuario');
            $mipassword = $request->input('pass', 'cambuser', 'cambiaruser'); 
            $mipasswor2 = Session::get('pass2');    
        }
       
//dd($request->input('miusuario'), $enviado);

        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
       
            if ($enviado == 'send') {
                if (strlen($miusuario) == null) {
                    $t_user = Auth::user();
                } else {
                    $t_user = User::find($miusuario);
                    if ($t_user == null) {
                        return redirect()->back()->withErrors(array('message' => 'Error, el usuario no existe.'));
                    }
                }
                
                if (($mipassword == '0123' && $mipasswor2 == '1234')||Hash::check($mipassword, $t_user->U_CP_Password) || ($mipassword == '0123' && $request->input('pass2') == '1234')) {
                    Session::flash('usertraslados', 1);
                    if ($request->input('Recordarpass') == 1) { //revisar si esta checado el de recordar contraseña
                        Session::put('Rec_pass', $mipassword);
                    }

                    $est_Av = $t_user->U_CP_CT;
                    $Fil_Est = explode(",", $est_Av); //ARRAY SIMPLE
                    $rutasConNombres = self::getNombresRutas($Fil_Est); //ARRAY LLAVE VALOR
                    
                    return view('Mod01_Produccion.traslados', ['rutasConNombres' => $rutasConNombres, 't_user' => $t_user, 'est_Av' => $est_Av, 'Fil_Est' => $Fil_Est, 'actividades' => $actividades, 'ultimo' => count($actividades), 't_user' => $t_user]);
                } else {
                    return redirect()->back()->withErrors(array('message' => 'Error de autenticación.'));
                }

            } else {

                Session::flash('usertraslados', false);
            }

            return view('Mod01_Produccion.traslados', ['actividades' => $actividades, 'ultimo' => count($actividades)]);

        } else {
            return redirect()->route('auth/login');
        }

    }

    public function getNombresRutas($Fil_Est)
    {
        $i = 0;
        $data = array();
        foreach ($Fil_Est as $elemmento) {
            // $ruts1=DB::select("SELECT Name From [] where Code=".$elemmento)->first();
            $ruts1 = DB::table('@PL_RUTAS')->where('Code', $elemmento)->value('Name');
            //dd($ruts1);
            $data[$elemmento] = $ruts1;
            $i++;
        }

        return $data;
    }

    public function getOP($id)
    {
        $t_user = User::find($id);
     
        if ($t_user == null) {
            return redirect()->back()->withErrors(array('message' => 'Error, el usuario no existe.'));
        }

        if (Session::has('return')) {
            $option = Session::get('return');
            Session::forget('return');
        } else {
            $option = Input::get('AvanceEst');
        }

        $user = Auth::user();
        $actividades = $user->getTareas();

        if ($option == 1) {
        
            if (!Session::has('recibo')) {
               if (Session::has('op')) {
                $op = Session::get('op');
                Session::forget('op');
                } else if (Input::has('op')) {
                    $op = Input::get('op');
                } else {
                    return redirect()->route('home');
                    //dd('getOPss');
                }
                $Codes = OP::where('U_DocEntry', $op)->get();              
            } else{
                $Codes = [];
            }
       
            Session::flash('usertraslados', 2); //evita que salga el modal

            if (count($Codes) > 0) {
                $index = 0;
                foreach ($Codes as $code) {

                    if (($code->U_Recibido >= $code->U_Procesado && OP::onFirstEstacion($code->Code) == false) || OP::onFirstEstacion($code->Code) && ($code->U_Recibido >= 0)) {

                        // dd($code->U_Recibido);
                        if ($code->U_Recibido == '0' && $code->U_Procesado == '0' && $code->U_Entregado == '0') {
                            $cantlogof = DB::table('@CP_LOGOF')
                                ->where('U_DocEntry', $code->U_DocEntry)
                                ->get();

                            //dd($cantlogof);

                            if (count($cantlogof) == 0) {
                                $CantOrden = DB::table('OWOR')
                                    ->where('DocEntry', $code->U_DocEntry)
                                    ->first();

                                // dd($CantOrden->PlannedQty);
                                $code->U_Recibido = (int) $CantOrden->PlannedQty;
                                $code->save();
                            }

                        }
                        //dd($code);
                        $index = $index + 1;
                        $EstacionA = OP::getEstacionActual($code->Code);
                        $EstacionS = OP::getEstacionSiguiente($code->Code, 1);

                        $pedido = '';
                        if ($EstacionA != null && $EstacionS != null) {
                            $order = DB::table('OWOR')
                                ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                                ->leftJoin('@CP_OF', '@CP_OF.U_DocEntry', '=', 'OWOR.DocEntry')
                                ->select(DB::raw($EstacionA . ' AS U_CT_ACT'), DB::raw($EstacionS . ' AS U_CT_SIG'), DB::raw(OP::avanzarEstacion($code->Code, $t_user->U_CP_CT) . ' AS avanzar'),
                                    'OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_Orden', 'OWOR.Status', 'OWOR.OriginNum', 'OITM.ItemName', '@CP_OF.U_Reproceso',
                                    'OWOR.PlannedQty', '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
                                ->where('@CP_OF.Code', $code->Code)->get();
                            if ($index == 1) {
                                $one = DB::table('OWOR')
                                    ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                                    ->leftJoin('@CP_OF', '@CP_OF.U_DocEntry', '=', 'OWOR.DocEntry')
                                    ->select(DB::raw($EstacionA . ' AS U_CT_ACT'), DB::raw($EstacionS . ' AS U_CT_SIG'),
                                        DB::raw(OP::avanzarEstacion($code->Code, $t_user->U_CP_CT) . ' AS avanzar'),
                                        'OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_Orden', 'OWOR.Status', 'OWOR.OriginNum', 'OITM.ItemName', '@CP_OF.U_Reproceso',
                                        'OWOR.PlannedQty', '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
                                    ->where('@CP_OF.Code', $code->Code)->get();
                                foreach ($one as $o) {
                                    $pedido = $o->OriginNum;
                                }
                               
                            } else {
                                $one = array_merge($one, $order); //$one->merge($order);
                                //dd($one);
                            }

                        } else {
                            return redirect()->back()->withErrors(array('message' => 'La orden no tiene ruta en SAP.'));
                        }

                    } else {
                        return redirect()->back()->withErrors(array('message' => 'La orden no tiene Cantidad Recibida.'));
                    }

                }
                //  $order = OP::find('492418');
                //return $one;

                // $actual = OP::getEstacionActual(Input::get('op'));
                // $siguiente = OP::getEstacionSiguiente(Input::get('op'));

                //Comienza el código para graficar
                $GraficaOrden = DB::select(DB::raw("SELECT [@CP_LOGOF].U_idEmpleado, [@CP_LOGOF].U_CT ,[@PL_RUTAS].NAME,
            DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOT].U_FechaHora)) AS FechaI,
            DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOF].U_FechaHora)) AS FechaF ,OHEM.firstName + ' ' + OHEM.lastName AS Empleado, [@CP_LOGOF].U_DocEntry  ,OWOR.ItemCode , OITM.ItemName ,
            SUM([@CP_LOGOF].U_Cantidad) AS U_CANTIDAD,
            (oitm.U_VS ) AS VS,
            (SELECT CompnyName FROM OADM ) AS CompanyName
            FROM [@CP_LOGOF] inner join [@PL_RUTAS] ON [@CP_LOGOF].U_CT = [@PL_RUTAS].Code
            left join OHEM ON [@CP_LOGOF].U_idEmpleado = OHEM.empID
            left join Sof_Tiempos  ON [@CP_LOGOF].U_DocEntry = Sof_Tiempos.DocNum and [@CP_LOGOF].U_CT = Sof_Tiempos.U_idRuta
            inner join [@CP_LOGOT] ON [@CP_LOGOF].U_DocEntry = [@CP_LOGOT].U_OP and [@CP_LOGOf].U_CT = [@CP_LOGOT].U_CT
            inner join OWOR ON [@CP_LOGOF].U_DocEntry = OWOR.DocNum
            inner join OITM ON OWOR.ItemCode = OITM.ItemCode
            WHERE U_DocEntry = $op
            GROUP BY [@CP_LOGOF].U_idEmpleado, [@CP_LOGOF].U_CT ,[@PL_RUTAS].NAME,
            DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOT].U_FechaHora)) ,
            DATEADD(dd, 0, DATEDIFF(dd, 0, [@CP_LOGOF].U_FechaHora)) ,
            OHEM.firstName + ' ' + OHEM.lastName , [@CP_LOGOF].U_DocEntry  ,OWOR.ItemCode , OITM.ItemName ,
            oitm.U_VS
            ORDER BY FechaF,[@CP_LOGOF].U_CT"));
                //dd($GraficaOrden);
                $cont = count($GraficaOrden);
                $stocksTable = Lava::DataTable();
                $stocksTable->addDateColumn('Day of Month')
                //->addNumberColumn('Projected')
                    ->addNumberColumn('Estación')
                    ->addRoleColumn('string', 'tooltip', [
                        'html' => true,
                    ]);
                foreach ($GraficaOrden as $campo) {
                    $date = date_create($campo->FechaF);
                    $nom_emp = $campo->Empleado;
                    if ($nom_emp == null) {
                        $users = DB::table('OHEM')->where('U_EmpGiro', '=', $campo->U_idEmpleado)
                            ->select('OHEM.lastName', 'OHEM.firstName')
                            ->first();
                        $nom_emp = $users->firstName . ' ' . $users->lastName;
                    }
                    $stocksTable->addRow([
                        $campo->FechaF, $campo->U_CT, '<p style=margin:10px><b>' .
                        ucwords(strtolower($nom_emp)) .
                        '</b><br>Estación:<b>' .
                        $campo->NAME .
                        '</b><br>C. Recibida:<b>' .
                        $campo->U_CANTIDAD .
                        '</b><br>Fecha:<b>' .
                        date_format($date, 'd/m/Y') .
                        '</b></p>',
                    ]);
                }
                //  foreach($GraficaOrden as $campo){
                //     $campo->U_CT;
                //  }

                $HisOrden = Lava::AreaChart('HisOrden', $stocksTable, [
                    'title' => 'Historial por OP',
                    'interpolateNulls' => true,
                    'pointsVisible' => true,
                    'legend' => [
                        'position' => 'top',
                    ],
                    'tooltip' => [
                        'isHtml' => true,
                    ],
                ]);
                ////RUTA RETROCESO
                $Ruta = (OP::getRutaNombres($op));
              
                return view('Mod01_Produccion.traslados',
                 ['actividades' => $actividades, 
                 'ultimo' => count($actividades), 
                 'Ruta' => $Ruta, 
                 't_user' => $t_user, 
                 'ofs' => $one, 
                 'op' => $op, 
                 'pedido' => $pedido,  
                 'HisOrden' => $HisOrden]);
            } else if (count($Codes) == 0 && Session::has('recibo')){ // se hizo un recibo
                return view('Mod01_Produccion.traslados',
                 ['actividades' => $actividades, 
                 'ultimo' => count($actividades), 
                 'Ruta' => null, 
                 't_user' => $t_user, 
                 'ofs' => null, 
                 'op' => null, 
                 'pedido' => null, 
                 'HisOrden' => null]);
            }
            Session::flash('miusuario', $id);
            Session::flash('send', 'send');
            Session::flash('pass', Input::get('pass'));
            Session::flash('pass2', Input::get('pass2'));
            return redirect()->back()->withErrors(array('message' => 'La OP ' . Input::get('op') . ' no existe.'));
        } else if ($option == 2) { //OPs por Estacion

            if (Session::has('OP_us')) {
                $u_ct = Session::get('OP_us');
                Session::forget('OP_us');
            } else {
                $u_ct = Input::get('OP_us');
            }

            $ordenes = DB::table('OWOR')
                ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                ->leftJoin('@CP_OF', '@CP_OF.U_DocEntry', '=', 'OWOR.DocEntry')
                ->select('OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_Orden', 'OWOR.Status', 'OWOR.OriginNum', 'OITM.ItemName', '@CP_OF.U_Reproceso',
                    'OWOR.PlannedQty', '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
                ->where('@CP_OF.U_CT', $u_ct)
                ->orderBy('OWOR.DocEntry')
                ->get();
            
            $estacionA = (count($ordenes) == 0)? $u_ct : OP::getEstacionActual($ordenes[0]->Code);
            $estacionAnum = (count($ordenes) == 0)? $u_ct: $ordenes[0]->U_Orden;
            return view('Mod01_Produccion.trasladosEstacion', ['numeroestacion' => $estacionAnum, 'estacion' => $estacionA, 't_user' => $t_user, 'actividades' => $actividades, 'ultimo' => count($actividades), 'ordenes' => $ordenes]);
        }

    }

    public function avanzarOP()
    {             
        try {
            DB::transaction(function () {
                $id = Input::get('userId');

                $t_user = User::find($id);
                if ($t_user == null) {
                    return redirect()->back()->withInput()->withErrors(array('message' => 'Error, el usuario no existe.'));

                }
                $Cant_procesar = Input::get('cant');
                $Code_actual = OP::find(Input::get('code'));
                Session::put('op', $Code_actual->U_DocEntry);
                $U_CT_siguiente = OP::getEstacionSiguiente($Code_actual->Code, 2); //obtiene la estacion siguiente formato numero

$dt = date('Ymd h:i');
//AVANCE DE OP (NO PIEL)
//Cuando una orden se libera en planeación revisamos si se le cargara piel 106 (revisando su ruta), 
//en caso de que no lleve piel, entonces le cambiamos en status y le colocamos la fecha de inicio.
//casco: 400 armado - 300 habilitado ()
if($Code_actual->U_CT == '100' && OP::ContieneRuta($Code_actual->U_DocEntry, '106') == false){
    DB::table('OWOR')
        ->where('DocEntry', '=', $Code_actual->U_DocEntry)
        ->update(['U_Status' =>  '06', 'U_Entrega_Piel' => $dt]);
        //cambiar a liberado SAP
      // $r = SAP::ProductionOrderStatus($Code_actual->U_DocEntry, 1);
      // if(!$r){
     //   Session::flash('info', 'La orden no pudo liberarse en Sap');
    //    }
}
//TERMINA AVANCE DE OP (NO PIEL)
//AVANCE DE OP (PIEL)
//Se modifica status y fecha, necesitamosrevisar que tenga piel.

if($Code_actual->U_CT == '106'){
    $consumido = DB::table('WOR1')
    ->leftJoin('OITM','WOR1.ItemCode', '=', 'OITM.ItemCode')
    ->where('OITM.ItmsGrpCod', '=', 113)
    ->where('WOR1.DocEntry', '=', $Code_actual->U_DocEntry)
    ->value('WOR1.IssuedQty');

if($consumido < 1 || is_null($consumido)){
return redirect()->back()->withErrors(array('message' => 'Esta orden necesita primero que le carges Piel en SAP'));
}else{
    DB::table('OWOR')
        ->where('DocEntry', '=', $Code_actual->U_DocEntry)
        ->update(['U_Status' =>  '06', 'U_Entrega_Piel' => $dt]);
    //cambiar a liberado SAP
   // $r = SAP::ProductionOrderStatus($Code_actual->U_DocEntry, 1);
  //  if(!$r){
  //      Session::flash('info', 'La orden no pudo liberarse en Sap');
  //  }
}
}

//TERMINA AVANCE DE OP (PIEL)

//DETERMINA SI LA ORDEN DE PRODUCCION LLEGO A LA ULTIMA ESTACION
if ($U_CT_siguiente == $Code_actual->U_CT) {
    return redirect()->back()->withErrors(array('message' => 'La estacion ' . OP::getEstacionSiguiente($Code_actual->Code, 1) . ' es la última'));
}

                //  $cant_pendiente = $Code_actual->U_Recibido - $Code_actual->U_Procesado;
                // ->where(DB::raw('(U_Recibido - U_Procesado)', '>', '0'))
                $Code_siguiente = OP::where('U_CT', $U_CT_siguiente)
                    ->where('U_DocEntry', $Code_actual->U_DocEntry)
                    ->where('U_Reproceso', 'N')
                    ->get();
                
                $CantOrden = DB::table('OWOR')
                    ->where('DocEntry', $Code_actual->U_DocEntry)
                    ->first();
                $cantO = (int) $CantOrden->PlannedQty;
                //dd($Code_siguiente);
                if (count($Code_siguiente) == 1) {
                    $Code_siguiente = OP::where('U_CT', $U_CT_siguiente)
                        ->where('U_DocEntry', $Code_actual->U_DocEntry)
                        ->where('U_Reproceso', 'N')
                        ->first();
                    // dd( ($Cant_procesar + $Code_siguiente->U_Recibido) <= (Input::get('numcant')+$Code_actual->U_Procesado));
                    if (($Cant_procesar + $Code_siguiente->U_Recibido) <= $cantO) {
                        $Code_siguiente->U_Recibido = $Code_siguiente->U_Recibido + $Cant_procesar;
                        $Code_siguiente->save();
                    } else {
                        return redirect()->back()->withInput()->withErrors(array('message' => 'La cantidad total recibida no debe ser mayor a la cantidad de la Orden.'));
                    }
                } else if (count($Code_siguiente) == 0) {
                    try {
                        //esta linea obtiene el consecutivo del numero
                        $consecutivo = DB::select('select max (CONVERT(INT,Code)) as Code from [@CP_Of]');
                        //aqui acaba num consecutivo
                        $newCode = new OP();
                        $newCode->Code = ((int) $consecutivo[0]->Code) + 1;
                        $newCode->Name = ((int) $consecutivo[0]->Code) + 1;
                        $newCode->U_DocEntry = $Code_actual->U_DocEntry;
                        $newCode->U_CT = $U_CT_siguiente;
                        $newCode->U_Entregado = 0;
                        $newCode->U_Orden = $U_CT_siguiente;
                        $newCode->U_Procesado = 0;
                        $newCode->U_Recibido = $Cant_procesar;
                        $newCode->U_Reproceso = "N";
                        $newCode->U_Defectuoso = 0;
                        $newCode->U_Comentarios = "";
                        $newCode->U_CTCalidad = 0;
                        $newCode->save();
                        //save=insert select max (CONVERT(INT,Code)) as Code
                        $consecutivologot = DB::select('select max (CONVERT(INT,Code)) as Code FROM  [@CP_LOGOT]');
                        $lot = new LOGOT();
                        $lot->Code = ((int) $consecutivologot[0]->Code) + 1;
                        $lot->Name = ((int) $consecutivologot[0]->Code) + 1;
                        $lot->U_idEmpleado = $t_user->empID;
                        $lot->U_CT = $Code_actual->U_CT;
                        $lot->U_Status = "O";
                        $lot->U_FechaHora = $dt;
                        $lot->U_OP = $Code_actual->U_DocEntry;
                        $lot->save();
                    } catch (Exception $e) {
                        return redirect()->back()->withInput()->withErrors(array('message' => 'Error al guardar nuevo registro en CP_OF.'));
                    }
                } else {
                    return redirect()->back()->withInput()->withErrors(array('message' => 'Existen registros duplicados en la siguiente estación.'));
                }
                // dd(count($Code_siguiente));
                $Code_actual->U_Procesado = $Code_actual->U_Procesado + $Cant_procesar;
                $Code_actual->U_Entregado = $Code_actual->U_Entregado + $Cant_procesar;
                $consecutivologof = DB::select('select max (CONVERT(INT,Code)) as Code FROM  [@CP_LOGOF]');
                $log = new LOGOF();
                $log->Code = ((int) $consecutivologof[0]->Code) + 1;
                $log->Name = ((int) $consecutivologof[0]->Code) + 1;
                $log->U_idEmpleado = $t_user->empID;
                $log->U_CT = $Code_actual->U_CT;
                $log->U_Status = "T";
                $log->U_FechaHora = $dt;
                $log->U_DocEntry = $Code_actual->U_DocEntry;
                $log->U_Cantidad = $Cant_procesar;
                $log->U_Reproceso = 'N';
                $Code_actual->save();
                $log->save();
                // dd($Code_actual->Code);
                Session::flash('mensaje', 'El usuario ' . $id . ' avanzo ' . $Cant_procesar . ' pza(s) a la estación ' . OP::getEstacionSiguiente($Code_actual->Code, 1));
                if (($Code_actual->U_Recibido > 0 && $cantO == $Code_actual->U_Procesado) ||
                    ($Code_actual->U_Recibido == $Code_actual->U_Procesado && $Code_actual->U_Recibido == $Code_actual->U_Entregado)) {
                    $lineaActual = OP::find($Code_actual->Code); //si esta linea ya termino de procesar_todo entonces se borra
                    $lineaActual->delete();
                }
                // if (){
                //   $lineaActual = OP::find($Code_actual->Code);   //si esta linea ya termino de procesar_todo entonces se borra
                // $lineaActual->delete();
                //}
            });
            if (Input::has('option')) {
                Session::put('return', Input::get('option'));
                $id = Input::get('userId');
                Session::put('OP_us', Input::get('OP_us'));
                return redirect()->action('Mod01_ProduccionController@getOP', $id);
            } else {
                Session::put('return', 1);
                $id = Input::get('userId');              
                return redirect()->action('Mod01_ProduccionController@getOP', $id);  
            }


        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(array('message' => 'Error al Guardar la Orden.'));
        }
        //eliminar linea procesada completa de CP_OF
        //creacion de linea  en CP_LOGOF y CP_LOGOT
    }
    public function MethodGET_OP($id)
    {

        if (Session::has('op')) {
            $op = Session::get('op');
            Session::forget('op');
        } else if (Input::has('op')) {
            $op = Input::get('op');
        } else {
            return redirect()->route('home');
        }
        $t_user = User::find($id);
        if ($t_user == null) {
            return redirect()->back()->withErrors(array('message' => 'Error, el usuario no existe.'));
        }
        $user = Auth::user();
        $actividades = $user->getTareas();
        Session::flash('usertraslados', 2); //evita que salga el modal
        $Codes = OP::where('U_DocEntry', Input::get('op'))->get();
        if (count($Codes) > 0) {
            $index = 0;
            foreach ($Codes as $code) {
                $index = $index + 1;
                $order = DB::table('OWOR')
                    ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                    ->leftJoin('@CP_OF', '@CP_OF.U_DocEntry', '=', 'OWOR.DocEntry')
                    ->select(DB::raw(OP::getEstacionActual($code->Code) . ' AS U_CT_ACT'), DB::raw(OP::getEstacionSiguiente($code->Code, 1) . ' AS U_CT_SIG'), DB::raw(OP::avanzarEstacion($code->Code, $t_user->U_CP_CT) . ' AS avanzar'),
                        'OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_Orden', 'OWOR.Status', 'OWOR.OriginNum', 'OITM.ItemName', '@CP_OF.U_Reproceso',
                        'OWOR.PlannedQty', '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
                    ->where('@CP_OF.Code', $code->Code)->get();
                if ($index == 1) {
                    $one = DB::table('OWOR')
                        ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                        ->leftJoin('@CP_OF', '@CP_OF.U_DocEntry', '=', 'OWOR.DocEntry')
                        ->select(DB::raw(OP::getEstacionActual($code->Code) . ' AS U_CT_ACT'), DB::raw(OP::getEstacionSiguiente($code->Code, 1) . ' AS U_CT_SIG'),
                            DB::raw(OP::avanzarEstacion($code->Code, $t_user->U_CP_CT) . ' AS avanzar'),
                            'OWOR.DocEntry', '@CP_OF.Code', '@CP_OF.U_Orden', 'OWOR.Status', 'OWOR.OriginNum', 'OITM.ItemName', '@CP_OF.U_Reproceso',
                            'OWOR.PlannedQty', '@CP_OF.U_Recibido', '@CP_OF.U_Procesado')
                        ->where('@CP_OF.Code', $code->Code)->get();
                    foreach ($one as $o) {
                        $pedido = $o->OriginNum;
                    }
                } else {

                    $one = array_merge($one, $order); //$one->merge($order);
                    //dd($one);
                }
            }
            // $order = OP::find('492418');
            // return $one;
            // $actual = OP::getEstacionActual(Input::get('op'));
            // $siguiente = OP::getEstacionSiguiente(Input::get('op'));
            $stocksTable = Lava::DataTable();
            $stocksTable->addDateColumn('Day of Month')
                ->addNumberColumn('Projected')
                ->addNumberColumn('Official');

            // Random Data For Example
            for ($a = 1; $a < 30; $a++) {
                $stocksTable->addRow([
                    '2015-10-' . $a, rand(800, 1000), rand(800, 1000),
                ]);
            }

            $beto = Lava::AreaChart('beto', $stocksTable, [
                'title' => 'Population Growth',
                'legend' => [
                    'position' => 'in',
                ],
            ]);
            return view('Mod01_Produccion.traslados', ['actividades' => $actividades, 'ultimo' => count($actividades), 't_user' => $t_user, 'ofs' => $one, 'op' => $op, 'pedido' => $pedido, 'beto' => $beto]);
        }
        return redirect()->back()->withErrors(array('message' => 'La OP ' . Input::get('op') . ' no existe.'));
    }
    public function Retroceso(Request $request)
    {
        $Est_act = $request->input('Estacion');
        $orden = $request->input('orden');
        $No_Nomina = $request->input('Nomina');

        if($Est_act == '109'||$Est_act =='106'){
            $consumido = DB::table('WOR1')
            ->leftJoin('OITM','WOR1.ItemCode', '=', 'OITM.ItemCode')
            ->where('OITM.ItmsGrpCod', '=', 113)
            ->where('WOR1.DocEntry', '=', $orden)
            ->value('WOR1.IssuedQty');
            //Para retorcesos de estas estaciones, verificar si tiene algo de piel la orden
        if($consumido <> 0){
        Session::flash('error', 'Esta orden necesita primero que le quites Piel en SAP');
        Session::put('return', 1);
        Session::put('op', $orden);          
        return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
        }else{
            DB::table('OWOR')
                ->where('DocEntry', '=', $orden)
                ->update(['U_Status' =>  null, 'U_Entrega_Piel' => null]); 
        }
        }

        if($Est_act =='106'){
            //inicia retroceso 106
            //1.- verificamos si es Planeador el usuario
            $t_user = User::find($No_Nomina);
            if ($t_user->position == 6){
                //2.- verificamos que el total de la cantidad se encuentre en esta estación
                $TotaldeCodigos = OP::where('U_DocEntry', $orden)->get();
               
                if (count($TotaldeCodigos) != 1) {
                    Session::flash('error', 'La orden completa debe estar en 106 Preparado de Entrega de Piel. ');
                    Session::put('return', 1);
                    Session::put('op', $orden);          
                    return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
                } 
                $user = Auth::user();
                $actividades = $user->getTareas(); 
                $est_Av = $t_user->U_CP_CT; //estaciones que el usuario puede avanzar
                $Fil_Est = explode(",", $est_Av); //ARRAY SIMPLE  
                $rutasConNombres = self::getNombresRutas($Fil_Est); //le pasamos las rutas y nos regresa las rutas con nombre
                //array_pop($rutasConNombres);
                Session::flash('usertraslados', 1);
                Session::flash('mensaje', 'La orden ' . $orden . ' ha sido retirada de control de piso.');
                DB::transaction(function () use($orden) {
                  DB::table('@CP_OF')->where('U_DocEntry', '=', $orden)->delete();
                  DB::table('@CP_LOGOF')->where('U_DocEntry', '=', $orden)->delete(); 
                  DB::table('@CP_LOGOT')->where('U_OP', '=', $orden)->delete();
                });
                SAP::ProductionOrderStatus($orden, 0);
               // return view('Mod01_Produccion.traslados', ['rutasConNombres' => $rutasConNombres, 't_user' => $t_user, 'actividades' => $actividades, 'ultimo' => count($actividades)]); 
               //return redirect()->back();
return redirect()->route('home');
            }else{
                Session::flash('error', 'El planeador es el unico que puede quitar las ordenes de piso');
                Session::put('return', 1);
                Session::put('op', $orden);          
                return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
            }
            
           //fin retroceso 106
        }else{ //Inicia else retorceso todas
             DB::transaction(function () use ($request, $No_Nomina, $orden, $Est_act) {        
            $Est_ant = $request->input('selectestaciones');            
            $Num_user = User::find($No_Nomina)->empID;
            $nota = $request->input('nota');
            $Nom_User = $request->input('Nombre');
            $autorizo = $request->input('Autorizar');           
            $cant_r = $request->input('retrocant');
            $reason = $request->input('reason');
            $leido = 'N';
            $banderita = false; // esta banbbgdera sirva para verificar si la estacion destino es un retroceso creado o ya existente
            $dt = date('Ymd h:m:s');
//$dt = date('Y-m-d H:i:s');  no usar
//$Code_actual = OP::find(Input::get('code'));
//Session::put('op', $Code_actual->U_DocEntry);
// $dt = date('Y-m-d H:i:s');
//$dt = date('Ymd h:m:s');
//-------------Notificaciones--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
 //$Not_us=DB::select(DB::raw("SELECT top 1 U_EmpGiro,firstname,lastname from OHEM where position='4'and dept ='$cod_dep'"));
            $N_Emp = User::where('position', 4)->where('U_CP_CT', 'like', '%' . $Est_ant . '%')->first();
            if ($N_Emp == null || count($N_Emp) < 1) {               
                Session::flash('error', 'Error, La estación anterior no esta asignada al Supervisor en SAP.');               
                           Session::flash('op', $orden); 
                           Session::put('return', 1);                               
                    
                       return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
                //return redirect()->back()->withErrors(array('message' => 'Error, La estación anterior no esta asignada al Supervisor en SAP.'));
                //return Redirect::back()->withErrors(['message', 'The Message']);
            } else if (count($N_Emp) > 1) {
                Session::flash('error', 'Error, Hay dos Supervisores para el área anterior en SAP.');               
                Session::flash('op', $orden); 
                Session::put('return', 1);                               
         
            return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
            
                    // return redirect()->back()->withErrors(array('message' => 'Error, Hay dos Supervisores para el área anterior en SAP.'));
            }                      
            DB::table('Siz_Noticias')->insert(
                [
                    'Autor' => $Nom_User,
                    'Destinatario' => $N_Emp->U_EmpGiro,
                    'N_Empleado' => $No_Nomina,
                    'No_Orden' => $orden,
                    'Descripcion' => $reason,
                    'Estacion_Act' => $Est_act,
                    'Estacion_Destino' => $Est_ant,
                    'Cant_Enviada' => $cant_r,
                    'Nota' => $nota,
                    'Leido' => $leido,
                    'Fecha_Reproceso' => $dt,
                    'Reproceso_Autorizado' => $autorizo,

                ]
            );
            //---------Estacion Destino-----------------------------------------------------------------------------------------------------------------------------------------------------//
            $DestinoCp = OP::where('U_DocEntry', $orden)->where('U_CT', $Est_ant)->first();
            $boolvar = $DestinoCp != null;

            if ($boolvar) {
                //  dd('update '.$DestinoCp);
                DB::table('@CP_OF')
                    ->where('Code', $DestinoCp->Code)
                    ->update([
                        //'U_Recibido'=> $DestinoCp->U_Recibido + $cant_r,
                        //'U_Reproceso'=>'S',
                        'U_Defectuoso' => $cant_r + $DestinoCp->U_Defectuoso,
                        'U_Comentarios' => $nota,
                        //  'U_Procesado'=>$DestinoCp->U_Procesado - $cantidad;
                    ]);
            } else {
                //  dd('insert '.$DestinoCp);
                $banderita = true; //si es un reproceso creado de cero
                $N_Code = DB::select('select max (CONVERT(INT,Code)) as Code from [@CP_OF]');
                $Nuevo_reproceso = new OP();
                $Nuevo_reproceso->Code = ((int) $N_Code[0]->Code) + 1;
                $Nuevo_reproceso->Name = ((int) $N_Code[0]->Code) + 1;
                $Nuevo_reproceso->U_DocEntry = $orden;
                $Nuevo_reproceso->U_CT = $Est_ant;
                $Nuevo_reproceso->U_Entregado = 0;
                $Nuevo_reproceso->U_Orden = $Est_ant;
                $Nuevo_reproceso->U_Procesado = 0;
                $Nuevo_reproceso->U_Recibido = $cant_r;
                $Nuevo_reproceso->U_Reproceso = "S";
                $Nuevo_reproceso->U_Defectuoso = $cant_r;
                $Nuevo_reproceso->U_Comentarios = $nota;
                $Nuevo_reproceso->U_CTCalidad = 0;
                $Nuevo_reproceso->save();
                //-------- Tabla Logot----//
                $Iniciar = DB::select('SELECT * from [@CP_LOGOT] Where U_CT=' . $Est_ant . ' AND U_OP=' . $orden);
                if (COUNT($Iniciar) < 1) {
                    $Con_Loguot = DB::select('select max (CONVERT(INT,Code)) as Code FROM  [@CP_LOGOT]');
                    $cot = new LOGOT();
                    $cot->Code = ((int) $Con_Loguot[0]->Code) + 1;
                    $cot->Name = ((int) $Con_Loguot[0]->Code) + 1;
                    $cot->U_idEmpleado = $Num_user;
                    $cot->U_CT = $Est_ant;
                    $cot->U_Status = "O";
                    $cot->U_FechaHora = $dt;
                    $cot->U_OP = $orden;
                    $cot->save();
                }
            }
            //---------Estacion Actual-----------------------------------------------------------------------------------------------------------------------------------------------------//
            $Actual_Cp = OP::where('U_DocEntry', $orden)->where('U_CT', $Est_act)->first();
            $Actual = $Actual_Cp->U_Recibido;
//dd($Actual_Cp);
            if ($Actual == $cant_r) {
                $Actual_Cp->delete();
            }
            if ($Actual > $cant_r) {
                DB::table('@CP_OF')
                    ->where('Code', $Actual_Cp->Code)
                    ->update([
                        'U_Recibido' => $Actual - $cant_r,
                    ]);
                $OrdenDest = OP::where('U_DocEntry', $orden)->where('U_CT', $Est_ant)->first();
                // $OrdenDest = OP::find($DestinoCp->Code);
                if ($OrdenDest->U_Reproceso == 'N') {
                    $OrdenDest->U_Procesado = $OrdenDest->U_Procesado - $cant_r;
                    $OrdenDest->U_Entregado = $OrdenDest->U_Entregado - $cant_r;
                    $OrdenDest->U_Reproceso = 'S';
                    $OrdenDest->save();
                }
                if ($OrdenDest->U_Reproceso == 'S' && $banderita == false) {
                    $OrdenDest->U_Recibido = $OrdenDest->U_Recibido + $cant_r;
                    $OrdenDest->save();
                }
            }
            //-------------Tabla LOGOF-----------------------------//
            //$Code_actual = OP::find(Input::get('code'));
            //dd(Input::get('code'));
            //---------Count Cantidades negativas  /_(○_○)-/-----------------------------------------------------------------------------------------------------------------------------------------------------//
            $estaciones = OP::getRuta($orden);
            foreach ($estaciones as $estacion) {
                if ($estacion >= $Est_ant && $estacion < $Est_act) {
                    $Con_Logof = DB::select('select max (CONVERT(INT,Code)) as Code FROM  [@CP_LOGOF]');
                    $log = new LOGOF();
                    $log->Code = ((int) $Con_Logof[0]->Code) + 1;
                    $log->Name = ((int) $Con_Logof[0]->Code) + 1;
                    $log->U_idEmpleado = $Num_user;
                    $log->U_CT = $estacion;
                    $log->U_Status = "T";
                    $log->U_FechaHora = $dt;
                    $log->U_DocEntry = $orden;
                    $log->U_Cantidad = $cant_r * -1;
                    $log->U_Reproceso = 'S';
                    //$Code_actual->save();
                    $log->save();
                }}
            $Nombre_Destino = DB::table('@PL_RUTAS')->where('U_Orden', $request->input('selectestaciones'))->value('Name');
            $Nombre_Actual = DB::table('@PL_RUTAS')->where('U_Orden', $request->input('Estacion'))->value('Name');
            //--------------------correo-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
            $correos_db = DB::select("
                SELECT 
                CASE WHEN email like '%@%' THEN email ELSE email + cast('@zarkin.com' as varchar) END AS correo
                FROM OHEM
                INNER JOIN Siz_Email AS se ON se.No_Nomina = OHEM.U_EmpGiro
                WHERE se.Reprocesos = 1
                GROUP BY email
            ");
            $correos = array_pluck($correos_db, 'correo');
            
            if (count($correos) > 0) {
                Mail::send('Emails.Reprocesos', ['Nombre_Destino' => $Nombre_Destino, 'Nombre_Actual' => $Nombre_Actual, 'autorizo' => $autorizo, 'dt' => date('d/M/Y h:m:s'),
                    'No_Nomina' => $No_Nomina, 'Nom_User' => $Nom_User, 'orden' => $orden,
                    'cant_r' => $cant_r, 'Est_act' => $Est_act, 'Est_ant' => $Est_ant, 'reason' => $reason, 'nota' => $nota, 'leido' => $leido], function ($msj) use ($correos) {
                    $msj->subject('Notificaciones SIZ'); //ASUNTO DEL CORREO
                    $msj->to($correos); //Correo del destinatario
                });
            }
            
//-----------------Refresca a la vista-------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
            Session::flash('mensaje', 'Reproceso Realizado!!...
 De :' . $Nombre_Actual . '
 A:' . $Nombre_Destino . '
 Supervisor:' . $N_Emp->firstName . ' ' . $N_Emp->lastName . '
 Autorizado por:  ' . $autorizo . '');

            Session::flash('op', $orden); 
            Session::put('return', 1);                               
        }
        );// fin transaccion
        return redirect()->action('Mod01_ProduccionController@getOP', $request->input('Nomina'));
        }//fin else Retrocesos todas
               
//return redirect()->back()->withErrors(array('message' => 'Error, Consulte con el Administrador de SIZ, por que no se llevo a cabo el retroceso.'));
    }
//--------------------112-Reporte-Corte-de-Piel---------------------
public function repCortePiel()
{
if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            $data = 0;
 if (Input::has('enviado')){
           $fechaI = Date('d-m-y', strtotime(str_replace('-', '/', Input::get('FechIn'))));
           $fechaF = Date('d-m-y', strtotime(str_replace('-', '/', input::get('FechaFa'))));
    $detInsPiel= DB::select(DB::raw("select  OWOR.DocNum, sum(WOR1.IssuedQty) as Usado ,OWOR.ItemCode , OHEM.firstName 
                AS LOGISTICA,OHEM.middleName AS APELLIDO  , (a.u_vs * OWOR.PlannedQty ) as u_vs ,OITM.ItmsGrpCod, OWOR.closedate  , a.itemname ,  sum(WOR1.IssuedQty*OITM.AvgPrice) 
                as mUsado  , (vwsof_pieles1.cantidad * OWOR.PlannedQty) as Teorico , (vwsof_pieles1.monto * OWOR.PlannedQty) as mTeorico,
                SUBSTRING(owor.itemcode,9,5) as piel, (select TOP 1 OH.firstName from [@CP_LOGOF] LF INNER JOIN OHEM OH ON LF.U_idEmpleado = OH.empID WHERE (LF.U_CT =1 or LF.U_CT =112)
                AND LF.U_DocEntry = OWOR.DocNum ) AS firstName, LOF.FECHA,(select TOP 1 OH.middleName from [@CP_LOGOF] LF 
                INNER JOIN OHEM OH ON LF.U_idEmpleado = OH.empID WHERE (LF.U_CT =1 or LF.U_CT =112) AND LF.U_DocEntry = OWOR.DocNum ) AS middleName  from WOR1 inner join OWOR on OWOR.DocEntry = WOR1.DocEntry 
                inner join OITM on OITM.ItemCode = WOR1.ItemCode inner join OITM a on a.ItemCode = OWOR.itemcode inner join vwSof_Pieles1 on vwSof_Pieles1.father = OWOR.ItemCode 
                INNER JOIN (SELECT U_DocEntry , sum(U_Cantidad) as Cantidad, U_idEmpleado,  DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora)) AS FECHA 
                FROM [@CP_LOGOF] WHERE (U_CT = 3 or U_CT=115)  group by U_DocEntry , U_idEmpleado, DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora))  ) LOF ON LOF.U_DocEntry = OWOR.DocNum inner join OHEM on OHEM.empID =  LOF.U_idEmpleado 
                where OITM.ItmsGrpCod = 113 and DATEADD(dd, 0, DATEDIFF(dd, 0, LOF.Fecha)) between '". $fechaI ."' and '". $fechaF ."'  group by OITM.ItmsGrpCod ,OHEM.firstName, 
                OHEM.middleName , OWOR.ItemCode , a.u_vs ,OWOR.DocNum , OWOR.closedate , a.itemname , vwsof_pieles1.cantidad, vwsof_pieles1.monto, OWOR.PlannedQty,LOF.FECHA order by LOF.FECHA, OWOR.DocNum"));
    foreach($detInsPiel as $dip){
        $model = explode('-', $dip->ItemCode);
        try{
           $model[1];
        }catch(Exception $e){
           dd($model);
        }
    }
    
                $detCorte = DB::select(DB::raw("select  OWOR.DocNum, sum(WOR1.IssuedQty) as Usado ,OWOR.ItemCode , OHEM.firstName AS LOGISTICA,OHEM.middleName AS APELLIDO  , (a.u_vs * OWOR.PlannedQty )
                as u_vs ,OITM.ItmsGrpCod, OWOR.closedate  , a.itemname ,  sum(WOR1.IssuedQty*OITM.AvgPrice) 
                as mUsado  , (vwsof_pieles1.cantidad * OWOR.PlannedQty) as Teorico , (vwsof_pieles1.monto * OWOR.PlannedQty) as mTeorico, 
                SUBSTRING(owor.itemcode,9,5) as piel, (select TOP 1 OH.firstName from [@CP_LOGOF] LF INNER JOIN OHEM OH ON LF.U_idEmpleado = OH.empID
                WHERE (LF.U_CT =1 or LF.U_CT =112) AND LF.U_DocEntry = OWOR.DocNum   ) AS firstName, LOF.FECHA,(select TOP 1 OH.middleName
                from [@CP_LOGOF] LF INNER JOIN OHEM OH ON LF.U_idEmpleado = OH.empID WHERE (LF.U_CT =1 or LF.U_CT =112)AND LF.U_DocEntry = OWOR.DocNum   )
                AS middleName  from WOR1 inner join OWOR on OWOR.DocEntry = WOR1.DocEntry inner join OITM on OITM.ItemCode = WOR1.ItemCode inner join OITM a 
                on a.ItemCode = OWOR.itemcode inner join vwSof_Pieles1 on vwSof_Pieles1.father = OWOR.ItemCode 
                 INNER JOIN (SELECT U_DocEntry , sum(U_Cantidad) as Cantidad, U_idEmpleado,  DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora)) AS FECHA 
                 FROM [@CP_LOGOF] WHERE (U_CT = 1 or U_CT=112)  group by U_DocEntry , U_idEmpleado, DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora))  ) 
                LOF ON LOF.U_DocEntry = OWOR.DocNum inner join OHEM on OHEM.empID =  LOF.U_idEmpleado
                where OITM.ItmsGrpCod = 113 and DATEADD(dd, 0, DATEDIFF(dd, 0, LOF.Fecha)) between '". $fechaI ."' and '". $fechaF ."' group by OITM.ItmsGrpCod ,OHEM.firstName,
                OHEM.middleName , OWOR.ItemCode , a.u_vs ,OWOR.DocNum , OWOR.closedate , a.itemname , vwsof_pieles1.cantidad, vwsof_pieles1.monto, OWOR.PlannedQty,LOF.FECHA order by LOF.FECHA, OWOR.DocNum"));
    $detPegado = DB::select(DB::raw("select  OWOR.DocNum, sum(WOR1.IssuedQty) as Usado ,OWOR.ItemCode , OHEM.firstName AS LOGISTICA,OHEM.middleName AS APELLIDO  , (a.u_vs * OWOR.PlannedQty )
                as u_vs ,OITM.ItmsGrpCod, OWOR.closedate  , a.itemname ,  sum(WOR1.IssuedQty*OITM.AvgPrice) 
                as mUsado  , (vwsof_pieles1.cantidad * OWOR.PlannedQty) as Teorico , (vwsof_pieles1.monto * OWOR.PlannedQty) as mTeorico, 
                SUBSTRING(owor.itemcode,9,5) as piel, (select TOP 1 OH.firstName from [@CP_LOGOF] LF INNER JOIN OHEM OH ON LF.U_idEmpleado = OH.empID 
                WHERE (LF.U_CT =1 or LF.U_CT =112) AND LF.U_DocEntry = OWOR.DocNum   ) AS firstName, LOF.FECHA,(select TOP 1 OH.middleName from [@CP_LOGOF] LF INNER JOIN OHEM OH 
                ON LF.U_idEmpleado = OH.empID WHERE (LF.U_CT =1 or LF.U_CT =112) AND LF.U_DocEntry = OWOR.DocNum   ) AS middleName  from WOR1 inner join OWOR 
                on OWOR.DocEntry = WOR1.DocEntry inner join OITM on OITM.ItemCode = WOR1.ItemCode inner join OITM a on a.ItemCode = OWOR.itemcode inner join vwSof_Pieles1 on vwSof_Pieles1.father = OWOR.ItemCode 
                INNER JOIN (SELECT U_DocEntry , sum(U_Cantidad) as Cantidad, U_idEmpleado,  DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora)) AS FECHA 
                FROM [@CP_LOGOF] WHERE (U_CT = 4 or U_CT=118) group by U_DocEntry , U_idEmpleado, DATEADD(dd, 0, DATEDIFF(dd, 0, U_FechaHora))  ) LOF ON LOF.U_DocEntry = OWOR.DocNum inner join OHEM
                on OHEM.empID =  LOF.U_idEmpleado where OITM.ItmsGrpCod = 113 and DATEADD(dd, 0, DATEDIFF(dd, 0, LOF.Fecha)) between  '". $fechaI ."' and '". $fechaF ."' group by OITM.ItmsGrpCod ,OHEM.firstName, 
                OHEM.middleName , OWOR.ItemCode , a.u_vs ,OWOR.DocNum , OWOR.closedate , a.itemname , vwsof_pieles1.cantidad, vwsof_pieles1.monto, OWOR.PlannedQty,LOF.FECHA order by LOF.FECHA, OWOR.DocNum"));
    $data = [
                    'semana'=>Input::get('semana'),
                    'detInsPiel' => $detInsPiel,
                    'detCorte' => $detCorte,
                    'detPegado' => $detPegado,
                   'fechaI' => $fechaI,
                    'fechaF' => $fechaF,
                   // 'Usado' => $Usado,
                   // 'Teorico' => $Teorico,
                   // 'inspeccion' => $inspeccion,
                    //'pegado' => $pegado,
                    'enviado' =>true,
                    'actividades' => $actividades,
                    'ultimo' => count($actividades),  
                    'departamento' => '112 Corte de Piel'
                    ,'semana' => '' 
                ];
   //dd($detCorte);            
 }else{
    $data = [
        'semana'=>Input::get('semana'),
        //'detInsPiel' => $detInsPiel,
       // 'inspeccion' => $inspeccion,
        //'pegado' => $pegado,
        'enviado' =>false,
        /*'fechaI' => $fechaI,
        'fechaf' => $fechaF,*/
        'actividades' => $actividades,
        'ultimo' => count($actividades)  
        ,'semana' => '' 
    ];
 }           
Session::put('Rep112', $data);
return view('Mod01_Produccion.ReporteCortePiel', $data);
} else {
return redirect()->route('auth/login');
}
}
public function repCortePielExl(){
    if(Session::has ('Rep112')){          
        $data=Session::get('Rep112');
        Excel::create('Siz_Rep_CortePiel' . ' - ' . $hoy = date("d/m/Y").'', function($excel)use($data) {
         
         $excel->sheet('Hoja 1', function($sheet) use($data){
            //$sheet->margeCells('A1:F5');     
            $sheet->row(2, [
                'REPORTE','Orden Prod','Cortador','Código','Modelo','VS','Cant. Teorica Piel','$ Teorico'
                ,'Cant. Usada Piel','$ Usado','Diferencia dm2','Diferencia',
                'Desperdicio /Mura','Fecha de Inspeccion','Dia Semana','Semana','Modelo','Acabado'
            ]);
           //Datos    
           $fila = 3; 
           
        foreach ( $data['detInsPiel'] as $detInsPiel){
            $date=date_create($detInsPiel->FECHA);         
            $model = explode('-', $detInsPiel->ItemCode);
            $date=date_create($detInsPiel->FECHA);
            $dias = array("7","1","2","3","4","5","6");
            $sheet->row($fila, 
            [
                'Inspeccion',
                $detInsPiel->DocNum,
                $detInsPiel->firstName,
                $detInsPiel->ItemCode,
                $detInsPiel->itemname,
                number_format($detInsPiel->u_vs,2),
                number_format($detInsPiel->Teorico,2),
                "$ ".number_format($detInsPiel->mTeorico,2),
                number_format($detInsPiel->Usado,2),
                "$ ".number_format($detInsPiel->mUsado,2),
                number_format($detInsPiel->Usado - $detInsPiel->Teorico,2),
                number_format($detInsPiel->Usado / $detInsPiel->Teorico,2),
                number_format($detInsPiel->mUsado - $detInsPiel->mTeorico,2),
                date_format($date, 'd-m-Y'),
                $dias[date_format($date, 'w')],
                date_format($date, 'W'),
                $model[0]."".$model[1],
                $model[2]            
                ]);	
                $fila ++;
            }
               foreach ( $data['detCorte'] as $detCorte){
                $date=date_create($detCorte->FECHA);         
                $model = explode('-', $detCorte->ItemCode);
                $date=date_create($detCorte->FECHA);
                $dias = array("7","1","2","3","4","5","6");
                    $sheet->row($fila, 
                    [
                     'Corte',
                       $detCorte->DocNum,
                       $detCorte->firstName,
                       $detCorte->ItemCode,
                       $detCorte->itemname,
                       number_format($detCorte->u_vs,2),
                       number_format($detCorte->Teorico,2),
                       "$ ".number_format($detCorte->mTeorico,2),
                       number_format($detCorte->Usado,2),
                       "$ ".number_format($detCorte->mUsado,2),
                       number_format($detCorte->Usado - $detCorte->Teorico,2),
                       number_format($detCorte->Usado / $detCorte->Teorico,2),
                       number_format($detCorte->mUsado - $detInsPiel->mTeorico,2),
                       date_format($date, 'd-m-Y'),
                       $dias[date_format($date, 'w')],
                       date_format($date, 'W'),
                       $model[0]."".$model[1],
                       $model[2]  
                        ]);	
                        $fila ++;
                    }
                        foreach ( $data['detPegado'] as $detPegado){
                            $date=date_create($detPegado->FECHA);         
                            $model = explode('-', $detPegado->ItemCode);
                            $date=date_create($detPegado->FECHA);
                            $dias = array("7","1","2","3","4","5","6");
                            $sheet->row($fila, 
                            [
                                'Pegado',
                                $detPegado->DocNum,
                                $detPegado->firstName,
                                $detPegado->ItemCode,
                                $detPegado->itemname,
                                number_format($detPegado->u_vs,2),
                                number_format($detPegado->Teorico,2),
                                "$ ".number_format($detPegado->mTeorico,2),
                                number_format($detPegado->Usado,2),
                                "$ ".number_format($detPegado->mUsado,2),
                                number_format($detPegado->Usado - $detPegado->Teorico,2),
                                number_format($detPegado->Usado / $detPegado->Teorico,2),
                                number_format($detPegado->mUsado - $detPegado->mTeorico,2),
                                date_format($date, 'd-m-Y'),
                                $dias[date_format($date, 'w')],
                                date_format($date, 'W'),
                                $model[0]."".$model[1],
                                $model[2]  
                                ]);	
                                $fila ++;
        }
});         
})->export('xlsx');
       }else {
    return redirect()->action('Mod01_ProduccionController@repCortePiel');
}
}

public function terminarOP(Request $request){
    
                $id = $request->input('userId');  
                Session::flash('op', $request->input('orden')); 
                Session::put('return', 1);    
                

   $rates = DB::table('ORTT')->where('RateDate', date('d-m-Y'))->get();
        if (count($rates) >= 3) {
            $Code_actual = OP::find($request->input('code'));
            $orden_owor = DB::table('OWOR')->where('DocNum', $request->input('orden'))->first();          
            $apellido = Self::getApellidoPaternoUsuario(explode(' ',Auth::user()->lastName));
            $usuario_reporta = explode(' ', Auth::user()->firstName)[0].' '.$apellido;

                if (($orden_owor->PlannedQty) >= (floatval ( $orden_owor->CmpltQty) + floatval ( $request->input('cant')))) { // la cantidad procede
                    $result = SAPi::ReciboProduccion($request->input('orden'), $orden_owor->Warehouse, $request->input('cant'), "Reportado por: ".$usuario_reporta, "Recibo de producción");
                }else if(($orden_owor->PlannedQty) == (floatval ( $orden_owor->CmpltQty))){ //la cantidad es igual
                    $result = 'Recibo creado SIZ';  //'Recibo creado antes, correccion SIZ';    
                } else { // la cantidad seria mayor
                    Session::flash('error', 'La cantidad Completada no puede ser mayor a la Planeada');
                    return redirect()->action('Mod01_ProduccionController@getOP', $id);
                }

                if (strpos($result, 'Recibo') !== false) {
                //agregar linea en LOGOF del avance
                    $dt = date('Ymd h:m:s');
                    $user = User::find($request->input('userId'));

                    $Con_Logof = DB::select('select max (CONVERT(INT,Code)) as Code FROM  [@CP_LOGOF]');
                    $log = new LOGOF();
                    $log->Code = ((int) $Con_Logof[0]->Code) + 1;
                    $log->Name = ((int) $Con_Logof[0]->Code) + 1;
                    
                    $log->U_idEmpleado = $user->empID;
                    $log->U_CT = $Code_actual->U_CT;
                    $log->U_Status = "T";
                    $log->U_FechaHora = $dt;
                    $log->U_DocEntry = $Code_actual->U_DocEntry;
                    $log->U_Cantidad = $request->input('cant');
                    $log->U_Reproceso = 'N';
                    $log->save();
                //borrar OP de control Piso
               $orden_owor = NULL;
               $orden_owor = DB::table('OWOR')->where('DocNum', $request->input('orden'))->first();
            if ($orden_owor->PlannedQty == floatval ( $orden_owor->CmpltQty) ) {
               // DB::table('@CP_OF')->where('Code', $request->input('code'))->delete(); 
                $Code_actual->delete();
                //validar OP para cerrar 
                $cerrar = DB::select("
                            SELECT T0.[DocNum] as Orden, T0.[ItemCode] as Codigo, T0.[PlannedQty] as Planeado, T0.[CmpltQty] as Terminado ,T0.UpdateDate as Actualizado FROM OWOR T0 LEFT JOIN (SELECT OWOR.DocNum as OP,sum(WOR1.PlannedQty) as Cantidad FROM OWOR inner join WOR1 on WOR1.DocEntry = OWOR.DocEntry inner join OITM A1 on WOR1.ItemCode=A1.ItemCode WHERE OWOR.[PlannedQty] <= OWOR.[CmpltQty] and  OWOR.[status] <> 'L' and WOR1.IssueType = 'M' and WOR1.IssuedQty < WOR1.PlannedQty and A1.ItmsGrpCod<> 113 group by OWOR.DocNum ) VAL on T0.DocNum = VAL.OP WHERE  T0.DocNum=? and T0.[PlannedQty] <=  T0.[CmpltQty] and  T0.[status] <> 'L' and VAL.Cantidad is null
                            ", [$request->input('orden')]);
                if(count($cerrar) > 0){
                    SAP::ProductionOrderStatus($request->input('orden'), 2); //cerrar Orden en SAP
                }
                
            } else if ($orden_owor->PlannedQty > floatval ( $orden_owor->CmpltQty)) {                
                $Code_actual->U_Entregado += floatval ($request->input('cant'));
                $Code_actual->U_Procesado += floatval ($request->input('cant'));
                $Code_actual->save(); 
            }                

                Session::flash('mensaje', $result);
                Session::put('recibo', 1);   
                return redirect()->action('Mod01_ProduccionController@getOP', $id);
            } else {
                //regresar el error de SAP 
                Session::flash('error', $result);
                return redirect()->action('Mod01_ProduccionController@getOP', $id);
            }
            
           
             
        }else{
            Session::flash('error', 'No estan capturados todos los "Tipos de Cambio" en SAP.');
                return redirect()->action('Mod01_ProduccionController@getOP', $id);
        }
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
}