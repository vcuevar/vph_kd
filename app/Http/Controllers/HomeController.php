<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Modelos\MOD01\LOGOF;
use App\Modelos\MOD01\LOGOT;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use App\OP;
use App\User;
use Mail;
use Response;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $actividades = $user->getTareas();
        // Inicia notificacion de Mo4 Traslado recepcion
         $trasladosRecepcion = DB::table('SIZ_SolicitudesMP')
            ->join('SIZ_MaterialesTraslados', 'SIZ_MaterialesTraslados.Id_Solicitud', '=', 'SIZ_SolicitudesMP.Id_Solicitud')
            ->leftjoin('OHEM', 'OHEM.U_EmpGiro', '=', 'SIZ_SolicitudesMP.Usuario')
            ->leftjoin('OUDP', 'OUDP.Code', '=', 'dept')
            ->join('SIZ_AlmacenesTransferencias', function ($join) {
                $join->on('SIZ_AlmacenesTransferencias.Code', '=', DB::raw('SUBSTRING(Destino, 1, 6)'))
                    ->where('SIZ_AlmacenesTransferencias.TrasladoDeptos', '<>', 'D')
                    ->whereNotNull('TrasladoDeptos');
            })
            ->groupBy('SIZ_SolicitudesMP.Id_Solicitud', 
            'SIZ_SolicitudesMP.FechaCreacion', 'SIZ_SolicitudesMP.Usuario', 
            'SIZ_SolicitudesMP.Status', 'firstName', 'lastName',
             'OHEM.dept', 'Name', 'SIZ_SolicitudesMP.AlmacenOrigen')
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
            ->where('SIZ_SolicitudesMP.Status', 'Pendiente')->get();
           
        if (count ($trasladosRecepcion) > 0) {
            $ruta = 'TRASLADO RECEPCION';
            $result = DB::table('SIZ_routes_log')
                ->where('Usuario', Auth::user()->U_EmpGiro)
                ->where('route', $ruta)
                ->update(['ultimaFecha' => (new \DateTime('now'))->format('Y-m-d H:i:s')]);
            if($result > 1){// borrar si hay mas de 2 registros iguales
                DB::table('SIZ_routes_log') 
                ->where('Usuario', Auth::user()->U_EmpGiro)
                ->where('route', $ruta)->delete();
                $result = 0;
            }

            if ($result == 0) { //insertar si no hay algun registro
                DB::table('SIZ_routes_log')->insert(
                    ['route' => $ruta, 'Usuario' => Auth::user()->U_EmpGiro, 'ultimaFecha' => (new \DateTime('now'))->format('Y-m-d H:i:s')]
                );
            }
        }
        
        // Finaliza notificacion de Mod4 Traslado Recepcion
     
        $links = DB::select('Select top 6 l.route, tm.name as tarea, m.name as modulo from SIZ_routes_log l
                inner join Siz_Tarea_menu tm on tm.route = l.route
				left join Siz_Modulos_Grupo mg on mg.id_tarea = tm.id
                left join Siz_Modulo m on m.id = mg.id_modulo				
                where l.Usuario = ?
                and tm.name is not null and m.name is not null
                group by tm.name, m.name, l.route
                order by tm.name, m.name', [Auth::user()->U_EmpGiro]);
        return view('homeIndex',   ['traslados' => count($trasladosRecepcion), 'links' => $links, 'actividades' => $actividades, 'ultimo' => count($actividades), 'isAdmin'=> User::isAdmin()]);
    }

    public function UPT_Noticias($id){     
       DB::table('Siz_Noticias')
        ->where('Id', $id)
        ->update(['Leido' => 'Si']);
        $user = Auth::user();                
       return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
*/
    public function create(Request $request)
    { 
        $user = Auth::user();
        $actividades = $user->getTareas();
        $id_noticia=$request->input("id");

        $id_user=Auth::user()->U_EmpGiro;    
        $noticias=DB::select(DB::raw("SELECT * FROM Siz_Noticias WHERE Destinatario='$id_user'and Leido='N'"));

      return view('Mod01_Produccion/Noticias', ['actividades' => $actividades,'noticias' => $noticias,'id_user' => $id_user, 'ultimo' => count($actividades)]);
    }

    public function showPdf($PdfName){
        $filename = "assets\\ayudas_pdf\\".$PdfName;
        $path = public_path($filename);
         return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
             'Content-Disposition' => 'inline; filename="'.$filename.'"'
         ]);
    }
    public function showPdf2($r0, $PdfName){
        $filename = "assets\\ayudas_pdf\\".$PdfName;
        $path = public_path($filename);
         return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
             'Content-Disposition' => 'inline; filename="'.$filename.'"'
         ]);
    }
    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ShowArticulos(Request $request)
    {
        if (Auth::check()) {
            
            $consulta= DB::select('SELECT ItemCode, ItemName, InvntryUom AS UM FROM OITM WHERE PrchseItem = \'Y\' AND InvntItem = \'Y\' AND U_TipoMat = \'MP\'');

            //Definimos las columnas del MRP
            $columns = array(
                ["data" => "ItemCode", "name" => "Código"],
                ["data" => "ItemName", "name" => "Descripción"],
                ["data" => "UM", "name" => "UM"],            
            );          

            return response()->json(array('data' => $consulta, 'columns' => $columns, 'pkey' => 'ItemCode'));
            
        } else {
            return redirect()->route('auth/login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
