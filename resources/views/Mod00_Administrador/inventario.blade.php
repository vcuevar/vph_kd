@extends('app') 
@section('content')
    @include('partials.menu-admin')
    <style>
            th { font-size: 12px; }
            td { font-size: 11px; }
            th, td { white-space: nowrap; }
            div.container {
                min-width: 980px;
                margin: 0 auto;
            }
            div.ColVis {
                float: left;
            }
            .DTFC_LeftBodyWrapper{
                margin-top: 0px;
            }
            
            td:first-child{
                width:40px;
            }
            th:first-child {
                position: -webkit-sticky;
                position: sticky;
                left: 0px;
                z-index: 4;
                width: 40px;
            }
            table.dataTable thead .sorting_asc{
                position: sticky;
            }
            table { //me ayudo a que no se desfazaran las columnas en Chrome
                table-layout: fixed;
            }
                
            </style>
    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-6.5 col-md-9 col-sm-8">
                    <div class="visible-xs visible-sm"><br><br></div>               
                <h3 class="page-header">
                    Inventario de Cómputo
                </h3>
            </div>
            <div class="col-lg-6.5 col-md-9 col-sm-8">
                <div class="hidden-xs">
                    <div class="hidden-ms">
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i> <a href="{!! url('home') !!}">Inicio</a>
                            </li>
                            <li>
                                <i class="fa fa-archive"></i> <a href="{!! url('MOD00-ADMINISTRADOR') !!}">MOD-Administrador</a>
                            </li>
                            <li>
                                <i class="fa fa-archive"></i> <a href="#">Inventario cómputo</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
                @if (count($errors) > 0)
                <div class="alert alert-danger text-center" role="alert">
                    @foreach($errors->getMessages() as $this_error)
                    <strong>¡Lo sentimos!  &nbsp; {{$this_error[0]}}</strong><br> @endforeach
                </div>
                @elseif(Session::has('mensaje'))
                <div class="row">
                    <div class="alert alert-success text-center" role="alert">
                        {{ Session::get('mensaje') }}
                    </div>
                </div>
                @endif

            </div>
        <style>
            td {
                font-family: 'Helvetica';
                font-size: 80%;
            }

            th {
                font-family: 'Helvetica';
                font-size: 90%;
            }
        </style>
        <!-- /.row -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <table id="tinventario" class="display" cellspacing="0" style="width:100%">
                        <thead>
                            <tr>          
                                    <th width="110px">Acciones</th>                  
                                    <th># Equipo</th>
                                    <th>Estatus</th>
                                    <th>Ubicación</th>
                                    <th>Area</th>
                                    <th>Descripción equipo</th>
                                    <th>Ultima Actualización</th>
                                    <th>Fecha Actualización</th>

                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Contraseña Correo</th>
                                    
                                    <th>Tipo_equipo</th>
                                    <th>Monitor</th>
                                    <th>No.Serie</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Procesador</th>
                                    <th>Velocidad</th>
                                    <th>Arquitectura</th>
                                    <th>Memoria</th>
                                    <th>Espacio_DD</th>
                                    <th>Proteccion Eléctrica</th>
                                    <th>Descripción Eléctrica</th>
                                    
                                    <th>SO</th>
                                    <th>Licencia SO</th>
                                    <th>Ofimática</th>
                                    <th>Licencia Ofimática</th>
                                    <th>Antivirus</th>
                                    <th>Licencia Antivirus</th>
                                    <th>Otro Software</th>
                                    <th>Licencia Otro Software</th>
                                    
                                    <th>Próximo Mtto.</th>
                                    <th>Ultimo Mtto.</th>
                                    <th>Observaciones Mtto.</th>
                                    <th>Garantía</th>
                                    <th>Fecha Garantía</th>
                                    <th>Usuario Local</th>
                                    <th>Contraseña Local</th>
                                    <th>Usuario Dominio</th>
                                    <th>Contraseña Dominio</th>
                                    <th>Usuario Antivirus</th>
                                    <th>Contraseña Antivirus</th>
                                    <th>Fecha Alta</th>
                                    
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>
                    </table>
                    
                </div>
            </div>
           
        </div>
         @yield('subcontent-01')
    </div>
@endsection
 
@section('script')

$('#tinventario thead tr').clone(true).appendTo( '#tinventario thead' );

$('#tinventario thead tr:eq(1) th').each( function (i) {
    var title = $(this).text();
    $(this).html( '<input style="color: black;"  type="text" placeholder="Filtro '+title+'" />' );
   
    $( 'input', this ).on( 'keyup change', function () {       
            
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search(this.value, true, false)
                    
                    .draw();
            } 
                
    } );
} );
var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
var f=new Date();
var hours = f.getHours();
var ampm = hours >= 12 ? 'pm' : 'am';
var fecha = 'ACTUALIZADO: '+ diasSemana[f.getDay()] + ', ' + f.getDate() + ' de ' + meses[f.getMonth()] + ' del ' + f.getFullYear()+', A LAS '+hours+":"+f.getMinutes()+ ' ' + ampm; 
var f = fecha.toUpperCase();

var table = $('#tinventario').DataTable({
    dom: 'Bfrtip',
    buttons: [
        
        {
            text: '<i class="fa fa-copy" aria-hidden="true"></i> Copy', 
            extend: 'copy',    
            exportOptions: {
                columns: ':visible',                
            }             
        },
        {
            text: '<i class="fa fa-file-excel-o"></i> Excel',
            className: "btn-success",
            extend: 'excelHtml5',
            message: "SALOTTO S.A. DE C.V.\n",
            messagetwo: "INVENTARIO DE EQUIPO.\n",
            messagethree: f,
            exportOptions: {
                columns: ':visible',                
            }          
        }, 
        {            
            text: '<i class="fa fa-file-pdf-o"></i> Pdf',           
            className: "btn-danger",            
                    action: function ( e, dt, node, config ) {                                
                         var data=table.rows( { filter : 'applied'} ).data().toArray();               
                         var json = JSON.stringify( data );
                         $.ajax({
                            type:'POST',
                            url:'reporte/inventario',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                            
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "arr": json
                                },
                                success:function(data){
                                    window.open('reporte/inventarioComputoPDF', '_blank')                                   
                            }
                         });
                     }           
        },
       
        {
            text: '<i class="fa fa-print"></i> Imprimir',
           
            extend: 'print',
            title: 'Reporte de Inventario',
            exportOptions: {
                columns: ':visible',                
            }
        },
        {            
            text: '<i class="fa fa-plus"></i> Nuevo',           
            className: "btn-primary",
                    action: function ( e, dt, node, config ) {                                                      
                         $.ajax({
                            type:'GET',
                            url:'altaInventario',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                            
                            data: {
                                "_token": "{{ csrf_token() }}",                               
                                },
                                success:function(data){
                                    window.location.href = 'altaInventario';                                   
                            }
                         });
                     }           
        }
       
    ],
    "order": [[ 1, "desc" ]],
    orderCellsTop: true,    
    scrollY:        "300px",
    "pageLength": 50,
    scrollX:        true,
    scrollCollapse: true,
    paging:         true,
    fixedColumns:   true,
    processing: true,
    responsive: true,
    deferRender:    true,
    ajax: {
        url: '{!! route('datatables.inventario') !!}',
        data: function () {
                         
                        }              
    },
    columns: [
        { data: 'action', name:  'action'},                 
        { data: 'numero_equipo', name: 'numero_equipo'},
        { data: 'estatus', name:  'estatus'},
        { data: 'ubicacion', name: 'ubicacion'},
        { data: 'area', name:  'area'},
        { data: 'nombre_equipo', name: 'nombre_equipo'},
        { data: 'usuario_actualizacion'},
        { data: 'fecha_actualizacion', defaultContent: 'SIN',
        render: function(data){
            if (data === null){return data;}
        var d = new Date(data);
        return moment(d).format("DD-MM-YYYY");
        }
        },
//8
        { data: 'nombre_usuario', name:  'nombre_usuario'},
        { data: 'correo', name: 'correo'},
        { data: 'correo_password', name: 'correo_password'},
//11
        { data: 'tipo_equipo', name:  'tipo_equipo'},
        { data: 'nombre_monitor', name: 'nombre_monitor'},
        { data: 'noserie', name:  'noserie'},
        { data: 'marca', name:  'marca' },
        { data: 'modelo', name:  'modelo'},
        { data: 'procesador', name:  'procesador'},
        { data: 'velocidad', name:  'velocidad' },
        { data: 'arquitectura', name:  'arquitectura'},
        { data: 'memoria', name: 'memoria' },
        { data: 'espacio_disco', name:  'espacio_disco'},
        { data: 'proteccion_electrica'},
        { data: 'descripcion_electrica'},
    //23    
        { data: 'so', name:  'so' },
        { data: 'l_so', name:  'l_so' },
        { data: 'ofimatica', name:  'ofimatica'},
        { data: 'l_ofimatica'},
        { data: 'antivirus', name:  'antivirus'},
        { data: 'l_antivirus'},
        { data: 'otros', name:  'otros'},        
        { data: 'l_otros'},
        //31
        { data: 'Fecha_mttoProgramado', name:  'Fecha_mttoProgramado', defaultContent: 'SIN',
        render: function(data){ 
            if (data === null){return data;}  
            var d = new Date(data);               
            return moment(d).format("DD-MM-YYYY");
        }
        },
        { data: 'Fecha_mantenimiento', name:  'Fecha_mantenimiento', defaultContent: 'SIN',
        render: function(data){   
            if (data === null){return data;}
            var d = new Date(data);               
            return moment(d).format("DD-MM-YYYY");
        }
        },  
        { data: 'Observaciones'},
        { data: 'garantia'},
        { data: 'Fecha_garantia', name: 'Fecha_garantia', defaultContent: 'SIN',
        render: function(data){
            if (data === null){return data;}
        var d = new Date(data);
        return moment(d).format("DD-MM-YYYY");
        }
        },
        { data: 'local_user'},
        { data: 'local_pass'},
        { data: 'dominio_user'},
        { data: 'dominio_pass'},
        { data: 'antivirus_user'},
        { data: 'antivirus_pass'},
        
        { data: 'fecha_alta', name: 'fecha_alta', defaultContent: 'SIN',
        render: function(data){
            if (data === null){return data;}
        var d = new Date(data);
        return moment(d).format("DD-MM-YYYY");
        }
        },
    ],
    "language": {
        "url": "{{ asset('assets/lang/Spanish.json') }}",
        buttons: {
            copyTitle: 'Copiar al portapapeles',
            copyKeys: 'Presiona <i>ctrl</i> + <i>C</i> para copiar o la tecla <i>Esc</i> para continuar.',
            copySuccess: {
                _: '%d filas copiadas',
                1: '1 fila copiada'
            }
        }
    },
    columnDefs: [
        {  "width": "300px", targets: 0 },

    ],
    //revision
  
});
@endsection

<script>
document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
</script>

























