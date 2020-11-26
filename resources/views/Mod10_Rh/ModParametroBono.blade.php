@extends('home')
@section('homecontent')
<div class="container" >
  <div class="row">
    <div class="visible-xs"><br><br></div>
         <h3 class="page-header">
           Modificación Parámetros de Bono  
         </h3>    
     </div>
     <div class="row">
    <div class="col-md-11">
            @include('partials.alertas')
      </div>       
 </div> 
{!! Form::open(['url' => 'home/mod_parametro2/'.$id, 'method' => 'POST']) !!}
 <div class="row"> 
 <div class="form-group col-md-3">
    <label>Tipo de Empleado</label>
    <input type="text" class="form-control" name="tipo_emp" value="{{$PBono[0]->tipoEmpleado}}" required autofocus>
    </div>
         <div class="form-group col-md-3">
    <label>Tipo de Bono</label>
    <select type="text" class="form-control" name="tbono_in" value="{{$PBono[0]->tipoBono}}" required>
    <option></option>
    <option value="1">Producción</option>
    <option value="2">Productividad</option>
    <option value="3">Calidad</option>
    <option value="4">Destajo</option>
  </select>
  </div>
    <div class="form-group col-md-3">
    <label>Rango Inicio</label>
    <input type="number" class="form-control" name="rango_in" required min="1"  value="{{$PBono[0]->VSMin}}">
    </div>
  </div>
  <div class="row"> 
  <div class="form-group col-md-3">
    <label>Rango Fin</label>
    <input type="number" class="form-control" name="rango_fin" required min="1"  value="{{$PBono[0]->VSMax}}">
    </div>
    <div class="form-group col-md-3">
    <label>Unidad de Medida</label>
    <input type="text" class="form-control" name="uni_med"  value="{{$PBono[0]->UM}}">
    </div>  
     <div class="form-group col-md-3">
    <label>Bono (en MXN)</label>
    <input type="number" class="form-control" name="bono_mxn" required min="1" value="{{$PBono[0]->bono}}">
    </div>
</div>
<div class="row">
<div class="col-md-0 col-md-offset-7">
        <button type="submit" class="btn btn-primary">Guardar</button>    
        <a type="button"  class="btn btn-default" href="{!!url('home/PARAMETROS BONOS')!!}">Cancelar</a>
      </div>  
    </div>
{!! Form::close() !!}
@endsection