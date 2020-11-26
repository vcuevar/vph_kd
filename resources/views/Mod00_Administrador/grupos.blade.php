@extends('app')

@section('content')
<style>
img{
    border:100%;
    border-color: black;
}
</style>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">

            <li>
                <img src="{{asset('images/white.png')}}" class="img-responsive">
                <ul id="demo" class="">
                    @foreach ($grupos as $a)
                        <li>
                            <a href="{!! url("admin/grupos/".$a->typeID) !!}">{{ $a->name }}</a>
                        </li>
                    @endforeach
                    <li class="divider"></li>
                </ul>
            </li>
            @include('partials.section-navbar')
        </ul>
    </div>
    <!-- /.navbar-collapse -->
    </nav>
    <div id="page-wrapper2">
        <div class="container-fluid" >
            <!-- Page Heading -->
            <div class="container">
                <div  class="row">
                    <div class="visible-xs"><br><br></div>
                    <h3 class="page-header">
                        Módulos del Grupo {{$nombre_grupo}}:
                    </h3>
                        <div class= "hidden-xs">
                        <div class= "hidden-sm">
                        <div class= "col-lg-6.5 col-md-8 col-sm-7">
                            <ol class="breadcrumb">
                    
                             <li>
                                <i class="fa fa-dashboard"></i> <a href="{!! url('home') !!}">Inicio</a>
                            </li>
                            <li>
                                <i class="fa fa-archive"></i>  <a href="{!! url('MOD00-ADMINISTRADOR') !!}">MOD-Administrador</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-file"></i> <a href="{!! url('admin/grupos/'.$id_grupo) !!}">Módulos</a>
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

                <div class="row">

                    <br>

                   {{--este form tiene que enviar la informacion para crear un modulo--}}
                    <form role="form" method="post" action="{!!url('/admin/createModulo/'.$id_grupo)!!}">

                        <div class="col-md-4">
                            {{ csrf_field() }}

                                <label for="sel1">Módulos Existentes:</label>
                                <select class="form-control" id="sel1" name="sel1">
                                    @foreach($modulos as $m)
                                    <option value="{{$m->id}}">{{$m->name}}</option>
                                        @endforeach

                                </select>
                            <br>
                            <div align="right">

                                <button type="submit" class="btn btn-success">Agregar</button>

                            </div><br><br><br>
                       </div>


                </form>
                    <br><br><br>
                </div>
                    <div class="row">
                        <div class="">
                    @foreach ($modulos_grupo as $m)

                            <div class="">
                            
                                <div class="col-md-4 col-sm-12 col-xs-12">                                                                                         
                                    <div class="thumbnail">
                                        <div class="caption">
                                            <h4>{{$m->name}}</h4>
                                            <p>{{$m->descripcion}}</p>
                                            <p align="right">
                                                <a href="{{'delete_modulo/'.$id_grupo.'/'.$m->id_modulo}}" class="btn btn-danger" role="button">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{'conf_modulo/'.$id_grupo.'/'.$m->id_modulo}}" class="btn btn-default" role="button">
                                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                                </a>
                                            </p>
                                        </div>                                   
                                    </div>
                                </div>
                            </div>
                    @endforeach
                      </div>
</div>
                    <!-- Modal -->

                    <div class="modal fade" id="mymodal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="pwModalLabel">Cambio de Password</h4>
                                </div>
                                {!! Form::open(['url' => 'cambio.password', 'method' => 'POST']) !!}
                                <div class="modal-body">

                                    <div class="form-group">
                                        <div>
                                            <label for="password" class="col-md-12 control-label">Id de Usuario:</label>
                                            <input type="text" name="userId" class="form-control" id="userId" value="" readonly/>
                                            <label for="password" class="col-md-12 control-label">Ingresa la nueva Contraseña:</label>
                                            <input id="password" type="password" class="form-control" name="password" required maxlength="6">
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                        <!-- /#wrapper -->


                    <script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>

                    <script type="text/javascript" >



                        $(document).ready(function (event) {




                            $('#mymodal').on('show.bs.modal', function (event) {
                                var button = $(event.relatedTarget) // Button that triggered the modal
                                var recipient = button.data('whatever') // Extract info from data-* attributes
                                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                                var modal = $(this)

                                modal.find('#userId').val(recipient)
                            });
                        });

                    </script>

                </div>

            </div>


@endsection
            @section('script')



@endsection