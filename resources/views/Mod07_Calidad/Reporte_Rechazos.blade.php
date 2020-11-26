@extends('home')

            @section('homecontent')


                <div class="container" >

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="page-header">
                                {{env('EMPRESA_NAME')}}
                                <small>Recepción de Materiales</small>
                            </h3>
                           
                                                      
                          
                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">

                        <div class="col-md-12 ">
                            @include('partials.alertas')                        
                        </div>

<style>
    .options {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 40%;
}
</style>
                        <!-- Modal -->

  <div class="modal fade" id="pass" role="dialog" >
      <div class="modal-dialog modal-md" role="document">
           <div class="modal-content" >
              <div class="modal-header">

                   <h4 class="modal-title" id="pwModalLabel">Reporte de Rechazos <small><i data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-question-sign"  title="Ayuda Shift+F1"></i></small></h4>
              </div>
              {!! Form::open(['url' => 'pdfRechazo', 'method' => 'POST']) !!}

              <div class="modal-body">
                   <input type="text" hidden name="send" value="send">
                  <div class="form-group">
                       @include('partials.alertas')
                  </div>
                  <div class="form-group">
                      <label for="date_range" class="control-label">Rango Fecha de Revisión:</label><br>
                       Desde:<input type="date" id="FechIn" name="FechIn" class="form-control" required>
                      Hasta:<input type="date" id="FechaFa" name="FechaFa" class="form-control" required >
                      </div>
                                     
                      <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Filtros  de Busqueda
        <span class="glyphicon glyphicon-chevron-down"></span>
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
      
                      <div class="form-group">
                                                <label for="text_selCuatro">Proveedor:</label>
                                                
                                                <select 
                                                data-live-search="true" 
                                                class="boot-select form-control" 
                                                title="No has seleccionado nada" 
                                                data-size="5"
                                                data-dropup-auto="false" 
                                                data-select-all-text="Marcar Todos"
                                                data-deselect-all-text="Desmarcar Todos"
                                                data-selected-text-format="count"
                                                data-count-selected-text="{0} Seleccionados"   
                                                data-live-search-placeholder="Busqueda" 
                                                id="arti"  
                                                name="arti" 
                                                autofocus >

                                                    @foreach ($Proveedores as $proveedor)
                                                        <option value="{{$proveedor->proveedorId}}" >{{$proveedor->proveedorId}} - {{$proveedor->proveedorNombre}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
<div class="form-group">
                                                <label for="text_selCuatro">Artículo:</label>
                                                
                                                <select 
                                                data-live-search="true" 
                                                class="boot-select form-control" 
                                                title="No has seleccionado nada" 
                                                data-size="5"
                                                data-dropup-auto="false" 
                                                data-select-all-text="Marcar Todos"
                                                data-deselect-all-text="Desmarcar Todos"
                                                data-selected-text-format="count"
                                                data-count-selected-text="{0} Seleccionados"   
                                                data-live-search-placeholder="Busqueda" 
                                                id="arti"  
                                                name="arti" 
                                                autofocus >

                                                    @foreach ($Articulos as $Articulo)
                                                        <option value="{{$Articulo->materialCodigo}}" >{{$Articulo->materialCodigo}} - {{$Articulo->materialDescripcion}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>                                    

<div>
<form id="form1" name="form1">
   <fieldset>
        <label>Registros :</label><br>
        <label class="radio-inline">
      <input  type="radio" name="registro"value="0" checked>Todos
      </label>
  
      <label class="radio-inline">
      <input  type="radio" name="registro" value="1">Solo Rechazados
      </label>
      
      <label class="radio-inline">
      <input  type="radio" name="registro"  value="2" >Solo Aceptados
      </label>
   </fieldset>
     
<fieldset>
 <label >Opciones de exportación :</label><br>
       <label class="radio-inline">
      <input  type="radio" id="expor" name="expor" value="1" checked>PDF
      </label>
      
      <label class="radio-inline">
      <input  type="radio" id= "expor" name="expor" value="2" >EXCEL
      </label>
</fieldset>
 
     
  </form>
    
  
            
        </div>
      </div>
    </div>

                         </div>


                         <div class="modal-footer">
                          <input formtarget="_blank" id="submit" name="submit" type="submit" value="Generar" onclick="mostrar();"  class="btn btn-primary"/>

                             <a type="button" class="btn btn-default"  href="{!!url('home')!!}">Cancelar</a>
                            
                            </div>
                    {!! Form::close() !!}
                    </div>
                            </div>
            </div><!-- /modal -->

                     
                        <!-- /cantidadModal-->

                    </div>
                    <!-- /.container -->

                    @endsection

                    @section('homescript')

                        var myuser = $('#login').data("field-id");
                       
                        $('#pass').modal(
                        {
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                        }
                        );
                        document.onkeyup = function(e) {
         if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM07_02.pdf","_blank");
  } 
};
                    @endsection

                    <script>

                        function mostrar(){
                            $("#hiddendiv").show();
                        };

                    </script>