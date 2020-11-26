@extends('app')

@section('content')

@include('partials.menu-admin')



    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div >
                   <div class="col-lg-6.5 col-md-9 col-sm-8">
                    <div class="visible-xs visible-sm"><br><br></div>
                    <h3 class="page-header">
                        Log de Notificaciones
                    </h3>
                </div>
                       <div class= "col-lg-6.5 col-md-12 col-sm-7">
                        <div class="hidden-xs">
                        <div class="hidden-sm">
                        <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="users">Usuarios</a>
                       </li>
                       <li>
                            <i class="fa fa-home"></i>  <a href="{!! url('/admin/Notificaciones') !!}">Log Notificaciones</a>
                        </li>
                    </ol>
                </div>
            </div>
            @include('partials.alertas')
            <table id="usuarios" class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                             <th>#</th>
                                            <th>Autor</th>
                                            <th>Destinatario</th>  
                                            <th>Descripción</th>
                                            <th>Estacion Actual</th> 
                                            <th>Estacion Destino</th>
                                            <th>Cantidad Recibida</th>                                        
                                            <th>Nota</th> 
                                            <th>Leido</th>  
                                            <th>Modificar</th>  
                                            <th>Eliminar</th>  
                                    </tr>
                                    </thead>
                                    <tbody>
                        @foreach ($noti as $campo)
                        <tr>
                        <th>{{ $campo->Id}}</th>
                        <td>{{ $campo->Autor }}</td>
                        <td>{{ $campo->Destinatario}}</td>
                        <td>{{ $campo->Descripcion }}</td>
                        <td>{{ $campo->Estacion_Act }}</td>
                        <td>{{ $campo->Estacion_Destino }}</td>
                        <td>{{ $campo->Cant_Enviada }}</td>
                        <td>{{ $campo->Nota }}</td>  
                        <td>{{ $campo->Leido }}</td>                                              
                        <td>
                            <a href=" Mod_Noti/{{$campo->Id}}/{{0}}" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                        <td>
                        <a href="delete_Noti/{{$campo->Id}}" class="btn btn-danger"><i class="glyphicon glyphicon-trash"value"delete"></i></a>
                        </td>
                        </tr>
                    @endforeach 
                    </tbody>
                                
    </div>

@endsection
