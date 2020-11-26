<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BO Planeación</title>
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

                        <b>Reporte de BackOrder Programado (Planeación)</b>
                        <h2>Estatus de Fundas</h2>                       
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
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">OP</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Pedido</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">No. Serie</th>                                
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Nombre del Cliente</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Modelo</th>

                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Acabado</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Descripción</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Cant</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">%</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Total %</th>

                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Funda</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">Dias A.</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">F. Compra</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">F. Venta</th>
                                <th align="center" bgcolor="#474747" style="color:white" ;scope="col">F. Produc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data)>0) 
                            @foreach ($data as $rep)
                            <tr>
                                <td align="center" scope="row">
                                    {{$rep->OP}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->Pedido}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->NO_SERIE}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->CLIENTE}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->codigo1}}
                                </td>

                                <td align="center" scope="row">
                                    {{$rep->codigo3}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->Descripcion}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->Cant}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->VSind}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->VS}}
                                </td>

                                <td align="center" scope="row">
                                    {{$rep->Funda}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->DEstacion}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->fentrega}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->fechaentregapedido}}
                                </td>
                                <td align="center" scope="row">
                                    {{$rep->u_fproduccion}}
                                </td>
                              
                            </tr>
                            @endforeach  @endif
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
                        $tittle = 'Siz_Reporte_BackOrderP.Pdf'; 
                        
                        $pdf->page_text(40, 23, $empresa, $font, 9);
                        $pdf->page_text(585, 23, $date, $font, 9);  

                        $pdf->page_text(35, 565, $text, $font, 9);                         
                        $pdf->page_text(620, 565, $tittle, $font, 9);                                                 
                    </script>
                </footer>
                @yield('subcontent-01')

</body>

</html>