<?php
namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Dompdf\Dompdf;
//excel
use Illuminate\Http\Request;
//DOMPDF
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Session;
use Maatwebsite\Excel\Facades\Excel;
//Fin DOMPDF
use Illuminate\Support\Facades\Validator;
use Datatables;

class Mod10_RhController extends Controller
{
    public function parametrosModal()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();

            return view('Mod10_Rh.ReporteBonos',
                ['actividades' => $actividades,
                    'ultimo' => count($actividades),
                    'dataGerente' => 0, 'dataCorte' => 0,
                    'dataCostura' => 0, 'dataCojineria' => 0,
                    'dataTapiceria' => 0, 'dataCarpinteria' => 0,
                    /*  'mo_cor' => 0, 'mo_cos' => 0, 'mo_tap' => 0,*/
                    'ca_cor' => 0, 'ca_cos' => 0, 'ca_coj' => 0,
                    'ca_tap' => 0, 'ca_car' => 0, 'ca_gt' => 0,
                    'enviado' => false,
                    'semana' => '']);
        } else {
            return redirect()->route('auth/login');
        }
    }

    public function calculoBonos(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            //INICIA BONOS PRODUCCION
            $VSAcumulado = DB::table('OWOR')
                ->leftJoin('@CP_LOGOF', '@CP_LOGOF.U_DocEntry', '=', 'OWOR.DocEntry')
                ->leftJoin('OITM', 'OITM.ItemCode', '=', 'OWOR.ItemCode')
                ->select(DB::raw(
                    ' sum (CASE when ([@CP_LOGOF].U_CT=175 and DATEPART(isoww, [@CP_LOGOF].U_FechaHora) = ' . Input::get('semana') . ' ) then (OITM.U_VS) else 0  end ) as vs175,
                sum (CASE when ([@CP_LOGOF].U_CT=115 and DATEPART(isoww, [@CP_LOGOF].U_FechaHora) = ' . Input::get('semana') . ' ) then (OITM.U_VS) else 0  end ) as vs121,
                sum (CASE when ([@CP_LOGOF].U_CT=136 and DATEPART(isoww, [@CP_LOGOF].U_FechaHora) = ' . Input::get('semana') . ' ) then (OITM.U_VS) else 0  end ) as vs136,
                sum (CASE when ([@CP_LOGOF].U_CT=148 and DATEPART(isoww, [@CP_LOGOF].U_FechaHora) = ' . Input::get('semana') . ' ) then (OITM.U_VS) else 0  end ) as vs148,
                sum (CASE when ([@CP_LOGOF].U_CT=418 and DATEPART(isoww, [@CP_LOGOF].U_FechaHora) = ' . Input::get('semana') . ' ) then (OITM.U_VS) else 0  end ) as vs418'
                ))
                ->first();
            //Gerente
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Gerente')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $GerenteVS = $VSAcumulado->vs175;
            $GerenteBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $GerenteVS);
            $dataGerente = [$parametrosBono->tipoEmpleado, $GerenteVS, $GerenteBono];
            //Supervisor de Corte
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Supervisor Corte')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $CorteVS = $VSAcumulado->vs121;
            $CorteBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $CorteVS);
            $dataCorte = [$parametrosBono->tipoEmpleado, $CorteVS, $CorteBono];
            //Supervisor de Costura
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Supervisor Costura')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $CosturaVS = $VSAcumulado->vs136;
            $CosturaBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $CosturaVS);
            $dataCostura = [$parametrosBono->tipoEmpleado, $CosturaVS, $CosturaBono];
            //Supervisor de Cojineria
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Supervisor Cojineria')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $CojineriaVS = $VSAcumulado->vs148;
            $CojineriaBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $CojineriaVS);
            $dataCojineria = [$parametrosBono->tipoEmpleado, $CojineriaVS, $CojineriaBono];
            //Supervisor de Tapiceria
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Supervisor Tapiceria')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $TapiceriaVS = $VSAcumulado->vs175;
            $TapiceriaBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $TapiceriaVS);
            $dataTapiceria = [$parametrosBono->tipoEmpleado, $TapiceriaVS, $TapiceriaBono];
            //Supervisor de Carpinteria
            $parametrosBono = DB::table('Siz_Parametros_Bonos')->where('tipoEmpleado', 'Supervisor Carpinteria')->where('tipoBono', '1')->first();
            $VSMax = $parametrosBono->VSMax;
            $VSMin = $parametrosBono->VSMin;
            $Bono = $parametrosBono->bono;
            $CarpinteriaVS = $VSAcumulado->vs418;
            $CarpinteriaBono = Self::getBonoMXN($VSMax, $VSMin, $Bono, $CarpinteriaVS);
            $dataCarpinteria = [$parametrosBono->tipoEmpleado, $CarpinteriaVS, $CarpinteriaBono];
            //TERMINA BONOS PRODUCCION
            //INICIA BONOS PRODUCTIVIDAD
            //DEFINICION DE VARIABLES
            /* $mo_cor = $request->input('mo_cor');
            $mo_cos = $request->input('mo_cos');
            $mo_tap = $request->input('mo_tap');
            Self::getBono('2', 'Supervisor Costura', '50000');
            //-----------------------------------------------------------------------------------------
            $productividadCorte = Self::getBono('2', 'Supervisor Corte', $mo_cor);
            //-----------------------------------------------------------------------------------------
            $productividadCostura = Self::getBono('2', 'Supervisor Costura', $mo_cos);
            //-----------------------------------------------------------------------------------------
            $productividadTapiceria = Self::getBono('2', 'Supervisor Tapiceria', $mo_tap);
            //-------------TERMINA BONOS PRODUCTIVIDAD-------------------------------------------------
             */
            //INICIA BONOS DE CALIDAD
            $values = DB::table('Siz_Calidad_Depto')
                        ->where('semana', Input::get('semana'))
                        ->first();
                        
            if(count($values)<1){
                return redirect()->back()->withErrors(array('message' => 'Calidad no ha capturado la semana '.Input::get('semana')));
            }                        
            $suma = 0;
            $ca_cor = $values->CorteIn;
            $suma += $ca_cor;
            $ca_cos = $values->CostIn;
            $suma += $ca_cos;
            $ca_coj = $values->CojiIn;
            $suma += $ca_coj;
            $ca_tap = $values->TapIn;
            $suma += $ca_tap;
            $ca_car = $values->CarpIn;
            $suma += $ca_car;
            $ca_gt = $suma/5;
            // $ca_cor = $request->input('ca_cor');
            // $ca_cos = $request->input('ca_cos');
            // $ca_coj = $request->input('ca_coj');
            // $ca_tap = $request->input('ca_tap');
            // $ca_car = $request->input('ca_car');
            // $ca_gt = $request->input('ca_gt');
//------------------------------------------------------------------------------------
            $calidadCorte = Self::getBono('3', 'Supervisor Corte', $ca_cor);
//------------------------------------------------------------------------------------
            $calidadCostura = Self::getBono('3', 'Supervisor Costura', $ca_cos);
//------------------------------------------------------------------------------------
            $calidadCojineria = Self::getBono('3', 'Supervisor Cojineria', $ca_coj);
//------------------------------------------------------------------------------------
            $calidadTapiceria = Self::getBono('3', 'Supervisor Tapiceria', $ca_tap);
//------------------------------------------------------------------------------------
            $calidadCarpinteria = Self::getBono('3', 'Supervisor Carpinteria', $ca_car);
            $calidadGerente = Self::getBono('3', 'Gerente', $ca_gt);
//-------------TERMINA BONOS DE CALIDAD-----------------------------------------------
            $values = ['actividades' => $actividades,
                'ultimo' => count($actividades),
                'dataGerente' => $dataGerente,
                'dataCorte' => $dataCorte,
                'dataCostura' => $dataCostura,
                'dataCojineria' => $dataCojineria,
                'dataTapiceria' => $dataTapiceria,
                'dataCarpinteria' => $dataCarpinteria,
                /*'mo_cor' => $productividadCorte,
                'mo_cos' => $productividadCostura,
                'mo_tap' => $productividadTapiceria,*/
                'ca_cor' => $calidadCorte,
                'ca_cos' => $calidadCostura,
                'ca_coj' => $calidadCojineria,
                'ca_tap' => $calidadTapiceria,
                'ca_car' => $calidadCarpinteria,
                'ca_gt' => $calidadGerente,
                'enviado' => true,
                'semana' => Input::get('semana')];

            $request->session()->put('values', $values);

            return view('Mod10_Rh.ReporteBonos', $values);

        } else {
            return redirect()->route('auth/login');
        }
    }

    public function getBonoMXN($VSMax, $VSMin, $Bono, $UserVS)
    {
        //obtener rango segun la tabla
        $UserVSRango = floor($UserVS / 5) * 5;

        $proMensualMax = $VSMax * 4;
        $proMensualBase = $VSMin * 4;

        $VSDif = ($UserVSRango * 4) - $proMensualBase;
        $VSMaxDif = $proMensualMax - $proMensualBase;

        $porcentajeBono = ($VSDif * 100) / $VSMaxDif;
        $Bono = ($UserVS >= 55) ? ($porcentajeBono * $Bono) / 100 : 0;

        //echo($UserVSRango . "<br>");
        //echo(number_format($Bono, 2) . "<br>");

        return $Bono;
    }
 public function setParametrosBonos(){
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();    
$Ndatos = DB::table('Siz_Parametros_Bonos')
            ->orderBy('tipoBono','asc')
            ->get(); 
    return view('Mod10_Rh.NuevoParametroBono', 
    ['actividades' => $actividades, 
    'ultimo' => count($actividades),
 'enviado' => false,
 'Ndatos' => $Ndatos
    ]);
} else {
    return redirect()->route('auth/login');
}
}
    public function setParametrosBonos2(){

        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
DB::table('Siz_Parametros_Bonos')
            ->insert(
                [
                    'tipoBono'=> Input::get('tbono_in'),
                    'VSMin' => Input::get('rango_in'),
                    'VSMax'=> Input::get('rango_fin'),
                    'bono' => Input::get('bono_mxn'),
                    'tipoEmpleado' => Input::get('tipo_emp'),  
                    'UM' => Input::get('uni_med')        
                ]);
Session::flash('mensaje', 'Se enviaron los Parametros'); 
            return redirect()->back();
        } else {
            return redirect()->route('auth/login');
        }
    }  
    public function mod_parametro($id){
            $user = Auth::user();
            $actividades = $user->getTareas();
$PBono = DB::table('Siz_Parametros_Bonos')
   
            ->select('Siz_Parametros_Bonos.id as id_Par', 'Siz_Parametros_Bonos.*')
            ->where('Siz_Parametros_Bonos.id', '=',$id)
            ->orderBy('id_Par')
            ->get();
//dd($PBono[0]->tipoBono);    
return view('Mod10_Rh.ModparametroBono',compact('PBono', 'id'), 
['actividades' => $actividades, 
'ultimo' => count($actividades)
]);
{
return redirect()->route('home/PARAMETROS BONOS');
}
 }
public function mod_parametro2($id, Request $request){
try{
    DB::table('Siz_Parametros_Bonos')
    ->where("id", "=", $id)
    ->update(
           [
                'tipoBono'=> $request->input('tbono_in'),
                'VSMin' => $request->input('rango_in'),
                'VSMax'=> $request->input('rango_fin'),
                'bono' => $request->input('bono_mxn'),
                'tipoEmpleado' => $request->input('tipo_emp'),  
                'UM' => $request->input('uni_med') 
           ]
    ); 
 Session::flash('mensaje', 'Se modifico correctamente');
    return $this->setParametrosBonos();
}catch(Exception $e){
 Session::flash('error', 'ocurrio un error durante la actualizacion de este parámetro');
    return $this->setParametrosBonos();
}
      
    }
    public function delete_parametro($id)
    {
        $eliminar = DB::table('Siz_Parametros_Bonos')
        ->where('id', '=', $id)
        ->delete();
        Session::flash('mensaje', 'Se elimino correctamente');
        return redirect()->back();
    }
    public function getBono($tipoBono, $tipoEmpleado, $mo)
    {
        $rss = DB::table('Siz_Parametros_Bonos')
            ->where('tipoBono', $tipoBono)
            ->where('tipoEmpleado', $tipoEmpleado)
            ->orderBy('id', 'asc')
            ->get();
        $bonoproductividad = 0;
        if (count($rss) >= 2) {
            $valorminimo = $rss[0]->VSMin;
            $valormaximo = $rss[count($rss) - 1]->VSMax;
            // dd($valorminimo, $valormaximo);
            if ($mo < $valorminimo && $tipoBono == '3') {

            } else if ($mo >= $valormaximo && $tipoBono == '3') {
                $bonoproductividad = $rss[count($rss) - 1]->bono;
            } else if ($mo <= $valorminimo && $tipoBono == '2') {
                $bonoproductividad = $rss[0]->bono;

            } else if ($mo > $valormaximo && $tipoBono == '2') {

            } else {
                foreach ($rss as $rs) {
                    if ($mo >= $rs->VSMin && $mo <= $rs->VSMax) {
                        $bonoproductividad = $rs->bono;
                        break;
                    }
                }
            }
        }
        //dd($bonoproductividad);
        return [$tipoEmpleado, $mo, $bonoproductividad];
    }

    public function bonosPdf()
    {
        $pdf = \PDF::loadView('Mod10_Rh.ReporteBonosPDF', Session::get('values'));
        $pdf->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream('Siz_Reporte_Bonos' . ' - ' . $hoy = date("d/m/Y") . '.Pdf');
    }

    public function bonosCorte()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();

            return view('Mod10_Rh.bonoscorte',
                ['actividades' => $actividades,
                    'ultimo' => count($actividades),
                    'enviado' => false,
                    'semana' => ''
                   ]
                );
        } else {
            return redirect()->route('auth/login');
        }
    }
public function calculoBonosCorte()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actividades = $user->getTareas();
            $cortadores = DB::select(DB::raw("SELECT Sum(wor1.issuedqty)AS Usado, ohem.firstName, ohem.lastName, U_EmpGiro,
            (case when(Sum(wor1.issuedqty)<(select top 1 VSMin from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Cortador'))
            then 0 
            when(Sum(wor1.issuedqty)>(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Cortador'))
            then 600
            else 
            Convert(decimal(6,4),((select top 1 bono from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Cortador')
            /(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Cortador')))*(Sum(wor1.issuedqty))end )
            as bono
            FROM   
                                        wor1 inner join owor ON owor.docentry = wor1.docentry
                                        inner join oitm ON oitm.itemcode = wor1.itemcode
                                        inner join oitm a ON a.itemcode = owor.itemcode
                                        inner join vwsof_pieles1 ON vwsof_pieles1.father = owor.itemcode
                                        inner join (SELECT u_docentry, Sum(u_cantidad) AS Cantidad, u_idempleado, Dateadd(dd, 0, Datediff(dd, 0, u_fechahora)) AS FECHA FROM [@cp_logof] WHERE  ( u_ct = 1 OR u_ct = 112 )
                                        GROUP BY u_docentry, u_idempleado,Dateadd(dd, 0, Datediff(dd, 0, u_fechahora))) LOF ON LOF.u_docentry = owor.docnum
                                        inner join ohem ON ohem.empid = LOF.u_idempleado
                                        WHERE  oitm.itmsgrpcod = 113 AND DATEPART(isoww,LOF.fecha) = "." ".Input::get('semana')." "."
                                        GROUP BY firstName, lastName, U_EmpGiro"));              
            $inspeccion = DB::select(DB::raw("SELECT sum(A3.U_VS * CP.U_Cantidad) as U_VS, U_EmpGiro, RH.firstName, RH.lastName, 
            (case when(Sum(A3.U_VS * CP.U_Cantidad)<(select top 1 VSMin from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Inspector'))
             then 0 
            when(Sum(A3.U_VS * CP.U_Cantidad)>(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Inspector'))
            then 500
            else 
            Convert(decimal(6,4),((select top 1 bono from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Inspector')
            /(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Inspector')))*(Sum(A3.U_VS * CP.U_Cantidad))end )
            as bono
            FROM 
                                        OWOR OP inner join [@CP_LOGOF] CP on OP.DocEntry= CP.U_DocEntry 
                                        inner join OHEM RH on CP.U_idEmpleado=RH.empID 
                                        inner join OITM A3 on OP.ItemCode=A3.ItemCode 
                                        inner join OITM A4 on A3.U_Modelo=A4.ItemCode 
                                        Where DATEPART(isoww,CAST(CP.U_FechaHora as DATE)) = "." ".Input::get('semana')." "." and (CP.U_CT=3 or CP.U_CT=115)
                                        GROUP BY firstName, lastName, U_EmpGiro"));
            $pegado = DB::select(DB::raw("SELECT sum(A3.U_VS * CP.U_Cantidad) as U_VS, U_EmpGiro, RH.firstName, RH.lastName, 
            (case when(Sum(A3.U_VS * CP.U_Cantidad)<(select top 1 VSMin from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Pegador'))
             then 0 
            when(Sum(A3.U_VS * CP.U_Cantidad)>(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Pegador'))
            then 500
            else 
            Convert(decimal(6,4),((select top 1 bono from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Pegador')
            /(select top 1 VSMax from Siz_Parametros_Bonos where tipoBono = 4 and tipoEmpleado = 'Pegador')))*(Sum(A3.U_VS * CP.U_Cantidad))end )
            as bono
            FROM 
                                        OWOR OP inner join [@CP_LOGOF] CP on OP.DocEntry= CP.U_DocEntry 
                                        inner join OHEM RH on CP.U_idEmpleado=RH.empID 
                                        inner join OITM A3 on OP.ItemCode=A3.ItemCode 
                                        inner join OITM A4 on A3.U_Modelo=A4.ItemCode 
                                        Where DATEPART(isoww,CAST(CP.U_FechaHora as DATE)) = "." ".Input::get('semana')." "." and (CP.U_CT=4 or CP.U_CT=118)
                                        GROUP BY firstName, lastName, U_EmpGiro"));
            $data = [
                'semana'=>Input::get('semana'),
                'cortadores' => $cortadores,
                'inspeccion' => $inspeccion,
                'pegado' => $pegado,
                'enviado' =>true,
                'actividades' => $actividades,
                'ultimo' => count($actividades)   
            ];
            Session::put('corteData', $data);
            return view('Mod10_Rh.BonosCorte', $data);
        } else {
            return redirect()->route('auth/login');
        }
    }
    public function bonoscortePdf()
    {
        $pdf = \PDF::loadView('Mod10_Rh.bonosCortePDF', Session::get('corteData'));
        $pdf->setOptions(['isPhpEnabled' => true]);
        return $pdf->stream('Siz_Bonos_Corte' . ' - ' . $hoy = date("d/m/Y") . '.Pdf');
    }
    public function bonoscorteEXL(){
       if(Session::has ('corteData')){          
        $data=Session::get('corteData');
        Excel::create('Siz_Bonos_Corte' . ' - ' . $hoy = date("d/m/Y").'', function($excel)use($data) {
         
         $excel->sheet('Hoja 1', function($sheet) use($data){
            //$sheet->margeCells('A1:F5');     
            $sheet->row(2, [
               'Actividad','No.Nomina','Nombre','Apellido','Destajo','Bono' 
            ]);
           //Datos    
           $fila = 3;     
        foreach ( $data['cortadores'] as $cortador){
            $sheet->row($fila, 
            [
                'CORTADOR',
                $cortador->U_EmpGiro,
                $cortador->firstName,
                $cortador->lastName,
                $cortador->Usado,
                $cortador->bono,
                ]);	
                $fila ++;
            }
                foreach ( $data['inspeccion'] as $inspeccion){
                    $sheet->row($fila, 
                    [
                        'INSPECTOR',
                        $inspeccion->U_EmpGiro,
                        $inspeccion->firstName,
                        $inspeccion->lastName,
                        $inspeccion->U_VS,
                        $inspeccion->bono,
                        ]);	
                        $fila ++;
                    }
                        foreach ( $data['pegado'] as $pegado){
                            $sheet->row($fila, 
                            [
                                'PEGADOR',
                                $pegado->U_EmpGiro,
                                $pegado->firstName,
                                $pegado->lastName,
                                $pegado->U_VS,
                                $pegado->bono,
                                ]);	
                                $fila ++;
        }
});         
})->export('xlsx');
       }else {
    return redirect()->action('Mod10_RhController@bonosCorte');
}
}

}
