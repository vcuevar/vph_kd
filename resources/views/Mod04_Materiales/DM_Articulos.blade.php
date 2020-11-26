@extends('home') 
@section('homecontent')

<style>
    th, td{
        font-size: 12px;
    }
    
    .table{
        width: auto;
        margin-bottom:0px;
    }
    .detalle {
     margin-left: 3%;
    }
    .table > thead > tr > th, 
    .table > tbody > tr > th, 
    .table > tfoot > tr > th, 
    .table > thead > tr > td, 
    .table > tbody > tr > td,
    .table > tfoot > tr > td { 
        padding-bottom: 2px; padding-top: 2px; padding-left: 4px; padding-right: 0px;
    }
   
    .list-group-item {
        border: 1px solid #b3b0b0;
        padding: 3px 10px
    }
.list-group-item:last-child {
margin-bottom: 10px;

}
h5 small {
    font-size:100%;
}
.container {
padding-right: 15px;
padding-left: 15px;
margin-right: 15px;
margin-left: 10px;
}
.green-edit-field {
border: 1px solid #000000;
}
.boot-select{
    padding-bottom: 10px !important;
}
.bootstrap-select>.dropdown-toggle {
width: 100% !important;
}
</style>
<div class="container">
 {!! Form::open(['url' => 'articuloToSap', 'method' => 'POST', 'id' => 'mainform']) !!}
 {{ csrf_field() }}
    <!-- Page Heading -->
    <div class="row">
        
            <div class="visible-xs visible-sm"><br><br></div>
         
            <div class="col-md-12">
                <h3 class="page-header">
                    Datos Maestros de Artículos 
                    <div class="visible-xs visible-sm"><br></div>
                    <span class="pull-right">
                        @if(!isset($oculto))  
                            <a class="btn btn-primary" href="{{url('home/DATOS MAESTROS ARTICULOS')}}">Revisar Otro Artículo</a>                    
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirma" {{$privilegioTarea}}>
                                            <i class="fa fa-save" aria-hidden="true"></i> Guardar
                            </button>
                        @else
                            <a class="btn btn-primary btn-sm" href="{{URL::previous()}}"><i class="fa fa-angle-left"></i> Atras</a>
                        @endif
                        <div class="visible-xs visible-sm"><br></div>
                    </span>         
                </h3>
                
            </div>
        
    </div>
   <div class="row">
    <div class="col-md-12">
        @include('partials.alertas')
    </div>
</div>
    <div class="row">
        <div class="col-md-3">
       <ul>
         <li class="list-group-item active">
            <div>
                <h5>Código <small><span class="pull-right" style="color:white">{{$data[0]->ItemCode}}</span></small></h5>
                <input type="hidden" name="pKey" value="{{$data[0]->ItemCode}}">
            </div>
            
        </li>
       </ul>
        </div>
        <div class="col-md-7">
            <ul>
                <li class="list-group-item">
                    <div>
                       <h5 class="my-0"> <small>{{$data[0]->ItemName}}</small></h5>
                        <h5 class="my-0"></h5>
                    </div>
        
                </li>
            </ul>
        </div>
        
    </div>
    <!-- /.row -->
    <div class="row">
       
        <div class="col-md-7 col-sm-12">
            <ul>
                <li class="list-group-item green-edit-field">
                    <div>
                        <h5>Proveedor</h5>                    
                        <div class="input-group">
                        
                            <select data-live-search="true" class="boot-select" id="proveedor" name="proveedor" {{$privilegioTarea}}>                               
                                <option value="" {{ old('proveedor', $data[0]->CardCode??'SIN DATOS') == 'SIN DATOS' ? 'selected' : '' }}>SIN DATOS</option>
                                @foreach ($proveedores as $proveedor)
                                <option value="{{old('proveedor',$proveedor->CardCode)}}" {{ ($proveedor->CardCode == $data[0]->CardCode) ? 'selected' : '' }}>                                   
                                    <span>{{$proveedor->CardCode}}  &nbsp;&nbsp;&nbsp; {{$proveedor->CardName}}</span></option>
                                @endforeach
                            </select>                         
                        </div><!-- /input-group -->
                    </div>        
                </li>
            </ul>
        </div>
        <div class="col-md-3 col-sm-12">
        @if (Storage::disk('nas')->has($data[0]->ItemCode.'.jpg'))
        
            <a class="btn btn-primary" id="showImg" style="margin-bottom: 10px;" 
            
            src="data:image/jpeg;base64,{{ base64_encode(Storage::disk('nas')->get(''.$data[0]->ItemCode.'.jpg')) }}"
            ><i class="fa fa-camera" aria-hidden="true"></i> Ver Imagen</a>
        @else
        <a class="btn btn-warning" id="showImg" style="margin-bottom: 10px;" 
            
            src="data:image/jpeg;base64,{{ base64_encode(Storage::disk('nas')->get('SIN_IMAGEN.jpg')) }}"
            ><i class="fa fa-camera" aria-hidden="true"></i> Sin Imagen</a>
        @endif
        </div>
    </div>   
    
    <div class="row">
       <div class="col-md-4">
        <ul>
            <li class="list-group-item active"> 
                <i class="fa fa-cubes"></i> ALMACENES 
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">EXISTENCIA GDL <small ><span class="pull-right">{{ number_format($data[0]->A_Gdl, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
    
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">EXISTENCIA LERMA <small ><span class="pull-right">{{ number_format($data[0]->A_Lerma, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
    
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">WIP <small ><span class="pull-right">{{ number_format($data[0]->WIP, 2, '.', ',') }}</span></small> </h5>                    
                </div>    
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">OTROS <small ><span class="pull-right">{{ number_format($data[0]->ALM_OTROS, 2, '.', ',') }}</span></small> </h5>                    
                </div>    
            </li>
        </ul>
        <ul>     

              @if (count($columns) > 0)
              <li class="list-group-item active">
                <div>
                   <i class="fa fa-cube"></i> MRP <small><span
                                class="pull-right" style="color:white">{{date_format(new DateTime($semanas['fechaDeEjecucion']), 'd-m-Y H:i A')}}</span></small>                  
                </div>
            
            </li>
                  @foreach ($columns as $col)
                    <li class="list-group-item">
                        <div>
                            <h5 class="my-0">{{$col['name']}} <small ><span
                                        class="pull-right">{{ number_format($semanas[$col['data']], 2, '.', ',') }}</span></small> </h5>
                    
                        </div>
                    </li>
                    @endforeach
              @else
              <li class="list-group-item active">
                <div>
                   <i class="fa fa-cube"></i> MRP <small></small>                  
                </div>
            
            </li>
                  <li class="list-group-item">
                    <div>
                        <h5 class="my-0">SIN DATOS <small ><span class="pull-right"></span></small>
                        </h5>
                        <h5 class="my-0"></h5>
                    </div>
                
                </li>
              @endif                           
        </ul>
    </div> <!-- /.md-4 -->
    <div class="col-md-3">
        <ul>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">UM <small ><span
                                class="pull-right">{{ $data[0]->UM}}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">UM COMPRA<small ><span
                                class="pull-right">{{ $data[0]->UM_Com}}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">FACTOR <small ><span
                                class="pull-right">{{ number_format($data[0]->Factor, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            
        </ul>
        <ul>
            @if (count($columns) > 0)
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">Necesidad <small ><span
                                class="pull-right">{{ number_format($semanas['necesidadTotal'], 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item {{(($semanas['Necesidad']) < 0)? 'list-group-item-danger':'list-group-item-success'}}">
                <div>
                <h5 class="my-0">Disp. S/WIP <small ><span
                                class="pull-right" style="{{(($semanas['Necesidad']) < 0)? 'color:red':''}}">{{ number_format($semanas['Necesidad'], 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>                      
            @endif
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">OC <small ><span
                                class="pull-right">{{ number_format($data[0]->OC, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
        </ul>
        <ul>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">P. Reorden <small><span
                                class="pull-right">{{ number_format($data[0]->Reorden??0, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">S. Mínimo <small><span
                                class="pull-right">{{ number_format($data[0]->Minimo??0, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">S. Máximo <small><span
                                class="pull-right">{{ number_format($data[0]->Maximo??0, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
            <li class="list-group-item">
                <div>
                    <h5 class="my-0">T.E. <small><span
                                class="pull-right">{{ number_format($data[0]->TE??0, 2, '.', ',') }}</span></small> </h5>
                    <h5 class="my-0"></h5>
                </div>
            
            </li>
        </ul>
        <ul>
            <li class="list-group-item green-edit-field">
                <div>
                    <h5 class="my-0">MÉTODO</h5>
                    <div class="">                    
                        <select class="form-control" id="metodo" name="metodo" style="margin-bottom: 10px;" {{$privilegioTarea}}>
                            <option value="" {{ old('metodo', $data[0]->Metodo??'SIN DATOS') == 'SIN DATOS' ? 'selected' : '' }}>SIN
                                DATOS</option>
                            @foreach ($metodos as $metodo)
                            <option value="{{old('metodo',$metodo->FldValue)}}"
                                {{ ($metodo->Descr == $data[0]->Metodo) ? 'selected' : '' }}>
                                {{$metodo->Descr}}</option>
                            @endforeach
                        </select>
                    </div> 
                                                
                </div>            
            </li>
            <li class="list-group-item {{($data[0]->Linea == 'Obsoleto')? 'list-group-item-danger':''}}">
                <div>
                    <h5 class="my-0">ESTATUS <small><span
                                class="pull-right">{{ $data[0]->Linea??'SIN DATOS' }}</span></small> </h5>                    
                </div>            
            </li>
        </ul>
    </div> <!-- /.md-3 -->
  <div class="col-md-5">
    <ul>    
        <li class="list-group-item">
            <div>
                <h5 class="my-0">GRUPO ARTICULO <small><span class="pull-right">{{ $data[0]->Grupo??'SIN DATOS' }}</span></small> </h5>
            </div>
        </li>
    </ul>
    <ul>    
        <li class="list-group-item green-edit-field">
            <div>
                <h5 class="my-0">GRUPO PLANEACION </h5>
                    <div class="input-group">
                        <select  data-live-search="true"  class="boot-select" id="grupop" name="grupop" style="margin-bottom: 10px;" {{$privilegioTarea}}>
                            <option value="" {{ old('grupop', $data[0]->Grupo_Pla??'SIN DATOS') == 'SIN DATOS' ? 'selected' : '' }}>SIN
                                DATOS</option>
                            @foreach ($gruposPlaneacion as $grupo)
                            <option value="{{old('grupop',$grupo->FldValue)}}"
                                {{ ($grupo->Descr == $data[0]->Grupo_Pla) ? 'selected' : '' }}>
                                {{$grupo->Descr}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
        </li>
    </ul>
    <ul>    
        <li class="list-group-item active">
            <div>
              <i class="fa fa-usd"></i> COSTOS
            </div>
        </li>
        <li class="list-group-item">
            <div>
                <h5 class="my-0">ESTANDAR <small><span class="pull-right">${{ number_format($data[0]->CostoEstandar??0, 2, '.', ',') }} {{$data[0]->MonedaEstandar??'SIN DATOS'}}</span></small>
                </h5>
            </div>
        </li>
        <li class="list-group-item">
            <div>
                <h5 class="my-0">LISTA 10 <small><span class="pull-right">${{ number_format($data[0]->CostoL10??0, 2, '.', ',') }} {{$data[0]->MonedaL10??'SIN DATOS'}}</span></small>
                </h5>
            </div>
        </li>
        <li class="list-group-item green-edit-field">
            <h5 class="my-0">A-COMPRAS 
            <span class="pull-right">${{ number_format($data[0]->CostoACompras??0, 2, '.', ',') }}
                {{$data[0]->MonedaACompras??'SIN DATOS'}}</span></h5> 
            
            <div class="row">
                <div class="col-md-6">
                <input type="number" step="0.01" min="0" class="form-control" name="costocompras" id="costocompras" value="{{old('costocompras', number_format($data[0]->CostoACompras??0, 2, '.', ','))}}" {{$privilegioTarea}}>
                </div>
                <div class="col-md-6">
                <select class="form-control" id="monedacompras" name="monedacompras" style="margin-bottom: 10px;" {{$privilegioTarea}}>
                    <option value="{{old('monedacompras','MXP')}}" {{ ('MXP' == $data[0]->MonedaACompras) ? 'selected' : '' }}>
                        MXP</option>
                    <option value="{{old('monedacompras','USD')}}" {{ ('USD' == $data[0]->MonedaACompras) ? 'selected' : '' }}>
                        USD</option>
                    <option value="{{old('monedacompras','CAN')}}" {{ ('CAN' == $data[0]->MonedaACompras) ? 'selected' : '' }}>
                        CAN</option>
                        
                </select>
            </div>
        </li>
        <li class="list-group-item">   
            <div data-toggle="tooltip" data-placement="top" title="{{(($data[0]->FechaUltimaCompra??'')<>'')?("Fecha Ult. Compra: ". date_format(new DateTime($data[0]->FechaUltimaCompra), 'd-m-Y')):""}}" >
                <h5>ULT. COMPRA <small><span class="pull-right">${{ number_format($data[0]->CostoU??0, 2, '.', ',') }} {{$data[0]->MonedaU??'SIN DATOS'}}</span></small>
                </h5>
            </div>
        </li>
    </ul>
    <ul>    
        <li class="list-group-item green-edit-field">
            <div>
                <h5 class="my-0">COMPRADOR </h5>
                    <select class="form-control" id="comprador" name="comprador" style="margin-bottom: 10px;" {{$privilegioTarea}}>
                        <option value="" {{ old('comprador', $data[0]->Comprador??'SIN DATOS') == 'SIN DATOS' ? 'selected' : '' }}>SIN
                            DATOS</option>
                        @foreach ($compradores as $comprador)
                        <option value="{{old('comprador',$comprador->FldValue)}}" {{ ($comprador->Descr == $data[0]->Comprador) ? 'selected' : '' }}>
                            {{$comprador->Descr}}</option>
                        @endforeach
                    </select>
            </div>
        </li>
    </ul>
    <ul>    
        <li class="list-group-item">
            <div>
                <h5 class="my-0">CONSUME <small><span class="pull-right">{{ $data[0]->Ruta??'SIN DATOS' }}</span></small> </h5>
            </div>
        </li>
    </ul>
  </div><!-- /.md-5 -->
    </div> <!-- /.row -->
    
{!! Form::close() !!}
                    <div class="modal fade" id="confirma" tabindex="-1" role="dialog" >
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="pwModalLabel">Actualizar Artículo en SAP</h4>
                                </div>
                             
                                <div class="modal-body">

                                    <div class="form-group">
                                        <div>
                                           <h4>¿Desea continuar?</h4>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="button" id="submitBtn" class="btn btn-primary">Guardar</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                            class="sr-only">Close</span></button>
                                    <img src="" class="imagepreview" style="width: 100%;" onerror="this.src='{{URL::asset('/images/articulos/SIN_IMAGEN.jpg')}}'">
                                </div>
                            </div>
                        </div>
                    </div>
</div>
    <!-- /.container -->
@endsection
@section('homescript')
$("#submitBtn").click(function(){        

$("#mainform").submit(); // Submit the form

});

$("#showImg").click(function(){        
$('.imagepreview').attr('src', $("#showImg").attr('src'));
$('#imagemodal').modal('show');
});
@endsection
