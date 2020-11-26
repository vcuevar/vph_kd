<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transferencia SAP</title>
    <style>
        /*
                Generic Styling, for Desktops/Laptops
                */
                .firma {
                    border: none;
                
                }
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
            margin-top: 1.5%;
            width: 670;
            height: 45;
            position: absolute;
            right: 2%;
        }

        h5 {
            font-family: 'Helvetica';
            margin-bottom: 15;
        }
        h2 {
            font-family: 'Helvetica';
            margin-bottom: -15;
            margin-top:0;
        }
        small {
            font-size: 16px;
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
            top: 11%
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
        .text-left{
            text-align: left; 
            padding-left:4px;
        }
        .page_break { page-break-before: always; }
    </style>
</head>

<body>
    <div id="header">
        <img src="images/Mod01_Produccion/siz1.png">
        <!--empieza encabezado, continua cuerpo-->
        <table border="1px" class="table table-striped mytable">
            <thead class="thead-dark">
                <tr>
                    <td colspan="6" align="center" bgcolor="#fff">
                        <div class="fz"><b>{{env('EMPRESA_NAME')}}, S.A de C.V.</b><br><br>
                            <b>Mod04 - Generación de Traslado</b></div>
                                           
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <!--Cuerpo o datos de la tabla-->
    <div id="content">
  
            <div class="row">    
                
                <div class="col-md-12">
                <div><span style="float:right"><h2>
                @if($info1[0]->Printed == 'N')
                    ORIGINAL
                @else
                    COPIA
                @endif
                </h2></span>
                <h2 >Traslado SAP #{{$transfer}} <small> Solicitud Número: {{$info1[0]->FolioNum}}</small></h2></div>
                
               <div><span style="float:right"><h3>Lista de Precios 10</h3></span>
               <h3>De Almacén {{$info1[0]->Filler}}</h3></div>
                    <table class="table table-striped mytable" style="table-layout:fixed;">
                        <thead>
                            <tr>                        
                                <th style="width:5%;">#</th>
                                <th style="width:7%;">Código</th>
                                <th style="width:40%;">Descripción</th>
                                <th style="width:7%;">UM</th>
                                <th style="width:7%;">Almacén</th>
                                <th style="width:7%;">Cantidad</th>
                                <th style="width:10%;">Precio</th>
                                <th style="width:7%;">Moneda</th>
                                <th style="width:10%;">Total</th>                               
                                                             
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach ($transfer1 as $art)
                            <tr <?php ?>>
                        
                                <td style="width:5%;">{{$art->lineNum}}</td>
                                <td style="width:7%;">{{$art->ItemCode}}</td>
                                <td style="width:40%;" class="text-left">{{$art->Dscription}}</td>
                                <td style="width:7%;">{{$art->unitMsr}}</td>
                                <td style="width:7%;">{{$art->WhsCode}}</td>
                                <td style="width:7%;">{{number_format($art->Quantity, 2)}}</td>
                                <td style="width:10%;">${{number_format($art->Price, 2, '.',',')}}</td>
                                <td style="width:7%;">{{$art->Currency}}</td>
                                <td style="width:10%;">${{number_format($art->LineTotal,2)}}</td>
                               
                            </tr>
                            @endforeach  
                            <tr>
                                <td colspan="8" class="total">Total:</td>
                                <td>${{number_format($total1[0]->Total,'2', '.',',')}} </td>
                            </tr>                    
                    </table>
                   <br>
                    <div>
                    <table class="mytable">
                        <thead>
                        <th style="width:10%;">Comentario:</th>
                        </thead>
                        <tbody>
                        <tr>
                        <td style="width:90%;" class="text-left" >{{$info1[0]->Comments}}</td>
                        </tr>
                        </tbody>
                    </table></div>
                </div>
                <br><br><br><br>
                <table  class="firma">
                        
                        <tbody>
                        <tr class="firma">
                        <td style="width:45%; border-top: 2px solid black"  class="text-center firma" >Nombre y firma de quien entrega</td>
                        <td style="width:10%;" class="firma" ></td>
                        <td style="width:45%; border-top: 2px solid black" class="text-center firma" >Nombre y firma de quien recibe</td>
                        </tr>
                        </tbody>
                    </table>
            </div>        
    
    </div>


    <footer>
        <script type="text/php">
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 
            $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>'; 
            $date = 'Fecha de impresion: <?php echo date("d-m-Y H:i:s"); ?>'; 
            $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
            $tittle = 'Siz_Traslado.Pdf'; 
            $pdf->page_text(40, 23,$empresa, $font, 9); 
            $pdf->page_text(580, 23, $date, $font, 9); 
            $pdf->page_text(35, 580, $text, $font, 9); 
            $pdf->page_text(680, 580, $tittle, $font, 9);
        </script>
    </footer>
    @yield('subcontent-01')

</body>

</html>