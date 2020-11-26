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
use Datatables;
use Illuminate\Database\Eloquent\Collection;

ini_set("memory_limit", '512M');
ini_set('max_execution_time', 0);
class Mod02_PlaneacionController extends Controller
{
public function reporteMRP()
{
    if (Auth::check()) {
        $user = Auth::user();
        $actividades = $user->getTareas();
       
        $fechauser = Input::get('text_selUno');
        $tipo = Input::get('text_selDos');
       
        
        // if ($accion == 'Actualizar') {//se ejecuta la actualizacion de la tabla
        //     DB::update("exec SIZ_MRP");
        // }
        $f = DB::table('SIZ_Z_MRP')->first();// obtener fecha de ultima actualizacion
        if(isset($f)){
             $Text = 'Actualizado el: ' . \AppHelper::instance()->getHumanDate_format($f->fechaDeEjecucion, 'h:i A');
        }else{
             $Text = 'Sin datos';
        }
        $data = array(        
            'actividades' => $actividades,
            'ultimo' => count($actividades),
            'text' => $Text,
            'fechauser' => $fechauser,
            'tipo' => $tipo
        );
        return view('Mod02_Planeacion.reporteMRP', $data);
    } else {
        return redirect()->route('auth/login');
    }
}

public function actualizaMRP(){
        DB::update("exec SIZ_NEWMRP");
        
        Session::flash('mensaje', "MRP actualizado...");
        
        return redirect('home/MRP');
}
    public function DataShowMRP(Request $request)
    {
        if (Auth::check()) {
        $fecha = $request->get('fechauser');
        $tipo = $request->get('tipo');
        

         //   $fecha = Input::get('text_selUno');
         //   $tipo = Input::get('text_selDos');
         //   $accion = Input::get('text_selTres');  
         $consulta = '';
        switch ($tipo) {
            case 'Completo': 
               $tipo = '%';
                break;
            case 'Proyección':
                $tipo = 'P';
                break;
            case 'Con Orden':
                $tipo = 'C';
                break;
        }
        switch ($fecha) {
            case 'Producción':
                    // $consulta = DB::table('SIZ_T_MRP')
                    //     ->select(DB::raw('fechaDeEjecucion, Descr, Itemcode, ItemName, UM, ExistGDL, ExistLERMA, WIP, sum(S0) S0, sum(S1)S1, sum(S2)S2, sum(S3)S3, sum(S4)S4, sum(S5)S5, sum(S6)S6, sum(S7)S7, sum(S8)S8, sum(S9)S9, sum(S10)S10, sum(S11)S11, sum(S2)S12, sum(S13)S13, sum(S14)S14, sum(S15)S15, sum(S16)S16, sum(S17)S17, sum(S18)S18, sum(S19)S19, sum(necesidadTotal)necesidadTotal, OC, Reorden, Minimo, Maximo, TE, Costo,Proveedor, Comprador'))
                    //     ->where('U_C_Orden', 'like', $tipo)
                    //     ->groupBy( "fechaDeEjecucion", 'Descr', 'Itemcode', 'ItemName', 'UM', 'ExistGDL', 'ExistLERMA', 'WIP', 'Costo', 'Proveedor', 'Comprador', 'Reorden', 'Maximo', 'Minimo', 'TE', 'OC');
                    $consulta = DB::select('exec SIZ_SP_MRP ?, ?', ['semana', $tipo]);
                
                break;
            case 'Compras':
                    // $consulta = DB::table('SIZ_T_MRP')
                    //     ->select(DB::raw( 'fechaDeEjecucion, Descr, Itemcode, ItemName, UM, ExistGDL, ExistLERMA, WIP, sum(Sc0) S0, sum(Sc1)S1, sum(Sc2)S2, sum(Sc3)S3, sum(Sc4)S4, sum(Sc5)S5, sum(Sc6)S6, sum(Sc7)S7, sum(Sc8)S8, sum(Sc9)S9, sum(Sc10)S10, sum(Sc11)S11, sum(Sc2)S12, sum(Sc13)S13, sum(Sc14)S14, sum(Sc15)S15, sum(Sc16)S16, sum(Sc17)S17, sum(Sc18)S18, sum(Sc19)S19, sum(necesidadTotal)necesidadTotal, OC, Reorden, Minimo, Maximo, TE, Costo,Proveedor, Comprador'))
                    //     ->where('U_C_Orden', 'like', $tipo)
                    //     ->groupBy( "fechaDeEjecucion", 'Descr', 'Itemcode', 'ItemName', 'UM', 'ExistGDL', 'ExistLERMA', 'WIP', 'Costo', 'Proveedor', 'Comprador', 'Reorden', 'Maximo', 'Minimo', 'TE', 'OC');
                    $consulta = DB::select('exec SIZ_SP_MRP ?, ?', ['semana_c', $tipo]);
                break;
        }
        //Definimos las columnas del MRP
        $columns = array(
            ["data" => "Itemcode", "name" => "Código"],
            ["data" => "ItemName", "name" => "Descripción"],
            ["data" => "Descr", "name" => "Grupo"],
            ["data" => "UM", "name" => "UM"],
            ["data" => "ExistGDL", "name" => "Ext. Gdl"],
            ["data" => "ExistLERMA", "name" => "Ext. Lerma"],
            ["data" => "WIP", "name" => "WIP"],
            //["data" => "", "name" => ""],            
        );
            $columns_xls = array(
                ["data" => "Descr", "name" => "Grupo"],
                ["data" => "Itemcode", "name" => "Código"],
                ["data" => "ItemName", "name" => "Descripción"],
                ["data" => "UM", "name" => "UM"],
                ["data" => "ExistGDL", "name" => "EXISTENCIA GDL"],
                ["data" => "ExistLERMA", "name" => "EXISTENCIA LERMA"],
                ["data" => "WIP", "name" => "WIP"],
                //["data" => "", "name" => ""],            
            );
        //dd(array_has($consulta[0], 'ant'));
        //Si existe Cant Anterior agregamos la columna
        if ( array_key_exists('ant', $consulta[0]) ) {
            array_push($columns,["data" => "ant", "name" => "Anterior"]);
            array_push($columns_xls,["data" => "ant", "name" => "Anterior"]);
        } 
        //Obtenemos solo las columnas numericas para agregarlas al Array Columnas
        $numerickeys = array_where(array_keys((array)$consulta[0]), function ($key, $value) {
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
            array_push($columns,["data" => $value, "name" => $name, "defaultContent"=> ".00"]);
            array_push($columns_xls,["data" => $value, "name" => $name, "defaultContent"=> ".00"]);
        }
       
        //agregamos las ultimas columnas pendientes
        array_push($columns,["data" => "necesidadTotal", "name" => "Necesidad"]);
        array_push($columns,["data" => "Necesidad", "name" => "Disp. S/WIP"]);
        array_push($columns,["data" => "OC", "name" => "OC", "defaultContent" => ".00"]);
        array_push($columns,["data" => "Reorden", "name" => "P. Reorden"]);
        array_push($columns,["data" => "Minimo", "name" => "S. Minimo"]);
        array_push($columns,["data" => "Maximo", "name" => "S. Maximo"]);
        array_push($columns,["data" => "TE", "name" => "T.E."]);
        array_push($columns,["data" => "Costo", "name" => "Costo Compras"]);
        array_push($columns,["data" => "Moneda", "name" => "Moneda"]);
        array_push($columns,["data" => "Proveedor", "name" => "Proveedor"]);
        array_push($columns,["data" => "Comprador", "name" => "Comprador"]);
        
        array_push($columns_xls,["data" => "necesidadTotal", "name" => "Necesidad"]);
        array_push($columns_xls,["data" => "Necesidad", "name" => "Disp. S/WIP"]);
        array_push($columns_xls,["data" => "OC", "name" => "OC", "defaultContent" => ".00"]);
        array_push($columns_xls,["data" => "Reorden", "name" => "P. Reorden"]);
        array_push($columns_xls,["data" => "Minimo", "name" => "S. Minimo"]);
        array_push($columns_xls,["data" => "Maximo", "name" => "S. Maximo"]);
        array_push($columns_xls,["data" => "TE", "name" => "T.E."]);
        array_push($columns_xls,["data" => "Costo", "name" => "Costo Compras"]);
        array_push($columns_xls,["data" => "Moneda", "name" => "Moneda"]);
        array_push($columns_xls,["data" => "Proveedor", "name" => "Proveedor"]);
        array_push($columns_xls,["data" => "Comprador", "name" => "Comprador"]);
        



        
        
        return response()->json(array('data'=>$consulta, 'columns'=>$columns, 'columnsxls'=> $columns_xls));
            //collect($consulta)->toJson());
      
            // dd( Datatables::of(collect($consulta))
            //     // ->addColumn('Resto', function ($consulta) {
            //     //     return ($consulta->S13 + $consulta->S14 + $consulta->S15 + $consulta->S16 + $consulta->S17 + $consulta->S18 + $consulta->S19);
            //     // })
            //     ->addColumn('Necesidad', function ($consulta) {
            //         return ($consulta->ExistGDL + $consulta->ExistLERMA) - $consulta->necesidadTotal;
            //     })  
            //     ->make(true));
        } else {
            return redirect()->route('auth/login');
        }
    }
    
      
    public function mrpPDF() 
    {
        $data = json_decode(Session::get('mrp'));
      //  dd($data);
        $pdf = \PDF::loadView('Mod02_Planeacion.ReporteMrpPDF', compact('data'));
        $pdf->setPaper('Letter', 'landscape')->setOptions(['isPhpEnabled' => true]);  
        return $pdf->stream('Siz_MRP' . ' - ' . date("d/m/Y") . '.Pdf');
    }

    public function mrpXLS()
    {
        if (Auth::check()) {
        $path = public_path() . '/assets/plantillas_excel/Mod_02/mrp.xlsx';
        $data = json_decode(Session::get('mrp'), true);
        //se obtienen los nombres de las columnas
        $name_cols = array_pluck( json_decode(Session::get('cols'), true) , ['name']);
        //se obtienen las keys para obtener los valores de cada fila   
        $data_cols = array_pluck(json_decode(Session::get('cols'), true), ['data']);
        $mrp_parameter = Session::get('parameter'); 
        Excel::load($path, function ($excel) use ($data, $name_cols, $data_cols, $mrp_parameter) {
            $excel->sheet('General', function ($sheet) use ($data, $name_cols, $data_cols, $mrp_parameter) {
                $sheet->cell('A4', function ($cell) {
                    $cell->setValue("Fecha de Impresión: ".\AppHelper::instance()->getHumanDate(date("Y-m-d H:i:s")).' '. date("H:i:s"));
                });
                //obtenemos primer fila y la fecha de ejecucion
                $fechaEjecucion =$data[0][ 'fechaDeEjecucion'];
                //se coloca titulo del archivo 
                $sheet->cell('A2', function ($cell)  use ($mrp_parameter){
                    $cell->setValue($mrp_parameter);
                });
                //se coloca fecha de Ejecucion 
                $sheet->cell('A5', function ($cell)  use ($fechaEjecucion){
                    $cell->setValue('Fecha de Actualización: ' . \AppHelper::instance()->getHumanDate($fechaEjecucion));
                });                
                //se colocan los nombres de las columnas en el xls
                $sheet->row(6, $name_cols);
                //obtiene la ultima columna por texto: ejem. "F"      
                $column = \PHPExcel_Cell::stringFromColumnIndex(count($name_cols)-1);
                //ultima celda de encabezado seria:
                $cell = $column . '6';
                //el rango al que quiero aplicar estilo de encabezado
                $range = 'A6:' . $cell;
                $sheet->getStyle($range)->
                applyFromArray(
                    array(
                        'font' => array(
                            'name'      =>  'Arial',
                            'size'      =>  11,
                            'bold'      =>  true,
                            'color' => array('rgb' => '473AC9')
                        ),
                        'borders' => array(
                            'outline' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THICK,
                                
                            ),
                        ),
                    ));
                $sheet->setAutoFilter($range); //esto agrega un filtro encabezados
                $index = 7; // se inicia llenado de datos
                foreach ($data as $row) {
                    //se elabora la fila de acuedo a la cantidad de columnas (de acuerdo al nombre)
                    $fila = [];
                    foreach ($data_cols as $key) {
                        array_push($fila, $row[$key] ?: '0');
                    }
                    //se coloca la fila en el XLS
                    $sheet->row($index, $fila);

                    $index++;
                }
                
                $cant = count($data)+6; //+6 por las primeras filas
                $sheet->getColumnDimension('C')->setAutoSize(false);//ajusta ancho de celda segun texto
                $sheet->getColumnDimension('C')->setWidth(46);
                //ultima columna
                $sheet->getColumnDimension($column)->setAutoSize(true);
                
                //penultima columna
                $column2 = \PHPExcel_Cell::stringFromColumnIndex(count($name_cols) - 2);
                $sheet->getColumnDimension($column2)->setAutoSize(false);
                $sheet->getColumnDimension($column2)->setWidth(40);
                
                //formato para columnas con numeros (negativos rojo y centrados)
                $ultima_column_numero = \PHPExcel_Cell::stringFromColumnIndex(count($name_cols) - 3);
                $sheet->getStyle('E7:'.$ultima_column_numero.$cant)->getNumberFormat()->setFormatCode( '#,##0.00;[red]-#,##0.00');
                $sheet->getStyle('D7:'.$ultima_column_numero.$cant)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                //relleno encabezado semanas otro color
                $ultima_column_sem =\PHPExcel_Cell::stringFromColumnIndex(count($name_cols) - 11);
                $sheet->getStyle('H6:' . $ultima_column_sem . '6')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'startcolor' => array('rgb' => 'E78ABF')
                        ),
                    )
                );

                //relleno blanco para todas las columnas
                $sheet->getStyle('A7:'.$column.$cant)->applyFromArray(
                        array(
                            'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                              'startcolor' => array( 'rgb' => 'FFFFFF' )
                            ),
                        )
                    );
                //$sheet->getStyle('B6:B'.$cant)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                //alinear texto de estas columnas a la izquierda (se le pasa el rango de hasta donde hay datos en la columna)
                //$sheet->getStyle('C6:C'.$cant)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                //ultima columna
                //$sheet->getStyle($column.'6:'.$column.$cant)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
               //penultima columna
                //$sheet->getStyle($column2.'6:'.$column2.$cant)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            });
        })
            ->setFilename('SIZ Resumen de MRP')
           ->export('xlsx', [ 'Set-Cookie' => 'xlscook=done; path=/;' ]);
        } else {
        return redirect()->route('auth/login');
        }
    }
}