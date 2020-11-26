@extends('home')
@section('homecontent')
<div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div class="col-md-12">
                <div class="hidden-lg"><br><br></div>
                    <h3 class="page-header">
                        Traslados
                        <small>Producción</small>
                    </h3>
                    <div class="visible-lg">
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard">  <a href="{!! url('home') !!}">Inicio</a></i>
                        </li>
                        <li>
                            <i class= "fa fa-archive"> <a href="{!! url('home/TRASLADO ÷ AREAS') !!}">Traslados</a></i>
                    </ol>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12 ">
                    @include('partials.alertas')
                    
                    <div id="login" data-field-id="{{Session::get('usertraslados') }}" >
                    </div>
                    
                    @if(Session::has('usertraslados') && Session::get('usertraslados')>0)  
                    @endif                
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                <h3 class="panel-title">Estación {{$estacion}}</h3>
                                </div>
                                <div class="panel-body">
                                    <h5>Usuario para el traslado: {{$t_user->firstName.' '.$t_user->lastName}} &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#cambuser">Cambiar Usuario</a> </h5>
                                    <h5>Control de Piso número: {{$t_user->U_EmpGiro}}</h5>   
                                   
                                       
                                
                                </div>
                                <div style="overflow-x:auto" class="col-md-12">                                
                                <br>
                                </div>
                           
                           
                     </div>
                        </div>
                        </div>      
                            <div style="overflow-x:auto" class="col-md-12">
                         <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                            <?php

                                            switch ($t_user->position) {
                                                case '4'; //Supervisor SAP
                                                    break;
                                                case '3'; //Operador SAP
                                                    ?>
                                               <th>Avanzar OP</th>
                                            <?php
                                            break;
                                                case '1'; //Gerencia SAP
                                                    ?>
                                            <th>Avanzar OP</th>
                                            <?php
                                            break;
                                            }
                                            ?>   

                                        <th>Orden de Producción</th>
                                        <th>Descripción</th>
                                        <th>Reproceso</th>
                                        <th>Cantidad</th>
                                        <th>Cantidad Recibida</th>
                                        <th>Procesado</th>
                                                         
      </tr>
         </thead>
                                    <tbody>
                              @foreach ($ordenes as $orden)
                                    <tr>
                                    {{-- <td align="center"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#mymodal" data-whatever="{{$o->empID}}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                    </td>--}}
                                    {{-- <td align="center">  <a class="btn btn-default" href="{{url('users/edit/'.$o->empID)}}" role="button">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                        </td>--}}
                                        <?php
                                        switch ($t_user->position) {
                                            case '4'; //Supervisor SAP
                                                break;
                                            case '3'; //Operador SAP
                                                ?>
                                        <!--Boton Avanzar-->
                                        <td> <a class="btn btn-success" data-toggle="modal" data-target="#cantidad" data-whatever="{{$orden->Code}}" data-whatever2="{{$orden->U_Recibido - $orden->U_Procesado}}"><i class="fa fa-send-o" aria-hidden="true">   Avanzar</i>
                                            </a> 
                                        </td>
                                        <?php
                                        break;
                                            case '1'; //Gerencia SAP 1 pruebas
                                                ?>
                                         <!--Boton Avanzar-->
                                        <td> <a class="btn btn-success" data-toggle="modal" data-target="#cantidad" data-whatever="{{$orden->Code}}" data-whatever2="{{$orden->U_Recibido - $orden->U_Procesado}}"><i class="fa fa-send-o" aria-hidden="true">   Avanzar</i>
                                            </a> 
                                        </td>
                                        <?php
                                        break;
                                        }
                                        ?>
                                        <td> {{$orden->DocEntry}} </td>
                                        <td> {{$orden->ItemName}} </td>
                                        <td> {{$orden->U_Reproceso}} </td>
                                        <td> {{number_format($orden->PlannedQty,0)}} </td>
                                        <td> {{$orden->U_Recibido}} </td>
                                        <td> {{$orden->U_Procesado}} </td>
 
    </tr>
                                @endforeach
                        
         </tbody>
                                </table>   
                            
                </div>  <!-- end col-md-12 -->
            </div>  <!-- end row -->
            
            <!-- Modal -->
            <div class="modal fade" id="cambuser" role="dialog" >
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style=" background-color: rgb(189, 217, 254)">
                        <div class="modal-header" style="background-color: rgb(198,221,254)">
                            <h4 class="modal-title" id="pwModalLabel">Cambiar Usuario</h4>
                        </div>
                        {!! Form::open(['url' => 'home/TRASLADO ÷ AREAS', 'method' => 'POST']) !!}
                        <div class="modal-body image">
                            <input type="text" hidden name="send" value="send">
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
                                    <img src= "{{ URL::asset('images/Mod01_Produccion/password.png')}}" alt="">
                                </div>
                                <div class="col-md-6 col-md-offset-1 col-xs-5">
                                    @include('partials.alertas')
                                    <div id="hiddendiv">
                                        <label for="miusuario" class="control-label">Usuario:</label>
                                        <input autofocus id="miusuario" type="number" class="form-control" name="miusuario" required minlength="1">
                                        <label for="pass" class="control-label">Contraseña:</label>
                                    <input id="pass" type="password" class="form-control" name="pass" required minlength="1" value="{{Session::get('Rec_pass')}}">
                                    <br>
                                    </div>
                                    <input type="checkbox" name="Recordarpass">Recordar contraseña<br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary">Entrar</button>
                            <a type="button" class="btn btn-default"  href="" data-toggle="modal" data-target="#cambuser">Cancelar</a>
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

                        <div class="modal-header">

                            <h4 class="modal-title" id="pwModalLabel">Cantidad a Procesar</h4>
                        </div>

            <div class="modal-body">

               <div class="row">
                   <div class="col-md-6">
                       <input id="code" name="code" type="text" hidden>
                       <input id="option" name="option" type="text" value= "2" hidden>
                       <input id="OP_us" name="OP_us" type="text" value= "{{$numeroestacion}}" hidden>
                       @if(Session::has('usertraslados') && Session::get('usertraslados')>0)   @endif
                          <input id="userId" name="userId" type="text" hidden value="{{$t_user->U_EmpGiro}}">
                      
                       <input id="numcant" name="numcant" type="text" hidden>
                       <label for="cant" class="control-label">Cantidad:</label>
                       <input id="cant" type="number" class="form-control" name="cant" minlength="3" maxlength="5" min="1" autofocus>

                   </div>
                   <div class="col-md-6">

                       <div class="row">
                           <a class="btn" onclick="cambiarvalor(7)">7</a>
                           <a class="btn" onclick="cambiarvalor(8)">8</a>
                           <a class="btn" onclick="cambiarvalor(9)">9</a>
                       </div>
                       <div class="row">
                           <a class="btn" onclick="cambiarvalor(4)">4</a>
                           <a class="btn" onclick="cambiarvalor(5)">5</a>
                           <a class="btn" onclick="cambiarvalor(6)">6</a>
                       </div>
                       <div class="row">
                           <a class="btn" onclick="cambiarvalor(1)">1</a>
                           <a class="btn" onclick="cambiarvalor(2)">2</a>
                           <a class="btn" onclick="cambiarvalor(3)">3</a>
                       </div>
                       <div class="row">

                       </div>
                   </div>
               </div>

            </div>
                        <div class="modal-footer">

                            <a class="btn btn-default" onclick="limpiacant()"><i class="fa fa-eraser" aria-hidden="true"> </i></a>
                            <button type="submit" class="btn btn-primary">Procesar</button>
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

    // Execute something when the modal window is shown.


  $('#Retroceder').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    // Button that triggered the modal

    var codejs = button.data('codem');
    var urecibido = button.data('recibido');
    var modal = $(this)
    modal.find('#retrocant').val(urecibido);
    modal.find('#retrocant').attr('max', urecibido);
    $("#Estacion").val(codejs);


    // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var arreglo = new Array();
    $('#selectestaciones').empty();
    <?php

if (isset($Ruta)) {
    for ($i = 0; $i < count($Ruta); $i++) {
        ?>
    if( '<?php echo $Ruta[$i][0]; ?>' < codejs){
         $('#selectestaciones').append($('<option></option>').val(<?php echo $Ruta[$i][0]; ?>).html("<?php echo $Ruta[$i][1]; ?>"));
    }
    <?php
}
}
?>

    var modal = $(this);
    modal.find('#code').text(codejs);

  });

@endsection

<script>

    function limpiacant() { numcant
        $("#cant").val($("#numcant").val());
    }

    function cambiarvalor(num) {
        $("#cant").val($("#cant").val() + num);
    }

    function ocultar(){
        $("#hiddendiv").hide();
        $("#ocultar").hide();
        $("#mostrar").show();
        $("#miusuario").removeAttr('required');
    };
function mostrar(){
        $("#hiddendiv").show();
        $("#mostrar").hide();
        $("#ocultar").show();
        $('#miusuario').attr('required', 'required');
    };
</script>