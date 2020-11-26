@extends('home') 
@section('page_name')
Transferencias Pendientes
@endsection
@section('homecontent')
   
    <style>
            th { font-size: 12px; }
            td { font-size: 11px; }
            th, td { white-space: nowrap; }
           
      
           
         .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
        visibility: visible;
        }
                
            </style>
    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12" style="margin-bottom: -20px;">
                    <div class="visible-xs visible-sm"><br><br></div>               
                <h3 class="page-header">
                    Transferencias Pendientes <small>SOLICITUDES Y TRASLADOS</small>
                </h3>
            </div>
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
      <div class="col-md-12 ">
        @include('partials.alertas')
      </div>
    </div>
        <div class="">
            <div class="">
                <div class="">
                    <table id="tsolicitudes" class="table table-striped table-bordered" style="width:100%" >
                        <thead>
                            <tr>                                                                                                                                             
                                    <th>Tipo</th>                  
                                    <th># Num</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Origen</th>
                                    <th>Destino</th>

                                    <th>Estatus Solicitud</th>
                                    <th>Codigo</th>
                                    <th>Descripción</th>
                                    <th>UDM</th>
                                    <th>Pendiente</th>

                                    <th>Estatus</th>
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


var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
var f=new Date();
var hours = f.getHours();
var ampm = hours >= 12 ? 'pm' : 'am';
var fecha = 'ACTUALIZADO: '+ diasSemana[f.getDay()] + ', ' + f.getDate() + ' de ' + meses[f.getMonth()] + ' del ' + f.getFullYear()+', A LAS '+hours+":"+f.getMinutes()+ ' ' + ampm; 
var f = fecha.toUpperCase();

var table = $('#tsolicitudes').DataTable({
    dom: 'Bfrtlip',       
    "order": [[0, "asc"],[ 1, "desc" ]],
    orderCellsTop: true,   
    scrollX:        true,
    paging:         true,
     "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"] ],
    "pageLength": 10,
    processing: true,
    responsive: true,
    deferRender:    true,
     buttons: [
            {
            text: '<i class="fa fa-file-excel-o"></i> Excel',
            className: "btn-success",
            extend: 'excelHtml5',
            message: "SALOTTO S.A. DE C.V.\n",
            messagetwo: "TRANSFERENCIAS PENDIENTES (Solicitudes y Traslados).\n",
            messagethree: f,
                     
        }, 
        ],
    ajax: {
        url: '{!! route('datatables.transferencias_pendientes') !!}',
        data: function () {
                         
                        }              
    },
    columns: [        
        { data: 'TIPO_DOC'},
        { data: 'NUMERO'},
        { data: 'FECHA',
          render:function(data){
        moment.locale('es');
      return moment(data).format('DD/MM/YYYY HH:mm a');
    }},
                                
        { data: 'USUARIO'},
        { data: 'ORIGEN'},
        { data: 'DESTINO'},    

        { data: 'ST_SOL'},       
        { data: 'CODIGO'},       
        { data: 'DESCRIPCION'},       
        { data: 'UDM'},       
        { data: 'PENDIENTE'}, 

        { data: 'STATUS_LIN'},  


    ],
    "language": {
      "url": "{{ asset('assets/lang/Spanish.json') }}",       
    },
    columnDefs: [],
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

























