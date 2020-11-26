<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

use App\Grupo;
use App\Modelos\MOD01\LOGOF;
use App\Modelos\MOD01\MODULOS_GRUPO_SIZ;
use App\Modelos\MOD01\TAREA_MENU;
use App\OP;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

Route::get('/', 'HomeController@index');
Route::get(
    '/home',
    [
        'as' => 'home',
        'uses' => 'HomeController@index',
    ]
);
/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
 */
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
 */
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::post('passwordUpdate', ['as' => 'passwordUpdate', 'uses' => 'Auth\FunctionsController@cambioPasswordUsers']);
Route::get('viewpassword', ['as' => 'viewpassword', 'uses' => 'Auth\FunctionsController@viewpassword']);
/*
|--------------------------------------------------------------------------
| MOD00-ADMINISTRADOR Routes
|--------------------------------------------------------------------------
 */
Route::get('MOD00-ADMINISTRADOR', 'Mod00_AdministradorController@index');
Route::get('admin/users', 'Mod00_AdministradorController@allUsers');
Route::get('admin/detalle-depto/{depto}', 'Mod00_AdministradorController@showUsers');
Route::get('admin/plantilla/{depto}', 'Mod00_AdministradorController@PlantillaExcel');
Route::get('admin/Plantilla_PDF/{depto}', 'Mod00_AdministradorController@Plantilla_PDF');
Route::get('datatables.showusers', 'Mod00_AdministradorController@DataShowUsers')->name('datatables.showusers');
Route::get('users/edit/{empid}', 'Mod00_AdministradorController@editUser');
Route::post('cambio.password', 'Mod00_AdministradorController@cambiopassword');
//Rutas del Módulo de inventarios
Route::get('admin/inventario', 'Mod00_AdministradorController@inventario');
Route::get('datatables.inventario', 'Mod00_AdministradorController@DataInventario')->name('datatables.inventario');
Route::post('admin/reporte/inventario', 'Mod00_AdministradorController@backOrderAjaxToSession');
Route::get('admin/reporte/inventarioComputoPDF', 'Mod00_AdministradorController@ReporteInventarioComputoPDF');
Route::get('admin/generarPdf/{id}', 'Mod00_AdministradorController@generarPdf');

Route::get('admin/inventarioObsoleto', 'Mod00_AdministradorController@inventarioObsoleto');

Route::get('admin/altaInventario', 'Mod00_AdministradorController@altaInventario');
Route::post('admin/altaInventario', 'Mod00_AdministradorController@saveInventario');
Route::post('admin/ModInventario', 'Mod00_AdministradorController@ModInventario');
Route::get('/admin/altaMonitor', 'Mod00_AdministradorController@altaMonitor');
Route::post('admin/altaMonitor', 'Mod00_AdministradorController@altaMonitor2');

Route::get('admin/monitores', 'Mod00_AdministradorController@monitores');
Route::get('admin/mark_obs/{id}', 'Mod00_AdministradorController@mark_obs');
Route::get('admin/mark_rest/{id}', 'Mod00_AdministradorController@mark_rest');
Route::get('admin/delete_inv/{id}', 'Mod00_AdministradorController@delete_inv');
Route::get('admin/mod_inv/{id}', 'Mod00_AdministradorController@mod_inv');
Route::get('admin/mod_mon/{id}/{mensaje}', 'Mod00_AdministradorController@mod_mon');
Route::post('admin/mod_mon2', 'Mod00_AdministradorController@mod_mon2');
Route::post('admin/mod_inv2', 'Mod00_AdministradorController@mod_inv2');
Route::get('admin/grupos/{id}', 'Mod00_AdministradorController@editgrupos');
Route::post('admin/createModulo/{id}', 'Mod00_AdministradorController@createModulo');
Route::post('admin/createMenu/{id}', 'Mod00_AdministradorController@createMenu');
Route::post('admin/createTarea/{id_grupo}', 'Mod00_AdministradorController@createTarea'); //si se usa
Route::get('admin/grupos/delete_modulo/{grupo}/{id}', 'Mod00_AdministradorController@deleteModulo');
Route::get('admin/grupos/conf_modulo/{grupo}/{id}', 'Mod00_AdministradorController@confModulo');
Route::get('admin/grupos/conf_modulo/{grupo}/quitar-tarea/{id}', 'Mod00_AdministradorController@deleteTarea');
Route::get('datatable/{idGrup}/{idMod}', 'Mod00_AdministradorController@confModulo');
Route::get('datatables.data', 'Mod00_AdministradorController@anyData')->name('datatables.data');

/*
|--------------------------------------------------------------------------
|NOTICIAS Y NOTIFICACIONES 'BRAYAN'
|--------------------------------------------------------------------------
*/
Route::get('admin/Nueva', 'Mod00_AdministradorController@Noticia');
Route::get('admin/emails', 'Mod00_AdministradorController@Email');
Route::post('admin/Nueva', 'Mod00_AdministradorController@Noticia2');
Route::post('admin/save/email', 'Mod00_AdministradorController@saveEmail');
Route::get('admin/email/del/{id}', 'Mod00_AdministradorController@deleteEmail');
Route::get('admin/Notificaciones', 'Mod00_AdministradorController@Notificacion');
Route::post('admin/Notificaciones', 'Mod00_AdministradorController@Notificacion2');
Route::get('admin/Mod_Noti/{id}/{mensaje}', 'Mod00_AdministradorController@Mod_Noti');
Route::post('admin/Mod_Noti2/', 'Mod00_AdministradorController@Mod_Noti2');
Route::get('admin/delete_Noti/{id}', 'Mod00_AdministradorController@delete_Noti');
Route::post('admin/delete_Noti/', 'Mod00_AdministradorController@delete_Noti');
//Route::get('admin/Nueva', 'Mod00_AdministradorController@Show');


Route::get('updateprivilegio', 'Mod00_AdministradorController@updateprivilegio');
Route::get('dropdown', function () {
    return TAREA_MENU::where('id_menu_item', Input::get('option'))
        ->lists('name', 'id');
});
Route::get('switch', function () {
    $vava = MODULOS_GRUPO_SIZ::find(2);
    $vava->id_menu = null;
    $vava->save();
    var_dump(count(MODULOS_GRUPO_SIZ::find(1)));
});
Route::post('nuevatarea', 'Mod00_AdministradorController@nuevatarea');
/*
|--------------------------------------------------------------------------
| MOD01-PRODUCCION Routes
|--------------------------------------------------------------------------
*/
Route::get('home/TRASLADO ÷ AREAS', [
    'middleware' => 'routelog', 'as' => 'traslado', 'uses' => 'Mod01_ProduccionController@traslados'
]);
Route::post('home/TRASLADO ÷ AREAS', 'Mod01_ProduccionController@traslados');
Route::get('home/TRASLADO ÷ AREAS/{id}', 'Mod01_ProduccionController@getOP');
Route::post('home/TRASLADO ÷ AREAS/{id}', 'Mod01_ProduccionController@getOP');
//la siguiente ruta avanza la orden //
Route::post('home/traslados/avanzar', 'Mod01_ProduccionController@avanzarOP');
Route::post('home/traslados/Reprocesos', 'Mod01_ProduccionController@Retroceso');
//Route::get('home/traslados/Reprocesos', 'Mod01_ProduccionController@getOP');
Route::post('/', 'HomeController@index');
Route::get('Mod01_Produccion/Noticias', 'HomeController@create');
Route::get('leido/{id}', 'HomeController@UPT_Noticias');
Route::post('/leido', 'HomeController@UPT_Noticias');
//REPORTE DE PRODUCCION
Route::get('home/REPORTE PRODUCCION', 'Reportes_ProduccionController@produccion1')->middleware('routelog');
Route::post('home/REPORTE PRODUCCION', 'Reportes_ProduccionController@produccion1');
//REPORTE 112-CORTE PIEL///
Route::get('home/112 CORTE DE PIEL', 'Mod01_ProduccionController@repCortePiel')->middleware('routelog');
Route::post('home/112 CORTE DE PIEL', 'Mod01_ProduccionController@repCortePiel');
Route::post('home/reporte/DetinsPiel', 'Mod01_ProduccionController@repCortePiel');
Route::get('home/repCortePielExl', 'Mod01_ProduccionController@repCortePielExl');
//PDF de Historial por OP
Route::get('home/ReporteOpPDF/{op}', 'Mod01_ProduccionController@ReporteOpPDF');
Route::get('home/ReporteMaterialesPDF/{op}', 'Mod01_ProduccionController@ReporteMaterialesPDF');
Route::get('home/ReporteProduccionPDF', 'Reportes_ProduccionController@ReporteProduccionPDF');
Route::get('home/ReporteProduccionEXL', 'Reportes_ProduccionController@ReporteProduccionEXL');
//REPORTE DE HISTORIAL X OP
Route::get('home/HISTORIAL OP', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/HISTORIAL OP', 'Reportes_ProduccionController@historialOP');
Route::get('home/reporte/historialXLS', 'Reportes_ProduccionController@historialOPXLS');
//REPORTE DE MATERIALES X OP
Route::get('home/MATERIALES OP', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/MATERIALES OP', 'Reportes_ProduccionController@materialesOP');
Route::get('home/reporte/materialesXLS', 'Reportes_ProduccionController@materialesOPXLS');
//REPORTE BACK ORDER
Route::get('home/BACK ORDER', 'Reportes_ProduccionController@backorder')->middleware('routelog');
Route::get('datatables.showbackorder', 'Reportes_ProduccionController@DataShowbackorder')->name('datatables.showbackorder');
Route::post('home/reporte/backorderPDF', 'Reportes_ProduccionController@backOrderAjaxToSession');
Route::get('home/reporte/backorderVentasPDF', 'Reportes_ProduccionController@ReporteBackOrderVentasPDF');
Route::get('home/reporte/backorderPlaneaPDF', 'Reportes_ProduccionController@ReporteBackOrderPlaneaPDF');
Route::get('home/reporte/backorderXLS', 'Reportes_ProduccionController@ReporteBackOrderXLS');
//RPORTE BACK ORDER CASCO
Route::get('home/BACK ORDER CASCO', 'Reportes_ProduccionController@backorderCasco')->middleware('routelog');
Route::get('datatables.showbackordercasco', 'Reportes_ProduccionController@DataShowbackorderCasco')->name('datatables.showbackordercasco');

Route::get('home/reporte/backorderCascoPDF', 'Reportes_ProduccionController@ReporteBackOrderCascoPDF');
//Ruta generica para guardar ajaxtoSession
Route::post('home/reporte/ajaxtosession/{id}', 'Reportes_ProduccionController@AjaxToSession');
/*
    |--------------------------------------------------------------------------
    | MOD07-CALIDAD Routes
    |--------------------------------------------------------------------------
    */
Route::get('home/NUEVO RECHAZO', 'Mod07_CalidadController@Rechazo')->middleware('routelog');
Route::post('RechazosNuevo', 'Mod07_CalidadController@RechazoIn');
Route::get('Mod07_Calidad/Mod_Rechazo/{id}/{mensaje}', 'Mod07_CalidadController@Mod_Rechazo');
Route::post('Mod07_Calidad/Mod_RechazoUPDT', 'Mod07_CalidadController@Mod_RechazoUPDT');
Route::get('admin/Delete_Rechazo/{id}', 'Mod07_CalidadController@Delete_Rechazo');
Route::post('admin/Delete_Rechazo/', 'Mod07_CalidadController@Delete_Rechazo');
Route::get('search/autocomplete', 'Mod07_CalidadController@autocomplete');
Route::post('/pdfRechazo', 'Mod07_CalidadController@Pdf_Rechazo');
Route::get('home/REPORTE DE RECHAZOS', 'Mod07_CalidadController@Reporte')->middleware('routelog');
Route::get('home/CANCELACIONES', 'Mod07_CalidadController@Cancelado')->middleware('routelog');

Route::get('datatables.cancelacionrechazos', 'Mod07_CalidadController@DataShowCancelaciones')->name('datatables.cancelacionrechazos');

//Route::get('borrado/{id}', 'Mod07_CalidadController@UPT_Cancelado');
Route::post('home/cancelaciones/quitar', 'Mod07_CalidadController@UPT_Cancelado');
Route::get('home/HISTORIAL', 'Mod07_CalidadController@Historial')->middleware('routelog');
Route::post('/excel', 'Mod07_CalidadController@excel');
////reporte calidad
Route::get('home/CALIDAD POR DEPTO', 'Mod07_CalidadController@repCalidad')->middleware('routelog');
Route::post('home/CALIDAD POR DEPTO', 'Mod07_CalidadController@repCalidad2');
Route::get('getAutocomplete', function () {
    return view('Mod07_Calidad.RechazoFrame');
})->name('getAutocomplete');
Route::get('search', array('as' => 'search', 'uses' => 'Mod07_CalidadController@search'));
Route::get('autocomplete', array('as' => 'autocomplete', 'uses' => 'Mod07_CalidadController@autocomplete'));
//
//-------------------------//
//RUTAS DE RECURSOS HUMANOS//---------------------------------------------------------
//-------------------------//
//
Route::get('home/CALCULO DE BONOS', 'Mod10_RhController@parametrosmodal')->middleware('routelog');
//Route::get('home/rh/reportes/bonos','Mod10_RhController@calculoBonos');
Route::post('home/rh/reportes/bonos', 'Mod10_RhController@calculoBonos');
Route::get('home/PARAMETROS BONOS', 'Mod10_RhController@setParametrosBonos')->middleware('routelog');
Route::post('home/PARAMETROS BONOS', 'Mod10_RhController@setParametrosBonos2');
Route::get('home/rh/reportes/bonosPdf', 'Mod10_RhController@bonosPdf');
Route::get('home/BONOS CORTE', 'Mod10_RhController@bonosCorte')->middleware('routelog');
Route::post('home/rh/reportes/bonosCorte', 'Mod10_RhController@calculoBonosCorte');
Route::get('home/rh/reportes/bonoscortePdf', 'Mod10_RhController@bonoscortePdf');
Route::get('home/rh/reportes/bonoscorteEXL', 'Mod10_RhController@bonoscorteEXL');
Route::get('home/mod_parametro/{id}', 'Mod10_RhController@mod_parametro');
Route::post('home/mod_parametro2/{id}', 'Mod10_RhController@mod_parametro2');
Route::get('home/delete_parametro/{id}', 'Mod10_RhController@delete_parametro');
//
//-------------------------//
//RUTAS DE COMPRAS//---------------------------------------------------------
//-------------------------//
//
Route::get('home/CONSULTA OC', 'Mod03_ComprasController@pedidosCsv')->middleware('routelog');
Route::post('home/CONSULTA OC', 'Mod03_ComprasController@postPedidosCsv');
Route::get('home/desPedidosCsv', 'Mod03_ComprasController@desPedidosCsv');
Route::get('home/PedidosCsvPDF', 'Mod03_ComprasController@PedidosCsvPDF');
//
//-------------------------//
//RUTAS DE MATERIALES ALMACEN//---------------------------------------------------------
//-------------------------//
//
Route::get('home/ENTRADAS ALMACEN', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/ENTRADAS ALMACEN', 'Mod04_MaterialesController@reporteEntradasAlmacen');
Route::get('home/reporte/ENTRADAS ALMACEN', 'Mod04_MaterialesController@reporteEntradasAlmacenPDF');
Route::get('home/reporte/entradasXLS', 'Mod04_MaterialesController@entradasXLS');
Route::get('datatables.showentradasmp', 'Mod04_MaterialesController@DataShowEntradasMP')->name('datatables.showentradasmp');
Route::get('home/reporte/entradasPDF', 'Mod04_MaterialesController@entradasPDF');

//DESPLIEGUE DATOS MAESTROS ARTICULOS
Route::get('home/DATOS MAESTROS ARTICULOS', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/DATOS MAESTROS ARTICULOS/{redirek?}', 'Mod04_MaterialesController@DM_Articulos');
Route::post('articuloToSap', 'Mod04_MaterialesController@articuloToSap');
Route::get('OITM.show', 'HomeController@ShowArticulos')->name('OITM.show');
//
//SOLICITUD DE MATERIALES
Route::get('home/1 SOLICITUD MATERIALES', 'Mod04_MaterialesController@solicitudMateriales')->middleware('routelog');
Route::get('OITM.WH.show', 'Mod04_MaterialesController@ShowArticulosWH')->name('OITM.WH.show');
Route::post('home/saveArt', 'Mod04_MaterialesController@saveArt')->name('home/saveArt');
//AUTORIZACION
Route::get('home/2 AUTORIZACION', 'Mod04_MaterialesController@AutorizacionSolicitudes')->middleware('routelog');
Route::get('datatables.Solicitudes_Auht', 'Mod04_MaterialesController@DataSolicitudes_Auht')->name('datatables.Solicitudes_Auht');
Route::get('home/AUTORIZACION/solicitud/{id}', 'Mod04_MaterialesController@ShowDetalleSolicitud');
Route::get('home/DATOS MAESTROS ARTICULO/{id}', 'Mod04_MaterialesController@DM_Articulo');
Route::post('home/AUTORIZACION/solicitud/articulos/remove', 'Mod04_MaterialesController@removeArticuloNoAutorizado');
Route::post('home/AUTORIZACION/solicitud/articulos/edit', 'Mod04_MaterialesController@editArticulo');
Route::get('home/AUTORIZACION/solicitud/articulos/return/{id}', 'Mod04_MaterialesController@returnArticuloSolicitud');
Route::get('home/AUTORIZACION/solicitud/update/{id}', 'Mod04_MaterialesController@Solicitud_A_Picking');
//3 PICKING ARTICULOS
Route::get('home/2 PICKING ARTICULOS', 'Mod04_MaterialesController@pickingArticulos')->middleware('routelog');
Route::get('datatables.solicitudesMP', 'Mod04_MaterialesController@DataSolicitudes')->name('datatables.solicitudesMP');
Route::get('home/2 PICKING ARTICULOS/solicitud/{id}/{qr_itemcode?}/{qr_cant?}', 'Mod04_MaterialesController@ShowDetalleSolicitud');
Route::post('home/PICKING ARTICULOS/solicitud/articulos/remove', 'Mod04_MaterialesController@removeArticuloSolicitud');
Route::get('home/PICKING ARTICULOS/solicitud/articulos/return/{id}', 'Mod04_MaterialesController@returnArticuloSolicitud');
Route::get('home/PICKING ARTICULOS/solicitud/PDF/{id}', 'Mod04_MaterialesController@SolicitudPDF');
Route::get('home/PICKING ARTICULOS/solicitud/update/{id}', 'Mod04_MaterialesController@Solicitud_A_Traslados');
Route::post('home/PICKING ARTICULOS/solicitud/articulos/edit', 'Mod04_MaterialesController@editArticuloPicking');
Route::get('home/lotes/{tabla}/{alm}/{item}', 'Mod04_MaterialesController@vistaLotes');
Route::post('home/lotes/insert', 'Mod04_MaterialesController@insertLotes');
Route::get('home/lotes/remove/{id}/{lote}/{alm}', 'Mod04_MaterialesController@removeLote');
Route::get('disponibilidadAlmacenMP', function(){
     
    $data = DB::select("SELECT 
		 COALESCE (SUM(CASE WHEN WhsCode = 'APG-PA'  
         THEN OnHand ELSE 0 END) - (COALESCE (t1.A, 0) + COALESCE (tr.A, 0)), 0) 
         AS stockapgpa, 
		 COALESCE (SUM(CASE WHEN WhsCode = 'AMP-ST' 
          THEN OnHand ELSE 0 END) - (COALESCE (t1.B, 0) + COALESCE (tr.B, 0)), 0) 
          AS stockampst		
        FROM dbo.OITW
		LEFT JOIN (select ItemCode, sum(Cant_ASurtir_Origen_A)  A , sum(Cant_ASurtir_Origen_B)  B  
        from SIZ_MaterialesSolicitudes where 
		  EstatusLinea in ('S', 'P', 'I', 'E', 'N')
		 group by ItemCode) as t1 on t1.ItemCode = OITW.ItemCode
		 LEFT JOIN(
		 select itemCode,
		 SUM(CASE WHEN sol.AlmacenOrigen = 'APG-PA'  THEN Cant_ASurtir_Origen_A ELSE 0 END) A,
		 SUM(CASE WHEN sol.AlmacenOrigen = 'AMP-ST'  THEN Cant_ASurtir_Origen_A ELSE 0 END) B		
		 from SIZ_MaterialesTraslados mat
		 LEFT JOIN SIZ_SolicitudesMP sol on sol.Id_Solicitud = mat.Id_Solicitud
			where mat.EstatusLinea in ('S', 'P', 'I', 'E', 'N')
			group by ItemCode
		 ) tr on tr.ItemCode = OITW.ItemCode
		where OITW.ItemCode = ?
        GROUP BY OITW.ItemCode, t1.A, t1.B, tr.A, tr.B",[Input::get('codigo')]);
        return $data;
        });
// 4 TRASLADOS
Route::get('home/4 GENERAR TRASLADO', 'Mod04_MaterialesController@TrasladosArticulos')->middleware('routelog');
Route::get('datatables.solicitudesTraslados', 'Mod04_MaterialesController@DataTraslados')->name('datatables.solicitudesTraslados');
Route::get('home/TRASLADOS/solicitud/{id}', 'Mod04_MaterialesController@ShowDetalleTraslado');
Route::get('home/pdf/solicitud/{id}', 'Mod04_MaterialesController@ShowDetallePdf');
Route::get('home/pdf/solicitud/PDF/{id}', 'Mod04_MaterialesController@SolicitudPDF_Traslados');
Route::get('home/TRASLADOS/solicitud/update/{id}', 'Mod04_MaterialesController@HacerTraslados');
Route::get('home/TRASLADOS/solicitud/updatepicking/{id}', 'Mod04_MaterialesController@Solicitud_A_PickingTraslados');
Route::get('home/TRASLADOS/solicitud/PDF/traslado/{transfer}', 'Mod04_MaterialesController@getPdfTraslado');
//5 TRASLADOS DEPTOS ENTREGA
Route::get('home/TRASLADO ENTREGA', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::get('home/reporte/TRASLADO ENTREGA', 'Reportes_ProduccionController@showModal');
Route::post('home/reporte/TRASLADO ENTREGA', 'Mod04_MaterialesController@trasladoEntrega');
Route::get('home/reporte2/TRASLADO ENTREGA', 'Mod04_MaterialesController@trasladoEntrega');
Route::get('OITM.WH.traslados', 'Mod04_MaterialesController@ShowArticulosWHTraslados')->name('OITM.WH.traslados');
Route::post('home/reporte/saveTraslado', 'Mod04_MaterialesController@saveTraslado')->name('home/reporte/saveTraslado');
Route::get('home/PDF/traslado/{transfer}', 'Mod04_MaterialesController@getPdfTraslado');
Route::post('home/PDF/traslado', 'Mod04_MaterialesController@getPdfTraslado');
Route::post('home/PDF/solicitud', 'Mod04_MaterialesController@getPdfSolicitud');
Route::get('lotesdeptos/{id?}', 'Mod04_MaterialesController@lotesdeptos');
Route::get('home/TRASLADO ENTREGA/update/{almacen_origen}/{id}', 'Mod04_MaterialesController@HacerEntrega');
Route::get('home/entregas_lotes', 'Mod04_MaterialesController@Entregaslotes');
Route::get('datatables.Entregaslotes', 'Mod04_MaterialesController@DataEntregaslotes')->name('datatables.Entregaslotes');
//6 TRASLADOS DEPTOS RECEPCION
Route::get('home/TRASLADO RECEPCION', 'Mod04_MaterialesController@TrasladosDeptos')->middleware('routelog');
Route::get('datatables.traslados', 'Mod04_MaterialesController@DataTrasladosDeptos')->name('datatables.traslados');
Route::get('home/TRASLADO RECEPCION/solicitud/{id}', 'Mod04_MaterialesController@ShowDetalleTrasladoDeptos');
Route::post('home/TRASLADO RECEPCION/solicitud/articulos/remove', 'Mod04_MaterialesController@removeArticuloTrasladoDepto');
Route::get('home/TRASLADO RECEPCION/solicitud/articulos/return/{id}', 'Mod04_MaterialesController@returnArticuloTrasladosDepto');
Route::get('home/TRASLADO RECEPCION/solicitud/update/{id}', 'Mod04_MaterialesController@updateArticuloTrasladoDepto');
Route::post('home/TRASLADO RECEPCION/solicitud/articulos/edit', 'Mod04_MaterialesController@editArticuloTrasladosDepto');
Route::get('home/TRASLADO RECEPCION/solicitud/PDF/traslado/{transfer}', 'Mod04_MaterialesController@getPdfTraslado');

//REPORTE DE ENTRADAS Y SALIDAS
Route::get('home/ENTRADAS SALIDAS', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/ENTRADAS SALIDAS', 'Mod04_MaterialesController@EntradasSalidas');
Route::get('datatables.ioWhs', 'Mod04_MaterialesController@DataShowEntradasSalidas')->name('datatables.ioWhs');
Route::get('home/reporte/ENTRADAS SALIDAS', 'Mod04_MaterialesController@reporteiowhsPDF');
Route::get('home/reporte/entradasysalidasXLS', 'Mod04_MaterialesController@iowhsXLS');
Route::get('home/reporte/entradasysalidasPDF', 'Mod04_MaterialesController@iowhsPDF');

//REPORTE TRANSFERENCIAS PENDIENTES
Route::get('home/TRANSFERENCIAS PENDIENTES', 'Mod04_MaterialesController@TransferenciasPendientes')->middleware('routelog');
Route::get('datatables.transferencias_pendientes', 'Mod04_MaterialesController@DataShowTransferenciasPendientes')->name('datatables.transferencias_pendientes');

//
//-------------------------//
//RUTAS DE MRP//---------------------------------------------------------
//-------------------------//
//
Route::get('home/MRP', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/MRP', 'Mod02_PlaneacionController@reporteMRP');
Route::get('home/reporte/mrpXLS', 'Mod02_PlaneacionController@mrpXLS');
Route::get('datatables.showmrp', 'Mod02_PlaneacionController@DataShowMRP')->name('datatables.showmrp');
Route::get('home/reporte/mrpPDF', 'Mod02_PlaneacionController@mrpPDF');

Route::get('home/ACTUALIZAR MRP', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/ACTUALIZAR MRP', 'Mod02_PlaneacionController@actualizaMRP');
///Ruta Ayudas
Route::get('home/ayudas_pdf/{PdfName}', 'HomeController@showPdf');
Route::get('home/{r0}/ayudas_pdf/{PdfName}', 'HomeController@showPdf2');
//
//-------------------------//
//RUTAS DE PRODUCCION POR AREAS//---------------------------------------------------------
//-------------------------//
//
Route::get('home/PRODUCCION POR AREAS', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/PRODUCCION POR AREAS', 'Reportes_ProduccionController@reporteProdxAreas');
Route::get('home/reporte/PRODUCCION POR AREAS', 'Reportes_ProduccionController@reporteProdxAreasPDF');
Route::get('home/reporte/produccionxareasXLS', 'Reportes_ProduccionController@produccionxareasXLS');

Route::get('/pruebas', function (Request $request) {
   $var1= \Artisan::call('cache:clear');
   $var= Artisan::call('config:cache');
    dd($var1);
    $tareas = DB::table('Siz_Tarea_Menu')->get();

    foreach ($tareas as $k) {
        DB::update('update Siz_Tarea_Menu set route = ? where name = ?', [$k->name, $k->name]);
    }
   
});

Route::get('/crear-orden', function (Request $request) {
\AppHelper::instance()->CreateOrder();
});

Route::get('/sap-test', function (Request $request) {
    $vCmp = new COM ('SAPbobsCOM.company') or die ("Sin conexión");
    $vCmp->DbServerType="6"; 
    $vCmp->server = "SERVER-SAPBO";
    $vCmp->LicenseServer = "SERVER-SAPBO:30000";
    $vCmp->CompanyDB = "SALOTTO";
    $vCmp->username = "controlp";
    $vCmp->password = "190109";
    $vCmp->DbUserName = "sa";
    $vCmp->DbPassword = "B1Admin";
    $vCmp->UseTrusted = false;
    $vCmp->language = "6";
    $lRetCode = $vCmp->Connect;
    if ($lRetCode <> 0) {
       Session::flash('sap', $vCmp->GetLastErrorDescription());
    } else {
        Session::flash('sap',' - conexión con SAP DI API exitosa!!');
    }  
    return view('welcome');
    
 });

Route::post('home/traslados/terminar', 'Mod01_ProduccionController@terminarOP');
//IMPRESION ETIQUETAS QR
Route::get('home/GENERACION ETIQUETAS', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/GENERACION ETIQUETAS/{redirek?}', 'Mod04_MaterialesController@QR_Articulos');
Route::post('etiquetaQR', 'Mod04_MaterialesController@generaEtiquetaQR');
Route::get('OITM.show', 'HomeController@ShowArticulos')->name('OITM.show');
//Visualizacion de Articulos para INVITADOS
Route::get('qr/{itemCode}/{proveedor}/{cantXbulto}', 'GuestController@getArticulo')->name('qr')->middleware('guest');
Route::get('qr/{itemCode}/{proveedor}/{cantXbulto}', 'Mod04_MaterialesController@getArticulo')->name('qr2');

//CAPTURA DEFECTIVOS
Route::get('home/CALIDAD CAPTURA DEFECTIVOS', 'Reportes_ProduccionController@showModal')->middleware('routelog');
Route::post('home/reporte/CALIDAD CAPTURA DEFECTIVOS', 'Mod07_CalidadController@showCapturaDefectivos');
Route::any('home/reporte/CALIDAD CAPTURA DEFECTIVOS', 'Mod07_CalidadController@showCapturaDefectivos')->name('defectoscaptura');

Route::get('datatables.defectivoscaptura', 'Mod07_CalidadController@DataShowDefectivosCaptura')->name('datatables.defectivoscaptura');
Route::any('home/reporte/calidad/capturadefectivos/combobox', 'Mod07_CalidadController@comboboxCapturaDefectivos');
Route::any('home/reporte/calidad/capturadefectivos/combobox2', 'Mod07_CalidadController@comboboxCapturaDefectivosOperarios');
Route::post('home/calidad/capturadefectivos/addorupdate', 'Mod07_CalidadController@capturadefectivos_addorupdate');
Route::post('home/capturadefectivos/quitar', 'Mod07_CalidadController@CDE_quitar');

//TABLA DEFECTIVOS
Route::get('home/CALIDAD DEFECTIVOS X AREA', 'Mod07_CalidadController@showDefectivosTabla')->middleware('routelog');
Route::get('datatables.defectivostabla', 'Mod07_CalidadController@DataShowDefectivosTabla')->name('datatables.defectivostabla');
Route::any('home/calidad/tabladefectivos/combobox', 'Mod07_CalidadController@combobox');
Route::post('home/calidad/tabladefectivos/addorupdate', 'Mod07_CalidadController@defectivos_addorupdate');
Route::post('home/tabladefectivos/quitar', 'Mod07_CalidadController@CDA_quitar');

//REPORTE DEFECTIVOS

Route::get('home/CALIDAD REPORTE DEFECTIVOS', 'Mod07_CalidadController@showReporteDefectivos')->middleware('routelog');
Route::post('home/reporte/CALIDAD REPORTE DEFECTIVOS', 'Mod07_CalidadController@exportarReporteDefectivos');
Route::post('home/reporte/009 CATALOGO DE EMPLEADOS', 'Reportes_ProduccionController@R009');
Route::get('datatables.R009', 'Reportes_ProduccionController@DataShow009')->name('datatables.R009');