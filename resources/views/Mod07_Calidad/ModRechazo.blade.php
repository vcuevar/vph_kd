@extends('app')

@section('content')

@include('partials.menu-admin')


    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div >
                    <div class="visible-xs"><br><br></div>
                    <h3 class="page-header">
                        Usuarios
                    </h3>
                    
                       <div class= "col-lg-6.5 col-md-8 col-sm-7">
                        <div class="hidden-xs">
                        <div class="hidden-sm">
                        <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="users">Modificación de Rechazos</a>
                       </li>
                       <li>
                            <i class="fa fa-bell"></i>  <a href="{!! url('/admin/Notificaciones') !!}">Notificaciones</a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
    {!! Form::open(['url' => 'Mod07_Calidad/rechazoNuevo', 'method' => 'POST']) !!}
    <div class="form-group">
    <input type="hidden" class="form-control" id="Id_Folio" name="Id_Folio" value="{{$Mod_Rechazo[0]->folio}}">

    <label for="exampleFormControlInput1">Fecha de revisión</label>
    <input type="date"class="form-control" id="Fech_Rev" name="Fech_Rev" placeholder="Fecha de revisión"  value="{{$Mod_Rechazo[0]->fechaRevision}}"require>
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Fecha de Recepción:</label>
    <input type="date" class="form-control" id="Fech_Recp" name="Fech_Recp" placeholder="Destinatario"  value="{{$Mod_Rechazo[0]->fechaRecepcion}}"require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Id del Proveedor</label>
    <input type="text" class="form-control" id="Id_prov" name="Id_prov" placeholder="Folio del Proveedor"value="{{$Mod_Rechazo[0]->proveedorId}}" require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Nombre del Proveedor</label>
    <input type="text" class="form-control" id="Proveedor" name="Proveedor" placeholder="Nombre del Proveedor"value="{{$Mod_Rechazo[0]->proveedorNombre}}" require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Codigo de Material</label>
    <input type="text" class="form-control" id="Codigo" name="Codigo" value="{{$Mod_Rechazo[0]->materialCodigo}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Material IUM</label>
    <input type="text" class="form-control" id="ium" name="ium"value="{{$Mod_Rechazo[0]->materialIUM}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Descripcion de Material</label>
    <input type="text" class="form-control" id="Material" name="Material"value="{{$Mod_Rechazo[0]->materialDescripcion}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Cantidad Revisada</label>
    <input type="number" class="form-control" id="C_Revisada" name="C_Revisada" value="{{$Mod_Rechazo[0]->cantidadRevisada}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Cantidad Aceptada</label>
    <input type="number" class="form-control" id="C_Aceptada" name="C_Aceptada" value="{{$Mod_Rechazo[0]->cantidadAceptada}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Cantidad Rechazada</label>
    <input type="number" class="form-control" id="C_Rechazada" name="C_Rechazada" value="{{$Mod_Rechazo[0]->cantidadRechazada}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Descripcion del Rechazo</label>
    <input type="text" class="form-control" id="D_Rechazo" name="D_Rechazo" value="{{$Mod_Rechazo[0]->DescripcionRechazo}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Numero de Documento</label>
    <input type="text" class="form-control" id="N_Doc" name="N_Doc" value="{{$Mod_Rechazo[0]->DocumentoNumero}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Nombre del Inspector</label>
    <input type="text" class="form-control" id="Inspector" name="Inspector" value="{{$Mod_Rechazo[0]->InspectorNombre}}"  require >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Observaciones</label>
    <textarea class="form-control" id="Observaciones"name="Observaciones" rows="3" value="{{$Mod_Rechazo[0]->Observaciones}}"  require></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Enviar</button> 
  {!! Form::close() !!} 
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    </div>
    </div>



    <!-- /#wrapper -->

@endsection

