<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Modelos\MOD01\LOGOF;
use App\Modelos\MOD01\LOGOT;
use App\OP;
use App\User;
use Auth;
use DB;
use Hash;
use Dompdf\Dompdf;
//excel
use Illuminate\Http\Request;
//DOMPDF
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Lava;
use Mail;
use Session;
use Maatwebsite\Excel\Facades\Excel;

class Mod03_ComprasController extends Controller
{
public function pedidosCsv()
{
    if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas(); 
        if (Session::has('OrdenCompra')) {
           Session::forget('OrdenCompra');
        }      
         return view('Mod03_Compras.Pedidos',
             ['actividades' => $actividades,
                 'ultimo' => count($actividades),                           
                ]
             );
    } else {
        return redirect()->route('auth/login');
    }
}
public function postPedidosCsv()
{
    if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();    
$pedido = DB::select(DB::raw("SELECT POR1.LineStatus, OITM.BuyUnitMsr, OPOR.CANCELED, OPOR.DocStatus, OITM.ValidComm,OPOR.DocNum as NumOC, OPOR.DocDate as FechOC, OPOR.CardCode as CodeProv, OPOR.CardName as NombProv, POR1.ItemCode as Codigo, POR1.Dscription as Descrip, POR1.Quantity as CantTl, POR1.OpenQty as CantPend, POR1.ShipDate as FechEnt, OSLP.SlpName as Elaboro, 'ARTICULOS' as TipoOC, UFD1.Descr as Comprador, case when datediff(day,POR1.ShipDate, getdate()) >  0 and datediff(day,POR1.ShipDate, getdate()) < 9 then 'A-08 dias' when datediff(day,POR1.ShipDate, getdate()) >  8 and datediff(day,POR1.ShipDate, getdate()) < 16 then 'A-15 dias' 
         when datediff(day,POR1.ShipDate, getdate()) > 15 and datediff(day,POR1.ShipDate, getdate()) < 31 then 'A-30 dias' when datediff(day,POR1.ShipDate, getdate()) > 30 and datediff(day,POR1.ShipDate, getdate()) < 61 then 'A-60 dias' when datediff(day,POR1.ShipDate, getdate()) > 60 and datediff(day,POR1.ShipDate, getdate()) < 91 then 'A-90 dias' when datediff(day,POR1.ShipDate, getdate()) > 90 then 'A-MAS dias' End as Grupo, 1 as QtyMat, OPOR.Comments , POR1.Price, POR1.Currency FROM OPOR INNER JOIN POR1 ON OPOR.DocEntry = POR1.DocEntry LEFT JOIN OITM ON POR1.ItemCode = OITM.ItemCode 
         INNER JOIN OSLP on OSLP.SlpCode= POR1.SlpCode LEFT JOIN UFD1 on OITM.U_Comprador= UFD1.FldValue and UFD1.TableID='OITM' and UFD1.FieldID=10 
         WHERE   POR1.ItemCode is not null and DocNum = " .Input::get('NumOC'). " Union all 
         SELECT POR1.LineStatus, OITM.BuyUnitMsr, OPOR.CANCELED, OPOR.DocStatus, OITM.ValidComm, OPOR.DocNum as NumOC, OPOR.DocDate as FechOC, OPOR.CardCode as CodeProv, OPOR.CardName as NombProv, POR1.ItemCode as Codigo, POR1.Dscription as Descrip, POR1.Quantity as CantTl, POR1.OpenQty as CantPend, OPOR.DocDueDate as FechEnt, OSLP.SlpName as Elaboro, 'SERVICIOS' as TipoOC, 'Libre' as Comprador, case 
         when datediff(day,OPOR.DocDueDate, getdate()) >  0 and datediff(day,OPOR.DocDueDate, getdate()) < 9 then 'A-08 dias' when datediff(day,OPOR.DocDueDate, getdate()) >  8 and datediff(day,OPOR.DocDueDate, getdate()) < 16 then 'A-15 dias' when datediff(day,OPOR.DocDueDate, getdate()) > 15 and datediff(day,OPOR.DocDueDate, getdate()) < 31 then 'A-30 dias' when datediff(day,OPOR.DocDueDate, getdate()) > 30 and datediff(day,OPOR.DocDueDate, getdate()) < 61 then 'A-60 dias' when datediff(day,OPOR.DocDueDate, getdate()) > 60 and datediff(day,OPOR.DocDueDate, getdate()) < 91 then 'A-90 dias' 
         when datediff(day,OPOR.DocDueDate, getdate()) > 90 then 'A-MAS dias' End as Grupo, 1 as QtyMat, OPOR.Comments, POR1.Price, POR1.Currency FROM OPOR INNER JOIN POR1 ON OPOR.DocEntry = POR1.DocEntry LEFT JOIN OITM ON POR1.ItemCode = OITM.ItemCode INNER JOIN OSLP on OSLP.SlpCode= POR1.SlpCode 
         WHERE    POR1.ItemCode is null and DocNum = " .Input::get('NumOC'). "ORDER BY  CantPend desc, Descrip"));
//dd($pedido); 
 if (count($pedido)>0){
  
  $datas = ['actividades' => $actividades,
                 'ultimo' => count($actividades),            
                 'pedido' => $pedido,
 ];
 Session::put('OrdenCompra', $datas);
    return view('Mod03_Compras.Pedidos', $datas);
}else{
    return redirect()->back()->withErrors(array('message' => 'La orden no existe.'));
}
    } else {
        return redirect()->route('auth/login');
    }
}
public function desPedidosCsv()
{
   try{
    if(Session::has ('OrdenCompra')){          
        $datas=Session::get('OrdenCompra');
        Excel::create('Siz_Orden_Compra' . ' - ' . $hoy = date("d/m/Y").'', function($excel)use($datas) {
         
         $excel->sheet('Hoja 1', function($sheet) use($datas){
            //$sheet->margeCells('A1:F5');     
            $sheet->row(1, ['Orden Compra','# Proveedor','Sku','Largo','Ancho','Alto','Version','Cantidad','Fecha Ped','Fecha Emb']);
           //Datos    
           $fila = 2;        
        foreach ( $datas['pedido'] as $pedi){
            $date=date_create($pedi->FechOC);
            $dat=date_create($pedi->FechEnt); 
          if($pedi->LineStatus == 'O'){
            $sheet->row($fila, 
            [
                $pedi->NumOC,
                $pedi->CodeProv,
                $pedi->Codigo,
                '',
                '',
                '',
                $pedi->ValidComm,
                number_format($pedi->CantTl,2),
                date_format($date, 'd-m-Y'),          
                date_format($dat, 'd-m-Y')          
                ]);	
          }

                $fila ++;
            }
});         
})->download('csv');
return  redirect()->back();
       }else {
    return redirect()->back()->withErrors(array('message' => 'No se almaceno correctamente la OC.'));
}
   }catch(Exception $e){
    return redirect()->back()->withErrors(array('message' => 'Error al descargar archivo CSV'));
   }
}
public function PedidosCsvPDF()
{
    $pdf = \PDF::loadView('Mod03_Compras.PedidosPDF', Session::get('OrdenCompra'));
    $pdf->setOptions(['isPhpEnabled' => true]);
    return $pdf->stream('Siz_Orden_Compra' . ' - ' . $hoy = date("d/m/Y") . '.Pdf');
}
}