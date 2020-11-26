@extends('app')

@section('content')

@include('partials.menu-admin')



    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div >
                   <div class="col-md-12">
                    <div class="visible-xs visible-sm"><br><br></div>
                    <h3 class="page-header">
                    Configuración de envío de Correo
                    </h3>
                </div>
                       <div class= "col-lg-6.5 col-md-12 col-sm-7">
                        <div class="hidden-xs">
                        <div class="hidden-sm">
                        
                </div>
            </div>
            @include('partials.alertas')

            <div class="row">
                    {!! Form::open(['url' => 'admin/save/email', 'method' => 'POST']) !!}
                
                    <div class="col-md-3">
                        <label>Usuario</label>
                        <select class="form-control" name="nomina" id="nomina" required>
                            <option value="">Seleccione</option>
                            @foreach ($activeUsers as $rep)
                            <option value="{{$rep->U_EmpGiro}}">{{$rep->firstName.' '.$rep->lastName}}</option>
                            @endforeach
                        </select>
                       
                    </div>
                    <div class="col-md-2">   
                        <label>Reprocesos</label>
                        <select class="form-control" name="reprocesos" id="reprocesos">
                            <option value="1">Activado</option>
                            <option value="0">Desactivado</option>
                        </select>
                        
                    </div>
                    <div class="col-md-2">                        
                        <label>Solicitudes MP</label>
                        <select class="form-control" name="solicitudmp" id="solicitudmp">
                            <option value="1">Activado</option>
                            <option value="2">Solicitudes</option>
                            <option value="3">Autorizaciones</option>
                            <option value="0">Desactivado</option>
                        </select>
                       
                    </div>
                    <div class="col-md-2">                        
                        <label>Error Existencias</label>
                        <select class="form-control" name="errorexistencia_04" id="errorexistencia_04">
                            <option value="1">Activado</option>
                            <option value="0">Desactivado</option>
                        </select>
                       
                    </div>
                    <div class="col-md-2">                        
                        <label>Traslados Dept.</label>
                        <select class="form-control" name="traslados_04" id="traslados_04">
                            <option value="1">Activado</option>
                            <option value="2">Entregas</option>
                            <option value="3">Err_Material</option>
                            <option value="0">Desactivado</option>

                        </select>
                       
                    </div>
                    <div class="col-md-3">
                       
                                <button class="btn btn-primary" style="margin-top:25px" type="submit">Guardar</button>
    
                    </div>

                            {!! Form::close() !!}
                </div><!-- /.row -->
<br>
            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                            <th>#</th>
                                            <th># Nómina</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Reprocesos</th>  
                                            <th>Solicitudes MP</th>
                                            <th>Error Existencias</th>
                                            <th>Traslados</th>
                                            <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                        @foreach ($emails as $campo)
                        <tr>
                        <th>{{ $campo->id}}</th>
                        <td>{{ $campo->No_Nomina }}</td>
                        <td>{{ App\User::find($campo->No_Nomina)['firstName'].' '.App\User::find($campo->No_Nomina)['lastName'] }}</td>
                        <td>{{ App\User::find($campo->No_Nomina)['email'].'@zarkin.com' }}</td>
                        <td>{{ $campo->Reprocesos==1?'Activado':'-'}}</td>
                        <td>
                            @if ($campo->SolicitudesMP == 1)
                            Activado
                            @elseif ($campo->SolicitudesMP == 2)
                            Solicitudes
                            @elseif ($campo->SolicitudesMP == 3)
                            Autorizaciones
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $campo->SolicitudesErrExistencias==1?'Activado':'-' }}</td>
                        <td>
                             @if ($campo->Traslados == 1)
                            Activado
                            @elseif ($campo->Traslados == 2)
                            Entregas
                            @elseif ($campo->Traslados == 3)
                            Err_Material
                            @else
                            -
                            @endif     
                        </td>                 
                        <td>
                            <a class="btn btn-warning" id="btneditar-{{ $campo->id}}" onclick="getItem({{ $campo->id}})" 
                                data-id="{{ $campo->id}}" 
                                data-nomina="{{ $campo->No_Nomina}}" 
                                data-reproceso="{{ $campo->Reprocesos}}" 
                                data-solicitudmp="{{$campo->SolicitudesMP}}" 
                                data-errorexistencia_04="{{$campo->SolicitudesErrExistencias}}"
                                data-traslados_04="{{$campo->Traslados}}">
                                
                            <i class="glyphicon glyphicon-edit"></i></a>
                       
                        <a href="email/del/{{$campo->id}}" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></a>
                        </td>
                        </tr>
                    @endforeach 
                    </tbody>
                                
    </div>

@endsection
<script>
function getItem(btn) {

var nomina = $("#btneditar-"+btn).data("nomina");
var reprocesos = $("#btneditar-"+btn).data("reproceso");
var solicitudmp = $("#btneditar-"+btn).data("solicitudmp");
var errorexistencia_04 = $("#btneditar-"+btn).data("errorexistencia_04");
var traslados_04 = $("#btneditar-"+btn).data("traslados_04");

$('#nomina').val(nomina);
$('#reprocesos').val(reprocesos);
$('#solicitudmp').val(solicitudmp);
$('#errorexistencia_04').val(errorexistencia_04);
$('#traslados_04').val(traslados_04);
}
</script>
@section('script')
  
    @endsection