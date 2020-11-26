@extends('home') 
@section('homecontent')
   
    <div class="container" ng-controller="MainController">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12" style="margin-bottom: -20px;">
                    <div class="visible-xs visible-sm"><br><br></div>               
                <h3 class="page-header">
                   Traslado Entrega de Mercancía<small> Solicitud de Entrega #{{$id}}</small>
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
        @if($itemsConLotes > 0 && count($articulos_validos) > 0)

        <div class="alert alert-info" role="alert">
            Favor de Especificar Lotes correctamente para los materiales que corresponde.
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
  
     <div class="row">
  <div class="col-md-12">     
      <span class="pull-right">
      
                     <a class="btn btn-primary btn-sm" href="{{url('/home/entregas_lotes')}}"><i class="fa fa-angle-left"></i> Otras Entregas Pendientes</a>                                                              
                            
                            @if ($itemsConLotes > 0 && count($articulos_validos) > 0)
                              <a class="btn btn-success btn-sm" disabled><i class="fa fa-send"></i> Enviar Entrega</a>  
                            @elseif(count($articulos_validos) > 0)                              
                              <a class="btn btn-success btn-sm" href="{{url('/home/TRASLADO ENTREGA/update/'.$almacen_origen.'/'.$id)}}"><i class="fa fa-send"></i> Enviar Entrega</a>
                            @endif
                   
            </span>   
            </div></div>
            
    @if (Session::has('ambos'))
    <div class="row" >
        <div class="col-md-6">
            <div class="alert alert-success" role="alert">
                Para Mercancía que se entregó en sus almacenes: <br>
                Transferencia interna {{Session::get('interno')}} realizada.
                 <a class="btn btn-danger btn-sm" href="{{'PDF/traslado/'.Session::get('interno')}}" target="_blank"><i
                        class="fa fa-file-pdf-o"></i> PDF</a>                 
            </div>
        </div>
        <div class="col-md-6">            
            <div class="alert alert-success" role="alert">
                Para Mercancía que se entregará en otros almacenes: <br>
                {{Session::get('externo')}}. 
            </div>
        </div>
    </div>
    @elseif(Session::has('interno'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
                Transferencia interna {{Session::get('interno')}} realizada. 
                <a class="btn btn-danger btn-sm" href="{{url('home/PDF/traslado/'.Session::get('interno'))}}" target="_blank"><i
                        class="fa fa-file-pdf-o"></i> PDF</a>                 
            </div>
        </div>
    </div>
    @elseif(Session::has('externo'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
            {{Session::get('externo')}}.
            </div>
        </div>
    </div> 
    @endif
    
    <div class="row">
    <div class="col-md-12">  
    @if (count($articulos_validos) > 0)
    <table>
      <thead>
        
        <tr>
          
          <th>Código</th>
          <th >Descripción</th>
          <th >UM</th>
          <th >A Surtir</th>
          <th>Cant. {{$almacen_origen}}</th>
          <th>Acciones</th>
                   
        </tr>
      </thead>
      <tbody>

        @foreach ($articulos_validos as $art)
        <tr <?php ?>>
                   
          <td><a href="{{url('home/DATOS MAESTROS ARTICULO/'.$art->ItemCode)}}"><i
              class="fa fa-hand-o-right"></i> {{$art->ItemCode}}</a></td>
          <td>{{$art->ItemName}}</td>
          <td>{{$art->UM}}</td>          
          @if ($art->AlmacenOrigen > 0 && $art->BatchNum > 0)
            <td>
              <a href="{{url('home/lotes/traslados/'.$almacen_origen.'/'.$art->Id)}}" >
              <i class="fa fa-hand-o-right"></i> {{number_format($art->CA, 2)}}</a>
            </td>
          @else
              <td>{{number_format($art->CA, 2)}}</td>
          @endif         
          <td>{{number_format($art->AlmacenOrigen, 2)}}</td>          
          <td> 
          
            <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}" class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>  

        </td>          
                    
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>
</div> <!-- /.row -->   
@endif
@if (count($articulos_novalidos)>0)
    <div class="row">
  <div class="col-md-12">
    <h4>Material retirado de entrega</h4>
    <table>
      <thead>
        <tr>

          <th>Código</th>
          <th>Descripción</th>
          <th>Destino</th>
          <th>Cant. a Recibir</th>
          <th>Cant. {{$almacen_origen}}</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($articulos_novalidos as $art)
        <tr>
          <td>{{$art->ItemCode}}</td>
          <td>{{$art->ItemName}}</td>
          <td>{{$art->Destino}}</td>
          <td>{{number_format($art->CA, 2)}}</td>          
          <td>{{number_format($art->AlmacenOrigen, 2)}}</td>          
          <td><a @if ($art->CA < $art->AlmacenOrigen)
          href="{{'articulos/return/'.$art->Id}}"
            @else
            disabled = "disabled"
                @endif
            role="button"  class="btn btn-default"><i class="fa fa-arrow-circle-o-up fa-lg" style="color:royalblue"></i></a>
            <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}" class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>          
           
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
        <h4 class="modal-title">Quitar Articulo</h4>
        <div class="modal-body">

          <input type="hidden" id="articulo-id" name="articulo" >
          <h4>¿Cuál es la razón por la que no surtirá este artículo?</h4>     

          <input type="radio" name="reason" value="Material Cancelado">
          Material Cancelado<br>

          <input type="radio" name="reason" value="Material No se encuentra">
          El Material no se encuentra<br>         

          <input type="radio" name="reason" value="Material No Disponible / Apartado">
          Material No Disponible / Apartado<br>

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
    <div class="modal-content" >
      <div class="modal-header">


        {!! Form::open(['url' => 'home/PICKING ARTICULOS/solicitud/articulos/edit', 'method' => 'POST']) !!}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Detalle de Surtido (<codigo id='tituloedit'></codigo>)</h4>
      </div>
      <div class="modal-body">
        <div class="row">
         <div class="col-md-12">
              <div ng-if="pendiente < (canta -- cantb)" class="alert alert-danger" role="alert">
              
              <strong>Las cantidad a surtir debe ser menor o igual @{{pendiente}}</strong><br>
        
            </div>
         </div>
          
          <div > 
          <div class="form-group col-md-6">
            <label for="canta">Tomar de APG-PA: <apgpa id="stockAPGPA"></apgpa></label>
            <input ng-model="canta"  id="canta" name="canta" type="number" class="form-control" min="0.01" step="any" required>
          </div>
          <div class="form-group col-md-6">
            <label for="canta">Tomar de AMP-ST: <ampst id="stockAMPST"></ampst></label>
            <input ng-model="cantb"  id="cantb" name="cantb" type="number" class="form-control" min="0.01" step="any" required>
          </div>
          </div>
          <div class="">
           
            <input id="cantr" name="cantr" type="hidden" class="form-control"  readonly>
          </div>
          <div class="form-group col-md-6">
            <label for="cantp" >Cantidad a Surtir</label>
          <input id="cantp" value="@{{canta -- cantb}}" name="cantp" type="text" class="form-control" min="0.01" step="any" max="@{{cantp}}" readonly>
          </div>
          <div class="form-group col-md-12" ng-show="pendiente > (canta -- cantb)">
              <h5>¿Cuál es la razón por la que se surtirá una cantidad menor?</h5>
              <input type="radio" name="reason" value="Se posterga" checked>
              Se posterga entrega<br>
              <input type="radio" name="reason" value="No se completa existencia">
              No se completa existencia<br>                         
          </div>
          <div class="form-group col-md-12">
            <input type="hidden" id="articulo-id" name="articulo">
            <input type="hidden" value="@{{pendiente}}" id="pendiente" name="pendiente">
            <input type="hidden"  id="itemcode" name="itemcode">
            <span>
              <h6>NOTA:</h6>
              <h6>* La Cantidad a Surtir puede ser menor a la cantidad Pendiente</h6>
              <h6>* La Cantidad a Surtir puede modificarse con las Cantidades de los Almacenes</h6>
            </span>
          </div>
        </div>      

      </div>
      <br>
      <div class="modal-footer">

        <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button data-ng-disabled="pendiente < (canta -- cantb) || (canta -- cantb) == 0" type="submit" class="btn btn-primary">Guardar</button>
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
// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
var modal = $(this)
modal.find('#articulo-id').val(id)
});

$('#edit').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget) // Button that triggered the modal
var id = button.data('id') // Extract info from data-* attributes
var cantp = button.data('cantp')
var itemcode = button.data('itemcode')
var maxa = button.data('maxa')
var maxb = button.data('maxb')
var nom = button.data('nom')

var modal = $(this)
modal.find('#articulo-id').val(id)
modal.find('#itemcode').val(itemcode)
modal.find('#tituloedit').text(itemcode + nom)
modal.find('#cantr').val(cantp) //autorizada
modal.find('#canta').attr('max', maxa)
modal.find('#cantb').attr('max', maxb)
  $.get("{!! url('disponibilidadAlmacenMP') !!}",
  { codigo: itemcode },
  function(data) {

  modal.find('#stockAPGPA').text(Number(data[0].stockapgpa).toFixed(2))
  modal.find('#stockAMPST').text(Number(data[0].stockampst).toFixed(2))
  });
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
        $scope.canta = event.currentTarget.dataset.canta * 1;    
        $scope.cantb = event.currentTarget.dataset.cantb * 1;   
        $scope.cantr= event.currentTarget.dataset.cantr * 1;    
        $scope.pendiente= event.currentTarget.dataset.cantp * 1;     
        
      };
 
    }]);

   
</script>

























