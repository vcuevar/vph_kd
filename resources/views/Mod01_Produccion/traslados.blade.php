@extends('home')

@section('homecontent')

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div class="col-md-12">
                <div class="hidden-lg"><br><br></div>
                    <h3 class="page-header">
                        Traslados
                        <small>Producción  <i data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-question-sign"  title="Ayuda Shift+F1"></i></small> 
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



                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Usuario Traslado</h3>
                                </div>
                                <div class="panel-body">
                                    <h5>Usuario: {{$t_user->firstName.' '.$t_user->lastName}} &nbsp;&nbsp;<a href="" data-toggle="modal" data-target="#cambuser">Cambiar Usuario</a> </h5>
                                    <h5>Control de Piso: {{$t_user->U_EmpGiro}}</h5>

                                    @if(Session::get('usertraslados') == 1)
                                        {!! Form::open(['url' => 'home/TRASLADO ÷ AREAS/'.$t_user->U_EmpGiro, 'method' => 'POST']) !!}
                                        <fieldset>
                                        <div class="col-md-6">                                           

                                            <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="AvanceEst" id="op" value="1" checked>
                                                    <label class="form-check-label" for="op">
                                                            Orden de Producción:
                                                    </label>
                                            </div> 
                                            <input class="form-control" autofocus type="number" name="op" id="op_input" min="1" max="9999999999">                                                                                                                                         
                                            <input id="pass" hidden type="password" name="pass" value="0123">
                                            <input id="pass2" hidden type="password" name="pass2" value="1234">
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="AvanceEst" id="OP_us" value="2">
                                                        <label class="form-check-label" for="OP_us">
                                                                Avanzar por Estación
                                                        </label>
                                                       

                                                      </div>
                                                                                        
                                            <select  class="form-control" name="OP_us" id="OP_input">
                                            @foreach($rutasConNombres as $key => $value)
                                                <option  class="col-md-6"value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        </fieldset>
                                     @else
                                       {!! Form::open(['url' => 'home/TRASLADO ÷ AREAS/', 'method' => 'POST']) !!}
                                       <input type="text" hidden name="send" value="send">
                                <input id="miusuario" hidden type="number"  name="miusuario" value="{{$t_user->U_EmpGiro}}">
                                        <input id="pass" hidden type="password" name="pass" value="0123">
                                        <input id="pass2" hidden type="password" name="pass2" value="1234">
                                       <input type="checkbox" hidden name="Recordarpass" value="0">
                                       <div align="right" >
                                    <button type="sumbmit"class="btn btn-success">Mover otra OP</button>
                                    </div>
                                    {!! Form::close() !!}
                                     @endif
                                </div>
                                @if(Session::get('usertraslados') == 1)
                                <div class="panel-footer">
                                    <div align="right">
                                    <button type="submit" class="btn btn-primary" >Consultar</button>
                                    </div>

                                </div>
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        </div>
                            @if(Session::get('usertraslados') == 2 && !Session::has('recibo'))
                              <div class="col-md-5">
                                  <div class="panel panel-default">
                                      <div class="panel-heading">
                                          <h3 class="panel-title">Orden de Producción</h3>
                                      </div>
                                        <div class="panel-body">
                                        <div class="row">
                                        <div class="col-md-6">
                                         <h5> O.P.: {{$op}} <br><br>
                                         <h5>Pedido: {{$pedido}}
                                        </div>
                                        <div class="col-md-6">
                                         <a href="../ReporteOpPDF/{{$op}}" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> Historial</a> </h4> <br><br>
                                        <a href="../ReporteMaterialesPDF/{{$op}}" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i> Materiales</Center></a></h4>
                                        </div>
                                       </div>
                                        </div>
                                        </div>
                                  </div>

{!! Html::style('assets/css/tablas.css') !!}

<style>
@media only screen
  and (min-device-width : 320px)
  and (max-device-width : 480px) {
    td:nth-of-type(1):before { content: "Código"; }
    td:nth-of-type(2):before { content: "Descripción"; }
    td:nth-of-type(3):before { content: "Reproceso"; }
    td:nth-of-type(4):before { content: "Cantidad"; }
    td:nth-of-type(5):before { content: "Cantidad Recibida"; }
    td:nth-of-type(6):before { content: "Procesado"; }
    td:nth-of-type(7):before { content: "Estación Actual"; }
    td:nth-of-type(8):before { content: "Estación Siguiente"; }
    td:nth-of-type(9):before { content: "Retroceder OP"; }
    td:nth-of-type(10):before { content: "Avanzar OP"; }
    }
</style>



                            <div style="overflow-x:auto" class="col-md-12">

                         <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>

                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Reproceso</th>
                                        <th>Cantidad</th>
                                        <th>Cantidad Recibida</th>
                                        <th>Procesado</th>
                                        <th>Estación Actual</th>
                                        <th>Estación Siguiente</th>
<?php

switch ($t_user->position) {
    case '6'; //Planeador
    ?>
    <th>Retroceder OP</th>
    <th>Avanzar OP</th>
    <?php
    break;
    case '5'; //Almacén de piel
    ?>
    <th>Retroceder OP</th>
    <th>Avanzar OP</th>
    <?php
    break;
    case '4'; //Supervisor SAP
        break;
    case '3'; //Operador SAP
        ?>
   <th>Avanzar OP</th>
<?php
break;
    case '1'; //Gerencia SAP
        ?>
<th>Retroceder OP</th><th>Avanzar OP</th>
<?php
break;
}
?>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ofs as $of)
                                        <tr>
                                            {{-- <td align="center">  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#mymodal" data-whatever="{{$o->empID}}">
                                                     <i class="fa fa-edit" aria-hidden="true"></i>
                                                 </button>
                                             </td>--}}

                                           {{-- <td align="center">  <a class="btn btn-default" href="{{url('users/edit/'.$o->empID)}}" role="button">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            </td>--}}
                                            <td> {{$of->Code}} </td>
                                            <td> {{$of->ItemName}} </td>
                                            <td> {{$of->U_Reproceso}} </td>
                                            <td> {{number_format($of->PlannedQty,0)}} </td>
                                            <td> {{$of->U_Recibido}} </td>
                                            <td> {{$of->U_Procesado}} </td>
                                            <td> {{$of->U_CT_ACT}} </td>
                                            <td> {{$of->U_CT_SIG}} </td>

<?php
$aux = 'disabled';
switch ($t_user->position) {
    case '6'; //Planeador
    ?> <!--Boton Retroceder--> 
       @if($of->U_Orden == '106') 
       <?php
        $aux = '';
        ?>
       @endif 
       <td> <a class="btn btn-danger {{$aux}}" data-toggle="modal"
data-target="#Retroceder" class="btn btn-info btn-lg" data-codem="{{$of->U_Orden}}" data-recibido="{{$of->U_Recibido - $of->U_Procesado}}">
       <i class="fa fa-mail-reply-all" aria-hidden="1">   Retroceder</i>
       </a> </td>
<!--Boton Avanzar-->
        <td> <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
        data-target="#cantidad" data-whatever="{{$of->Code}}"
        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
        <i class="fa fa-send-o" aria-hidden="true"> 
          Avanzar
          </i>
        </a> </td>
    <?php   
    break;        
    case '5'; //Almacén de 
       $desabilitarretroceso = '';
    ?> <!--Boton Retroceder--> 
       @if($of->U_Orden !== '109') 
       <?php   
        $desabilitarretroceso = 'disabled';
       ?>
       @endif
       <td> <a class="btn btn-danger {{ $desabilitarretroceso}}" data-toggle="modal"
       data-target="#Retroceder" class="btn btn-info btn-lg" data-codem="{{$of->U_Orden}}" data-recibido="{{$of->U_Recibido - $of->U_Procesado}}">
       <i class="fa fa-mail-reply-all" aria-hidden="1">  Retroceder</i>
       </a> </td>
      
<!--Boton Avanzar-->
        <td> <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
        data-target="#cantidad" data-whatever="{{$of->Code}}"
        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
        <i class="fa fa-send-o" aria-hidden="true">   Avanzar</i>
        </a> </td>
    <?php   
    break;                
    case '4'; //Supervisor SAP
        break;
    case '3'; //Operador SAP
        ?>
                  <!--Boton Avanzar-->
            <td>
                @if($of->U_CT_SIG !== "Terminar OP")
                    @if($of->U_CT_SIG == "Error en ruta")
                        <a class="btn btn-danger disabled" >
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Avanzar
                        </i>
                        </a> 
                    @else
                    <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
                        data-target="#cantidad" data-whatever="{{$of->Code}}"
                        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Avanzar
                        </i>
                    </a> 
                    @endif
                @else
                        <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
                        data-target="#terminar" data-whatever="{{$of->Code}}"
                        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Terminar 
                        </i>
                    </a> 
                @endif
            </td>
<?php
break;
    case '1'; //Gerencia SAP 1 pruebas
        ?>

                                                <!--Boton Retroceder-->
                                                <td> <a class="btn btn-danger" data-toggle="modal"
                                            data-target="#Retroceder" class="btn btn-info btn-lg" data-codem="{{$of->U_Orden}}" data-recibido="{{$of->U_Recibido - $of->U_Procesado}}">
                                                    <i class="fa fa-mail-reply-all" aria-hidden="1">   Retroceder</i>
                                                </a> </td>

                                                    <!--Boton Avanzar-->
                             <td>               
                @if($of->U_CT_SIG !== "Terminar OP")
                    @if($of->U_CT_SIG == "Error en ruta")
                        <a class="btn btn-danger disabled" >
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Avanzar
                        </i>
                        </a> 
                    @else
                    <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
                        data-target="#cantidad" data-whatever="{{$of->Code}}"
                        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Avanzar
                        </i>
                    </a> 
                    @endif
                @else
                        <a class="btn btn-success {{$of->avanzar}}" data-toggle="modal"
                        data-target="#terminar" data-whatever="{{$of->Code}}"
                        data-whatever2="{{$of->U_Recibido - $of->U_Procesado}}">
                        <i class="fa fa-send-o" aria-hidden="true">   
                        Terminar 
                        </i>
                    </a> 
                @endif
            </td>
<?php
break;
}
?>


                                        </tr>
                                    @endforeach
</tbody>
                                </table>

                            </div>
                            @else
                            <?php Session::pull('recibo') ?>  
                            @endif
                    @endif
                </div>  <!-- end col-md-12 -->
            </div>  <!-- end row -->
            <!-- Modal -->
            <div class="modal fade" id="pass" role="dialog" >
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style=" background-color: rgb(189, 217, 254)">
                        <div class="modal-header" style="background-color: rgb(198,221,254)">

                            <h4 class="modal-title" id="pwModalLabel">Login</h4>
                        </div>
                        {!! Form::open(['url' => 'home/TRASLADO ÷ AREAS', 'method' => 'POST']) !!}
                        <div class="modal-body image">

                            <input type="text" hidden name="send" value="send">
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">

                                    <img src= "{{ URL::asset('images/Mod01_Produccion/password.png')}}"
                                         alt="">
                                </div>
                                <div class="col-md-6 col-md-offset-1 col-xs-5">
                                    @include('partials.alertas')
                                    <div id="hiddendiv" style="display: none">
                                        <label for="miusuario" class="control-label">Usuario:</label>
                                        <input autofocus id="miusuario" type="number" class="form-control" name="miusuario" minlength="1">
                                        <br>
                                    </div>
                                    @if(Session::has('usertraslados'))
                                    <label for="pass" class="control-label">Contraseña:</label>
                                    <input autofocus id="pass" type="password" class="form-control" name="pass" required minlength="1" value="{{Session::get('Rec_pass')}}">
                                    @endif
                                    <input type="checkbox" name="Recordarpass">Recordar contraseña<br>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                        <a  href="" data-toggle="modal" data-target="#cambuser"id="mostrar" onclick="mostrar()">Cambiar Usuario</a>
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary">Entrar</button>
                            <a type="button" class="btn btn-default"  href="{!!url('home')!!}">Cancelar</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!-- /modal -->
 <!-- Modal2 -->
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
                                    <img src= "{{ URL::asset('images/Mod01_Produccion/password.png')}}"alt="">
                                </div>
                                <div class="col-md-6 col-md-offset-1 col-xs-5">
                                    @include('partials.alertas')
                                    <div id="hiddendiv">
                                    <label for="miusuario" class="control-label">Usuario:</label>
                                    <input autofocus id="miusuario" type="number" class="form-control" name="miusuario" required minlength="1">
                                    <br>
                                    </div>
                                    @if(Session::has('usertraslados'))
                                    <label for="pass" class="control-label">Contraseña:</label>
                                    <input autofocus id="pass" type="password" class="form-control" name="pass" required minlength="1" value="{{Session::get('Rec_pass')}}">
                                    @endif
                                    <input type="checkbox" name="Recordarpass">Recordar contraseña<br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary">Entrar</button>
                            <a type="button" class="btn btn-default" href="" data-toggle="modal" data-target="#cambuser">Cancelar</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!-- /modal2-->
            <!--  cantidadModal -->
            <div class="modal fade" id="cantidad" role="dialog" >
                <div class="modal-dialog modal-sm" role="document">
                    {!! Form::open(['url' => 'home/traslados/avanzar/', 'method' => 'POST']) !!}
                    <div class="modal-content" >

                        <div class="modal-header" >

                            <h4 class="modal-title" id="pwModalLabel">Cantidad a Procesar</h4>
                        </div>

            <div class="modal-body">

               <div class="row">
                   <div class="col-md-6">
                       <input id="code" name="code" type="text" hidden>
                       @if(Session::has('usertraslados') && Session::get('usertraslados')>0)
                          <input id="userId" name="userId" type="text" hidden value="{{$t_user->U_EmpGiro}}">
                       @endif
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


        <!-- .Model retroceso -->

<div class="modal fade" id="Retroceder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

      {!! Form::open(['url' => 'home/traslados/Reprocesos', 'method' => 'POST']) !!}

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" name="Orden"id="N.Orden"value="Orden">Orden de Producción No.  @if(isset($op)) {{$op}}@endif</h4>
      <div class="modal-body">


        <div class="alert alert-success" align="center" ><label for="message-text" class="control-label" value="Nota"> Estación Actual:</label>
        <div name="Actual" id="code" value="code" ></div>
        <input type="hidden" id="Estacion" name="Estacion"> </div>
        @if(isset($t_user,$of))
        <input  type="hidden" id=Autorizar name="Autorizar"
        value='{{Auth::user()->firstName.' '.Auth::user()->lastName}}'>

        <input type="hidden" id="Nombre" name="Nombre"
        value='{{$t_user->firstName.' '.$t_user->lastName}}'>
        <input type="hidden" id="Nomina" name="Nomina"
        value='{{$t_user->U_EmpGiro}}'>
        <input type="hidden" id="orden" name="orden"
        value="{{$op}}" >
        <div><label for="message-text" class="control-label" value="Nota">Cantidad a trasferir:</label></div>
        <input align="center"type="number" id="retrocant" name="retrocant" min="1" required>

@endif

    <div class="dropdown">
    <label  for="message-text" class="control-label"  >Estaciones Anteriores</label>
    <select class="form-control" name="selectestaciones" id="selectestaciones" required></select>
</div>


  <input type="radio" name="reason" value="Error de Avance.">
  Error de Avance.<br>

  <input type="radio" name="reason" value=" Daño en área actual.">
  Daño en área actual.<br>

  <input type="radio" name="reason" value="Mal hecho en área anteriores (calidad)">
  Mal hecho en área anteriores (calidad)<br>

  <input type="radio" name="reason" value="Producto incompleto (falta materiales).">
   Producto incompleto (falta materiales).<br>

  <input type="radio" name="reason" value="Cambio de Diseño">
  Cambio de Diseño<br>

  <input type="radio" name="reason" value=" Otro…">
   Otro…<br>


                <div>
                <label for="message-text" class="control-label" value="Nota">Nota</label>
                <textarea class="form-control" name="nota" id="message-text"  required maxlength="115"></textarea>
</div>
</div>
         <div class="modal-footer">

        <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" >Enviar</button>
        </div>
</div>
</div>
</div>
</div>
    {!! Form::close() !!}


                          <!--  cantidadModal -->
            <div class="modal fade" id="terminar" role="dialog" >
                <div class="modal-dialog modal-sm" role="document">
                    {!! Form::open(['url' => 'home/traslados/terminar/', 'method' => 'POST']) !!}
                    <div class="modal-content" >

                        <div class="modal-header" >

                            <h4 class="modal-title" id="pwModalLabel">Recibo Producción</h4>
                        </div>

            <div class="modal-body">

               <div class="row">
                   <div class="col-md-12">
                       <input id="code" name="code" type="text" hidden>
                       @if(Session::has('usertraslados') && Session::get('usertraslados')>0)
                          <input id="userId" name="userId" type="text" hidden value="{{$t_user->U_EmpGiro}}">
                       @endif
                       
                       <input type="hidden" id="orden" name="orden"
                       @if(isset($op)) value="{{$op}}" @endif
         >
                       <label for="cant" class="control-label">Cantidad a Procesar:</label>
                       <input id="cant" type="number" class="form-control" name="cant"  step="1" min="1">

                   </div>
                 
               </div>

            </div>
                        <div class="modal-footer">

                            <div id="espera" class="progress" style="display: none">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span>Espere un momento...<span class="dotdotdot"></span></span>
                                </div>
                            </div>
                                        &nbsp;&nbsp;&nbsp;
                           <input id="submit" name="submit" type="submit" value="Liberar" onclick="mostrarespera();"  class="btn btn-primary"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>

                    </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- /modal -->

        <!-- /.container -->
<?php
//$GraficaOrden=1;
if (isset($HisOrden) && !is_null($HisOrden)) {
    ?>
<?=
    Lava::render('AreaChart', 'HisOrden', 'chart');
    ?>
<?php
}
?>
<div id="chart"></div>
@endsection
@section('homescript')

    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM01_01.pdf","_blank");
  } 
};
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
 $('#terminar').on('show.bs.modal', function (event) {
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
    if( '<?php echo $Ruta[$i][0]; ?>' < codejs && '<?php echo $Ruta[$i][0]; ?>' > "106"){
         $('#selectestaciones').append($('<option></option>').val(<?php echo $Ruta[$i][0]; ?>).html("<?php echo $Ruta[$i][1]; ?>"));
    } 
    if('<?php echo $Ruta[$i][0]; ?>' == "106" && '<?php echo $t_user->position ?>' == "5"){
        $('#selectestaciones').append($('<option></option>').val(<?php echo $Ruta[$i][0]; ?>).html("<?php echo $Ruta[$i][1]; ?>"));
    }
    if('<?php echo $Ruta[$i][0]; ?>' == "100" && '<?php echo $t_user->position ?>' == "6"){
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
    function mostrarespera(){
        $("#espera").show();
    };

</script>