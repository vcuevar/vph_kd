@extends('home')
@section('homecontent')

<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12 ">
            <div class="hidden-lg"><br></div>
            <h3 class="page-header">
                Cancelación de Rechazos
                <small>Calidad</small>
            </h3>
          
        </div>
    </div>

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
.table-condensed > thead > tr > td, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > td {
padding: 1px 5px 1px 5px;
}


        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
            visibility: visible;
        }

        td {
            font-family: 'Helvetica';
            font-size: 100%;
        }

        th {
            font-family: 'Helvetica';
            font-size: 100%;
        }
    </style>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12 ">
            @include('partials.alertas')
        </div>
    </div>
    <div class="">
        <div class="">
            <div class="">
                <table id="tsolicitudes" class="stripe table-condensed" style="width:100%">
                    <thead>
                        <tr>
                            <th>F. Revisión</th>
                            <th># Factura</th>
                            <th>Proveedor</th>
                            <th>Descripción</th>
                            <th>UM</th>
                            <th>Cant. Aceptada</th>
                            <th>Cant. Rechazada</th>
                            <th>Cant. Revisada</th>
                            <th>Inspector</th>
                            <th>Descripción Rechazo</th>
                            <th>Quitar</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>

    </div>
    @yield('subcontent-01')

    <div class="modal fade" id="confirma" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pwModalLabel">Quitar</h4>
                </div>
     {!! Form::open(['url' => 'home/cancelaciones/quitar', 'method' => 'POST']) !!}
                <div class="modal-body">
    
                    <div class="form-group">
                      
                            <input type="hidden" name="code" class="form-control" id="code" value="" />
                            <h4>¿Desea continuar?</h4>
                        
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Quitar</button>
                </div>
    {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

$('#confirma').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget) // Button that triggered the modal
var recipient = button.data('whatever') // Extract info from data-* attributes
// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
var modal = $(this)

modal.find('#code').val(recipient)
});

var table = $('#tsolicitudes').DataTable({
dom: 'frtip',
"order": [[ 0, "desc" ]],
orderCellsTop: true,
scrollX: true,
paging: true,
processing: true,
responsive: true,
deferRender: true,
fixedColumns: {
leftColumns: 2
},
ajax: {
url: '{!! route('datatables.cancelacionrechazos') !!}',
data: function () {

}
},
columns: [
    { data: 'fechaRevision',
    render: function(data){
        if (data === null){return data;}
        var d = new Date(data);
        return moment(d).format("DD-MM-YYYY");
    }
    },
    { data: 'DocumentoNumero'},
    { data: 'proveedorNombre'},
    { data: 'materialDescripcion'},
    { data: 'materialUM'},
    { data: 'cantidadAceptada'},
    { data: 'cantidadRechazada'},
    { data: 'cantidadRevisada'},
    { data: 'InspectorNombre'},
    { data: 'DescripcionRechazo'},
    { data: 'action'},
],
"language": {
"url": "{{ asset('assets/lang/Spanish.json') }}",
},
columnDefs: [
    {"targets":0, "type":"date-eu"}
]
//revision

});
@endsection

<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
</script>