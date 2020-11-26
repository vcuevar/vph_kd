@extends('home')
@section('homecontent')
        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                    <div class="visible-xs"></div>
                    <h4 class="page-header">
                      SALOTTO SA de CV  -  Bonos Departamento de Corte  -  Semana: {{$semana}}
                    </h4>
                    <div id="login" data-field-id="{{$enviado}}" >
                       <div class= "col-lg-6.5 col-md-12 col-sm-7 hidden-xs hidden-sm">

                        <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="#">RH</a>
                       </li>
                       <li>
                            <i class="fa fa-money"></i>  <a href="#">Bonos</a>
                        </li>
                    </ol>
                 </div>
            </div>
<div class="row">
        <div class="col-md-8">
             <div class="table-responsive">
             <table  class="table table-striped header-fixed">
                    <thead class="thead-dark">
                    <div class="text-right">
                    <a class="btn btn-success btn-sm" href="bonoscorteEXL"> <i class="fa fa-file-excel-o"></i> Excel</a>
                    <a class="btn btn-danger btn-sm" href="bonoscortePdf" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
                    </div>
                    <h4>Corte de Piel</h4>
                        <tr>
                        <th scope="col" style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col" style="text-align: center;">Destajo (DM2)</th>
                        <th scope="col" style="text-align: center;">Bono</th>
                        </tr>
                    </thead>    
                <tbody>
                   @if(isset($cortadores))     
                    @foreach ($cortadores as $cortador)
                    <tr>
                    <td style="text-align: center;">{{$cortador->U_EmpGiro}}</td>
                    <td>{{$cortador->firstName}}, {{$cortador->lastName}}</td>
                    <td style="text-align: center;">{{number_format($cortador->Usado,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($cortador->bono,2)}}</td>                    
                    </tr> 
                    @endforeach
                    @endif
                </tbody>
            </table>
            </div> 
        </div> 
</div> 
<div class="row">
        <div class="col-md-8">
             <div class="table-responsive">
             <table  class="table table-striped header-fixed">
                    <thead class="thead-dark">
                    <h4>Inspección de Corte</h4>
                        <tr>
                        <th scope="col"style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col"style="text-align: center;">Destajo (VS)</th>
                        <th scope="col"style="text-align: center;">Bono</th>
                        </tr>
                    </thead>    
                <tbody>
                   @if(isset($inspeccion))     
                    @foreach ($inspeccion as $insperctor)
                    <tr>
                    <td style="text-align: center;">{{$insperctor->U_EmpGiro}}</td>
                    <td>{{$insperctor->firstName}}, {{$insperctor->lastName}}</td>
                    <td style="text-align: center;">{{number_format($insperctor->U_VS,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($insperctor->bono,2)}}</td>                    
                    </tr> 
                    @endforeach
                    @endif
                </tbody>
            </table>
            </div> 
        </div> 
</div> 
<div class="row">
        <div class="col-md-8">
             <div class="table-responsive">
             <table  class="table table-striped header-fixed">
                    <thead class="thead-dark">
                    <h4>Pegado para Costura</h4>
                        <tr>
                        <th scope="col" style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col" style="text-align: center;">Destajo (VS)</th>
                        <th scope="col" style="text-align: center;">Bono</th>
                        </tr>
                    </thead>    
                <tbody>
                   @if(isset($pegado))     
                    @foreach ($pegado as $pegador)
                    <tr>
                    <td style="text-align: center;">{{$pegador->U_EmpGiro}}</td>
                    <td>{{$pegador->firstName}}, {{$pegador->lastName}}</td>
                    <td style="text-align: center;">{{number_format($pegador->U_VS,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($pegador->bono,2)}}</td>                    
                    </tr> 
                    @endforeach
                    @endif
                </tbody>
            </table>
            </div> 
        </div> 
</div> 
    <!-- Modal -->
    <div class="modal fade " id="pass" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        </div>
                        {!! Form::open(['url' => 'home/rh/reportes/bonosCorte', 'method' => 'POST']) !!}
                        <div class="modal-body">
                        <div class="form-group">
                            <h2 style="text-align: center;">Semana</h2>
                           <div class="row">
                                        <div class="col-md-4 col-md-offset-4">
                                        <input name="semana" type="number" class="form-control" required autofocus min="1" max="{{date("W", strtotime("-1 week"))}}" value="{{date("W", strtotime("-1 week"))}}">
                                        </div>
                           </div>
                       <!-- <br>
                            <h4>Parámetros Calidad</h4>
                            <div class="row">

                                    <div class="col-md-4">
                                            <label>Corte</label>
                                            <input name="ca_cor" type="number" class="form-control" required min = "1">
                                    </div>
                                    <div class="col-md-4">
                                            <label>Costura</label>
                                            <input name="ca_cos" type="number" class="form-control" required min="1">
                                    </div>
                                    <div class="col-md-4">
                                            <label>Cojineria</label>
                                            <input name="ca_coj" type="number" class="form-control" required min="1">
                                    </div>
                            </div> <br>
                            <div class="row">

                                    <div class="col-md-4">
                                            <label>Tapiceria</label>
                                            <input name="ca_tap" type="number" class="form-control" required min="1">
                                    </div>
                                    <div class="col-md-4">
                                            <label>Carpinteria</label>
                                            <input name="ca_car" type="number" class="form-control" required min="1">
                                    </div>
                                    <div class="col-md-4">
                                            <label>Gerente Producción</label>
                                            <input name="ca_gt" type="number" class="form-control" required min="1">
                                    </div>
                            </div>

                        </div>-->

                        </div>
                        <div class="modal-footer">
                       <button type="submit" class="btn btn-primary">Entrar</button>
                            <a type="button" class="btn btn-default"  href="{!!url('home')!!}">Cancelar</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!-- /modal -->

@endsection

@section('homescript')


var myuser = $('#login').data("field-id");

if(myuser == false){
        $('#pass').modal(
        {
                show: true,
                backdrop: 'static',
                keyboard: false
        }
        );
}
@endsection
