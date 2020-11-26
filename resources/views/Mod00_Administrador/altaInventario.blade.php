@extends('app') 
@section('content')
    @include('partials.menu-admin')
<div class="container">
    <!-- Page Heading -->
    <div class="row">
        <div class="visible-xs visible-sm"><br><br></div>
        <div class="col-lg-6.5 col-md-8 col-sm-7">
            <h3 class="page-header">
                Alta de Inventario
            </h3>
        </div>
    </div>
    <div class="row"> 
        <div class="col-lg-6.5 col-md-8 col-sm-7">
            <div class="hidden-xs">
                <div class="hidden-sm">
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i> <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="{!! url('MOD00-ADMINISTRADOR') !!}">MOD-Asministrador</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="inventario">Inventario cómputo</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="altaInventario">Alta Inventario</a>
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-11">
                @include('partials.alertas')
          </div>       
     </div>   

     <style>
         .hoverblack  li > a:hover {
        color: #000;
        text-decoration: none;
        }
        required{
            color:red;
        }
      
     </style>
    {{--este form tiene que enviar la informacion para crear un modulo--}} 
    {!! Form::open(['url' => 'admin/altaInventario', 'method' => 'POST']) !!}
        <div class="row">
            <div class="col-md-11">
                <div class="form-group " style="display: block;">
                    <button id="saveRegistro" type="submit" class="btn btn-primary" name="saveRegistro">Guardar</button></div>
                <div class="" id="tab1" data-role="tab">
                    <ul class="nav nav-tabs hoverblack">
                        <li class="active"><a href="#Registro" data-toggle="tab" >Registro</a></li>
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
                                        <label for="estatus">Estatus <required>*</required></label>
                                        <select class="form-control" name="estatus" value="{{ old('estatus') }}" required>
                                            <option>ACTIVO</option>
                                            <option>INACTIVO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="ubicacion">Ubicación <required>*</required></label>
                                        <select class="form-control" name="ubicacion" value="{{ old('ubicacion') }}" required>
                                            <option>LERMA OFICINAS</option>
                                            <option>LERMA CARPINTERIA</option>
                                            <option>GUADALAJARA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="area">Área</label>
                                        <input type="text" name="area" class="form-control" value="{{ old('area') }}" autofocus>
                                    </div>
                                </div>
                            </div> <!-- /.row-->
                            <div class="row">
                                <div class="col-md-8 col-sm-6">
                                    <div class="form-group">
                                        <label for="NombreEquipo">Descripción <required>*</required></label>
                                        <input type="text" name="nombre_equipo" class="form-control" placeholder="Ej. HP Probook 4520s"
                                            value="{{ old('nombre_equipo') }}" required>
                                    </div>
                                </div>
                            </div> <!-- /.row -->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="usuario_actualizacion">Usuario que hace el Alta </label>
                                        <input type="text" name="usuario_actualizacion" class="form-control" readonly
                                            value="{{ Auth::user()->firstName.' '.Auth::user()->lastName }}" >
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="NombreEquipo">Fecha de Actualización</label>
                                        <input type="text" name="fecha_actualizacion" class="form-control" readonly value="<?php echo date('d-m-Y'); ?>">
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
                                        <input type="text" name="nombre_usuario" class="form-control" placeholder="Nombre y Apellido"
                                            value="{{ old('nombre_usuario') }}"  >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="Correo">Correo</label>
                                        <input type="email" name="correo" class="form-control minuscula" placeholder="nombre.apellido@zarkin.com"
                                            value="{{ old('correo') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="correo_password">Contraseña de Correo</label>
                                        <input type="text" name="correo_password" class="form-control minuscula" value="{{ old('correo_password') }}" >
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
                                        <select class="form-control" name="tipo_equipo" value="{{ old('tipo_equipo') }}" required>
                                            <option>ESCRITORIO</option>
                                            <option>LAPTOP</option>
                                            <option>ALL IN ONE</option>
                                            <option>SERVIDOR</option>
                                            <option>TABLET</option>
                                            <option>TELEFONO ANALOGO</option>
                                            <option>TELEFONO DIGITAL</option>
                                            <option>SMARTPHONE</option>
                                            <option>RADIO (Walkie-talkies)</option>
                                            <option>NO BREAK (UPS)</option>
                                            <option>EQUIPO INFORMATICO (OTRO)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-6">
                                    <div class="form-group">
                                        <label for="monitor">Monitor</label>
                                        <select class="form-control" name="monitor" value="{{ old('monitor') }}">
                                            <option value="1">N/A</option>
                                            @foreach ($monitores as $monitor)
                                            <option value="{{ $monitor->id_mon }}">{{ $monitor->nombre_monitor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                            </div> <!-- /.row-->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="serie">Serie</label>
                                        <input type="text" name="serie" class="form-control" value="{{ old('serie') }}" >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="procesador">Procesador </label>
                                        <input type="text" name="procesador" class="form-control" value="{{ old('procesador') }}" >
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="marca">Marca</label>
                                        <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="velocidad">Velocidad Procesador(GHZ) </label>
                                        <input type="number" step="any" name="velocidad" class="form-control" value="{{ old('velocidad') }}" >
                                    </div>
                                </div>
                            </div><!-- /.row-->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="modelo">Módelo</label>
                                        <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="arquitectura">Arquitectura </label>
                                        <select class="form-control" name="arquitectura" value="{{ old('arquitectura') }}" >
                                            <option value="X64">X64</option>
                                            <option value="X32">X32</option>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- /.row-->
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="memoria">Memoria RAM (GB) </label>
                                        <input type="number" name="memoria" class="form-control" value="{{ old('memoria') }}" >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="disco_duro">Disco Duro (GB) </label>
                                        <input type="number" name="disco_duro" class="form-control" value="{{ old('disco_duro') }}" >
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="electrica">Protección Eléctrica</label>
                                        <select class="form-control" name="electrica" value="{{ old('electrica') }}" >
                                            <option value="NO">NO</option>
                                            <option value="SI">SI</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="descripcion_electrica">Descripción (P. Eléctrica)</label>
                                        <input type="text" name="descripcion_electrica" class="form-control" value="{{ old('descripcion_electrica') }}">
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
                                    <input type="text" name="so" class="form-control" value="{{ old('so') }}" >
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_so">Licencia SO</label>
                                    <input type="text" name="l_so" class="form-control minuscula" value="{{ old('l_so') }}">
                                </div>
                            </div>
                        </div><!-- /.row-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="ofimatica">Ofimática </label>
                                    <input type="text" name="ofimatica" class="form-control" value="{{ old('ofimatica') }}" >
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_ofimatica">Licencia Ofimática</label>
                                    <input type="text" name="l_ofimatica" class="form-control minuscula" value="{{ old('l_ofimatica') }}" >
                                </div>
                            </div>    
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="antivirus">Antivirus</label>
                                    <input type="text" name="antivirus" class="form-control" value="{{ old('antivirus') }}" >
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_antivirus">Licencia Antivirus</label>
                                    <input type="text" name="l_antivirus" class="form-control minuscula" value="{{ old('l_antivirus') }}" >
                                </div>
                            </div>    
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="otro">Otro</label>
                                    <input type="text" name="otro" class="form-control" value="{{ old('otro') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="l_otro">Licencia Otro</label>
                                    <input type="text" name="l_otro" class="form-control minuscula" value="{{ old('l_otro') }}">
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
                                            value="{{ old('mantenimiento_programado') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="mantenimiento_realizado">Último Mantenimiento Realizado</label>
                                        <input type="Date" name="mantenimiento_realizado" class="form-control" placeholder=""
                                            value="{{ old('mantenimiento_realizado') }}">
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label for="ObservacionesTec">Observaciones</label>
                                    <textarea id="ObservacionesTec" rows="3" class="form-control k-textbox" data-role="textarea" name="ObservacionesTec"
                                    data-maxwords="50"></textarea>    
                                </div>
                            </div><!-- /.row -->
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="garantia">Garantía</label>
                                        <select class="form-control" name="garantia" value="{{ old('garantia') }}">
                                            <option value="VENCIDA">VENCIDA</option>
                                            <option value="VIGENTE">VIGENTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="fecha_garantia">Vencimiento Garantía</label>
                                        <input type="Date" name="fecha_garantia" class="form-control" placeholder=""
                                            value="{{ old('fecha_garantia') }}">
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
                                        <input type="text" name="local_user" class="form-control" value="{{ old('local_user') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="local_pass">Contraseña Local</label>
                                        <input type="text" name="local_pass" class="form-control minuscula" value="{{ old('local_pass') }}">
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="dominio_user">Usuario Dominio</label>
                                        <input type="text" name="dominio_user" class="form-control" value="{{ old('dominio_user') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="dominio_pass">Contraseña Dominio</label>
                                        <input type="text" name="dominio_pass" class="form-control minuscula" value="{{ old('dominio_pass') }}">
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="antivirus_user">Usuario Antivirus </label>
                                        <input type="text" name="antivirus_user" class="form-control" value="{{ old('antivirus_user') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="antivirus_pass">Contraseña Antivirus</label>
                                        <input type="text" name="antivirus_pass" class="form-control minuscula" value="{{ old('antivirus_pass') }}">
                                    </div>
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.container-->
                    </div><!-- /.tabpanel-->
                </div>
                
            </div>
        </div>
    {!! Form::close() !!}
</div>

@endsection
@section('script')
$('input:not(.minuscula)').keyup(function() {
this.value = this.value.toLocaleUpperCase();
});
@endsection