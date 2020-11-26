@extends('app') 
@section('content')
@include('partials.menu-admin')

    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="visible-xs visible-sm"><br><br></div>
            <div class="col-lg-6.5 col-md-8 col-sm-7">
                <h3 class="page-header">
                    Modificación de Inventario - Equipo #{{$i->numero_equipo}}
                </h3>
            </div>
        </div>
        <div class="row">
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i> <a href="{!! url('home') !!}">Inicio</a>
                </li>
                <li>
                    <i class="fa fa-archive"></i> <a href="{!! url('MOD00-ADMINISTRADOR') !!}">MOD-Asministrador</a>
                </li>
                <li>
                    <i class="fa fa-archive"></i> <a href="{{Request::root().'/admin/inventario'}}">Inventario cómputo</a>
                </li>
                <li>
                    <i class="fa fa-archive"></i> <a href="#">Modificación de inventario</a>
                </li>

            </ol>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-11">
                 @include('partials.alertas')
            </div>
        </div>
        <style>
            .hoverblack li>a:hover {
                color: #000;
                text-decoration: none;
            }
        
            required {
                color: red;
            }
          
        </style>
        {{--este form tiene que enviar la informacion--}} 
        {!! Form::open(['url' => 'admin/mod_inv2', 'method' => 'POST']) !!}
       <input type="hidden" name="id_inv" class="form-control" value="{{$i->id_inv}}">
        <div class="row">
        <div class="col-md-11">
            <div class="form-group " style="display: block;">
                <button id="saveRegistro" type="submit" class="btn btn-primary" name="saveRegistro">Actualizar</button></div>
            <div class="" id="tab1" data-role="tab">
                <ul class="nav nav-tabs hoverblack">
                    <li class="active"><a href="#Registro" data-toggle="tab">Registro</a></li>
                    <li class=""><a data-toggle="tab" href="#Usuario" id="UsuarioT">Usuario</a></li>
                    <li class=""><a href="#Hardware" data-toggle="tab" id="HardwareT">Hardware</a></li>
                    <li class=""><a id="SoftwareT" href="#Software" data-toggle="tab">Software</a></li>
                    <li class=""><a id="MantenimientoT" href="#Mantenimiento" data-toggle="tab">Mantenimiento</a></li>
                    <li class=""><a id="ControlT" href="#Control" data-toggle="tab">Control y Acceso</a></li>
                </ul>
    
            </div>
            <div class="tab-content" style="margin-top:10px">
                <div role="tabpanel" class="tab-pane active" id="Registro">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="numero_equipo">{{'# Equipo'}} <required>*</required></label>
                                    <input type="number" name="numero_equipo" class="form-control"
                                        value="{{ old('numero_equipo',$i->numero_equipo) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="estatus">Estatus <required>*</required></label>
                                    <select class="form-control" name="estatus" value="{{ $i->estatus }}" required>
                                        <option {{old('estatus',$i->estatus)=="ACTIVO"? 'selected':''}}>ACTIVO</option>
                                        <option {{old('estatus',$i->estatus)=="INACTIVO"? 'selected':''}}>INACTIVO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="ubicacion">Ubicación <required>*</required></label>
                                    <select class="form-control" name="ubicacion" value="{{ $i->ubicacion }}" required>
                                        <option {{old('ubicacion',$i->ubicacion)=="LERMA OFICINAS"? 'selected':''}}>LERMA OFICINAS</option>
                                        <option {{old('ubicacion',$i->ubicacion)=="LERMA CARPINTERIA"? 'selected':''}}>LERMA CARPINTERIA</option>
                                        <option {{old('ubicacion',$i->ubicacion)=="GUADALAJARA"? 'selected':''}}>GUADALAJARA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="area">Área</label>
                                    <input type="text" name="area" class="form-control" value="{{ old('area',$i->area) }}" autofocus>
                                </div>
                            </div>
                        </div> <!-- /.row-->
                        <div class="row">
                            <div class="col-md-8 col-sm-6">
                                <div class="form-group">
                                    <label for="NombreEquipo">Descripción <required>*</required></label>
                                    <input type="text" name="nombre_equipo" class="form-control"
                                        placeholder="Ej. HP Probook 4520s" value="{{ old('nombre_equipo',$i->nombre_equipo) }}" required>
                                </div>
                            </div>
                        </div> <!-- /.row -->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="usuario_actualizacion">Usuario que Actualiza </label>
                                    <input type="text" name="usuario_actualizacion" class="form-control" readonly
                                        value="{{ Auth::user()->firstName.' '.Auth::user()->lastName }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="NombreEquipo">Fecha de Actualización</label>
                                    <input type="text" name="fecha_actualizacion" class="form-control" readonly
                                        value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="usuario_actualizacion">Usuario última Actualización </label>
                                    <input type="text" name="x" class="form-control" readonly
                                        value="{{ $i->usuario_actualizacion }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <?php
                                        
                                        $v = date_format(date_create($i->fecha_actualizacion), 'd-m-Y')
                                    ?>
                                    <label for="NombreEquipo">Fecha última Actualización</label>
                                    <input type="text" name="xx" class="form-control" readonly
                                        value="{{ $v }}">
                                </div>
                            </div>
                        </div> <!-- /.row -->
    
                    </div> <!-- /.container-->
                </div> <!-- /.tabpanel-->
    
                <div role="tabpanel" class="tab-pane" id="Usuario">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="nombre_usuario">Nombre</label>
                                    <input type="text" name="nombre_usuario" class="form-control"
                                        placeholder="Nombre y Apellido" value="{{ old('nombre_usuario', $i->nombre_usuario) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="Correo">Correo</label>
                                    <input type="email" name="correo" class="form-control minuscula"
                                        placeholder="nombre.apellido@zarkin.com" value="{{ old('correo',$i->correo) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="correo_password">Contraseña de Correo</label>
                                    <input type="text" name="correo_password" class="form-control minuscula"
                                        value="{{ old('correo_password',$i->correo_password)}}">
                                </div>
                            </div>
                        </div><!-- /.row-->
                    </div><!-- /.container-->
                </div><!-- /.tabpanel-->
                <div role="tabpanel" class="tab-pane" id="Hardware">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 col-sm-6">
                                <div class="form-group">
                                    <label for="tipo_equipo">Tipo de Equipo <required>*</required></label>
                                    <select class="form-control" name="tipo_equipo" value="{{ $i->tipo_equipo}}"
                                        required>
                                        
                                        <option {{old('tipo_equipo',$i->tipo_equipo)=="ESCRITORIO"? 'selected':''}}>ESCRITORIO</option>
                                        <option {{old('tipo_equipo',$i->tipo_equipo)=="LAPTOP"? 'selected':''}}>LAPTOP</option>
                                        <option {{old('tipo_equipo',$i->tipo_equipo)=="ALL IN ONE"? 'selected':''}}>ALL IN ONE</option>
                                        <option {{old('tipo_equipo',$i->tipo_equipo)=="SERVIDOR"? 'selected':''}}>SERVIDOR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6">
                                <div class="form-group">
                                    <label for="monitor">Monitor</label>
                                    <select class="form-control" name="monitor">
                                        <option value="1" {{ old('monitor', $i->id_mon) == 'N/A' ? 'selected' : '' }}>N/A</option>
                                        @foreach ($monitores as $monitor)
                                        <option value="{{ $monitor->id_mon }}" {{ old('monitor', $i->id_mon) == $monitor->id_mon ? 'selected' : '' }}>{{ $monitor->nombre_monitor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    
                        </div> <!-- /.row-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="serie">Serie</label>
                                    <input type="text" name="serie" class="form-control" value="{{ old('serie',$i->noserie) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="procesador">Procesador </label>
                                    <input type="text" name="procesador" class="form-control"
                                        value="{{ old('procesador', $i->procesador) }}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="marca">Marca</label>
                                    <input type="text" name="marca" class="form-control" value="{{ old('marca',$i->marca)}}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="velocidad">Velocidad Procesador(GHZ) </label>
                                    <input type="number" step="any" name="velocidad" class="form-control"
                                        value="{{ old('velocidad',$i->velocidad)}}" >
                                </div>
                            </div>
                        </div><!-- /.row-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="modelo">Módelo</label>
                                    <input type="text" name="modelo" class="form-control" value="{{ old('modelo',$i->modelo)}}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4">
                                <div class="form-group">
                                    <label for="arquitectura">Arquitectura </label>
                                    <select class="form-control" name="arquitectura" value="{{ $i->arquitectura}}" >
                                        <option value="X64" {{old('arquitectura',$i->arquitectura)=="X64"? 'selected':''}}>X64</option>
                                        <option value="X32" {{old('arquitectura',$i->arquitectura)=="X32"? 'selected':''}}>X32</option>
                                    </select>
                                </div>
                            </div>
                        </div><!-- /.row-->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="memoria">Memoria RAM (GB) </label>
                                    <input type="number" name="memoria" class="form-control" value="{{ old('memoria',$i->memoria)}}"
                                        >
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="disco_duro">Disco Duro (GB) </label>
                                    <input type="number" name="disco_duro" class="form-control"
                                        value="{{ old('disco_duro', $i->espacio_disco)}}" >
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="electrica">Protección Eléctrica</label>
                                    <select class="form-control" name="electrica" value="{{ $i->proteccion_electrica}}">
                                        <option value="NO" {{old('proteccion_electrica',$i->proteccion_electrica)=="NO"? 'selected':''}}>NO</option>
                                        <option value="SI" {{old('proteccion_electrica',$i->proteccion_electrica)=="SI"? 'selected':''}}>SI</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="descripcion_electrica">Descripción (P. Eléctrica)</label>
                                    <input type="text" name="descripcion_electrica" class="form-control"
                                        value="{{ old('descripcion_electrica',$i->descripcion_electrica)}}">
                                </div>
                            </div>
                        </div><!-- /.row-->
                    </div> <!-- /.container-->
                </div><!-- /.tabpanel-->
                <div role="tabpanel" class="tab-pane" id="Software">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="so">SO </label>
                                    <input type="text" name="so" class="form-control" value="{{ old('so',$i->so)}}" >
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_so">Licencia SO</label>
                                    <input type="text" name="l_so" class="form-control minuscula" value="{{ old('l_so',$i->l_so)}}">
                                </div>
                            </div>
                        </div><!-- /.row-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="ofimatica">Ofimática</label>
                                    <input type="text" name="ofimatica" class="form-control" value="{{ old('ofimatica',$i->ofimatica)}}"
                                        >
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_ofimatica">Licencia Ofimática</label>
                                    <input type="text" name="l_ofimatica" class="form-control minuscula"
                                        value="{{ old('l_ofimatica',$i->l_ofimatica)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="antivirus">Antivirus</label>
                                    <input type="text" name="antivirus" class="form-control" value="{{ old('antivirus',$i->antivirus)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_antivirus">Licencia Antivirus</label>
                                    <input type="text" name="l_antivirus" class="form-control minuscula"
                                        value="{{ old('l_antivirus',$i->l_antivirus)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="otro">Otro</label>
                                    <input type="text" name="otro" class="form-control" value="{{ old('otro',$i->otros)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_otro">Licencia Otro</label>
                                    <input type="text" name="l_otro" class="form-control minuscula" value="{{ old('l_otro',$i->l_otros)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-->
                </div><!-- /.tabpanel-->
                <div role="tabpanel" class="tab-pane" id="Mantenimiento">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="mantenimiento_programado">Próximo Mantenimiento</label>
                                    <input type="Date" name="mantenimiento_programado" class="form-control" placeholder=""
                                        value="{{ old('mantenimiento_programado',$i->Fecha_mttoProgramado)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="mantenimiento_realizado">Último Mantenimiento Realizado</label>
                                    <input type="Date" name="mantenimiento_realizado" class="form-control" placeholder=""
                                        value="{{ old('mantenimiento_realizado',$i->Fecha_mantenimiento)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label for="ObservacionesTec">Observaciones</label>
                                <textarea id="ObservacionesTec" rows="2" class="form-control" data-role="textarea"
                                    name="ObservacionesTec" data-maxwords="50" value="{{old('ObservacionesTec',$i->Observaciones)}}"></textarea>
                            </div>
                        </div><!-- /.row -->
                        <br>
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label for="garantia">Garantía</label>
                                    <select class="form-control" name="garantia" value="{{ $i->garantia}}">
                                        <option value="VENCIDA" {{old('garantia',$i->garantia)=="VENCIDA"? 'selected':''}}>VENCIDA</option>
                                        <option value="VIGENTE" {{old('garantia',$i->garantia)=="VIGENTE"? 'selected':''}}>VIGENTE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="fecha_garantia">Vencimiento Garantía</label>
                                    <input type="Date" name="fecha_garantia" class="form-control" placeholder=""
                                        value="{{ old('fecha_garantia',$i->Fecha_garantia)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-->
                </div><!-- /.tabpanel-->
                <div role="tabpanel" class="tab-pane" id="Control">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="local_user">Usuario Local </label>
                                    <input type="text" name="local_user" class="form-control"
                                        value="{{ old('local_user',$i->local_user)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="local_pass">Contraseña Local</label>
                                    <input type="text" name="local_pass" class="form-control minuscula"
                                        value="{{ old('local_pass',$i->local_pass)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="dominio_user">Usuario Dominio</label>
                                    <input type="text" name="dominio_user" class="form-control"
                                        value="{{ old('dominio_user',$i->dominio_user)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="dominio_pass">Contraseña Dominio</label>
                                    <input type="text" name="dominio_pass" class="form-control minuscula"
                                        value="{{ old('dominio_pass',$i->dominio_pass)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="antivirus_user">Usuario Antivirus </label>
                                    <input type="text" name="antivirus_user" class="form-control"
                                        value="{{ old('antivirus_user',$i->antivirus_user)}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="antivirus_pass">Contraseña Antivirus</label>
                                    <input type="text" name="antivirus_pass" class="form-control minuscula"
                                        value="{{ old('antivirus_pass',$i->antivirus_pass)}}">
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-->
                </div><!-- /.tabpanel-->
            </div>
    
        </div>
    </div>
    {!! Form::close() !!}

    </div> <!-- /.container -->

@endsection
 
@section('script')
$('input:not(.minuscula)').keyup(function() {
this.value = this.value.toLocaleUpperCase();
});
@endsection