@extends('app')

@section('content')
@include('partials.menu-admin')

    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
            <div class="visible-xs"><br><br></div>    
            <div> 
                    <h3 class="page-header">
                        Alta de Monitores
                    </h3>                   
                    <div class= "col-lg-6.5 col-md-8 col-sm-5">
                        <div class="hidden-xs">
                        <div class="hidden-sm">
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="MOD00-Administrador">MOD-Administrador</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i>  <a href="inventario">Gestiòn de Inventarios</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i> <a href="monitores">Monitores</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i>  <a href="altaMonitor">Alta Monitores</a>
                        </li>

                    </ol>
                        </div>
                     </div>
                    </div>
                   </div>
            </div>
            <!-- /.row -->
         <div class="container">
        <div class="row">
             {{--este form tiene que enviar la informacion para crear un modulo--}}
             <div class="col-lg-5.5 col-md-8 col-sm-7">
             {!! Form::open(['url' => 'admin/altaMonitor', 'method' => 'POST']) !!}
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre Monitor</label>
                    <input type="text" id="nombre_monitor" name="nombre_monitor" class="form-control" placeholder="Ej. HP LV1911" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
             {!! Form::close() !!} 
             </div>
             @yield('subcontent-01')


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