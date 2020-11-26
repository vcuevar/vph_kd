@extends('Mod00_Administrador.admin')

@section('subcontent-01')




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

               {!! Form::open(['url' => 'users', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right col-xs-12', 'role' => 'search']) !!}

                   <div class="form-group">
                       {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre de Usuario']) !!}

                   </div>
                   <div class="form-group">
                       <button type="submit" class="btn btn-primary col-xs-12">Buscar</button>
                   </div>

               {!! Form::close() !!}


           </div>

              
                <div class="row">
                
                             <h4>Usuarios Activos</h4>
                            <!-- Table -->
                    <div class= "col-lg-6.5 col-md-7 col-xs-8">
                <div style="overflow-x:auto"> <table id="usuarios" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Reset</th>
                            <th>Departamento</th>

                            <th># Empleado</th>
                            <th># Nómina</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Puesto</th>
                            <th>Estaciones</th>

                        </tr>
                        </thead>
                        @foreach ($users as $o)
                            <tr>
                               {{-- <td align="center">  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#mymodal" data-whatever="{{$o->empID}}">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                </td>--}}

                                <form class="form-horizontal"  role="form" method="post" action="{{url('users/edit/'.$o->empID)}}">

                                <td align="center">  <button type="submit" class="btn btn-default">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                </td>
                                </form>
                                <td> {{$o->Depto}}</td>
                                <td> {{$o->empID}} </td>
                                <td> {{$o->U_EmpGiro}} </td>
                                <td> {{$o->firstName}} </td>
                                <td> {{$o->lastName}} </td>
                                <td> {{$o->jobTitle}} </td>
                                <td> {{$o->U_CP_CT}} </td>


                            </tr>
                        @endforeach
                    </div>
                    </table></div>


                        <div align="center"> {!! $users->render() !!} </div>




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
                                        <div >
                                            <label for="password" class="col-md-12 control-label">Id de Usuario:</label>
                                            <input type="text" name="userId" class="form-control" id="userId" value="" readonly/>
                                            <label for="password" class="col-md-12 control-label">Ingresa la nueva Contraseña:</label>
                                            <input id="password" type="password" class="form-control" name="password" required maxlength="20">
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



@endsection
