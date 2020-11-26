<!DOCTYPE html>
<html lang="en">

        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>{{ 'Plantilla de Personal' }}</title>
                <style>
                /*
                Generic Styling, for Desktops/Laptops
                */
                img {
                display: block;
                margin-left:50px;
                width: 700%;
                margin-top:4.5%;
            }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-family:arial;
                }

                th {
                    color: white;
                    font-weight: bold;
                    color: black;
                    font-family: 'Helvetica';
                    font-size:80%;
                }
                td{
                    font-family: 'Helvetica';
                    font-size:80%;
                }

                    img{
                    width:500;
                        height: 20;
                        position: absolute;right:-2%;
                        align-content:;
                    }
                    h3{
                        font-family: 'Helvetica';
                    }
                    b{
                        font-size:100%;
                    }
                #header  {position: fixed; margin-top:2px; }
                #content {position: relative; top:17%}

            </style>
        </head>
<body>

<div id="header" >
<img src="images/Mod01_Produccion/siz1.png" >
<!--empieza encabezado, continua cuerpo-->
            <table border="1px" class="table table-striped">
                <thead class="thead-dark">
                        <tr>
                         <td colspan="6" align="center" bgcolor="#fff">
                         <b><?php echo 'SALOTTO S.A. de C.V.'; ?></b><br>
                         <b>Recursos Humanos</b>
                         <h3>Reporte de Bonos a Supervisores</h3>
                         <h3></h3></td>

                         </tr>
                         </thead>
</table>
<br>
<!--Cuerpo o datos de la tabla-->
<h4 class="page-header">
        Bonos de la Semana {{$semana}}
    </h4>
<div class="row">
<div class="col-md-10">
<h3>Producción</h3>
    <table border="1px" class="table table-striped ">
                 <tr>
                    <th>Empleado:</th>
                    <td>{{$dataGerente[0]}}</td>
                    <td>{{$dataCorte[0]}}</td>
                    <td>{{$dataCostura[0]}}</td>
                    <td>{{$dataCojineria[0]}}</td>
                    <td>{{$dataTapiceria[0]}}</td>
                    <td>{{$dataCarpinteria[0]}}</td>
                  </tr>
                  <tr>
                    <th>Valor Sala:</th>
                    <td>{{number_format($dataGerente[1], 2)}}</td>
                    <td>{{number_format($dataCorte[1], 2)}}</td>
                    <td>{{number_format($dataCostura[1], 2)}}</td>
                    <td>{{number_format($dataCojineria[1], 2)}}</td>
                    <td>{{number_format($dataTapiceria[1], 2)}}</td>
                    <td>{{number_format($dataCarpinteria[1], 2)}}</td>
                  </tr>
                  <tr>
                    <th>Bono:</th>
                    <td>{{"$ ".number_format($dataGerente[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCorte[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCostura[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCojineria[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataTapiceria[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCarpinteria[2], 2)}}</td>
                  </tr>

        </table>
</div>  
<div class="row">
    <div class="col-md-10">
        <h3>Calidad</h3>
            <table border="1px" class="table table-striped">
                         <tr>
                            <th>Empleado:</th>
                            <td>{{$ca_gt[0]}}</td>
                            <td>{{$ca_cor[0]}}</td>
                            <td>{{$ca_cos[0]}}</td>
                            <td>{{$ca_coj[0]}}</td>
                            <td>{{$ca_tap[0]}}</td>
                            <td>{{$ca_car[0]}}</td>
                          </tr>
                          <tr>
                            <th>% de Calidad:</th>
                            <td>{{number_format($ca_gt[1], 2)." %"}}</td>
                             <td>{{number_format($ca_cor[1], 2)." %"}}</td>
                             <td>{{number_format($ca_cos[1], 2)." %"}}</td>
                             <td>{{number_format($ca_coj[1], 2)." %"}}</td>
                             <td>{{number_format($ca_tap[1], 2)." %"}}</td>
                             <td>{{number_format($ca_car[1], 2)." %"}}</td>
                          </tr>
                          <tr>
                            <th>Bono:</th>
                            <td>{{"$ ".number_format($ca_gt[2], 2)}}</td>
                            <td>{{"$ ".number_format($ca_cor[2], 2)}}</td>
                            <td>{{"$ ".number_format($ca_cos[2], 2)}}</td>
                            <td>{{"$ ".number_format($ca_coj[2], 2)}}</td>
                            <td>{{"$ ".number_format($ca_tap[2], 2)}}</td>
                            <td>{{"$ ".number_format($ca_car[2], 2)}}</td>
                          </tr>

                </table>
    </div> <!-- /.col md -->
</div> <!-- /.row -->
<div class="row">
<div class="col-md-10">
<h3>Totales</h3>
    <table border="1px" class="table table-striped">
                 <tr>
                    <th>Empleado:</th>
                    <td>{{$dataGerente[0]}}</td>
                    <td>{{$dataCorte[0]}}</td>
                    <td>{{$dataCostura[0]}}</td>
                    <td>{{$dataCojineria[0]}}</td>
                    <td>{{$dataTapiceria[0]}}</td>
                    <td>{{$dataCarpinteria[0]}}</td>
                  </tr>
                  <tr>
                    <th>Total Bono:</th>
                    <td>{{"$ ".number_format($dataGerente[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCorte[2]+$ca_cor[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCostura[2]+$ca_cos[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCojineria[2]+$ca_coj[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataTapiceria[2]+$ca_tap[2], 2)}}</td>
                    <td>{{"$ ".number_format($dataCarpinteria[2]+$ca_car[2], 2)}}</td>
                  </tr>
        </table>



</div>


        <footer>
                <script type="text/php">
                $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
                $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                $tittle = 'Siz_RH_Reporte_Bonos.Pdf';
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(35, 755, $text, $font, 9);
                $pdf->page_text(405, 23, $date, $font, 9);
                $pdf->page_text(420, 755, $tittle, $font, 9);
                $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>';
                $pdf->page_text(40, 23, $empresa, $font, 9);
                </script>
        </footer>
     @yield('subcontent-01')

</body>

</html>