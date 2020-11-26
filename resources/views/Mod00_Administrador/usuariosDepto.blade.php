@extends('Mod00_Administrador.admin')

@section('subcontent-01')
       <h4>PLANTILLA DE {{$depto}}</h4>
   <div class="row">
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        visibility: visible;
    }
</style>

               <div class="col-md-12">

                   <table class="table table-bordered" id="users-table2">
                       <thead>
                       <input hidden value="{{$depto}}" id="getValue" name="getValue"/>
                       <tr>
                           <th># Nómina</th>
                           <th># CP</th>
                           <th>Nombre</th>  
                           <th>Apellido</th>
                           <th>Estaciones</th>
                           <th>Puesto</th>
                           <th>Acción</th>
                       </tr>
                       </thead>
                   </table>
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
                                        <div >
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
             <!--Aqui termina HTML -->
             
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

                        $('#users-table2').DataTable({
                            dom: 'lfrtip',
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('datatables.showusers') !!}',
                                data: function (d) {
                                    d.depto = $('input[name=getValue]').val();
                                }
                            },
                            columns: [
                                { data: 'U_EmpGiro', name: 'U_EmpGiro'},
                                { data: 'empID', name: 'empID'},
                                { data: 'firstName', name: 'firstName'},
                                { data: 'lastName', name: 'lastName'},
                                { data: 'U_CP_CT', name: 'U_CP_CT', orderable: false, searchable: false},
                                { data: 'jobTitle', name: 'jobTitle'},
                                { data: 'action', name: 'action', orderable: false, searchable: false}
                            ],
                            "language": {
                                "url": "{{ asset('assets/lang/Spanish.json') }}",
                            },
                            "columnDefs": [
                                { "width": "10%", "targets":0 },
                                { "width": "10%", "targets":0 },
                                { "width": "20%", "targets":0 },
                                { "width": "20%", "targets":0 },
                                { "width": "20%", "targets":0 },
                                { "width": "16%", "targets":0 },
                                { "width": "6%", "targets":0 }

                            ],
                        });

                    });

                </script>

        </div>



@endsection
