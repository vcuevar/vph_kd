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
            top: 20%
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
      
    </style>
</head>

<body>
    <div id="header">
        <img src="images/Mod01_Produccion/siz1.png">
        <!--empieza encabezado, continua cuerpo-->
        <table border="1px" class="table">
            <thead class="thead-dark">
                <tr>
                    <td colspan="6" align="center" bgcolor="#fff">
                        <div class="fz"><b>{{env('EMPRESA_NAME')}}, S.A de C.V.</b><br>
                            <b>Mod04 - Materiales</b></div>
                        <h2>Reporte de Movimientos de Entradas y Salidas de Articulos </h2>
                        <h3><b>Del:</b> {{\AppHelper::instance()->getHumanDate(array_get($fechas_entradas,'fi'))}} <b>al:</b> {{\AppHelper::instance()->getHumanDate(array_get($fechas_entradas,'ff'))}}</h3>
                    </td>
                </tr>
            </thead>
        </table>

    </div>
    <!--Cuerpo o datos de la tabla-->
    <div id="content">
        @if(count($data)>0)
            <div class="row">
           
            <div class="col-md-8">
                <table class="table" style="table-layout:fixed;">
                    <?php
                        $index = 0;
                        $totalEntrada = 0;
                        $moneda = 'MXP';   
                    ?>
                        @foreach ($data as $rep)
                        @if($index == 0)
                        <?php
                            $DocN = $rep->BASE_REF; 
                            $totalEntrada = 0;
                           // $totalEntrada = $rep->LineaTotal + $rep->Iva;
                          //  $moneda = $rep->DocCur;
                            $moneda = '';
                        ?>
                           
                                <tr>
                                    <th style="width:100px" class="zrk-gris" scope="col"># Doc</th>
                                    <th style="width:120px" class="zrk-gris" scope="col">Fecha</th>
                                    <th style="width:110px" class="zrk-gris" scope="col">Fecha System</th>
                                    <th style="width:457px" class="zrk-gris" scope="col">Movimiento</th>
                                    <th style="width:120px" class="zrk-gris" scope="col">Usuario</th>
                                    <th style="width:120px" class="zrk-gris" scope="col" colspan ="2">Notas</th>                                    
                                    <th style="width:120px" class="zrk-gris" scope="col">Hora</th>
                                </tr>
                                <?php
                                    $totalEntrada += 0;
                                    $dateA = date_create($rep->DocDate);
                                    $dateB = date_create($rep->CreateDate);
                                ?>
                                <tr>
                                        <td style="width:60px" class="zrk-gris-claro" scope="row">
                                            {{$rep->BASE_REF}}
                                        </td>
                                        <td style="width:450px" class="zrk-gris-claro" scope="row">
                                            {{ date_format($dateA,'d-m-Y')}}
                                        </td>
                                        <td style="width:57px" class="zrk-gris-claro" scope="row">
                                            {{date_format($dateB,'d-m-Y')}}
                                        </td>
                                        <td style="width:70px" class="zrk-gris-claro" scope="row">
                                            {{$rep->JrnlMemo}}
                                        </td>
                                        <td style="width:100px" class="zrk-gris-claro" scope="row">
                                            {{$rep->U_NAME}} 
                                        </td>
                                        <td style="width:100px" class="zrk-gris-claro" scope="row"  colspan="2">
                                            {{$rep->Comments}} 
                                        </td>
                                      
                                        <td style="width:100px" class="zrk-gris-claro" scope="row">
                                            {{$rep->DocTime}} 
                                        </td>
                                    </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                   
                                </tr>
                                <tr>
                                    <th style="width:60px" class="zrk-gris-claro">Código</th>
                                    <th style="width:450px" class="zrk-gris-claro" colspan ="2">Descripción</th>
                                    <th class="zrk-gris-claro">Cantidad</th>
                                    <th style="width:70px" class="zrk-gris-claro">Valor Std</th>
                                    <th style="width:70px" class="zrk-gris-claro">TM</th>
                                    <th style="width:70px" class="zrk-gris-claro">Almacen</th>
                                    <th style="width:100px" class="zrk-gris-claro">VS</th>
                                </tr>
                                
                            

                            <tbody>
                            
                                    
                                    <tr>
                                        <td style="width:70px"  scope="row">
                                            {{$rep->ItemCode}}
                                        </td>
                                        <td style="width:70px"  scope="row" colspan ="2">
                                            {{$rep->Dscription}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->Movimiento,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            ${{number_format($rep->STDVAL,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->U_TipoMat}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->ALM_ORG}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->VSala,'2', '.',',')}}
                                        </td>
                                        
                                    </tr>

                            @elseif($DocN == $rep->BASE_REF)
                                <?php
                                    $totalEntrada += 0;
                                  
                                ?>
                                   
                                    <tr>
                                        <td style="width:70px"  scope="row">
                                            {{$rep->ItemCode}}
                                        </td>
                                        <td style="width:70px"  scope="row" colspan ="2">
                                            {{$rep->Dscription}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->Movimiento,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            ${{number_format($rep->STDVAL,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->U_TipoMat}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->ALM_ORG}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->VSala,'2', '.',',')}}
                                        </td>
                                       
                                    </tr>
                                    @else

                                <tr>
                                    <th style="width:100px" class="zrk-gris" scope="col"># Doc</th>
                                    <th style="width:120px" class="zrk-gris" scope="col">Fecha</th>
                                    <th style="width:110px" class="zrk-gris" scope="col">Fecha System</th>
                                    <th style="width:457px" class="zrk-gris" scope="col">Movimiento</th>
                                    <th style="width:120px" class="zrk-gris" scope="col">Usuario</th>
                                    <th style="width:120px" class="zrk-gris" scope="col" colspan ="2">Notas</th>
                                    
                                    <th style="width:120px" class="zrk-gris" scope="col">Hora</th>
                                </tr>
                                <?php
                                    $totalEntrada += 0;
                                    $dateA = date_create($rep->DocDate);
                                    $dateB = date_create($rep->CreateDate);
                                ?>
                                <tr>
                                        <td style="width:60px" class="zrk-gris-claro" scope="row">
                                            {{$rep->BASE_REF}}
                                        </td>
                                        <td style="width:450px" class="zrk-gris-claro" scope="row">
                                            {{ date_format($dateA,'d-m-Y')}}
                                        </td>
                                        <td style="width:57px" class="zrk-gris-claro" scope="row">
                                            {{date_format($dateB,'d-m-Y')}}
                                        </td>
                                        <td style="width:70px" class="zrk-gris-claro" scope="row">
                                            {{$rep->JrnlMemo}}
                                        </td>
                                        <td style="width:100px" class="zrk-gris-claro" scope="row">
                                            {{$rep->U_NAME}} 
                                        </td>
                                        <td style="width:100px" class="zrk-gris-claro" scope="row"  colspan="2">
                                            {{$rep->Comments}} 
                                        </td>
                                       
                                        <td style="width:100px" class="zrk-gris-claro" scope="row">
                                            {{$rep->DocTime}} 
                                        </td>
                                    </tr>
                                    <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <tr>
                                    <th style="width:60px" class="zrk-gris-claro">Código</th>
                                    <th style="width:450px" class="zrk-gris-claro" colspan ="2">Descripción</th>
                                    <th class="zrk-gris-claro">Cantidad</th>
                                    <th style="width:70px" class="zrk-gris-claro">Valor Std</th>
                                    <th style="width:70px" class="zrk-gris-claro">TM</th>
                                    <th style="width:70px" class="zrk-gris-claro">Almacen</th>
                                    <th style="width:100px" class="zrk-gris-claro">VS</th>

                                </tr>
                               
                          
                                    <?php
                                        $DocN = $rep->BASE_REF;
                                        $totalEntrada = 0;
                                        $moneda = '';
                                    ?>

                                    
                                    <tr>
                                        <td style="width:70px"  scope="row">
                                            {{$rep->ItemCode}}
                                        </td>
                                        <td style="width:70px"  scope="row" colspan ="2">
                                            {{$rep->Dscription}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->Movimiento,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            ${{number_format($rep->STDVAL,'2', '.',',')}}
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->U_TipoMat}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{$rep->ALM_ORG}} 
                                        </td>
                                        <td style="width:100px"  scope="row">
                                            {{number_format($rep->VSala,'2', '.',',')}}
                                        </td>
                                       
                                    </tr>
                                        @endif 
                                        @if($index == count($data)-1)
                                       

                                        @endif
                                        <?php
                                          $index++;
                                        ?>
                                            @endforeach 
                            </tbody>
                </table>
            </div>

        </div>
       @endif
    </div>


    <footer>
        <script type="text/php">
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 
            $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>'; 
            $date = 'Fecha de impresion: <?php echo date("d-m-Y H:i:s"); ?>'; 
            $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
            $tittle = 'Siz_Reporte_Entradas_Salidas.Pdf'; 
            $pdf->page_text(40, 23,$empresa, $font, 9); 
            $pdf->page_text(580, 23, $date, $font, 9); 
            $pdf->page_text(35, 580, $text, $font, 9); 
            $pdf->page_text(620, 580, $tittle, $font, 9);
        </script>
    </footer>
    @yield('subcontent-01')

</body>

</html>