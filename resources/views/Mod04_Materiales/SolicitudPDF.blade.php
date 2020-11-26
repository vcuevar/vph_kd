<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Almacén</title>
    <style>
        /*
                Generic Styling, for Desktops/Laptops
                */

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: arial;
        }

        th {
            color: white;
            font-weight: bold;
            font-family: 'Helvetica';
            font-size: 12px;
            background-color: #333333;
        }

        td {
            font-family: 'Helvetica';
            font-size: 11px;
        }

        img {
            display: block;
            margin-top: 3.8%;
            width: 670;
            height: 45;
            position: absolute;
            right: 2%;
        }

        h5 {
            font-family: 'Helvetica';
            margin-bottom: 15;
        }

        .fz {
            font-size: 100%;
            margin-top: 7px;
        }

        #header {
            position: fixed;
            margin-top: 2px;

        }

        #content {
            position: relative;
            top: 15%
        }

        table,
        th,
        td {
            text-align: center;
            border: 1px solid black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .zrk-silver{
            background-color: #AFB0AE;
            color: black;
        }
        .zrk-dimgray{
            background-color: #514d4a;
            color: white;
        }
        .zrk-gris-claro{
            background-color: #eeeeee;
            color: black;
        }
        .zrk-silver-w{
            background-color: #656565;
            color: white;
        }
        .table > thead > tr > th, 
        .table > tbody > tr > th, 
        .table > tfoot > tr > th, 
        .table > thead > tr > td, 
        .table > tbody > tr > td,
        .table > tfoot > tr > td { 
            padding-bottom: 2px; padding-top: 2px; padding-left: 4px; padding-right: 0px;
        }
        .total{
            text-align: right; 
            padding-right:4px;
        }
        .left{
            text-align: left;
            padding-right:4px;
        }
      
    </style>
</head>

<body>
    <div id="header">
        <img src="images/Mod01_Produccion/siz1.png">
        <!--empieza encabezado, continua cuerpo-->
        <table border="1px" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <td colspan="6" align="center" bgcolor="#fff">
                        <div class="fz"><b>{{env('EMPRESA_NAME')}}, S.A de C.V.</b><br>
                            <b>Mod04 - Materiales</b></div>
                        <h2>Picking de Artículos <small>Solicitud de Material #{{$id}}</small></h2>                       
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <!--Cuerpo o datos de la tabla-->
    <div id="content">
        @if(count($articulos)>0)
            <div class="row">            
                <div class="col-md-8">
                    <table class="table table-striped" style="table-layout:fixed;">
                        <thead>
                            <tr>                        
                                <th style="width:7%;">Código</th>
                                <th style="width:35%;">Descripción</th>
                                <th style="width:7%;">UM</th>
                                <th style="width:7%;">Cantidad a Surtir</th>
                                <th style="width:7%;">APG-PA</th>
                                <th style="width:7%;">AMP-ST</th>
                                <th style="width:15%;">Cantidad Picking</th>                               
                                <th style="width:15%;">Almacén Picking</th>                               
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach ($articulos as $art)
                            <tr <?php ?>>
                        
                                <td style="width:7%;">{{$art->ItemCode}}</td>
                                <td style="width:35%;">{{$art->ItemName}}</td>
                                <td style="width:7%;">{{$art->UM}}</td>
                                <td style="width:7%;">{{$art->Cant_Surtir}}</td>
                                <td style="width:7%;">{{number_format($art->APGPA, 2)}}</td>
                                <td style="width:7%;">{{number_format($art->AMPST, 2)}}</td>
                                <td style="width:15%;"></td>
                                <td style="width:15%;"></td>
                            </tr>
                            @endforeach                      
                    </table>
                </div>

            </div>
            <div style="width:70%;">
                <br><br>
                <table class="table">                   
                    <tr>
                        <td style="width:10%; height: 20px;">Observaciones de Solicitud:</td>
                        <td style="width:90%; height: 20px;" class="left">
                        {{$comment}}
                        </td>
                    </tr>
                </table>
            </div>
            <div style="width:70%;">
                <br>
               <table class="table">
                   <tr>
                       <td style="width:10%;">Material preparado por:</td>                     
                       <td style="width:90%;"></td> 
                   </tr>                  
                   <tr>
                       <td style="width:10%; height: 70px;">Observaciones de Picking:</td>                     
                       <td style="width:90%; height: 70px;"></td> 
                   </tr>
               </table>
            </div>
       @endif

    </div>


    <footer>
        <script type="text/php">
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 
            $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>'; 
            $date = 'Fecha de impresion: <?php echo date("d-m-Y H:i:s"); ?>'; 
            $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
            $tittle = 'Siz_Picking.Pdf'; 
            $pdf->page_text(40, 23,$empresa, $font, 9); 
            $pdf->page_text(580, 23, $date, $font, 9); 
            $pdf->page_text(35, 580, $text, $font, 9); 
            $pdf->page_text(680, 580, $tittle, $font, 9);
        </script>
    </footer>
    @yield('subcontent-01')

</body>

</html>