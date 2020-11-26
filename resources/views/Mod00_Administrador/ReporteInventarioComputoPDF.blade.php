<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventario de Cómputo</title>
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
            color: black;
            font-family: 'Helvetica';
            font-size: 65%;
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
            top: 11%
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

                        <b>Inventario de Cómputo</b>
                        <h2>Tecnologías de la Información</h2>                       
                    </td>

                </tr>
            </thead>
        </table>
       
    </div>
        <!--Cuerpo o datos de la tabla-->
        <div id="content">
            <div class="row">                
                    <table border="1px" class="table table-striped">
                        <thead class="table table-striped table-bordered table-condensed">
                            <tr>                                   
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">#_Equipo</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Usuario</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Estatus</th>                                
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Ubicacion</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Area</th>
                                                                
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Marca</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Modelo</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Procesador</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Memoria</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Espacio_DD</th>

                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">SO</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Ofimática</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Antivirus</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Mtto Programado</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Ultimo Mtto.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data)>0) 
                            @foreach ($data as $rep)
                            <tr>
                                <td align="center" scope="row">
                                    {{$rep->numero_equipo}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->nombre_usuario }}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->estatus}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->ubicacion}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->area}}
                                </td>

                                <td align="center" scope="row">
                                    {{$rep->marca}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->modelo}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->procesador}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->memoria}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->espacio_disco}}
                                </td>

                                <td align="center" scope="row">
                                    {{$rep->so}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->ofimatica}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->antivirus}}
                                </td>
                                <td align="center" scope="row">
                                    {{ date('d/m/Y', strtotime($rep->Fecha_mttoProgramado))}}
                                </td>
                                <td align="center" scope="row">
                                    {{ date('d/m/Y', strtotime($rep->Fecha_mantenimiento))}}
                                </td>
                              
                            </tr>
                            @endforeach 
                             @endif
                        </tbody>
                    </table>



                </div>
                </div>


                <footer>
                    <script type="text/php">
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 

                        $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>';
                        $date = 'Fecha de impresion:  <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                        $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
                        $tittle = 'Siz_Reporte_InventarioComputo.Pdf'; 
                        
                        $pdf->page_text(40, 23, $empresa, $font, 9);
                        $pdf->page_text(585, 23, $date, $font, 9);  

                        $pdf->page_text(35, 580, $text, $font, 9);                         
                        $pdf->page_text(620, 580, $tittle, $font, 9);                                                 
                    </script>
                </footer>
                @yield('subcontent-01')

</body>

</html>