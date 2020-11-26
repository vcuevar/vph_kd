<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    
    <title>BO Casco</title>
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
            color: white;
            font-family: 'Helvetica';
            font-size: 65%;
            background-color: #474747;
        }

        td {
            font-family: 'Helvetica';
            font-size: 60%;
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
            top: 14%
        }
        table, th, td {
            text-align: center;
            border: 1px solid black;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2
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

                        <b>Mod01 - Producción</b>
                        <h2>Programa de Armado de Casco</h2>                       
                    </td>

                </tr>
            </thead>
        </table>
       
    </div>
        <!--Cuerpo o datos de la tabla-->
        <div id="content">
                <table  id="tbackorder" class="display">
                        <thead >
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>

                                <th>Piezas:</th>
                                <th>{{$totales_pzas['Proceso']}}</th>
                                <th>{{$totales_pzas['PorIniciar']}}</th>
                                <th>{{$totales_pzas['Habilitado']}}</th>
                                <th>{{$totales_pzas['Armado']}}</th>

                                <th>{{$totales_pzas['Tapado']}}</th>
                                <th>{{$totales_pzas['Preparado']}}</th>
                                <th>{{$totales_pzas['Inspeccion']}}</th>
                                <th></th>
                                <th></th>
                            </tr>   
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>

                                <th>Valor Sala:</th>
                                <th>{{$totales_vs['Proceso']}}</th>
                                <th>{{$totales_vs['PorIniciar']}}</th>
                                <th>{{$totales_vs['Habilitado']}}</th>
                                <th>{{$totales_vs['Armado']}}</th>

                                <th>{{$totales_vs['Tapado']}}</th>
                                <th>{{$totales_vs['Preparado']}}</th>
                                <th>{{$totales_vs['Inspeccion']}}</th>
                                <th></th>
                                <th>{{$totales_vs['Proceso']}}</th>
                            </tr>   
                            <tr>
                                <th>Orden Casco</th>
                                <th>Fecha Programa</th>
                                <th>Dias Proc.</th>
                                <th>Orden Trabajo</th>
                                <th>Código</th>
        
                                <th>Descripción</th>
                                <th>En Proceso</th>
                                <th>Planeación (400)</th>
                                <th>Habilitado (403)</th>
                                <th>Armado (406)</th>
        
                                <th>Tapado (409)</th>
                                <th>Pegado Hule (415)</th>
                                <th>Inspec. Casco (418)</th>
                                <th>VS</th>
                                <th>Total VS</th>                       
                            </tr>
                        </thead>
                        <tbody>
                                @if(count($data)>0) 
                               
                                @foreach ($data as $rep)                           
                                <tr>
                                    <td align="center" scope="row">
                                        {{$rep->DocNum}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{date('d-m-Y', strtotime($rep->DueDate))}}
                                        
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->diasproc}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->U_OT}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->ItemCode}}
                                    </td>
    
                                    <td align="center" scope="row">
                                        {{$rep->ItemName}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->totalproc}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->xiniciar}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->Habilitado}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->Armado}}
                                    </td>
    
                                    <td align="center" scope="row">
                                        {{$rep->Tapado}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->Preparado}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->Inspeccion}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->uvs}}
                                    </td>
                                    <td align="center" scope="row">
                                        {{$rep->totalvs}}
                                    </td>
                                  
                                </tr>
                                @endforeach  @endif
                        </tbody>                        
                    </table>
        </div>


                <footer>
                    <script type="text/php">
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 

                        $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>';
                        $date = 'Fecha de impresion:  <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                        $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
                        $tittle = 'Siz_Reporte_BackOrderCasco.Pdf'; 
                        
                        $pdf->page_text(40, 23, $empresa, $font, 9);
                        $pdf->page_text(585, 23, $date, $font, 9);  

                        $pdf->page_text(35, 580, $text, $font, 9);                         
                        $pdf->page_text(620, 580, $tittle, $font, 9);                                                 
                    </script>
                </footer>
                @yield('subcontent-01')

</body>

</html>