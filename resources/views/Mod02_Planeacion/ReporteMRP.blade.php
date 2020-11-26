@extends('home') 
@section('homecontent')
<style>
    th { font-size: 13px; }
    td { font-size: 13px; }
   
    div.dataTables_wrapper {
    margin: 0 ;
    }
    div.container {
        min-width: 980px;
        margin: 0 auto;
    }
    td:first-child{
        width:2%;
    }
    th:first-child {
        position: -webkit-sticky;
        position: sticky;
        left: 0px;
        z-index: 4;
        width: 2%;
    }
    table.dataTable thead .sorting_asc{
        position: sticky;
    }
    .DTFC_LeftBodyWrapper{
        margin-top:68px;
    }
    .DTFC_LeftHeadWrapper {
        display:none;
    }

    th, td { white-space: nowrap; }
    .dataTables_wrapper .dataTables_length { /*mueve el selector de registros a visualizar*/
    float: right;
    }

    .yadcf-filter-range-number-seperator {
    margin-left: 0px; 
    margin-right: 10px;
    }
    .yadcf-filter-reset-button {
    display: inline-block;
    background-color: #337ab7;
        border-color: #2e6da4;
    }

    input{
        color: black;
    }
    div.dataTables_wrapper div.dataTables_processing {
        width: 700px;
        height: 400px;
        margin-left: -35%;
        background: linear-gradient(to right, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.95) 25%, rgba(255,255,255,0.95) 75%, rgba(255,255,255,0.2) 100%);
        z-index: 15;
    }
    table { //me ayudo a que no se desfazaran las columnas en Chrome
        table-layout: fixed;
    }
</style>
<?php
                $fecha = \Carbon\Carbon::now();
            ?>
<div class="container">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-11" >
            <h3 class="page-header" style="margin-bottom: -7px;">
                Resumen de MRP
                <small id="parameter">Necesidades de Materia Prima  ({{$fechauser}}/{{$tipo}})</small>
            </h3>
            
            <h4>{{$text}}</h4>
            <!-- <h5>Fecha & hora: {{\AppHelper::instance()->getHumanDate(date('d-m-Y h:i a', strtotime("now")))}}</h5> -->
        </div>
    </div>
     <div  id="infoMessage" class="alert alert-info" role="alert">
        ¡Importante!  Para un mejor rendimiento de las descargas, aplicar filtros al MRP.
     </div> 
    <!-- /.row -->
    <div class="row">
        <div id="ajax_processing" class="dataTables_wrapper">
            <div  class="dataTables_processing" style="display: block;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:25px; "></i><span style="font-size:25px; "><b>Procesando...</b></span></div>
        </div>
        
        <div class="col-md-12">
            <table id="tmrp" class="stripe cell-border display table-condensed" >
                        <thead class="">
                         <tr>

                         </tr>
                         </thead>
                       
                    </table>
        </div> <!-- /.col-md-12 -->

   </div> <!-- /.row -->
<input hidden value="{{$fechauser}}" id="fechauser" name="fechauser" />
<input hidden value="{{$tipo}}" id="tipo" name="tipo" />

</div>
    <!-- /.container -->
@endsection
 
@section('homescript')

var data,
tableName= '#tmrp',
columnas,
str,
jqxhr =  $.ajax({
        dataType:'json',
        type: 'GET',
        data:  {
              fechauser :$('input[name=fechauser]').val(),
              tipo :$('input[name=tipo]').val()       
                            
            },
        url: '{!! route('datatables.showmrp') !!}',
        success: function(data, textStatus, jqXHR) {
            data = JSON.parse(jqxhr.responseText);
            // Iterate each column and print table headers for Datatables
            $.each(data.columns, function (k, colObj) {
                str = '<th>' + colObj.name + '</th>';
                $(str).appendTo(tableName+'>thead>tr');
               // console.log("adding col "+ colObj.name);
            });
            columnas = data.columnsxls;
            // Add some Render transformations to Columns
            // Not a good practice to add any of this in API/ Json side
            data.columns[(Object.keys(data.columns).length) - 4].render = function (data, type, row) {
          
            var val = new Intl.NumberFormat("es-MX", {minimumFractionDigits:4}).format(data);
            return val;
            }
                    // Debug? console.log(data.columns[0]);
        var table = $(tableName).DataTable({
               
                dom: 'Blrtfip',
                orderCellsTop: true,  
                "pageLength": 10,
                scrollX:        true,
                paging:         true,
                fixedColumns: true,
                processing: true,
                deferRender:    true,
                scrollCollapse: true, 
                data:data.data,
                columns: data.columns,
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
                        action: function ( e, dt, node, config ) {
                                    this.text('<i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> Excel');             
                                    delete_cookie('xlscook');
                                    var data=table.rows( { filter : 'applied'} ).data().toArray();               
                                    var json = JSON.stringify( data );
                                     var col = JSON.stringify(columnas);
                                    //console.log(col);
                                    $.ajax({
                                        type:'POST',
                                        url:'ajaxtosession/mrp',
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},                            
                                        data: 
                                        {
                                            "_token": "{{ Session::token() }}",
                                            "arr": json,
                                            "cols":  col,
                                            "parameter": "Resumen de MRP (SIZ), Necesidades de Materia Prima ("+$('input[name=fechauser]').val()+"/"+$('input[name=tipo]').val()+")"
                                        },
                                        success:function(data)
                                        {
                                            window.location.href = 'mrpXLS';                                   
                                        },
                                        
                                        complete: checkCookie
                                    });
                                }         
                    }, 
                    
                ],
                "language": {
                    "url": "{{ asset('assets/lang/Spanish.json') }}",                    
                },
                columnDefs: [],
                "initComplete": function( settings, json ) {
                    $('#ajax_processing').hide();
                } 
            });

            $('#tmrp thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Filtro '+title+'" />' );
            
            $( 'input', this ).on( 'keyup change', function () {
            
            if ( table.column(i).search() !== this.value ) {
            table
            .column(i)
            .search(this.value, true, false)
            .draw();
            
            }
            
            } );
            } );
            yadcf.init(table,
            [
           
            {
                column_number : [4],
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            {
                column_number : [5],
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            {
                column_number : [6],
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            {
                column_number : [(Object.keys(data.columns).length) - 4], //Col Costo
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            {
                column_number : [(Object.keys(data.columns).length) - 8], //Col Pto. Reorden
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            {
                column_number : [(Object.keys(data.columns).length) - 10], //Col Necesidad
                filter_type: 'range_number',
                filter_default_label: ["Min", "Max"]
            },
            
            ],
            );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
        });
                       

$('#tmrp thead tr').clone(true).appendTo( '#tmrp thead' );
    function checkCookie(){
        var verif = setInterval(isxlscook,500,verif);
    }
    function isxlscook(verif){
        var loadState = getCookie("xlscook");        
        if (loadState == "done"){
        clearInterval(verif);
        delete_cookie('xlscook');
        $( ".btn-success" ).html('<span><i class="fa fa-file-excel-o"></i> Excel</span>');
        }
    }
    function getCookie(cookieName){
        var name = cookieName + "=";
        var cookies = document.cookie
        var cs = cookies.split(';');
        for (var i = 0; i < cs.length; i++)
        { 
            var c=cs[i]; 
            while(c.charAt(0)==' ')
            { 
                c=c.substring(1); 
            } 
            if (c.indexOf(name)==0)
            { 
                return c.substring(name.length, c.length); 
            } 
        } 
        return "" ; 
    } 
    var delete_cookie = function(name) {
        document.cookie = name + "=; Path=/; expires=Thu, 18 Dec 2013 12:00:00 UTC";        
    }; 
@endsection
<script>
    document.onkeyup = function(e) {
        if (e.shiftKey && e.which == 112) {
            window.open("ayudas_pdf/AYM00_00.pdf","_blank");
        }
       
    }
    
</script>