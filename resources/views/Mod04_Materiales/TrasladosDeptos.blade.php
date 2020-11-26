@extends('home')
@section('homecontent')

<div class="container" ng-controller="MainController">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Recepción de Materiales<small> Entrega #{{$id}} de {{$almacen_origen}}</small>
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
        {{ Session::pull('mensaje2') }}
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
        <a class="btn btn-primary btn-sm" href="{{url('home/TRASLADO RECEPCION')}}"><i class="fa fa-angle-left"></i>
          Atras</a>
        @if(!(Session::has('transfer3')))
        <a ng-click="sendArt()" id="spinn" class="btn btn-success btn-sm" href="{{'update/'.$id}}"><i
            class="fa fa-send"></i> Aceptar entrega</a>
        @endif
      </span>
      <!-- /.row -->

      <h4>Material a Entregar<small> #{{$id}} de {{$almacen_origen}}</small></h4>
      <table>
        <thead>
          <tr>
            <th colspan="3">Artículo</th>
            <th colspan="3">Cantidad</th>
            <th colspan="1"></th>
            <th colspan="1"></th>
          </tr>
          <tr>

            <th>Código</th>
            <th>Descripción</th>
            <th>UM</th>
            <th>Autorizada</th>
            <th>Recibida</th>
            <th>A Recibir</th>
            <th>Destino</th>

            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_validos as $art)
          <tr <?php ?>>

            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->UM}}</td>
            <td>{{number_format($art->Cant_Autorizada, 2)}}</td>
            <td>{{number_format($art->Cant_Autorizada - $art->Cant_PendienteA , 2)}}</td>
            <td>{{$art->Cant_ASurtir_Origen_A}}</td>
            <td>{{$art->Destino}}</td>

            <td>
              <a id="btneditar" ng-click="editar($event)" role="button" data-toggle="modal" data-target="#edit"
                data-id="{{$art->Id}}" data-itemcode="{{$art->ItemCode}}" data-cantp="{{$art->Cant_PendienteA}}"
                class="btn btn-default"><i class="fa fa-pencil fa-lg" style="color:#007BFF"></i></a>
              <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}"
                class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @else
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{url('home/TRASLADO RECEPCION')}}"><i class="fa fa-angle-left"></i>
          Atras</a>
      </span>
    </div>
  </div>
  @endif
  @if (count($articulos_novalidos)>0)
  <div class="row">
    <div class="col-md-12">
      <h4>Material retirado de entrega<small> #{{$id}} de {{$almacen_origen}}</small></h4>
      <table>
        <thead>
          <tr>

            <th>Código</th>
            <th>Descripción</th>
            <th>Destino</th>
            <th>Cant. a Recibir</th>
            <th>Regresar</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_novalidos as $art)
          <tr>
            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->Destino}}</td>
            <td>{{number_format($art->Cant_ASurtir_Origen_A, 2)}}</td>
            <td><a @if ($art->Cant_ASurtir_Origen_A <= $art->AlmacenOrigen)
                  href="{{'articulos/return/'.$art->Id}}"
                  @else
                  disabled = "disabled"
                  @endif
                  role="button" class="btn btn-default"><i class="fa fa-arrow-circle-o-up fa-lg"
                    style="color:royalblue"></i></a>
              <a role="button" data-toggle="modal" data-target="#remove2" data-id="{{$art->Id}}"
                class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>

            </td>
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
              <a class="btn btn-danger btn-sm" href="{{'PDF/traslado/'.$art->DocEntry}}" target="_blank"><i
                  class="fa fa-file-pdf-o"></i> </a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @endif

  <!-- .Model quitar -->

  <div class="modal fade" id="remove" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

          {!! Form::open(['url' => 'home/TRASLADO RECEPCION/solicitud/articulos/remove', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Quitar de Entrega</h4>
          <div class="modal-body">

            <input type="hidden" id="articulo-id" name="articulo">
            <h4>¿Cuál es la razón por la que no recibe este artículo?</h4>
            <input type="radio" name="reason" value="Se Surtira posteriormente" required checked>
            Se recibirá posteriormente<br>

            <input type="radio" name="reason" value="Material Dañado / Incompleto">
            Material Dañado / Incompleto<br>

            <input type="radio" name="reason" value="Material No se encuentra">
            El Material no se encuentra<br>


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

  <div class="modal fade" id="remove2" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

          {!! Form::open(['url' => 'home/TRASLADO RECEPCION/solicitud/articulos/remove', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Quitar de Entrega</h4>
          <div class="modal-body">

            <input type="hidden" id="articulo-id" name="articulo">
            <h4>¿Cuál es la razón por la que no recibe este artículo?</h4>

            <input type="radio" name="reason" value="Material No se encuentra" checked>
            El Material no se encuentra<br>
            <input type="radio" name="reason" value="Material Dañado / Incompleto">
            Material Dañado / Incompleto<br>



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



  <div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          {!! Form::open(['url' => 'home/TRASLADO RECEPCION/solicitud/articulos/edit', 'method' => 'POST']) !!}
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Editar cantidad</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div ng-if="pendiente < canta || (canta) == 0" class="alert alert-danger" role="alert">
                <strong>Las cantidad a recibir debe ser menor o igual @{{pendiente}} y diferente de 0</strong><br>
              </div>
            </div>
            <div class="form-group col-md-12">
              <label for="cantp">Cantidad a Recibir</label>
              <input ng-model="canta" id="cantp" name="cantp" type="number" class="form-control" min="0" step="any"
                max="@{{cantp}}" required>
            </div>
            <div class="form-group col-md-12" ng-show="pendiente > (canta)">
              <h5>¿Cuál es la razón por la que se recibe una cantidad menor?</h5>
              <input type="radio" name="reason" value="Se Posterga Entrega" checked>
              Se posterga entrega<br>
              <input type="radio" name="reason" value="Material Dañado / Incompleto">
              Material Dañado / Incompleto<br>

            </div>
            <div class="form-group col-md-12">
              <input type="hidden" id="articulo-id" name="articulo" value="@{{id}}">
              <input type="hidden" ng-model="pendiente" value="@{{pendiente}}" id="pendiente" name="pendiente">
              <input type="hidden" id="itemcode" name="itemcode">

            </div>
          </div>

        </div>
        <br>
        <div class="modal-footer">

          <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button data-ng-disabled="pendiente < (canta) || (canta) == 0" type="submit"
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

  var modal = $(this)
  modal.find('#articulo-id').val(id)
  });
  $('#remove2').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes

  var modal = $(this)
  modal.find('#articulo-id').val(id)
  });

  $('#edit').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  var cantp = button.data('cantp')
  console.log('jq'+cantp)
  var itemcode = button.data('itemcode')

  var modal = $(this)
  modal.find('#articulo-id').val(id)
  modal.find('#itemcode').val(itemcode)

  modal.find('#cantp').val(cantp)
  modal.find('#cantp').attr('max', cantp)

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
      
      $scope.editar = function($event){
        $scope.id = event.currentTarget.dataset.id;
        $scope.canta = event.currentTarget.dataset.cantp * 1;        
        $scope.pendiente= event.currentTarget.dataset.cantp * 1;     
      };
      $scope.sendArt = function(){
        $( "#spinn" ).html('<span><i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> Enviando...</span>');
        $scope.showme = true;
        $("#spinn").attr("disabled", true);
      };
    }]);

   
  </script>