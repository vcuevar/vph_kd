@extends('home')
@section('homecontent')
        <div class="container" >

            <!-- Page Heading -->
            <div class="row">

                    <div class="visible-xs"><br><br></div>
                    <h3 class="page-header">
                        Bonos de la Semana {{$semana}}
                    </h3>
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
            <!-- /.row -->
        <div class="row">
            <div class="col-md-10">
                    <div class="text-right">
                            <a class="btn btn-danger btn-sm" href="bonosPdf" target="_blank"><i class="fa fa-file-pdf-o"></i>  Descarga PDF</a>
                    </div>
                <h3>Producción</h3>
                    <table>
                                 <tr>
                                    <th>Empleado:</th>
                                    <td>{{$dataGerente[0]}}</td>
                                    <td>{{$dataCorte[0]}}</td>
                                    <td>{{$dataCostura[0]}}</td>
                                    <td>{{$dataCojineria[0]}}</td>
                                    <td>{{$dataTapiceria[0]}}</td>
                                    <td>{{$dataCarpinteria[0]}}</td>
                                  </tr>
                                  <tr>
                                    <th>Valor Sala:</th>
                                    <td>{{number_format($dataGerente[1], 2)}}</td>
                                    <td>{{number_format($dataCorte[1], 2)}}</td>
                                    <td>{{number_format($dataCostura[1], 2)}}</td>
                                    <td>{{number_format($dataCojineria[1], 2)}}</td>
                                    <td>{{number_format($dataTapiceria[1], 2)}}</td>
                                    <td>{{number_format($dataCarpinteria[1], 2)}}</td>
                                  </tr>
                                  <tr>
                                    <th>Bono:</th>
                                    <td>{{"$ ".number_format($dataGerente[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCorte[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCostura[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCojineria[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataTapiceria[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCarpinteria[2], 2)}}</td>
                                  </tr>

                        </table>
            </div> <!-- /.col md -->
        </div> <!-- /.row -->
            <div class="row">
                    <div class="col-md-10">
                        <h3>Calidad</h3>
                            <table>
                                         <tr>
                                            <th>Empleado:</th>
                                            <td>{{$ca_gt[0]}}</td>
                                            <td>{{$ca_cor[0]}}</td>
                                            <td>{{$ca_cos[0]}}</td>
                                            <td>{{$ca_coj[0]}}</td>
                                            <td>{{$ca_tap[0]}}</td>
                                            <td>{{$ca_car[0]}}</td>
                                          </tr>
                                          <tr>
                                            <th>% de Calidad:</th>
                                            <td>{{number_format($ca_gt[1], 2)." %"}}</td>
                                             <td>{{number_format($ca_cor[1], 2)." %"}}</td>
                                             <td>{{number_format($ca_cos[1], 2)." %"}}</td>
                                             <td>{{number_format($ca_coj[1], 2)." %"}}</td>
                                             <td>{{number_format($ca_tap[1], 2)." %"}}</td>
                                             <td>{{number_format($ca_car[1], 2)." %"}}</td>
                                          </tr>
                                          <tr>
                                            <th>Bono:</th>
                                            <td>{{"$ ".number_format($ca_gt[2], 2)}}</td>
                                            <td>{{"$ ".number_format($ca_cor[2], 2)}}</td>
                                            <td>{{"$ ".number_format($ca_cos[2], 2)}}</td>
                                            <td>{{"$ ".number_format($ca_coj[2], 2)}}</td>
                                            <td>{{"$ ".number_format($ca_tap[2], 2)}}</td>
                                            <td>{{"$ ".number_format($ca_car[2], 2)}}</td>
                                          </tr>

                                </table>
                    </div> <!-- /.col md -->
                </div> <!-- /.row -->
            <div class="row">
            <div class="col-md-10">
                <h3>Totales</h3>
                    <table>
                                 <tr>
                                    <th>Empleado:</th>
                                    <td>{{$dataGerente[0]}}</td>
                                    <td>{{$dataCorte[0]}}</td>
                                    <td>{{$dataCostura[0]}}</td>
                                    <td>{{$dataCojineria[0]}}</td>
                                    <td>{{$dataTapiceria[0]}}</td>
                                    <td>{{$dataCarpinteria[0]}}</td>
                                  </tr>
                                  <tr>
                                    <th>Total Bono:</th>
                                    <td>{{"$ ".number_format($dataGerente[2]+$ca_gt[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCorte[2]+$ca_cor[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCostura[2]+$ca_cos[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCojineria[2]+$ca_coj[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataTapiceria[2]+$ca_tap[2], 2)}}</td>
                                    <td>{{"$ ".number_format($dataCarpinteria[2]+$ca_car[2], 2)}}</td>
                                  </tr>
                        </table>
            </div>
        </div>
        <h3></h3>
    <!-- Modal -->
    <div class="modal fade " id="pass" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        {!! Form::open(['url' => 'home/rh/reportes/bonos', 'method' => 'POST']) !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <h2 style="text-align: center;">Semana</h2>
                            <div class="row">
                                <div class="col-md-12">
                                        @include('partials.alertas')
                                  </div>       
                             </div>          
                           <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                  <input name="semana" type="number" class="form-control" required autofocus min="1" max="{{date("W", strtotime("now"))}}" value="{{date("W", strtotime("now"))}}">
                                </div>
                           </div>
                        <br>
                           <!-- <h4>Parámetros Calidad</h4>
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
                            </div>-->          
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                            <a type="button" class="btn btn-default"  href="{!!url('home')!!}">Cancelar</a>
                        </div>
                        </div>
                </div>
            </div>
    </div>
         {!! Form::close() !!}
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