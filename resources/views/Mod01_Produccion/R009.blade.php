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
    left: 0;
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
                Catálogo de Empleados
                <small></small>
            </h3>
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
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Puesto</th>

                        <th>F. Ingreso</th>
                        <th>F. Egreso</th>
                        <th>Indicador</th>
                        <th>Archivo Foto</th>                    
                    </tr>

                </thead>
                <tbody></tbody>
                <tfoot>
                   
                </tfoot>
            </table>
        </div> <!-- /.col-md-12 -->

    </div> <!-- /.row -->
    <input hidden value="{{$option}}" id="option" name="option" />
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;"
                        onerror="this.src='{{URL::asset('/images/articulos/SIN_IMAGEN.jpg')}}'">
                </div>
            </div>
        </div>
    </div>
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

$(document).on("click", ".imagen", function () {
console.log('Ok');
$('.imagepreview').attr('src', $(this).attr('src'));
$('#imagemodal').modal('show');
});

var table = $('#tentradas').DataTable({
"order": [[ 1, "desc" ],[0, "asc"],[2, "asc"]],
dom: 'Bfrtip',
orderCellsTop: true,
scrollY: "300px",
"pageLength": 50,
scrollX: true,
scrollCollapse: true,
paging: true,
fixedColumns: false,
processing: true,
deferRender: true,
ajax: {
url: '{!! route('datatables.R009') !!}',
data: function (d) {
d.empleados = $('input[name=option]').val();
}
},
columns: [
// { data: 'action', name: 'action', orderable: false, searchable: false}
{ data: 'CODIGO'},
{ data: 'NOMBRE'},
{ data: 'DEPARTAMENTO'},
{ data: 'PUESTO'},
{ data: 'FEC_INGR',
render: function(data){
if (data != null){
var d = new Date(data);
return moment(d).format("DD-MM-YYYY");
}else{
return '';
}
}},
{ data: 'FEC_BAJA', 
render: function(data){
   if (data != null){
   var d = new Date(data);
    return moment(d).format("DD-MM-YYYY");
    }else{
    return '';
    }

}},
{ data: 'ESTADO'},

{
"data": "DTFOTO",
"render": function (data) {
    if(data.includes("image")){
        return '<a data-toggle="modal" class="imagen"><img height="45" width="45" src="' +data+ '" /> </a>';
    }else{
        return data;
    }

}
},
],
buttons: [
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

});


@endsection
<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AYM00_00.pdf","_blank");
  }
  }

</script>