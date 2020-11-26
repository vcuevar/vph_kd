@extends('home')

            @section('homecontent')


                <div class="container" >

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-md-11">
                            <h3 class="page-header">
                                {{'Reporte de Producción'}}
                                <small>Producción</small>
                            </h3>
                            @if(Session::has('Ocultamodal') && Session::get('Ocultamodal')>0)                                
                                <h3> {{$departamento}}</h3>
                                <h5>Fecha: del {{$fechaI}} al {{$fechaF}}</h5>                      
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <a class="btn btn-danger btn-sm" href="ReporteProduccionPDF" target="_blank"><i class="fa fa-file-pdf-o"></i> Reporte PDF</a>                                    
                            <a class="btn btn-success btn-sm" href="ReporteProduccionEXL"><i class="fa fa-file-excel-o"></i> Reporte XLS</a>                                    
                    </div>                         
                    </div>   @endif
                 <!-- /.row -->
                    <div class="row">
                
                            <div id="login" data-field-id="{{Session::get('Ocultamodal') }}" >
                            </div>

                            @if(Session::has('Ocultamodal') && Session::get('Ocultamodal')>0)
                               
                            <div class="row">
                                    <div style="overflow-x:auto;" class="col-md-11">
                                        @foreach ($ofs as $clave => $valor)
                                  

                                            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                                <thead>
                                                <h5>{{$clave}}</h5>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Orden</th>
                                                    <th>Pedido</th>
                                                    <th>Código</th>
                                                    <th>Modelo</th>
                                                    <th>VS</th>
                                                    <th>Cantidad</th>
                                                    <th>Total VS</th>
                                                </tr>
                                                </thead>
                                                @foreach ($valor as $val)
                                                    <tr>
                                                        <?php $tvs = $tvs + $val['TVS'];
$cant = $cant + $val['Cantidad'];
?>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;" > {{substr($val['fecha'],0,10)}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$val['orden']}} </td>
                                                        <td style="font-size: 10px; width: 6% padding-bottom: 0px; padding-top: 0px;"> {{$val['Pedido']}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$val['Codigo']}} </td>
                                                        <td style="font-size: 10px; width: 51% padding-bottom: 0px; padding-top: 0px;"> {{$val['modelo']}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$val['VS']}} </td>
                                                        <td style="font-size: 10px; width: 5% padding-bottom: 0px; padding-top: 0px;"> {{$val['Cantidad']}} </td>
                                                        <td style="font-size: 10px; width: 8% padding-bottom: 0px; padding-top: 0px;"> {{$val['TVS']}} </td>
                                                    </tr>
                                                @endforeach
                                            </table>

                                            @endforeach
                                        </div></div>

                                    <div class="row">
                                        <div style="overflow-x:auto" class="col-md-5 col-md-offset-6">
                                            <table id="totales" class="table table-striped table-bordered table-condensed">
                                                <thead >
                                                <h5>Totales</h5>
                                                <tr>
                                                    <th>Total Cantidad</th>
                                                    <th>Total VS</th>
                                                </tr>
                                                </thead>

                                                <tr>
                                                    <td style="font-size: 10px"> {{$cant}} </td>
                                                    <td style="font-size: 10px"> {{$tvs}} </td>
                                                </tr>

                                            </table></div></div>
                                    @endif
                        </div>


                        <!-- Modal -->

                        <div class="modal fade" id="pass" role="dialog" >
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content" >
                                    <div class="modal-header">

                                        <h4 class="modal-title" id="pwModalLabel">Reporte de producción</h4>
                                    </div>
                                    {!! Form::open(['url' => 'home/REPORTE PRODUCCION', 'method' => 'POST']) !!}

                                    <div class="modal-body">
                                        <input type="text" hidden name="send" value="send">
                                        <div class="form-group">
                                            @include('partials.alertas')
                                        </div>
                                        <div class="form-group">
                                            <label for="date_range" class="control-label">Rango de Fechas:</label><br>
                                            Desde:<input type="date" id="FechIn" name="FechIn" class="form-control" >
                                            Hasta:<input type="date" id="FechaFa" name="FechaFa" class="form-control" >
                                        </div>
                                        <div class="form-group">
                                            <label for="dep" class="control-label">Departamento:</label>
                                            <select class="form-control" id="dep" name="dep" required>
                                                <option value="100 Ordenes en Planeación">100 Ordenes en Planeación</option>
                                                <option value="103 Activar Orden es Area">103 Activar Orden es Area</option>
                                                <option value="106 Preparado Entrega de Piel">106 Preparado Entrega de Piel</option>
                                                <option value="109 Anaquel de Corte">109 Anaquel de Corte</option>
                                                <option value="112 Corte de Piel">112 Corte de Piel</option>
                                                <option value="115 Inspección de Corte">115 Inspección de Corte</option>
                                                <option value="118 Pegado para Costura">118 Pegado para Costura</option>
                                                <option value="121 Anaquel Costura">121 Anaquel Costura</option>
                                                <option value="124 Costura Recta">124 Costura Recta</option>
                                                <option value="127 Armado de Costura">127 Armado de Costura</option>
                                                <option value="130 Pespunte o Doble">130 Pespunte o Doble</option>
                                                <option value="133 Terminado de Costura">133 Terminado de Costura</option>
                                                <option value="136 Inspeccionar Costura">136 Inspeccionar Costura</option>
                                                <option value="139 Series Incompletas Costura">139 Series Incompletas Costura</option>
                                                <option value="140 Pegado de Delcrón">140 Pegado de Delcrón</option>
                                                <option value="142 Llenado del Cojin">142 Llenado del Cojin</option>
                                                <option value="145 Acojinado">145 Acojinado</option>
                                                <option value="148 Fundas Terminadas">148 Fundas Terminadas</option>
                                                <option value="151 Kitting">151 Kitting</option>
                                                <option value="154 Enfundado Tapiz">154 Enfundado Tapiz</option>
                                                <option value="157 Tapizar">157 Tapizar</option>
                                                <option value="160 Armado de Tapiz">160 Armado de Tapiz</option>
                                                <option value="163 Complementos">163 Complementos</option>
                                                <option value="166 Limpieza">166 Limpieza</option>
                                                <option value="169 Inspeccion Tapiceria">169 Inspeccion Tapiceria</option>
                                                <option value="172 Empaque">172 Empaque</option>
                                                <option value="175 Inspeccion Final">175 Inspeccion Final</option>
                                                <option value="400 Ordenes en Planeación">400 Ordenes en Planeación</option>
                                                <option value="403 Recepcion Habilitado">403 Recepcion Habilitado</option>
                                                <option value="406 Armado">406 Armado</option>                                               
                                                <option value="409 Tapado de Casco">409 Tapado de Casco</option>                                               
                                                <option value="415 Pegado Hule al Casco">415 Pegado Hule al Casco</option>
                                                <option value="418 Entrega Casco">418 Entrega Casco</option>
                                            </select>
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
                                {!! Form::open(['url' => 'home/traslados/avanzar/', 'method' => 'POST']) !!}
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