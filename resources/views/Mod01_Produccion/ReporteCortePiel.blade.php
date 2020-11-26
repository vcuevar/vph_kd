@extends('home')
@section('homecontent')
 <div class="container" >

<!-- Page Heading -->
 <div class="row">
  <div class="col-lg-12">
     <h3 class="page-header">
        Salotto SA DE CV 
        <small>Reporte 112 Corte de Piel</small>          
    </h3>
       @if($enviado)
        <h6>Fecha: del {{$fechaI}} al {{$fechaF}}</h6>
          <h4> {{$departamento}}</h4>
       @endif       
         </div>
        </div>
                 <!-- /.row -->
                    <div class="row">
                    <div class="text-right">
                    </div>
                        <div class="col-md-24">
                            @include('partials.alertas')
                            <div id="login" data-field-id="{{$enviado}}" >
                            </div>

                            @if($enviado)
                            
                            <div class="row">
                                        <div style="overflow-x:auto;" class="col-md-24">

                                            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                                <thead>
                                                <div  calss= "col-md-12"> 
                                                <div class="text-right">
                                                <a  class="btn btn-success btn-sm" href="repCortePielExl"> <i class="fa fa-file-excel-o"></i> Excel</a>
                                                <a disabled="" class="btn btn-danger btn-sm"  target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
                                                </div>
                                                </div>
                                                <h5>Detalles de Inspeccion Corte de Piel</h5>
                                     <tr>
                                                    <th>Orden Prod</th>
                                                    <th>Cortador</th>
                                                    <th>Código</th>
                                                    <th>Modelo</th>
                                                    <th>VS</th>
                                                    <th>Cant. Teorica Piel</th>
                                                    <th>$ Teorico</th>
                                                    <th>Cant. Usada Piel</th>
                                                    <th>$ Usado</th>
                                                    <th>Diferencia dm2</th>
                                                    <th>Diferencia</th>
                                                    <th>Desperdicio /Mura</th>
                                                    <th>Fecha de Inspeccion</th>
                                                    <th>Dia Semana</th>
                                                    <th>Semana</th>
                                                    <th>Modelo</th>
                                                    <th>Acabado</th>
                                                </tr>
                                                </thead>
                                 @foreach ($detInsPiel as $dato)
                                                    <tr>
                                                   <?php 
                                                   $diferiencia = $dato->Usado - $dato->Teorico;
                                                   $dife = $dato->Usado / $dato->Teorico;
                                                   $mura = $dato->mUsado - $dato->mTeorico;
                                                     $dias = array("7","1","2","3","4","5","6");
                                                     Input::get('semana');
                                                     $mod = "$dato->ItemCode";
                                                     $model = explode('-', $mod);
                                                     $date=date_create($dato->FECHA);
                                                      ?>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" > {{$dato->DocNum}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$dato->firstName}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$dato->ItemCode}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$dato->itemname}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($dato->u_vs,2)}} </td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{number_format($dato->Teorico,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($dato->mTeorico,2)}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" >{{number_format($dato->Usado,2)}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($dato->mUsado,2)}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{number_format($diferiencia,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($dife,2)}} </td>
                                                        <td style="font-size: 10px; width: 51% padding-bottom: 0px; padding-top: 0px;">{{number_format($mura,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'd-m-Y')}}</td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{$dias[date_format($date, 'w')]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'W')}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[0]}}{{$model[1]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[2]}} </td>
                                                    </tr>
                                          @endforeach
                                            </table>    
                                        </div></div>                                                             
                                     </div>
                                      <div class="row">
                                        <div style="overflow-x:auto;" class="col-md-24">

                                            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                                <thead>
                                                <h5>Detalle de Corte de Piel</h5>                                  
                                     <tr>
                                                    <th>Orden Prod</th>
                                                    <th>Cortador</th>
                                                    <th>Código</th>
                                                    <th>Modelo</th>
                                                    <th>VS</th>
                                                    <th>Cant. Teorica Piel</th>
                                                    <th>$ Teorico</th>
                                                    <th>Cant. Usada Piel</th>
                                                    <th>$ Usado</th>
                                                    <th>Diferencia dm2</th>
                                                    <th>Diferencia</th>
                                                    <th>Desperdicio /Mura</th>
                                                    <th>Fecha de Inspeccion</th>
                                                    <th>Dia Semana</th>
                                                    <th>Semana</th>
                                                    <th>Modelo</th>
                                                    <th>Acabado</th>
                                                </tr>
                                                </thead>
                                 @foreach ($detCorte as $datoo)
                                                    <tr>
                                                   <?php 
                                                   $diferiencia = $datoo->Usado - $datoo->Teorico;
                                                   $dife = $datoo->Usado / $datoo->Teorico;
                                                   $mura = $datoo->mUsado - $datoo->mTeorico;                                            
                                                     $dias = array("7","1","2","3","4","5","6");
                                                     Input::get('semana');
                                                     $mod = "$datoo->ItemCode";
                                                     $model = explode('-', $mod);    
                                                     $date=date_create($datoo->FECHA);                                                                                          
                                                      ?>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" > {{$datoo->DocNum}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$datoo->firstName}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$datoo->ItemCode}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$datoo->itemname}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($datoo->u_vs,2)}} </td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{number_format($datoo->Teorico,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($datoo->mTeorico,2)}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" >{{number_format($datoo->Usado,2)}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($datoo->mUsado,2)}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{number_format($diferiencia,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($dife,2)}} </td>
                                                        <td style="font-size: 10px; width: 51% padding-bottom: 0px; padding-top: 0px;">{{number_format($mura,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'd-m-yyyy')}}</td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{$dias[date_format($date, 'w')]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'W')}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[0]}}{{$model[1]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[2]}} </td>
                                                        </tr>
                                          @endforeach
                                            </table>    
                                        </div></div>                                                             
                                     </div>
                                      <div class="row">
                                        <div style="overflow-x:auto;" class="col-md-24">

                                            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                                <thead>
                                                <h5>Detalle de Pegado</h5>
                                                <tr>
                                                    <th>Orden Prod</th>
                                                    <th>Cortador</th>
                                                    <th>Código</th>
                                                    <th>Modelo</th>
                                                    <th>VS</th>
                                                    <th>Cant. Teorica Piel</th>
                                                    <th>$ Teorico</th>
                                                    <th>Cant. Usada Piel</th>
                                                    <th>$ Usado</th>
                                                    <th>Diferencia dm2</th>
                                                    <th>Diferencia</th>
                                                    <th>Desperdicio /Mura</th>
                                                    <th>Fecha de Inspeccion</th>
                                                    <th>Dia Semana</th>
                                                    <th>Semana</th>
                                                    <th>Modelo</th>
                                                    <th>Acabado</th>
                                                </tr>
                                                </thead>
                                 @foreach ($detPegado as $datto)
                                                    <tr>
                                                   <?php 
                                                   $diferiencia =$datto->Usado - $datto->Teorico;
                                                   $dife =$datto->Usado / $datto->Teorico;
                                                   $mura = $datto->mUsado - $datto->mTeorico;                                                  
                                                     $dias = array("7","1","2","3","4","5","6");
                                                     Input::get('semana');
                                                     $mod = "$datto->ItemCode";
                                                     $model = explode('-', $mod);  
                                                     $date=date_create($datto->FECHA);                                               
                                                      ?>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" > {{$datto->DocNum}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$datto->firstName}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$datto->ItemCode}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$datto->itemname}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($datto->u_vs,2)}} </td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{number_format($datto->Teorico,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($datto->mTeorico,2)}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" >{{number_format($datto->Usado,2)}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{"$ ".number_format($datto->mUsado,2)}}</td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{number_format($diferiencia,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{number_format($dife,2)}} </td>
                                                        <td style="font-size: 10px; width: 51% padding-bottom: 0px; padding-top: 0px;">{{number_format($mura,2)}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'd-m-yyyy')}}</td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{$dias[date_format($date, 'w')]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{date_format($date, 'W')}}</td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[0]}}{{$model[1]}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$model[2]}} </td>
                                                    </tr>
                                          @endforeach
                                            </table>    
                                        </div></div>   
                                     @endif                                                          
                            </div>

                        <!-- Modal -->

                        <div class="modal fade" id="pass" role="dialog" >
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content" >
                                    <div class="modal-header">

                                        <h4 class="modal-title" id="pwModalLabel">Reporte de producción</h4>
                                    </div>
                                    {!! Form::open(['url' => 'home/112 CORTE DE PIEL', 'method' => 'POST']) !!}

                                    <div class="modal-body">
                                        <input type="text" hidden name="enviado" value="true">
                                        <div class="form-group">
                                            @include('partials.alertas')
                                        </div>
                                        <div class="form-group">
                                            <label for="date_range" class="control-label">Rango de Fechas:</label><br>
                                            Desde:<input type="date" id="FechIn" name="FechIn" class="form-control" >
                                            Hasta:<input type="date" id="FechaFa" name="FechaFa" class="form-control" >
                                        </div>                                     
                                    </div>
                                    <div class="modal-footer">
                                        <div id="hiddendiv" class="progress" style="display: none">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                <span>Espere un momento...<span class="dotdotdot"></span></span>
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <input id="submit" name="submit" type="submit" value="Generar" onclick="mostrar();"  class="btn btn-primary"/>

                                        <a type="button" class="btn btn-default"  href="{!!url('home')!!}">Cancelar</a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div><!-- /modal -->
                        <!--  cantidadModal -->
                        <div class="modal fade" id="cantidad" role="dialog" >
                            <div class="modal-dialog modal-sm" role="document">
                                {!! Form::open(['url' => 'home/reporte/DetinsPiel', 'method' => 'POST']) !!}
                                <div class="modal-content" >

                                    <div class="modal-header" >

                                        <h4 class="modal-title" id="pwModalLabel">Reporte</h4>
                                    </div>

                                    <div class="modal-body">


                                    </div>
                                    <div class="modal-footer">

                                        <input id="submit" name="submit" type="submit" value="Procesar" onclick="mostrar();"  class="btn btn-primary"/>
                                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancelar</button>
                                    </div>

                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div><!-- /modal -->

                        <!-- /cantidadModal-->

                    </div>
                    <!-- /.container -->

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


                        $('#cantidad').on('show.bs.modal', function (event) {
                        var button = $(event.relatedTarget) // Button that triggered the modal
                        var recipient2 = button.data('whatever2') // Extract info from data-* attributes
                        var recipient = button.data('whatever') // Extract info from data-* attributes
                        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                        var modal = $(this)

                        modal.find('#cant').val(recipient2)
                        modal.find('#code').val(recipient)
                        modal.find('#numcant').val(recipient2)
                        modal.find('#cant').attr('max', recipient2);
                        });



                        $('#date_range').daterangepicker(

                        { "locale": {
                        "format": "DD/MM/YYYY",
                        "separator": " - ",
                        "applyLabel": "Guardar",
                        "cancelLabel": "Cancelar",
                        "fromLabel": "Desde",
                        "toLabel": "Hasta",
                        "customRangeLabel": "Personalizar",
                        "daysOfWeek": [
                        "Do",
                        "Lu",
                        "Ma",
                        "Mi",
                        "Ju",
                        "Vi",
                        "Sa"
                        ],
                        "monthNames": [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Setiembre",
                        "Octubre",
                        "Noviembre",
                        "Diciembre"
                        ],
                        "firstDay": 1
                        },

                        "opens": "center"
                        });


                    @endsection

                    <script>

                        function mostrar(){
                            $("#hiddendiv").show();
                        };

                    </script>