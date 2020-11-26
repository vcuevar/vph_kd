@extends('home')
@section('homecontent')

<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12 ">
            <div class="hidden-lg"><br></div>
            <h3 class="page-header">
                Reporte de Defectivos
                <small></small>
            </h3>

        </div>
    </div>
<style>
th {
background: white;
color: black;
font-weight: normal;
}
table {
border-collapse: none;
}
</style>
  
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12 ">
            @include('partials.alertas')
        </div>
    </div>
  
<!-- begin #content -->
{!! Form::open(['url' => 'home/reporte/CALIDAD REPORTE DEFECTIVOS', 'method' => 'POST']) !!}
<div class="row">
    <div class="col-md-3">

        <button value="pdf" name="exportar" class="btn btn-danger" style="margin-top:25px" type="submit"><i class="fa fa-file-pdf-o"></i>
            PDF</button>
        <button value="xls" name="exportar" class="btn btn-success" style="margin-top:25px" type="submit"><i class="fa fa-file-excel-o"></i>
            EXCEL</button>

    </div>
</div>
<br>
<div class="row">
    

    <div class="col-md-3">
       <label for="text_selCuatro">Departamento:</label>
        
        <select data-live-search="true" class="boot-select form-control" title="No has seleccionado nada" data-size="5"
            data-dropup-auto="false" 
             data-live-search-placeholder="Busqueda" id="departamento"
             name="departamento" autofocus required>
        
            @foreach ($deptos as $item)
            <option value="{{$item->llave}}" checked>{{$item->valor}}</option>
            @endforeach
        </select>

    </div>
    <div class="col-md-3">
            <label for="text_selCuatro">Fecha Inicial:</label>
            <input type="text" class="form-control" name="date" placeholder="Selecciona un lunes" required>
             
    </div>
  
 

   
</div><!-- /.row -->

{!! Form::close() !!}
<br>
<!-- end #content -->
    <div class="modal fade" id="confirma" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pwModalLabel">Quitar</h4>
                </div>
                {!! Form::open(['url' => 'home/capturadefectivos/quitar', 'method' => 'POST']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" name="code" id="confirma-id" class="form-control" value="" />
                        <input type="text" name="input-op" id="input-op" value="" class="form-control" style="display: none" />
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



<div class="modal fade" id="modalNuevo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle Defectivo</h4>
            </div>
            <div id="errors" class="col-md-12" ></div>
                {!! Form::open(['id' => 'form-modal', 'url' => 'home/calidad/capturadefectivos/addorupdate', 'method' => 'POST', 'class' => 'form-horizontal form-bordered']) !!}
                <div class="modal-body">

                    <div class="pane-body panel-form">

                        <div class="container-fluid">

                            <div id="modalInsert">

                                <input type="text" name="input-accion" id="input-accion" class="form-control" style="display: none" />
                                <input type="text" name="input-id" id="input-id" class="form-control" style="display: none" />
                             

                               <div class="form-group">
                                    <label class="col-md-3 control-label">Departamento</label>
                                    <div class="col-md-8">
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Operario</label>
                                   <div class="col-md-8">
                                     
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Descripción</label>
                                    <div class="col-md-8">
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Cantidad</label>
                                    <div class="col-md-8">
                                        <input type="number" name="cde_cantidad" id="cde_cantidad" data-parsley-trigger="focusout"
                                            min="1" data-parsley-required="true" class="form-control" step="1"
                                            required/>
                                    </div>
                                </div>                               

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Fecha</label>
                                    <div class="col-md-8">
                                        <input type="date" name="cde_fecha" id="cde_fecha" data-parsley-trigger="focusout"
                                            data-parsley-maxlength="50" data-parsley-required="true" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Inspector</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="cde_inspector" id="cde_inspector" readonly>
                                        <option value="{{Auth::user()->U_EmpGiro}}">{{Auth::user()->firstName.' '.Auth::user()->lastName}}</option>                                      
                                        </select>                                   
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

</div>
@endsection

@section('homescript')
var editor; // use a global for the submit and return data rendering in the examples

$('#confirma').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget) // Button that triggered the modal
var recipient = button.data('whatever') // Extract info from data-* attributes
// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
var modal = $(this)

modal.find('#code').val(recipient)
});
var date_input=$('input[name="date"]'); //our date input has the name "date"

date_input.datepicker({
    todayBtn: true,
format: 'dd/mm/yyyy',
todayHighlight: true,
language: "es",
orientation: "bottom right",
daysOfWeekDisabled: "0,2,3,4,5,6",
daysOfWeekHighlighted: "1",
calendarWeeks: true,
autoclose: true,
weekHeader: 'Sm',
});
@endsection

<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 


</script>
