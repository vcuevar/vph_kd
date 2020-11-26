@extends('home')
@section('homecontent')
<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 11px;
    }

    th,
    td {
        white-space: nowrap;
    }

    div.container {
        min-width: 980px;
        margin: 0 auto;
    }

    th:first-child {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 4;
    }

    table.dataTable thead .sorting_asc {
        position: sticky;
    }

    .DTFC_LeftBodyWrapper {
        margin-top: 84px;
    }

    .DTFC_LeftHeadWrapper {
        display: none;
    }

    .btn-group {
        //cuando es datatables y custom buttons
        margin-bottom: 0px;

    }

    .btn-group>.btn {
        float: none;
    }

    .btn {
        border-radius: 4px;
    }

    .btn-group>.btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
        border-radius: 4px;
    }

    .btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .btn-group>.btn:last-child:not(:first-child),
    .btn-group>.dropdown-toggle:not(:first-child) {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
</style>
<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-11">
            <h3 class="page-header">
                Reporte de Entradas y Salidas
                <small></small>
            </h3>
            <h5><b>Del:</b> {{\AppHelper::instance()->getHumanDate($fi)}} <b>al:</b>
                {{\AppHelper::instance()->getHumanDate($ff)}}</h5>
            <h5>Actualizado: {{date('d-m-Y h:i a', strtotime("now"))}}</h5>
            <!-- <h5>Fecha & hora: {{\AppHelper::instance()->getHumanDate(date('d-m-Y h:i a', strtotime("now")))}}</h5> -->
        </div>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-md-12" style="margin-top: 0px;">
            <table id="tentradas" class="table table-striped">
                <thead class="table-condensed">
                    <tr>
                        <th># Documento</th>
                       
                        <th>Fecha</th>
                        <th>F. Sistema</th>
                        <th>Movimiento</th>

                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Valor Std</th>
                        <th>TM</th>

                        <th>Usuario</th>
                        <th>Almacén</th>
                        <th>VS</th>
                       
                        <th>Notas</th>
                       
                        <th>Hora</th>
                    </tr>
               
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
               
                </tr>
                </tfoot>
            </table>
        </div> <!-- /.col-md-12 -->

    </div> <!-- /.row -->
    <input hidden value="{{$fi}}" id="fi" name="fi" />
    <input hidden value="{{$ff}}" id="ff" name="ff" />
    <input hidden value="{{$almacenes}}" id="almacenes" name="almacenes" />
    <input hidden value="{{$tipomat}}" id="tipomat" name="tipomat" />
</div>
<!-- /.container -->
@endsection

@section('homescript')
$('#tentradas thead tr').clone(true).appendTo( '#tentradas thead' );

$('#tentradas thead tr:eq(1) th').each( function (i) {
var title = $(this).text();
$(this).html( '<input style="color: black;" type="text" placeholder="Filtro '+title+'" />' );

$( 'input', this ).on( 'keyup change', function () {

if ( table.column(i).search() !== this.value ) {
table
.column(i)
.search(this.value, true, false)
.draw();
}

} );
} );


var table = $('#tentradas').DataTable({
"order": [[ 1, "desc" ],[0, "asc"],[2, "asc"]],
dom: 'Bfrtip',
orderCellsTop: true,
scrollY: "300px",
"pageLength": 50,
scrollX: true,
scrollCollapse: true,
paging: true,
fixedColumns: true,
processing: true,
deferRender: true,
ajax: {
url: '{!! route('datatables.ioWhs') !!}',
data: function (d) {
d.fi = $('input[name=fi]').val();
d.ff = $('input[name=ff]').val();
d.almacenes = $('input[name=almacenes]').val();
d.tipomat = $('input[name=tipomat]').val();
}
},
columns: [
// { data: 'action', name: 'action', orderable: false, searchable: false}
{ data: 'BASE_REF'},
{ data: 'DocDate', 
render: function(data){
var d = new Date(data);
return moment(d).format("DD-MM-YYYY");
}},
{ data: 'CreateDate', 
render: function(data){
var d = new Date(data);
return moment(d).format("DD-MM-YYYY");
}},
{ data: 'JrnlMemo'},

{ data: 'ItemCode', name: 'ItemCode'},
{ data: 'Dscription', name: 'Dscription'},
{ data: 'Movimiento', 
render: function(data){
var val = new Intl.NumberFormat("es-MX", {minimumFractionDigits:2}).format(data);
return val;
}},
{ data: 'STDVAL', 
render: function(data){
var val = new Intl.NumberFormat("es-MX", {minimumFractionDigits:2}).format(data);
return val;
}},
{ data: 'U_TipoMat'},

{ data: 'U_NAME'},
{ data: 'ALM_ORG'},
{ data: 'VSala',
render: function(data){
var val = new Intl.NumberFormat("es-MX", {minimumFractionDigits:2}).format(data);
return val;
}},


{ data: 'Comments'},

{ data: 'DocTime'},
],
buttons: [
{
text: '<i class="fa fa-columns" aria-hidden="true"></i> Columna',
className: "btn btn-primary",
extend: 'colvis',
postfixButtons: [
{
text: 'Restaurar columnas',
extend: 'colvisRestore',
}
]
},
{
text: '<i class="fa fa-copy" aria-hidden="true"></i> Copy',
extend: 'copy',
exportOptions: {
columns: ':visible',
}
},
{
text: '<i class="fa fa-file-excel-o"></i> Excel',
className: "btn-success",
action: function ( e, dt, node, config ) {
var data=table.rows( { filter : 'applied'} ).data().toArray();
var json = JSON.stringify( data );
$.ajax({
type:'POST',
url:'ajaxtosession/entradasysalidas',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
data: {
"_token": "{{ csrf_token() }}",
"arr": json
},
success:function(data){
window.location.href = 'entradasysalidasXLS';
}
});
}
},
{
text: '<i class="fa fa-file-pdf-o"></i> Pdf',
className: "btn-danger",
action: function ( e, dt, node, config ) {
var data=table.rows( { filter : 'applied'} ).data().toArray();
var json = JSON.stringify( data );
$.ajax({
type:'POST',
url:'ajaxtosession/entradasysalidas',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
data: {
"_token": "{{ csrf_token() }}",
"arr": json
},
success:function(data){
window.open('entradasysalidasPDF', '_blank')
}
});
}
}
],
"language": {
"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
buttons: {
copyTitle: 'Copiar al portapapeles',
copyKeys: 'Presiona <i>ctrl</i> + <i>C</i> para copiar o la tecla <i>Esc</i> para continuar.',
copySuccess: {
_: '%d filas copiadas',
1: '1 fila copiada'
}
}
},
columnDefs: [

],
"footerCallback": function ( row, data, start, end, display ) {
var api = this.api(), data;

// Remove the formatting to get integer data for summation
var intVal = function ( i ) {
return typeof i === 'string' ?
i.replace(/[\$,]/g, '')*1 :
typeof i === 'number' ?
i : 0;
};

// Total Cantidad
pageTotal = api
.column( 6, { page: 'current'} )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );
var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})

$( api.column( 6 ).footer() ).html(
pageT
);
// Total Val Standar
pageTotal = api
.column( 7, { page: 'current'} )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );
var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})

$( api.column( 7 ).footer() ).html(
 '$ '+pageT
);
// Total VS
pageTotal = api
.column( 11, { page: 'current'} )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );
var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})

$( api.column( 11 ).footer() ).html(
pageT
);
// Update footer for VS
//.toLocaleString("es-MX",{style:"currency", currency:"MXN"}) //example to format a number to Mexican Pesos
//var n = 1234567.22
//alert(n.toLocaleString("es-MX",{style:"currency", currency:"MXN"}))





}
});
@endsection
<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AYM00_00.pdf","_blank");
  }
  }

</script>