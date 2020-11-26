@extends('home')
@section('homecontent')
<div class="container" >
  <div class="row">
    <div class="visible-xs"><br><br></div>
         <h3 class="page-header">
            Set Parámetros de Bono  
         </h3>
        <div id="login" data-field-id="{{$enviado}}" >
            <div class= "col-lg-6.5 col-md-12 col-sm-7 hidden-xs hidden-sm">
                     <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                    </ol>
              </div>
         </div>
     </div>
     <div class="row">
    <div class="col-md-11">
            @include('partials.alertas')
      </div>       
 </div> 
{!! Form::open(['url' => 'home/PARAMETROS BONOS', 'method' => 'POST']) !!}
 <div class="row"> 
 <div class="form-group col-md-3">
    <label>Tipo de Empleado</label>
    <input type="text" class="form-control" name="tipo_emp"  required autofocus>
    </div>
         <div class="form-group col-md-3">
    <label>Tipo de Bono</label>
    <select type="number" class="form-control" name="tbono_in" required>
    <option></option>
    <option value="1">Producción</option>
    <option value="2">Productividad</option>
    <option value="3">Calidad</option>
    <option value="4">Destajo</option>
  </select>
  </div>
    <div class="form-group col-md-3">
    <label>Rango Inicio</label>
    <input type="number" class="form-control" name="rango_in" required min="1" value="text">
    </div>
  </div>
  <div class="row"> 
  <div class="form-group col-md-3">
    <label>Rango Fin</label>
    <input  type="number" class="form-control" name="rango_fin" required min="1" value="text">
    </div>
    <div class="form-group col-md-3">
    <label>Unidad de Medida</label>
    <input type="text" class="form-control" name="uni_med"  required >
    </div>  
     <div class="form-group col-md-3">
    <label>Bono (en MXN)</label>
    <input  type="number" class="form-control" name="bono_mxn" required min="1" value="text">
    </div>
</div>
<div class="row">
<div class="col-md-0 col-md-offset-8">
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>  
    </div>
{!! Form::close() !!}
    <br><br>
@if(isset($Ndatos))
        <div class="row">
        <div style="overflow-x:auto;" class="col-md-11">
        <div class="table-responsive">
            <table class="table table-striped header-fixed">
                    <thead class="thead-dark">
                                 <tr>
                                  <th style="width:20%; text-align: center;">Tipo de Empleado</th>
                                  <th style="width:10%; text-align: center;">Tipo de Bono</th>
                                  <th style="text-align: center;">Rango Inicio</th>
                                  <th style="text-align: center;">Rango Final</th>
                                  <th style="text-align: center;">UM</th>
                                  <th style="text-align: center;">Bono</th>   
                                  <th style="text-align: center;">Modificar</th>
                                  <th style="text-align: center;">Eliminar</th>      
                                 </tr>
                    </thead>
                    <tbody>  
                    @foreach ($Ndatos as $Ndato )
                               <tr>
                                <th scope="row" style="text-align: center;">{{$Ndato->tipoEmpleado}}</th>
                                <td style="text-align: center;">{{$Ndato->tipoBono}}</td>
                                <td style="text-align: center;">{{number_format($Ndato->VSMin)}}</td>
                                <td style="text-align: center;">{{number_format($Ndato->VSMax)}}</td>
                                <td style="text-align: center;">{{$Ndato->UM}}</td>
                                <td style="text-align: center;">{{"$ ".number_format($Ndato->bono)}}</td>
                                <td style="text-align: center;"><a href="mod_parametro/{{$Ndato->id}}" class="btn btn-warning"><i class="fa fa-pencil-square"></i</a></td>
                                <td style="text-align: center;"><a href="delete_parametro/{{$Ndato->id}}" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i</a></td>

                              </tr>
                   @endforeach
                    </tbody>
                    </table>
            </div>  
        </div>  
 </div>
  </div>
@endif <!-- /isset -->
@endsection
@section('homescript')
window.TrelloBoards.load(document, { allAnchors: false });
@endsection