@extends('home')

            @section('homecontent')


                <div class="container" >

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-md-11">
                                <div class="visible-xs"><br><br></div>
                            <h3 class="page-header">
                                {{'Reporte de Historial x OP'}}
                                <small>Producción</small>
                            </h3>
                                <h3>OP: {{$op}}  Status: {{$status}}</h3>                                                      
                                <h4>Descripción: {{$info->ItemCode.' '.$info->ItemName}}</h4>
                                <h4>Cliente: {{$info->CardCode.' '.$info->CardName}}</h4>
                        <!-- <h5>Fecha & hora: {{date('d-m-Y h:i a', strtotime("now"))}}</h5> -->                          
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">   
                                    <p align="right">                                                                                                     
                            <a href="../ReporteOpPDF/{{$op}}" target="_blank" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Reporte PDF</a> 
                            <a class="btn btn-success" href="historialXLS"><i class="fa fa-file-excel-o"></i> Reporte XLS</a>                                    
                    </p>
                        </div>                         
                    </div>   
                 <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                        <table  border="1px"class="table table-striped">
                    <thead class="table-bordered table-condensed" >
                        <tr>                      
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Fecha</th>
                        <th align="center" bgcolor="#474747" style="color:white"; scope="col">Estación</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Empleado</th>
                        <th align="center" bgcolor="#474747" style="color:white"; scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data)>0)
                    @foreach ($data as $rep)
                        <tr>                            
                            <td scope="row">
                                <?php echo date('d-m-Y', strtotime($rep->FechaF));  ?> 
                            </td>
                            <td scope="row">
                                {{$rep->NAME}}
                            </td>
                            <td scope="row">
                                {{ $rep->Empleado }}
                            </td>
                            <td align="center"scope="row">
                                {{ $rep->U_CANTIDAD }}
                            </td>
                        </tr>    
                    @endforeach ´
                    @endif
                    </tbody>
                </table>
                        </div>
                    </div>

                    </div>
                    <!-- /.container -->

                    @endsection

                    @section('homescript')
                   

                    @endsection

                    <script>

                        function mostrar(){
                            $("#hiddendiv").show();
                        };

                    </script>