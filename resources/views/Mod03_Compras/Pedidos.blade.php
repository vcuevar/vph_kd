@extends('home')
@section('homecontent')
        <div class="container" >

            <!-- Page Heading -->
            <div class="row">

                    <div class="visible-xs"><br><br></div>
                                      
                       <div class= "col-md-11 col-sm-7 hidden-xs hidden-sm">
                            <h3 class="page-header">
                                    Descarga de Orden de Compra 
                                    <small>Compras <i data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-question-sign"  title="Ayuda Shift+F1"></i></small>   
                                  </h3> 
                        <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                    </ol>
                 </div>
            </div> 
{!! Form::open(['url' => 'home/CONSULTA OC', 'method' => 'POST']) !!} 
<div class="row">
    <div class="col-md-11">
            @include('partials.alertas')
      </div>       
 </div>                          
 <div class="row">
    <div class="col-md-2">
        <h4>Número de O.C:</h4>
    </div>    
    <div class="col-md-2">
        <input name="NumOC" type="number" class="form-control" required min ="1" autofocus>                                                      
    </div> 
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Consultar</button>
    </div> 
</div>     
     
    {!! Form::close() !!} 
    <br>
    @if (Session::has('OrdenCompra'))

    <?php 
         
          $date=date_create($pedido[0]->FechOC); 
          $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
          $fecha_entrada = strtotime($pedido[0]->FechOC);
     ?>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Información de la Orden de Compra: {{$pedido[0]->NumOC}}</b> 
                </div>
                <div class="panel-body"> 
                    @if($pedido[0]->CANCELED == 'Y')
                    <h5>Estatus de la Orden: Cancelada</h5>
                    @elseif($pedido[0]->DocStatus == 'O') 
                    <h5>Estatus de la Orden: Abierta</h5>
                    @else
                    <h5>Estatus de la Orden: Cerrada</h5>
                    @endif
                    <h5>Líneas del Documento:  {{count($pedido)}}</h5>                            
                    <h5>Fecha de Orden:  {{date_format($date, 'd-m-Y')}}</h5>
                    <h5>Proveedor:  {{$pedido[0]->CodeProv.'  '.$pedido[0]->NombProv}}</h5>
                    <h5>Elaboro:  {{$pedido[0]->Elaboro}}</h5>
                    <h5>Comentarios: {{$pedido[0]->Comments}}</h5>
                </div>
             </div>
         </div>
    </div>  
        <div class="row">
        <div  class= "col-md-11"> 
                 <div class="text-right">
                   @if($fecha_entrada>=$fecha_actual) 
                        <a  class="btn btn-primary btn-sm"  href="desPedidosCsv"><i class="fa fa-file-text"></i>  CSV</a>
                        @else                        
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#csv">
                           <i class="fa fa-file-text"></i>  CSV
                         </button>
                         @endif
                        <a class="btn btn-danger btn-sm"  href="PedidosCsvPDF" target="_blank"><i class="fa fa-file-pdf-o"></i>  PDF</a>                    
                    </div>
                 </div>
            </div>
             <br>
        <div class="row">
        <div class="col-md-11">
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Descripción</th>
                        <th style="text-align: center;">UM</th>
                        <th style="text-align: center;">Cantidad Total</th>
                        <th style="text-align: center;">Cant. Pendiente</th>
                        <th style="text-align: center;">Precio Unitario</th>                                             
                        <th style="text-align: center;">Total</th>  
                        <th style="text-align: center;">Entrega</th>      
                    </tr>
                 </thead>
               <tbody>     
               <?php 
                    $suma=0;
                     ?>
            @foreach ($pedido as $pedi)
                    <tr>
                    <?php 
                     $dat=date_create($pedido[0]->FechEnt); 
                     $total= $pedi->CantPend * $pedi->Price;
                     $suma = $suma + $total;
                     $moneda = $pedi->Currency;                    
                     ?>
                        <td style="text-align: center;">{{$pedi->Codigo}}</td>
                        <td>{{$pedi->Descrip}}</td>
                        <td style="text-align: center;">{{$pedi->BuyUnitMsr}}</td>
                        <td style="text-align: center;">{{number_format($pedi->CantTl,2)}}</td>
                        <td style="text-align: center;">{{number_format($pedi->CantPend,2)}}</td>
                        <td style="text-align: right;">{{number_format($pedi->Price,4)}} {{$pedi->Currency}}</td>
                        <td style="text-align: right;">{{number_format($total,2)." ".$moneda}}</td>     
                        <td style="text-align: center;">{{date_format($dat, 'd-m-Y')}}</td>                                                   
                    </tr>
            @endforeach
            </tbody>
    </table>
    </div>
   </div>  
</div>
<div class="row">
<div class="col-md-11">
    <table  style="width: auto; position: relative; float: right;"class="table table-condensed">
    <tr> <th style="text-align: center;">Totales</th></tr>
     <tr> <td style="text-align: right;">Subtotal: {{number_format($suma,2)." ".$moneda}}</td></tr>
     <tr><td  style="text-align: right;">Impuesto: {{number_format($suma * 0.16,2)." ".$moneda}}</td></tr>
      <tr><td  style="text-align: right;"> Total: {{number_format(($suma * 0.16) + $suma,2)." ".$moneda}}</td></tr>
    </table>
    </div>  
   </div>  


 <div class="modal fade" id="csv" role="dialog" >
    <div class="modal-dialog modal-sm" role="document">
        {!! Form::open(['url' => 'home/desPedidosCsv', 'method' => 'GET']) !!}
        <div class="modal-content" >
            <div class="modal-header" >
                <h4 class="modal-title" id="pwModalLabel" style="text-align: center;">¡Aviso!</h4>
            </div>

            <div class="modal-body">

                Orden creada el día {{date_format($date, 'd-m-Y')}}, desea generar archivo CSV
            </div>
            <div class="modal-footer">
                <input id="submit" name="submit" type="submit" value="Procesar" onclick="ocultaModal();"  class="btn btn-primary"/>
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancelar</button>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
</div><!-- /modal -->
    
@endif <!-- /isset -->

@endsection
<script>

    function ocultaModal(){
        $("#csv").modal("hide")
    };

</script>
@section('homescript')
document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM03_01.pdf","_blank");
  } 
};
@endsection
