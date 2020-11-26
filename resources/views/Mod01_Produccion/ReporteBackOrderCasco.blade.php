@extends('home') 
@section('homecontent')
<style>
    th { font-size: 12px; }
    td { font-size: 11px; }
    th, td { white-space: nowrap; }
    div.container {
        min-width: 980px;
        margin: 0 auto;
    }
    th:first-child {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 5;
    }
    table.dataTable thead .sorting_asc{
        position: sticky;
    }
    .DTFC_LeftBodyWrapper{
        margin-top: 82px;
    }
    .DTFC_LeftHeadWrapper {
    display:none;
    }
  
</style>

<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-11">
            <h3 class="page-header">
                Reporte BackOrder Casco
                <small>Producción</small>
            </h3>

            <!-- <h5>Fecha & hora: {{date('d-m-Y h:i a', strtotime("now"))}}</h5> -->
        </div>
       
    </div>  
    <div  id="infoMessage" class="alert alert-info" role="alert">
        ¡Importante!  Para un mejor rendimiento de las descargas, aplicar filtros al BackOrder.
     </div> 
  
    <!-- /.row -->
    <div class="row">
        <div class="container">
            <table  id="tbackorder" class="display">
                <thead >
                    <tr>
                        <th>Orden Casco</th>
                        <th>Fecha del Programa</th>
                        <th>Dias Proc.</th>
                        <th>Orden de Trabajo</th>
                        <th>Código</th>

                        <th>Descripción</th>
                        <th>Total en Proceso</th>
                        <th>Planeación (400)</th>
                        <th>Habilitado (403)</th>
                        <th>Armado (406)</th>

                        <th>Tapado (409)</th>
                        <th>Pegado Hule (415)</th>
                        <th>Entrega Casco (418)</th>
                        <th>VS</th>
                        <th>Total Valor Sala</th>                       
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                   <th>TOTALES</th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                   <th></th>
                  </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
<!-- /.container -->
@endsection
 
@section('homescript')
$('#tbackorder thead tr').clone(true).appendTo( '#tbackorder thead' );

$('#tbackorder thead tr:eq(1) th').each( function (i) {
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

var table = $('#tbackorder').DataTable({
    "order": [[ 1, "asc" ],[0, "asc"]],
    dom: 'Bfrtip',
    buttons: [
        {
            text: '<i class="fa fa-columns" aria-hidden="true"></i> Columna',
            className: "btn btn-primary",
            extend: 'colvis',
            postfixButtons: [                                  
                {
                    text: 'Restaurar columnas',
                    extend: 'colvisRestore',     
                }             
                ]
        },
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
            messagetwo: "BACK ORDER PROGRAMADO CASCO.\n",
            messagethree: f,
            exportOptions: {
                columns: ':visible',                
            }          
        }, 
        {
            text: '<i class="fa fa-file-pdf-o"></i> Pdf',           
            className: "btn-danger",            
                    action: function ( e, dt, node, config ) {  
                        this.text('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> Pdf');                              
                         var data=table.rows( { filter : 'applied'} ).data().toArray();               
                         var json = JSON.stringify( data );
                         $.ajax({
                            type:'POST',
                            url:'reporte/ajaxtosession/bocasco',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                            
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "arr": json
                                },
                                success:function(data){
                                    window.open('reporte/backorderCascoPDF', '_blank');                                   
                            }
                         });
                     }         
        },
       
        {
            text: '<i class="fa fa-print"></i> Imprimir',
           
            extend: 'print',
            title: 'Reporte de Back Order Casco',
            exportOptions: {
                columns: ':visible',                
            }
        },
       
    ],
   
    orderCellsTop: true,    
    scrollY:        "300px",
    "pageLength": 50,
    scrollX:        true,
    scrollCollapse: true,
    paging:         true,
    fixedColumns:   true,
    processing:true,     
    deferRender:    true,
    ajax: {
        url: '{!! route('datatables.showbackordercasco') !!}',
        data: function () {
                         
                        }              
    },
    columns: [        
        // { data: 'action', name: 'action', orderable: false, searchable: false}

        { data: 'DocNum', name:  'DocNum', orderable: true, searchable: true},
        { data: 'DueDate', name: 'DueDate',
        render: function(data){   
            var d = new Date(data.split(' ')[0]);             
            return moment(d).format("DD-MM-YYYY");
        }},
        { data: 'diasproc', name:  'diasproc'},
        { data: 'U_OT', name: 'U_OT'},
        { data: 'ItemCode', name: 'ItemCode'},

        { data: 'ItemName', name: 'ItemName'},
        { data: 'totalproc', name:  'totalproc'},
        { data: 'xiniciar', name: 'xiniciar'},
        { data: 'Habilitado', name:  'Habilitado'},
        { data: 'Armado', name:  'Armado'},

        { data: 'Tapado', name:  'Tapado'},
        { data: 'Preparado', name:  'Preparado' },
        { data: 'Inspeccion', name:  'Inspeccion'},
        { data: 'uvs', name:  'uvs'},
        { data: 'totalvs', name:  'totalvs' },

    ],
    "language": {
       
       buttons: {
            copyTitle: 'Copiar al portapapeles',
            copyKeys: 'Presiona <i>ctrl</i> + <i>C</i> para copiar o la tecla <i>Esc</i> para continuar.',
            copySuccess: {
                _: '%d filas copiadas',
                1: '1 fila copiada'
            } 
        },
        "sProcessing":     '<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:6    0px;color:#1aafb7;"></i><span class="" style="font-size:30px; color:#1aafb7;">Procesando..</span> ',
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    },
    columnDefs: [
    
    ],   
    "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
        };

        // Total over all pages for VS
        total = api
            .column( 6)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page for VS
        pageTotal = api
            .column( 6, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer for VS
        //.toLocaleString("es-MX",{style:"currency", currency:"MXN"}) //example to format a number to Mexican Pesos
        //var n = 1234567.22
        //alert(n.toLocaleString("es-MX",{style:"currency", currency:"MXN"}))

        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 6 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );


        ////////////// 7 
        total = api
            .column( 7)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 7, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 7 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 8 
        total = api
            .column( 8)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 8, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 8 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 9 
        total = api
            .column( 9)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 9, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 9 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 10 
        total = api
            .column( 10)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 10, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 10 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 11 
        total = api
            .column( 11)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 11, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 11 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 12 
        total = api
            .column( 12)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 12, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 12 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////

        ////////////// 
        total = api
            .column( 14)
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Total over this page for TVS
        pageTotal = api
            .column( 14, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        var pageT = pageTotal.toLocaleString("es-MX", {minimumFractionDigits:2})
        var totalf = total.toLocaleString("es-MX", {minimumFractionDigits:2})
        $( api.column( 14 ).footer() ).html(
            pageT + ' (' + totalf + ')'
        );//////////////////
    }
});
@endsection
<script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM01_26.pdf","_blank");
  }
  }

</script>