@extends('home')

            @section('homecontent')


                <div class="container" >

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-md-11">
                            <h3 class="page-header">
                                {{$nombre }}
                                <small>Zarkin</small>
                            </h3>      
                           
                        </div>
                        
                        </div>
                       <style>
     div.dataTables_wrapper div.dataTables_processing {
        width: 500px;
        height: 250px;
        margin-left: 0%;
        background: linear-gradient(to right, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.95) 25%, rgba(255,255,255,0.95) 75%, rgba(255,255,255,0.2) 100%);
        z-index: 15;
    }
    input{
        color: black;
    }
    div.dataTables_wrapper {
        margin: 0 ;
    }
    div.container {
        min-width: 100%;
        margin: 0 auto;
    }
     table { //me ayudo a que no se desfazaran las columnas en Chrome
        table-layout: fixed;
    }
</style>

                        <!-- Modal -->

                        <div class="modal fade" id="pass" role="dialog" >
                            <div class="modal-dialog {{$sizeModal}}" role="document">
                                <div class="modal-content" >
                                    <div class="modal-header">

                                        <h4 class="modal-title" id="pwModalLabel">{{$nombre}}</h4>
                                    </div>
                                    {!! Form::open(['url' => 'home/reporte/'.$nombre, 'method' => 'POST']) !!}

                                    <div class="modal-body">
                                        @if($text <> '')
                                            <h5>{{$text}}</h5>
                                            <input hidden value="{{$text}}" id="text" name="text" />
                                        @endif
                                        <input type="text" hidden name="send" value="send">
                                        <div class="form-group">
                                            @include('partials.alertas-modal')
                                        </div>
                                        @if($fechas == true)
                                        <div class="form-group">                                        
                                            <label for="date_range" class="control-label">Rango de Fechas:</label><br>
                                            Desde:<input type="date" id="FechIn" name="FechIn"   value="{{ old('FechIn') }}" class="form-control" autofocus required>
                                            Hasta:<input type="date" id="FechaFa" name="FechaFa" value="{{ old('FechaFa') }}" class="form-control" required>
                                        </div>
                                        @endif
                                        @if($unafecha == true)
                                        <div class="form-group">
                                            <label for="date_range" class="control-label">Fecha:</label><br>
                                            <input type="date" id="FechIn" name="FechIn" value="{{ old('Fecha') }}" class="form-control" autofocus
                                                required>
                                           
                                        </div>
                                        @endif
                                        @if($fieldOtroNumber <> '')                                    
                                        <div class="form-group">
                                        Escribe {{$fieldOtroNumber}}:<input type="number" id="fieldOtroNumber" name="fieldOtroNumber" value="{{ old('fieldOtroNumber') }}" class="form-control" autofocus required>
                                        </div>
                                        @endif
                                        @if($fieldText <> '')                                    
                                        <div class="form-group">
                                        Escribe {{$fieldText}}:<input type="text" id="fieldText" name="fieldText" value="{{ old('fieldText') }}" class="form-control" autofocus required>
                                        </div>
                                        @endif
                                        @if($text_selUno <> '')                                    
                                        <div class="form-group">
                                            <label for="text_selUno">{{$text_selUno}}:</label>
                                            <select class="form-control" id="text_selUno" name="text_selUno" autofocus required>
                                                  @foreach ($data_selUno as $item)
                                                     <option value="{{$item}}">{{$item}}</option> 
                                                  @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        @if($text_selDos <> '')
                                            <div class="form-group">
                                                <label for="text_selDos">{{$text_selDos}}:</label>
                                                <select class="form-control" id="text_selDos" name="text_selDos" autofocus required>
                                                    @foreach ($data_selDos as $item)
                                                        <option value="{{$item}}">{{$item}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if($text_selTres <> '')
                                            <div class="form-group">
                                                <label for="text_selTres">{{$text_selTres}}:</label>
                                                <select class="form-control" id="text_selTres" name="text_selTres" autofocus required>
                                                    @foreach ($data_selTres as $item)
                                                        <option value="{{$item->llave}}">{{$item->valor}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if($text_selCinco <> '')
                                            <div class="form-group">
                                                <label for="text_selCinco">{{$text_selCinco}}:</label>
                                                <select class="form-control" id="data_selCinco" name="data_selCinco" autofocus required>
                                                    @foreach ($data_selCinco as $k => $v)
                                                        <option value="{{$k}}">{{$v}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if($text_selCuatro <> '')
                                            <div class="form-group">
                                                <label for="text_selCuatro">{{$text_selCuatro}}:</label>
                                                
                                                <select 
                                                data-live-search="true" 
                                                class="boot-select form-control" 
                                                title="No has seleccionado nada" 
                                                data-size="5"
                                                data-dropup-auto="false" 
                                                multiple data-actions-box="true" 
                                                data-select-all-text="Marcar Todos"
                                                data-deselect-all-text="Desmarcar Todos"
                                                data-selected-text-format="count"
                                                data-count-selected-text="{0} Seleccionados"   
                                                data-live-search-placeholder="Busqueda" 
                                                id="data_selCuatro" multiple="multiple" 
                                                name="data_selCuatro[]" 
                                                autofocus required>

                                                    @foreach ($data_selCuatro as $item)
                                                        <option value="{{$item->llave}}" checked>{{$item->valor}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if($data_table <> '')
                                           
                                              <div class="col-md-12">
                                                <table id="tabla" class="stripe cell-border display">
                                                    <thead class="table-condensed">
                                                        <tr>
                                            
                                                        </tr>
                                                    </thead>
                                            
                                                </table>
                                                <br>
                                            </div> <!-- /.col-md-12 -->
                                        
                                        @endif                                    
                                    </div>
                                   <div class="form-group">
                                    <input hidden id="pKey" name="pKey" />
                                </div>
                                    

                                    <div class="modal-footer">
                                        <div id="hiddendiv" class="progress" style="display: none">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                <span>Espere un momento...<span class="dotdotdot"></span></span>
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <input id="submit" name="submit" type="submit" value="{{$btnSubmitText}}" onclick="mostrar();"  class="btn btn-primary"/>
                                        @if ($btn3 !== '')
                                            <?php $path = '';  ?>
                                            <a type="button" class="btn btn-default" href="{{url()}}/{{$btn3['route']}}">{{$btn3['btnName']}}</a>    
                                        @else
                                            <a type="button" class="btn btn-default" href="{!!url('home')!!}">Cancelar</a>
                                        @endif
                                        
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div><!-- /modal -->                      

                    </div>
                    <!-- /.container -->

                    @endsection
                  @if($data_table <> '')  
                 
                    @section('homescript')
                        $('#pass').modal(
                        {
                            show: true,
                            backdrop: 'static',
                            keyboard: false
                        }
                        );
                        
                                                
                        var data,
                        tableName= '#tabla',
                      
                        str,
                        jqxhr =  $.ajax({
                                dataType:'json',
                                type: 'GET',
                                data:  {                                   
                                                    
                                    },
                                url: '{!! route($data_table) !!}',
                                success: function(data, textStatus, jqXHR) {
                                    data = JSON.parse(jqxhr.responseText);
                                    // Iterate each column and print table headers for Datatables
                                    $.each(data.columns, function (k, colObj) {
                                        str = '<th>' + colObj.name + '</th>';
                                        $(str).appendTo(tableName+'>thead>tr');
                                    // console.log("adding col "+ colObj.name);
                                    });
                                
                                        var table = $(tableName).DataTable({               
                                        dom: 'irtp',
                                        orderCellsTop: true,    
                                        scrollY:        "250px",
                                        "pageLength": 50,
                                        scrollX:        true,
                                        paging:         true,                                       
                                        processing: true,
                                        deferRender:    true,
                                        scrollCollapse: true,   
                                        data:data.data,
                                        columns: data.columns,            
                                        "language": {
                                            "url": "{{ asset('assets/lang/Spanish.json') }}",                    
                                        },                          
                                    });

                                    $('#tabla thead tr:eq(1) th').each( function (i) {
                                    var title = $(this).text();
                                    $(this).html( '<input type="text" placeholder="Filtro '+title+'" />' );
                                    
                                    $( 'input', this ).on( 'keyup change', function () {
                                    
                                    if ( table.column(i).search() !== this.value ) {
                                    table
                                    .column(i)
                                    .search(this.value, true, false)
                                    .draw();
                                    
                                    }
                                    
                                    } );
                                    } );

                                  $('#tabla tbody').on( 'click', 'tr', function () {
                                if ( $(this).hasClass('selected') ) {
                                    $(this).removeClass('selected');
                                    $('input[name=pKey]').val('');
                                }
                                else {
                                    table.$('tr.selected').removeClass('selected');
                                    $(this).addClass('selected');
                                    var idx = table.cell('.selected', 0).index();
                                    var fila = table.rows( idx.row ).data();  
                                    console.log(fila[0][data.pkey]);
                                    $('input[name=pKey]').val(fila[0][data.pkey]);                                                                                                   
                                }
                                   } );
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    var msg = '';
                                    if (jqXHR.status === 0) {
                                        msg = 'Not connect.\n Verify Network.';
                                    } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                    }
                                    console.log(msg);
                                }
                                });
                                            
                        $('#tabla thead tr').clone(true).appendTo( '#tabla thead' );                      
                   
                    @endsection
                    @else
                    @section('homescript')
                    $('#pass').modal(
                    {
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                    }
                    );                                                        
                    @endsection
                    @endif
                    <script>

                        function mostrar(){
                            $("#hiddendiv").show();
                        }; 
                     

                    </script>