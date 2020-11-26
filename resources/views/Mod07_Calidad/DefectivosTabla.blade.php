@extends('home')
@section('homecontent')

<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12 ">
            <div class="hidden-lg"><br></div>
            <h3 class="page-header">
                Tabla de Defectivos
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

        .table-condensed>thead>tr>td,
        .table-condensed>tbody>tr>td,
        .table-condensed>tfoot>tr>td {
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
                <table id="data-table" class="stripe table-condensed" style="width:100%">
                    <thead>
                        <tr>
                          
                            <th>Departamento</th>
                            <th>Descripción</th>                            
                            <th>Pond</th>
                            <th>Activo</th>
                            <th>Acciones</th>
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
                {!! Form::open(['url' => 'home/tabladefectivos/quitar', 'method' => 'POST']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" name="code" id="confirma-id" class="form-control" value="" />
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


<div class="modal fade" id="modalNuevo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle Defectivo</h4>
            </div>
            <div id="errors" class="col-md-12" ></div>
                {!! Form::open(['id' => 'form-modal', 'url' => 'home/calidad/tabladefectivos/addorupdate', 'method' => 'POST', 'class' => 'form-horizontal form-bordered']) !!}
                <div class="modal-body">

                    <div class="pane-body panel-form">

                        <div class="container-fluid">

                            <div id="modalInsert">

                                <input type="text" name="input-accion" id="input-accion" class="form-control" style="display: none" />
                                <input type="text" name="input-id" id="input-id" class="form-control" style="display: none" />

                               <div class="form-group">
                                    <label class="col-md-3 control-label">Departamento</label>
                                    <div class="col-md-8">
                                        {!! Form::select('depto', array(), null, ['id' => 'cda_depto',
                                        'class' => 'form-control selectpicker', "data-size" => "8", 'required' => 'required',
                                        "data-style"=>"btn-default", "data-live-search"=>"true", "title"=>"No has seleccionado nada"]) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Descripción</label>
                                    <div class="col-md-8">
                                        <input type="text" name="cda_descripcion" id="cda_descripcion" data-parsley-trigger="focusout"
                                            data-parsley-maxlength="50" data-parsley-required="true"
                                            class="form-control" minlength="1" maxlength="99" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Pond</label>
                                    <div class="col-md-8">
                                        <input type="number" name="cda_pond" id="cda_pond" data-parsley-trigger="focusout"
                                            min="1" data-parsley-required="true" class="form-control"
                                            required/>
                                    </div>
                                </div>                               

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Activo</label>
                                    <div class="col-md-8">
                                        <input type="checkbox" id="cda_activo" name="cda_activo">
                                    </div>
                                </div>

                              

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar</button>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection

@section('script')
var editor; // use a global for the submit and return data rendering in the examples

$('#confirma').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget) // Button that triggered the modal
var recipient = button.data('whatever') // Extract info from data-* attributes
// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
var modal = $(this)

modal.find('#code').val(recipient)
});

var table = $('#data-table').DataTable({
dom: 'Bfrtip',
"order": [[ 0, "desc" ]],
orderCellsTop: true,
scrollX: true,
paging: true,
processing: true,
responsive: true,
deferRender: true,
ajax: {
url: '{!! route('datatables.defectivostabla') !!}',
data: function () {

}
},
columns: [
{ data: 'depto'},
{ data: 'cda_descripcion', name: 'cda_descripcion'},
{ data: 'cda_pond', name: 'cda_pond'},
{ data: 'activo', name: 'activo'},
{ data: 'acciones', name: 'acciones'},
],
buttons:[{
text: '<i class="fa fa-plus"></i> Nuevo',
className: "btn-primary",
action: function ( e, dt, node, config ) {
 callnuevo();
}
}],
"language": {
"url": "{{ asset('assets/lang/Spanish.json') }}",
},
select: {
style: 'os',
selector: 'td:first-child'
},
tableTools: {
    sSwfPath: "{{ asset('assets/DataTables/swf/copy_csv_xls_pdf.swf') }}"
},
columnDefs: [

]
//revision

}); 

$.getScript("{{url('assets/js/defectivostabla.js')}}").done(function() {
        TableManageTableToolsEditor.init();
    });
@endsection

<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
</script>
