@extends('app') 
@section('content')
    @include('partials.menu-admin')

<div>

    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div>
                <h3 class="page-header">
                    {{Route::current()->getName()}}
                </h3>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="MOD00-ADMINISTRADOR">INICIO</a>
                    </li>
                    <li>
                        <i class="fa fa-archive"></i> <a href="inventario">MONITORES</a>
                    </li>
                    <li>
                        <i class="fa fa-archive"></i> <a href="altaInventario">ALTA MONITORES</a>
                    </li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="container">
            <div class="row">
                @if ($mensaje !='0')
                <div class="alert alert-success">
                    <strong>Modificado</strong> Se ha modificado el monitor correctamente
                </div>
                @endif {{--este form tiene que enviar la informacion para crear un modulo--}}
                <div class="col-md-6">
                    {!! Form::open(['url' => 'admin/mod_mon2', 'method' => 'POST']) !!}
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Nombre Monitor</label>
                        <input type="text" id="nombre_monitor" name="nombre_monitor" class="form-control" placeholder="Ej. HP LV1911" value="{{ $monitor->nombre_monitor }}"
                            required>
                        <input type="hidden" id="id_monitor" name="id_monitor" class="form-control" placeholder="Ej. HP LV1911" value="{{ $monitor->id }}"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button> {!! Form::close() !!}
                </div>
                @yield('subcontent-01') TEXT
            </div>



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