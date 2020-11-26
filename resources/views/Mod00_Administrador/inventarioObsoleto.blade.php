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
                        Inventario Obsoleto
                    </h3>
                    <div class= "col-lg-6.5 col-md-8 col-sm-7">
                        <div class="hidden-xs">
                        <div class="hidden-sm">
                    <ol class="breadcrumb">
                    <li>
                            <i class="fa fa-dashboard"></i> <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i>  <a href="{!! url('MOD00-ADMINISTRADOR') !!}">MOD-Administrador</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i>  <a href="inventario">Gestiòn de Inventarios</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="inventarioObsoleto">Inventario Obsoleto</a>
                        </li>
                    </ol>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                @if (count($errors) > 0)
                <div class="alert alert-danger text-center" role="alert">
                    @foreach($errors->getMessages() as $this_error)
                    <strong>¡Lo sentimos! &nbsp; {{$this_error[0]}}</strong><br>
                    @endforeach
                </div>
                @elseif(Session::has('mensaje'))
                <div class="row">
                    <div class="alert alert-success text-center" role="alert">
                        {{ Session::get('mensaje') }}
                    </div>
                </div>
                @endif
            
            </div>
        </div>
             <div class="row">
             <div class="table-responsive">
             <table class="table table-striped header-fixed">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre Equipo</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Número de Equipo</th>
                        <th scope="col">Tipo de Equipo</th>
                        <th scope="col">Monitor</th>
                        <th scope="col">Restaurar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($inventario as $inventario)
                        <tr>
                        <th scope="row">{{ $inventario->id }}</th>
                        <td>{{ $inventario->nombre_equipo }}</td>
                        <td>{{ $inventario->correo}}</td>
                        <td>{{ $inventario->numero_equipo }}</td>
                        <td>{{ $inventario->tipo_equipo }}</td>
                        <td>{{ $inventario->nombre_monitor }}</td>
                        <td>
                            <a href="mark_rest/{{$inventario->id_inv}}" class="btn btn-success"><i class="fa fa-reply"></i</a>
                        </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
</div>
                 
             </div>
             <div class="col-6">
             </div>
             </div>
             @yield('subcontent-01')
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    </div>
    </div>



    <!-- /#wrapper -->
@endsection

@section('script')





@endsection
