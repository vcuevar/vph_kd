<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MRP</title>
    <style>
        /*
                Generic Styling, for Desktops/Laptops
                */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: arial;
            table-layout:fixed;
        }

        th {
            color: white;
            font-weight: bold;
            font-family: 'Helvetica';
            font-size: 12px;
            text-align: center;  
            background-color: #333333;
        }

        td {
            font-family: 'Helvetica';
            font-size: 11px;
            text-align: center;           

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
            background-color: white;
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
        img {
            display: block;     
            margin-top:3.5%; 
            width: 670;
            height: 35;
            position: absolute;
            right: 2%;
        }

        h3 {
            font-family: 'Helvetica';
        }

        b {
            font-size: 100%;
        }

        #header {
            position: fixed;
            margin-top: 2px;
        }

        #content {
            position: relative;
            top: 13%
        }
        .sin-borde{
            border-left: 1px solid black;
            border-right: 1px solid black;
        }
        
        table{
            margin: -2px;
            width: 100%;
        }
    </style>
</head>

<body>

    <div id="header">
    <img src="images/Mod01_Produccion/siz1.png" >
        <!--empieza encabezado, continua cuerpo-->
        <table border="1px" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <td colspan="6" align="center" bgcolor="#fff">
                        <b>{{env('EMPRESA_NAME')}}, S.A de C.V.</b><br>

                        <b>Reporte de MRP</b>
                        <h2>Mod-02 Planeacion</h2>                       
                    </td>

                </tr>
            </thead>
        </table>
       
    </div>
    <?php
                $fecha = \Carbon\Carbon::now();
            ?>
        <!--Cuerpo o datos de la tabla-->
        <div id="content">
            <div class="">                
                    <table  border="1px"class="table table-striped">
                            <thead class="table-condensed" >
                                <tr>                      
                                    <th style="width:14%">Grupo</th>
                                    <th style="width: 5%">Código</th>
                                    <th style="width:35%">Descripción</th>
                                    <th style="width: 4%">UM</th>
                                    <th style="width: 6%">Costo C</th>
                                    
                                    <th style="width: 4%">T.E.</th>
                                    <th style="width:25%">Proveedor</th>
                                    <th style="width:8%">Comprador</th>
                                </tr>
                            </thead>
                    </table>
                    <table  border="1px"class="table table-striped">
                            <thead class="table-bordered table-condensed" >
                                <tr>
                                    <th style="width:8%;">E.Gdl</th>
                                    <th style="width:8%;">E.Lerma</th>
                                    <th style="width:8%">WIP</th>
                                    <th style="width:10%;">Anterior</th>
                                    <th style="width:7%;">Sem-{{$fecha->weekOfYear}}</th>

                                    <th style="width:7%;">Sem-{{$fecha->addWeek(1)->weekOfYear}}</th>
                                    <th style="width:7%;">Sem-{{$fecha->addWeek(1)->weekOfYear}}</th>
                                    <th style="width:7%;" ref="s4">Sem-{{$fecha->addWeek(1)->weekOfYear}}</th>
                                    <th style="width:7%;">Sem-{{$fecha->addWeek(1)->weekOfYear}}</th>
                                    
                                    <th style="width:7">Resto</th>
                                    <th style="width:10%;">Necesidad</th>
                                    <th style="width:7%;">S/WIP</th>
                                    <th style="width: 7%">OC</th>
                                    
                                </tr>
                            </thead>
                            </table>
                            
                            <table border="0" style="margin-top: -2px" class="table-bordered table-condensed">
                                
                                @if(true)
                            @foreach ($data as $rep)
                            
                                    <tr>
                                        <td>
                                            <table  border="1px" class="table-bordered table-condensed">
                                                    <tr>                            
                                                             <td style="width:14%;">
                                                                {{substr($rep->Descr,0,14)}}
                                                            </td>
                                                             <td style="width:5%;">
                                                                {{$rep->Itemcode}}
                                                            </td>
                                                             <td style="width:35%; text-align:left;">
                                                                {{ substr($rep->ItemName,0,45) }}
                                                            </td>
                                                             <td style="width:4%;">
                                                                {{ $rep->UM }}
                                                            </td>
                                                             <td style="width:6%;">
                                                                {{ $rep->Costo }}
                                                            </td>
                        
                                                             <td style="width:4%;">
                                                                {{ $rep->TE }}
                                                            </td>
                                                             <td style="width:25%;">
                                                                {{ substr($rep->Proveedor,0,33) }}
                                                            </td>
                                                            <?php
                                                                $name = explode(' ', $rep->Comprador);
                                                                $name = $name[0];
                                                            ?>
                                                             <td style="width:8%;">
                                                                {{ $name }}
                                                            </td>
                        
                                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table  border="1" class="table-bordered table-condensed">
                                                    <tr>                                                          
                                                                         <td class="sinborde" style="width:8%;">
                                                                                {{ $rep->ExistGDL }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:8%;">
                                                                                {{ $rep->ExistLERMA }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:8%;">
                                                                                {{ $rep->WIP }}  
                                                                            </td>
                                                                              <td class="sinborde" style="width:10%;">
                                                                                {{ $rep->S0 }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->S1 }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->S2 }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->S3 }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->S4 }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->S5 }}
                                                                            </td>
                                                                            
                                                                             
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->Resto }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:10%;">
                                                                                {{ $rep->necesidadTotal }}
                                                                            </td>
                                                                              <td class="sinborde" style="width:7%;">
                                                                                {{ $rep->Necesidad }}
                                                                            </td>
                                                                             <td style="width:7%;">
                                                                                {{ $rep->OC }}
                                                                            </td>                                  
                                                    
                                                    </tr>   
                                            </table>
                                        </td>
                                    </tr>
                                   
                                


                                     
                               
                                   
                                @endforeach 
                        
                       </table>
                            @endif
                            

            </div>
        </div>


                <footer>
                    <script type="text/php">
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 

                        $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>';
                        $date = 'Fecha de impresion:  <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                        $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
                        $tittle = 'Siz_Reporte_MRP.Pdf'; 
                        
                        $pdf->page_text(40, 23, $empresa, $font, 9);
                        $pdf->page_text(585, 23, $date, $font, 9);  

                        $pdf->page_text(35, 565, $text, $font, 9);                         
                        $pdf->page_text(620, 565, $tittle, $font, 9);                                                 
                    </script>
                </footer>
                @yield('subcontent-01')

</body>

</html>