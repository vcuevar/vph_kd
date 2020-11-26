@extends('home')
@section('homecontent')

<div class="container" ng-controller="MainController">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Picking de Artículos<small> Solicitud de Material #{{$id}}</small>
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
      @if($itemsConLotes > 0)

      <div class="alert alert-info" role="alert">
        Favor de Especificar Lotes correctamente para los materiales que corresponde.
      </div>
      @endif
      @if(false)  <!--($todosCantScan == 0)-->
      
      <div class="alert alert-info" role="alert">
        <!-- Existen Materiales sin Cantidad Escaneada. -->
      </div>
      @endif
      @if($showmodal == true)
    <div id="qr" data-field-id="{{$showmodal}}" 
    data-qrcant="{{$qr_cant}}"    
    data-itemcode="{{$qr_item[0]->ItemCode}}"
    data-itemname="{{substr(' '.$qr_item[0]->ItemName, 0, 25).'...'}}"
    data-maxapgpa="{{$qr_item[0]->APGPA}}"
    data-maxampst="{{$qr_item[0]->AMPST}}"
    data-id="{{$qr_item[0]->Id}}"
    data-cantr="{{floatval($qr_item[0]->Cant_Autorizada)}}"
    data-canta="{{floatval($qr_item[0]->Cant_ASurtir_Origen_A)}}"
    data-cantb="{{floatval($qr_item[0]->Cant_ASurtir_Origen_B)}}"
    data-cantp="{{$qr_item[0]->Cant_PendienteA}}">
      </div>
      @endif

    </div>
  </div>
 <style>
    td {
      white-space: nowrap;
      font-family: 'Helvetica';
      font-size: 80%;
    }
  
    th {
      font-family: 'Helvetica';
      font-size: 90%;
      
    }
  </style>
  <style>
    .sticky-column {
    position: sticky;
    position: -webkit-sticky;
    left: 0;
    z-index: 6;
    background-color: aliceblue;
    }
    .table-scroll {
      position: relative;
    }
  
    .table-scroll thead th {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
    }
  
    .table-scroll tfoot,
    .table-scroll tfoot th,
    .table-scroll tfoot td {
      position: -webkit-sticky;
      position: sticky;
      bottom: 0;
      z-index: 4;
    }
  
    th:first-child {
      position: -webkit-sticky;
      position: sticky;
      left: 0;
      z-index: 2;
  
    }
  
    thead th:first-child,
    tfoot th:first-child {
      z-index: 5;
    }
  
    .pane {
      overflow: auto;
      max-height: 300px;
    }
  </style>
  @if (count($articulos_validos)>0)
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
     
        <a class="btn btn-primary btn-sm" href="{{url('/home/2 PICKING ARTICULOS')}}"><i class="fa fa-angle-left"></i>
          Atras</a>
        <a class="btn btn-danger btn-sm" href="{{url('home/PICKING ARTICULOS/solicitud/PDF/'.$id)}}" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
        @if ($itemsConLotes <= 0 && $todosCantScan==0) <a class="btn btn-success btn-sm"
            href="{{url('home/PICKING ARTICULOS/solicitud/update/'.$id)}}"><i class="fa fa-send"></i> Enviar a Traslados (Sin
            Escanear)</a>
            @else
            @if ($itemsConLotes > 0 || $todosCantScan == 0)
            <a class="btn btn-success btn-sm" disabled><i class="fa fa-send"></i> Enviar a Traslados</a>
            @else
            <a class="btn btn-success btn-sm" href="{{url('home/PICKING ARTICULOS/solicitud/update/'.$id)}}"><i
                class="fa fa-send"></i> Enviar a Traslados</a>
            @endif
            @endif

      </span>
      
        </div>
      </div>
      <!-- /.row -->
<div class="row">
  <div class="col-md-12">
      <h4>Material a Surtir</h4>
      <div class="pane">
      <table class="table table-striped main-table table-scroll" style="margin-bottom:0px">
        <thead>
          
          <tr>

            <th>Código</th>
            <th>Descripción</th>
            <th>UM</th>
            <th>Cant. Autorizada</th>
            <th>Cant. Surtida</th>
            <th style="color:limegreen">A Surtir</th>
         

            <th>Cant. <div style="white-space: nowrap">APG-PA</div></th>
            <th>Cant. <div style="white-space: nowrap">AMP-ST</div></th>
            <th>Stock <div style="white-space: nowrap">APG-PA</div></th>
            <th>Stock <div style="white-space: nowrap">AMP-ST</div></th>           
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_validos as $art)
          <tr <?php ?>>

            <td class="sticky-column"><a href="{{url('home/DATOS MAESTROS ARTICULO/'.$art->ItemCode)}}"><i class="fa fa-hand-o-right"></i>
                {{$art->ItemCode}}</a></td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->UM}}</td>
            <td>{{$art->Cant_Autorizada}}</td>
            <td>{{number_format(($art->Cant_Autorizada - $art->Cant_PendienteA), 2)}}</td>
            <td>{{number_format($art->Cant_ASurtir_Origen_A + $art->Cant_ASurtir_Origen_B, 2)}}</td>
           
            <td>{{$art->Cant_ASurtir_Origen_A}}</td>
            <td>{{$art->Cant_ASurtir_Origen_B}}</td>
            @if ($art->APGPA > 0 && $art->BatchNum > 0)
            <td>
              <a href="{{url('home/lotes/solicitudes/APG-PA/'.$art->Id)}}"><i class="fa fa-hand-o-right"></i>
                {{number_format($art->APGPA, 2)}}</a>
            </td>
            @else
            <td>{{number_format($art->APGPA, 2)}}</td>
            @endif
            @if ($art->AMPST > 0 && $art->BatchNum > 0)
            <td>
              <a href="{{url('home/lotes/solicitudes/AMP-ST/'.$art->Id)}}"><i class="fa fa-hand-o-right"></i>
                {{number_format($art->AMPST, 2)}}</a>
            </td>
            @else
            <td>{{number_format($art->AMPST, 2)}}</td>
            @endif           
            <td>
              <div class="btn-group" role="group" aria-label="...">

                <a id="btneditar" ng-click="editar($event)" role="button" data-toggle="modal" data-target="#edit"
                  data-nom="{{substr(' '.$art->ItemName, 0, 25).'...'}}" data-maxb="{{$art->AMPST}}"
                  data-maxa="{{$art->APGPA}}" data-id="{{$art->Id}}" data-itemcode="{{$art->ItemCode}}"
                  data-cantr="{{$art->Cant_Autorizada}}" data-canta="{{$art->Cant_ASurtir_Origen_A}}"
                  data-cantb="{{$art->Cant_ASurtir_Origen_B}}" data-cantp="{{$art->Cant_PendienteA}}"
                  class="btn btn-default"><i class="fa fa-pencil fa-lg" style="color:#007BFF"></i></a>
                <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}"
                  class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>
              </div>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
      </div>
    </div>
  </div> <!-- /.row -->
  @else
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{url('home/2 PICKING ARTICULOS') }}"><i class="fa fa-angle-left"></i>
          Atras</a>
      </span>
    </div>
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
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_novalidos as $art)
          <tr>

            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->Destino}}</td>
            <td>{{number_format($art->Cant_ASurtir_Origen_A + $art->Cant_ASurtir_Origen_B, 2)}}</td>
            <td>{{number_format($art->Disponible, 2)}}</td>
            <td><a @if ($art->Disponible >= ($art->Cant_ASurtir_Origen_A + $art->Cant_ASurtir_Origen_B))                
                href="{{url('home/PICKING ARTICULOS/solicitud/articulos/return/'.$art->Id)}}"
                @else
                disabled = "disabled"
                @endif
                role="button" class="btn btn-default"><i class="fa fa-arrow-circle-o-up fa-lg"
                  style="color:royalblue"></i></a>
              <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}"
                class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>
              <a id="btneditar" ng-click="editar($event)" role="button" data-toggle="modal" data-target="#edit"
                data-nom="{{substr(' '.$art->ItemName, 0, 25).'...'}}" data-maxb="{{$art->AMPST}}"
                data-maxa="{{$art->APGPA}}" data-id="{{$art->Id}}" data-itemcode="{{$art->ItemCode}}"
                data-cantr="{{$art->Cant_Autorizada}}" data-canta="{{$art->Cant_ASurtir_Origen_A}}"
                data-cantb="{{$art->Cant_ASurtir_Origen_B}}" data-cantp="{{$art->Cant_PendienteA}}"
                class="btn btn-default"><i class="fa fa-pencil fa-lg" style="color:#007BFF"></i></a>

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

          {!! Form::open(['url' => 'home/PICKING ARTICULOS/solicitud/articulos/remove', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Quitar Articulo</h4>
          <div class="modal-body">

            <input type="hidden" id="articulo-id" name="articulo">
            <h4>¿Cuál es la razón por la que no surtirá este artículo?</h4>
            <input type="radio" name="reason" value="Se Surtira posteriormente" required checked>
            Se surtirá posteriormente<br>

            <input type="radio" name="reason" value="Material Dañado / Incompleto">
            Material Dañado / Incompleto<br>

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
      <div class="modal-content">
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

            <div>
              <div class="form-group col-md-6">

                <label for="canta">Tomar de APG-PA: <apgpa id="stockAPGPA"></apgpa></label>
                <input string-to-number ng-click="sumaab($event)" ng-model="canta" id="canta" name="canta" type="number" class="form-control" min="0" step="any"
                  required>
              </div>
              <div class="form-group col-md-6">
                <label for="canta">Tomar de AMP-ST: <ampst id="stockAMPST"></ampst></label>
                <input string-to-number ng-click="sumaab($event)" ng-model="cantb" id="cantb" name="cantb" type="number" class="form-control" min="0" step="any"
                  required>
              </div>
            </div>
            <div class="">

              <input id="cantr" name="cantr" type="hidden" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6 ng-hide">
              <label for="cantp">Cantidad a Surtir</label>
              <input id="cantp" ng-click="sumaab($event)" value="@{{canta -- cantb}}" name="cantp" type="text" class="form-control" min="0"
               ng-model="csurtir" step="any" max="@{{cantp}}" readonly>
            </div>
            <div class="form-group col-md-12" ng-show="pendiente > (canta -- cantb)">
              <h5>¿Cuál es la razón por la que se surtirá una cantidad menor?</h5>
              <input type="radio" name="reason" value="Se posterga" checked>
              Se posterga entrega<br>
              <input type="radio" name="reason" value="No hay existencia">
              No hay existencia<br>
            </div>
            <div class="form-group col-md-12">
              <input   type="hidden" id="articulo-id" name="articulo">
              <input type="hidden" value="@{{pendiente}}" id="pendiente" name="pendiente">
              <input type="hidden" id="itemcode" name="itemcode">
            <input type="hidden" id="idsol" name="idsol" value="{{$id}}">
              <span class="ng-hide">
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
          <button id="editbtn" data-ng-disabled="pendiente < (canta -- cantb) || (canta -- cantb) == 0" type="submit"
            ng-click="sumaab($event)" class="btn btn-primary">Guardar</button>
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>

  <div class="modal fade" id="edit2" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
  {!! Form::open(['url' => 'home/PICKING ARTICULOS/solicitud/articulos/edit', 'method' => 'POST']) !!}
  
          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Origen de Surtido (<codigo id='tituloedit'></codigo>)</h4>
        </div>
  
        <div class="modal-body">
          <div class="row">
            <div class="form-group">
              @include('partials.alertas-modal')
              @if(Session::has('qrmsg'))              
                <div class="alert alert-success" role="alert">
                  {{ Session::get('qrmsg') }}
                </div>
              @endif
            </div>
            <div class="col-md-12">
              <div ng-if="pendiente < (canta -- cantb)" class="alert alert-danger" role="alert">
  
                <strong>Las cantidad a surtir debe ser menor o igual @{{pendiente}}</strong><br>
  
              </div>
            </div>
  
            <div>
              <div class="form-group col-md-6">
  
                <label for="canta">Tomar de APG-PA: <apgpa id="stockAPGPA"></apgpa></label>
                <input string-to-number  ng-model="canta" id="canta" name="canta" type="number"
                  class="form-control" min="0" step="any" required readonly>
              </div>
              <div class="form-group col-md-6">
                <label for="canta">Tomar de AMP-ST: <ampst id="stockAMPST"></ampst></label>
                <input string-to-number  ng-model="cantb" id="cantb" name="cantb" type="number"
                  class="form-control" min="0" step="any" required readonly>
              </div>
            </div>
           
           
            <div class="form-group col-md-12 menorcantidaddiv" >
              <h5>¿Cuál es la razón por la que se surtirá una cantidad menor?</h5>
              <input type="radio" name="reason" value="Se posterga" checked>
              Se posterga entrega<br>
              <input type="radio" name="reason" value="No hay existencia">
              No hay existencia<br>
            </div>
            <div class="form-group col-md-12">
              <input type="hidden" id="articulo-id" name="articulo">
              <input type="hidden" value="@{{pendiente}}" id="pendiente" name="pendiente">
              <input type="hidden" id="itemcode" name="itemcode">
              <input type="hidden" id="idsol" name="idsol" value="{{$id}}">
              <input type="hidden" id="qrinput" name="qrinput" value="1">
              <span class="ng-hide">
                <h6>NOTA:</h6>
                <h6>* La Cantidad a Surtir puede ser menor a la cantidad Pendiente</h6>
                <h6>* La Cantidad a Surtir puede modificarse con las Cantidades de los Almacenes</h6>
              </span>
            </div>
          </div>
  
        </div>
        <br>
        <div class="modal-footer">
  
           <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="">
             Ok
           </button>
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
  //instead.
  var modal = $(this)
  modal.find('#articulo-id').val(id)
  });
var qrcode = $('#qr').data("field-id");
$('#qr').data("field-id", '');
var qritemcode = $('#qr').data("itemcode");
var qritemname = $('#qr').data("itemname");
var maxapgpa = $('#qr').data("maxapgpa");
var maxampst = $('#qr').data("maxampst");
var id = $('#qr').data("id");
var cantr = $('#qr').data("cantr");
var canta = $('#qr').data("canta");
var cantb = $('#qr').data("cantb");
var cantp = $('#qr').data("cantp");
var qrcant = $('#qr').data("qrcant");
$( "#editbtn" ).prop( "disabled", false );
console.log( (canta + cantb));
console.log( cantr);
if(cantr > (canta + cantb)){
 $( ".menorcantidaddiv" ).show();
}else{
$( ".menorcantidaddiv" ).hide();
}
if(qrcode == 1){
  if(canta >= qrcant){
    canta = qrcant;  
  }else if (cantb >= qrcant){
    cantb = qrcant;
  }else{
    canta = qrcant;
  }
$.get("{!! url('disponibilidadAlmacenMP') !!}",
{ codigo: qritemcode },
function(data) {
var modal = $('#edit2').modal(
{
show: true,
backdrop: 'static',
keyboard: false
});
modal.find('#articulo-id').val(id)
modal.find('#itemcode').val(qritemcode)
modal.find('#cantr').val(cantp) //autorizada
modal.find('#canta').attr('max', maxapgpa)
modal.find('#canta').val( parseFloat(canta * 1))
modal.find('#cantb').attr('max', maxampst)
modal.find('#cantb').val( parseFloat(cantb * 1))
modal.find('#cantp').attr('max', cantp)
modal.find('#cantp').val( parseFloat((canta + cantb) * 1))
modal.find('#pendiente').val( parseFloat(cantp * 1))

modal.find('#tituloedit').text(qritemcode + qritemname)
modal.find('#stockAPGPA').text((data[0].stockapgpa).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
modal.find('#stockAMPST').text((data[0].stockampst).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
});
}



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
  modal.find('#pendiente').val( parseFloat(cantp * 1))
  $.get("{!! url('disponibilidadAlmacenMP') !!}",
  { codigo: itemcode },
  function(data) {

modal.find('#stockAPGPA').text((data[0].stockapgpa).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
modal.find('#stockAMPST').text((data[0].stockampst).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
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
        console.log($scope.pendiente);   
        console.log($scope.canta);   
        console.log($scope.cantb);   
        $scope.csurtir = parseFloat( $scope.canta ) + parseFloat( $scope.cantb );
      };
      $scope.sumaabc = function($event){
       
        $scope.canta = angular.element(document.querySelector("#canta")).val();  
        $scope.cantb = angular.element(document.querySelector("#cantb")).val();
        $scope.pendiente = angular.element(document.querySelector("#qr"))[0].dataset.cantp;
        $scope.cantr = angular.element(document.querySelector("#qr"))[0].dataset.cantr;
   console.log($scope.pendiente);   
        console.log($scope.canta);   
        console.log($scope.cantb);  
        $scope.csurtir = parseFloat( $scope.canta ) + parseFloat( $scope.cantb );    
        
      };
 
    }]);

   
  </script>