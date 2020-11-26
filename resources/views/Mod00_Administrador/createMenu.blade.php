@extends('app')

@section('content')
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">

            <li>
                <img src="{{asset('images/zarkinlogo.jpg')}}" class="img-responsive">
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
                    <h3 class="page-header">
                        Menu del Módulo {{$modulo->name}}:
                    </h3>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('MOD00-ADMINISTRADOR') !!}">Administrador</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i> <a href="{!! url('admin/grupos/'.$id_grupo) !!}">Módulos</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i> <a href="{!! url('admin/grupos/conf_modulo/'.$id_modulo) !!}">Tareas</a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="container">
                <div class="row">
                    @include('partials.alertas')
                </div>

                <div class="row">

                    <br>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4>Grupo: {{$grupo->name}}</h4>
                                <p>Modulo: {{$modulo->name}}</p>
                            </div>
                        </div>
                    </div>
                    {{--este form tiene que enviar la informacion para crear un modulo--}}
                    <form role="form" method="post" action="{!!url('/admin/createTarea/'.$id_grupo)!!}">

                        <div class="col-md-4">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="sel1">Agregar una Tarea:</label>
                                    <select class="form-control" id="sel1" name="sel1" >
                                        <option value="">Seleccione un Menu</option>
                                        @foreach($menus_existentes as $m)
                                            <option value="{{$m->id}}">{{$m->name}}</option>
                                        @endforeach
                                    </select>
                                <br>
                                    <select class="form-control" id="sel2" name="sel2">
                                        <option>Debe escoger un Menu primero</option>
                                    </select>
                                <br>
                                    <div align="right">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#newTask" data-whatever="{{$id_modulo}}">Nuevo</button>
                                        <button  id="envio" disabled="disabled" type="submit" class="btn btn-success">Agregar</button>
                                </div>
                            </div>
                        </div>


                    </form>
                    <br><br><br>
                </div>
                <div class="row">
                    <br><br><br>
                    <div class="col-md-10">
                        <input hidden value="{{$id_modulo}}" id="getmodulo" name="getmodulo"/>
                        <input hidden value="{{$id_grupo}}" id="getgrupo" name="getgrupo"/>
                        <table class="table table-bordered" id="users-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Tarea</th>
                                <th>Menu</th>
                                <th>Escritura</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>


                    <!-- Modal -->

                    <div class="modal fade" id="newTask" role="dialog" >
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="pwModalLabel">Nueva Tarea</h4>
                                </div>
                                {!! Form::open(['url' => 'nuevatarea', 'method' => 'POST']) !!}
                                <div class="modal-body">

                                    <input type="text" hidden name="modulo" value="{{$id_modulo}}">
                                          <div class="row">
                                              <div class="col-md-6">
                                                  <div class="radio">
                                                      <label><input type="radio" name="radio1" value="1" checked>Selecciona</label>
                                                  </div>
                                                  <label for="sel3" class="control-label">Menú:</label>
                                                  <select class="form-control" id="sel3" name="sel3" >
                                                      @foreach($menus_existentes as $m)
                                                          <option value="{{$m->id}}">{{$m->name}}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="radio">
                                                      <label><input type="radio" name="radio1" value="2">Escribe uno nuevo:</label>
                                                  </div>
                                                  <label for="menu2" class="control-label">Menú:</label>
                                                  <input id="menu2" type="text" class="form-control" name="menu2" minlength="3" maxlength="30">
                                              </div>
                                          </div>

                                        <br>
                                   <div class="row">
                                       <div class="col-md-6">
                                           <label for="name" class="control-label">Nombre de la Tarea:</label>
                                           <input id="name" type="text" class="form-control" name="name" required minlength="3" maxlength="30">
                                       </div>
                                   </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Crear</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>





                </div>

            </div>

        </div>

    </div>

@endsection

@section('script')


    $('#sel1').change(function(){

    var val = $(this).val()

    if (val == '') {
    $('#envio').attr('disabled', 'disabled');
    }else{
    $('#envio').removeAttr('disabled');
    }

    $.get("{!! url('dropdown') !!}",
    { option: $(this).val() },
    function(data) {
    var model = $('#sel2');
    model.empty();
    $.each(data, function(index, element) {
    model.append("<option value='"+ index +"'>" + element + "</option>");
    });
    });
    });

    $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
    url: '{!! route('datatables.data') !!}',
    data: function (d) {
      d.id_modulo = $('input[name=getmodulo]').val(); 
      d.id_grupo = $('input[name=getgrupo]').val();      
    }
    },
    columns: [
    { data: 'id', name: 'id'},
    { data: 'tarea', name: 'tarea'},
    { data: 'menu', name: 'menu'},
    { data: 'priv', name: 'priv', orderable: false, searchable: false},
    { data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    "language": {
   "url": "{{ asset('assets/lang/Spanish.json') }}",
    },
    "columnDefs": [
    { "width": "5%", "targets":0 },
    { "width": "20%", "targets":0 },
    { "width": "20%", "targets":0 },
    { "width": "5%", "targets":0 },
    { "width": "15%", "targets":0 }
    ],
    "fnDrawCallback": function() {
    $('.toggle').bootstrapSwitch({size: "mini", onColor:"success"});
    }
    });


    $('#users-table').on('switchChange.bootstrapSwitch', 'input[type="checkbox"]', function() {
    var model2 = "";
    if ($(this).is(':checked')) {
    model2 = "checked";
    }else{
    model2 = "nocheked";
    }

    $.get("{!! url('updateprivilegio') !!}",
    { option: $(this).val(), check: model2 },
    function(data) {


    }
    );





    });



@endsection