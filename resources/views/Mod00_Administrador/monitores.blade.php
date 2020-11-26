@extends('app')

@section('content')
@include('partials.menu-admin')

    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
              <div class="visible-xs"><br><br></div>
                <div >
                    <h3 class="page-header">
                        Monitores
                    </h3>
                </div>
                <div class= "col-lg-6.5 col-md-8 col-sm-5">   
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
                            <i class="fa fa-archive"></i>  <a href="inventario">Gestiòn de Inventario</a>
                        </li>
                        <li>
                            <i class="fa fa-archive"></i>  <a href="monitores">Monitores</a>
                        </li>

                    </ol>
                       </div>
                       </div>
                    </table>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
             <div class="col-lg-5.5 col-md-8 col-sm-7">
         <div class="well">
         <a href="altaMonitor" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i></a>
         </div>
            </div>
             <div class="row">
             <div class="col-md-12">
             <div class="table-responsive">
             <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre Monitor</th>
                        <th scope="col">Modificar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($monitores as $monitor)
                        <tr>
                        <th scope="row">{{ $monitor->id }}</th>
                        <td>{{ $monitor->nombre_monitor }}</td>
                        <td>
                            <a href="mod_mon/{{$monitor->id}}/{{0}}" class="btn btn-warning"><i class="fa fa-pencil-square"></i</a>
                        </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
</div>
                 <div class="col-md-12">
                     @if (count($errors) > 0)
                         <div class="alert alert-danger text-center" role="alert">
                             @foreach($errors->getMessages() as $this_error)
                                 <strong>¡Lo sentimos!  &nbsp; {{$this_error[0]}}</strong><br>
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
