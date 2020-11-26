@extends('home')
@section('homecontent')

<div class="container" ng-controller="MainController">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Impresión<small> Solicitud de Material #{{$id}}</small>
      </h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 ">
      @include('partials.alertas')
      @if(Session::has('solicitud_err'))
      <div class="alert alert-danger" role="alert">
        {{ Session::get('solicitud_err') }}
      </div>
      @endif
      @if(Session::has('mensaje2'))
      <div class="alert alert-success" role="alert">
        {{ Session::get('mensaje2') }}
      </div>
      @endif
    </div>
  </div>
  <style>
    td {
      font-family: 'Helvetica';
      font-size: 80%;
    }

    th {
      font-family: 'Helvetica';
      font-size: 90%;
    }
  </style>

  @if (count($articulos_validos)>0)
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
        <div class="">
          <a class="btn btn-danger btn-sm" href="{{'PDF/'.$id}}" target="_blank"><i class="fa fa-file-pdf-o"></i>
            PDF</a>

        </div>
      </span>
      <!-- /.row -->

      <h4>Material en Proceso</h4>
      <table>
        <thead>
          <tr>
            <th colspan="3">Artículo</th>
            <th colspan="1"></th>
            <th colspan="1"></th>

          </tr>
          <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>UM</th>
            <th>Cantidad</th>
            <th>Destino</th>


          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_validos as $art)
          <tr>

            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->UM}}</td>
            <td>{{number_format($art->Cant_ASurtir_Origen_A, 2)}}</td>
            <td>{{$art->Destino}}</td>

          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @else
  <div class="row">

  </div>
  @endif
  @if (count($articulos_novalidos)>0)
  <div class="row">
    <div class="col-md-12">

      <h4>Material que NO se surtirá</h4>
      <table>
        <thead>
          <tr>

            <th>Código</th>
            <th>Descripción</th>
            <th>Destino</th>
            <th>Cant. Requerida</th>
            <th>Cant. Disponible</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_novalidos as $art)
          <tr>

            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->Destino}}</td>
            <td>{{$art->Cant_Requerida}}</td>
            <td>{{number_format($art->Disponible, 2)}}</td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @endif

  @if(count($pdf_solicitud) > 0)
  <div class="row">
    <div class="col-md-12">
      <h4>Transferencias de esta Solicitud</h4>
      <table>
        <thead>
          <tr>

            <th># Traslado</th>
            <th>Fecha</th>
            <th>Cancelado</th>
            <th>Comentario</th>
            <th>Pdf</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pdf_solicitud as $art)
          <tr>

            <td>{{$art->DocEntry}}</td>
            <td>{{date('d-m-Y', strtotime($art->DocDate))}}</td>
            <td>{{$art->CANCELED}}</td>
            <td>{{$art->Comments}}</td>
            <td>
              <a class="btn btn-danger btn-sm" href="{{url('home/PDF/traslado/'.$art->DocEntry)}}" target="_blank"><i
                  class="fa fa-file-pdf-o"></i> </a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @else
  <br>
    <div class="alert alert-info">
                    <strong>¡No hay Transferencias relacionadas!</strong>
                </div>
  @endif
  <!-- .Model quitar -->

  <div class="modal fade" id="remove" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

          {!! Form::open(['url' => 'home/2 PICKING ARTICULOS/solicitud/articulos/remove', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Quitar Articulo</h4>
          <div class="modal-body">

            <input type="hidden" id="articulo-id" name="articulo">
            <h4>¿Cuàl es la razón por la que no surtirá este artículo?</h4>
            <input type="radio" name="reason" value="Se Surtira posteriormente" required checked>
            Se surtirá posteriormente<br>

            <input type="radio" name="reason" value="Dañado / Incompleto">
            Material Dañado / Incompleto<br>

            <input type="radio" name="reason" value="No se encuentra">
            El Material no se encuentra<br>

            <input type="radio" name="reason" value="No Disponible / Apartado">
            Material No Disponible / Apartado<br>

            <input type="radio" name="reason" value="Otro">
            Otro…<br>

          </div>
          <div class="modal-footer">

            <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Quitar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}


  <div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">


          {!! Form::open(['url' => 'home/PICKING ARTICULOS/solicitud/articulos/edit', 'method' => 'POST']) !!}
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Detalles de Surtido</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div ng-if="pendiente < (canta -- cantb)" class="alert alert-danger" role="alert">

              <strong>Las cantidad a surtir debe ser menor o igual @{{pendiente}}</strong><br>

            </div>
            <div class="form-group col-md-6">
              <label for="cantr">Cantidad Pendiente</label>
              <input id="cantr" name="cantr" type="number" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
              <label for="cantp">Cantidad A Surtir</label>
              <input id="cantp" value="@{{canta -- cantb}}" name="cantp" type="text" class="form-control" min="0"
                step="any" max="@{{cantp}}" readonly>

            </div>
            <div class="form-group col-md-6">
              <label for="canta">Tomar de APG-PA:</label>
              <input ng-model="canta" id="canta" name="canta" type="number" class="form-control" min="0" step="any"
                required>
            </div>
            <div class="form-group col-md-6">
              <label for="canta">Tomar de AMP-ST:</label>
              <input ng-model="cantb" id="cantb" name="cantb" type="number" class="form-control" min="0" step="any"
                required>
            </div>
            <div class="form-group col-md-12" ng-show="pendiente > (canta -- cantb)">
              <h5>¿Cuàl es la razón por la que se surtira una cantidad menor?</h5>
              <input type="radio" name="reason" value="Se posterga" checked>
              Se posterga entrega<br>
              <input type="radio" name="reason" value="No hay existencia">
              No hay existencia<br>  
            </div>
            <div class="form-group col-md-12">
              <input type="hidden" id="articulo-id" name="articulo">
              <input type="hidden" value="@{{pendiente}}" id="pendiente" name="pendiente">
              <span>
                <h6>NOTA:</h6>
                <h6>* La Cantidad a Surtir puede ser menor a la Autorizada</h6>
                <h6>* La Cantidad a Surtir puede modificarse con las Cantidades de los Almacenes</h6>
              </span>
            </div>
          </div>

        </div>
        <br>
        <div class="modal-footer">

          <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button data-ng-disabled="pendiente < (canta -- cantb) || (canta -- cantb) == 0" type="submit"
            class="btn btn-primary">Guardar</button>
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
  @endsection

  @section('homescript')
  $('#remove').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes

  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods
  instead.
  var modal = $(this)
  modal.find('#articulo-id').val(id)
  });

  $('#edit').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  var cantp = button.data('cantp')

  var modal = $(this)
  modal.find('#articulo-id').val(id)
  modal.find('#cantr').val(cantp) //autorizada


  });

  @endsection
  <script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
  
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.2/angular.min.js"></script>
  <script>
    var app = angular.module('app', []);
   app.controller("MainController",["$scope", "$http", function($scope, $http){
      
      $scope.editar = function(item){
        $scope.id = $('#btneditar').data('id');
        $scope.canta = $('#btneditar').data('canta') * 1;     
        $scope.cantb = $('#btneditar').data('cantb') * 1;    
        $scope.cantr= $('#btneditar').data('cantr') * 1;     
        $scope.pendiente= $('#btneditar').data('cantp') * 1;     
        
      };
      
      $scope.sendArt = function(){
        $( "#spinn" ).html('<span><i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> Enviando...</span>');
        $scope.showme = true;
        $("#spinn").attr("disabled", true);
    };
    }]);

   
  </script>