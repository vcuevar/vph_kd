@extends('home')
@section('homecontent')

<div class="container" ng-controller="MainController">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Asignación de Lotes<small>   ARTÍCULO: {{$articulo->ItemCode.' - '.$articulo->ItemName }}</small>
      </h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 ">
      @include('partials.alertas')
      
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
  <?php 
    $Cant =  $articulo->Cant - $sumLotesAsignados;
  ?>
 
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right" style="padding-top:5px; padding-bottom: 5px">
      @if ($tabla == 'solicitudes') 
      <a class="btn btn-primary btn-sm" href="{{url('home/2 PICKING ARTICULOS/solicitud/'.$articulo->Id_Solicitud)}}"><i class="fa fa-angle-left"></i> Atras</a>
      @elseif ($tabla == 'traslados')
        <a class="btn btn-primary btn-sm" href="{{url('lotesdeptos/'.$articulo->Id_Solicitud)}}"><i class="fa fa-angle-left"></i> Atras</a>                                                              
      @endif  
        
      </span>
      
    </div>
  </div>
  <!-- /.row -->
  @if ($Cant == 0)
      <div class="alert alert-success" role="alert">
        Cantidad Completada
      </div>
  @else
     <div class="alert alert-info" role="alert">
      {{$articulo->Cant - $Cant}} {{$articulo->UM}} Asignados de {{$articulo->Cant}} {{$articulo->UM}}
    </div> 
  @endif

  <div class="row">
    <div class="col-md-6">
      <h5>Disponibles</h5>
      <table>
        <thead>
          <tr>
            <th># Lote</th>
            <th>Disponible</th>
          </tr>
        </thead>
        <tbody>
          @if (count($lotes)>0)
          <?php 
            $totalDisponible = array_sum(array_pluck($lotes, 'Disponible'));;
            $totalProceso = $lotes[0]->Proceso;
          ?>
          @foreach($lotes as $lote)
          <tr>
            <td>{{$lote->NumLote}}</td>
            <td style="padding-top:12px">{{number_format($lote->Disponible, 2)}}
            @if( $Cant > 0 )
              <span class="pull-right" >
                <a role="button" data-toggle="modal" data-target="#edit" data-id="{{$articulo->Id}}"
                  data-cant="{{$Cant}}" data-lote="{{$lote->NumLote}}" data-cantlote="{{$lote->Disponible}}"
                  class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i></a>
              </span>
            @endif
            </td>
            
          </tr>
          @endforeach
          <tr>
            <td>Total en Lotes Elegibles</td>
            <td>{{number_format($totalDisponible, 2)}}</td>
          </tr>
          <tr>
            <td>No Disponible (En proceso)</td>
            <td>{{number_format($totalProceso, 2)}}</td>
          </tr>
          @else
          <tr>
            <td></td>
            <td>
              <span class="pull-right">
                <a role="button" href="#" class="btn btn-default btn-sm"><i class="fa fa-arrow-right"></i></a>
              </span>
            </td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
   
    <div class="col-md-6">
      <h5>Asignados</h5>
      <table>
        <thead>
          <tr>
            <th># Lote</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
        @if (count($lotesAsignados)>0)
          @foreach($lotesAsignados as $lotea)
          <tr>
            <td>{{$lotea->lote}}</td>
            <td style="padding-top:12px">{{number_format($lotea->Cant, 2)}}
              <span class="pull-right">
                <a role="button" href="{{url('/home/lotes/remove/'.$lotea->Id_Item.'/'.$lotea->lote.'/'.$alm)}}" 
                  class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i></a>
              </span>
            </td>
          </tr>
          @endforeach
          <tr>
            <td>Total Asignado</td>
            <td>{{number_format($sumLotesAsignados, 2)}}</td>
          </tr>
          @else
          <tr>
            <td></td>
            <td>
              <span class="pull-right">
                <a role="button" href="#" 
                  class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></a>
              </span>
            </td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->

  <!-- .Model quitar -->

  <div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">

          {!! Form::open(['url' => 'home/lotes/insert', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Cantidad del Lote</h4>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="canta">Cantidad</label>
                <input id="canta" name="cant" type="number" 
                class="form-control" min="0.1" step="0.01" required>
              </div>
              <input type="hidden" value="{{$articulo->Id}}" name="articulo">
              <input type="hidden" value="{{$alm}}" name="alm">
              <input type="hidden" id="lote" name="lote">
            </div>
          </div>
          <div class="modal-footer">

            <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}

  @endsection

  @section('homescript')
 

  $('#edit').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal

  var id = button.data('id') // Extract info from data-* attributes
  var cant = button.data('cant') * 1;
  var lote = button.data('lote')
  var cantlote = button.data('cantlote') * 1;
  var cantbox = 0;
  if(cant < cantlote){
    cantbox = cant;
  }else{
    cantbox = cantlote;
  }
  console.log(cantbox);
  var modal = $(this)
  modal.find('#articulo-id').val(id)
  modal.find('#lote').val(lote)

  modal.find('#canta').val(cantbox)
  modal.find('#canta').attr('max', cant)

  });
  @endsection
  <script>
    document.onkeyup = function(e) {
      if (e.shiftKey && e.which == 112) {
        window.open("ayudas_pdf/AyM00_00.pdf", "_blank");
      }
    }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.2/angular.min.js"></script>
  <script>
    var app = angular.module('app', []);
    app.controller("MainController", ["$scope", "$http", function($scope, $http) {

      $scope.editar = function($event) {
        $scope.id = event.currentTarget.dataset.id;
        $scope.canta = event.currentTarget.dataset.canta * 1;
        $scope.cantb = event.currentTarget.dataset.cantb * 1;
        $scope.cantr = event.currentTarget.dataset.cantr * 1;
        $scope.pendiente = event.currentTarget.dataset.cantp * 1;

      };

    }]);
  </script>