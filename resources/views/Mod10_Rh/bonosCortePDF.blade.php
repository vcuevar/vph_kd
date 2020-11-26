
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
                         <h3>Bonos Área de Corte</h3>
                         <h3></h3></td>

                         </tr>
                         </thead>
</table>

<!--Cuerpo o datos de la tabla-->
<div class="row">
<div class="col-md-10">
<h4>Corte de Piel</h4>
<table border= "1px" class="table table-striped">
                        <tr>
                        <th scope="col" style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col" style="text-align: center;">Destajo (DM2)</th>
                        <th scope="col" style="text-align: center;">Bono</th>
                        </tr>        
                        @if(isset($cortadores))     
                    @foreach ($cortadores as $cortador)    
                    <tr>
                    <td style="text-align: center;">{{$cortador->U_EmpGiro}}</td>
                    <td>{{$cortador->firstName}}, {{$cortador->lastName}}</td>
                    <td style="text-align: center;">{{number_format($cortador->Usado,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($cortador->bono,2)}}</td>                  
                    </tr> 
                    @endforeach
                    @endif
            </table>
</div> 
<div class="row">
        <div class="col-md-8">
                    <h4>Inspección de Corte</h4>
                    <table border="1px" class="table table-striped">
                        <tr>
                        <th scope="col"style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col"style="text-align: center;">Destajo (VS)</th>
                        <th scope="col"style="text-align: center;">Bono</th>
                        </tr>
                        @if(isset($inspeccion))     
                    @foreach ($inspeccion as $insperctor)
                    <tr>
                    <td style="text-align: center;">{{$insperctor->U_EmpGiro}}</td>
                    <td>{{$insperctor->firstName}}, {{$insperctor->lastName}}</td>
                    <td style="text-align: center;">{{number_format($insperctor->U_VS,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($insperctor->bono,2)}}</td>                    
                    </tr>  
                    @endforeach
                    @endif
            </table>
            </div> 
        </div> 
<div class="row">
        <div class="col-md-8">
                    <h4>Pegado para Costura</h4>
                    <table border="1px" class="table table-striped">
                        <tr>
                        <th scope="col" style="text-align: center;">No.Nómina</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col" style="text-align: center;">Destajo (VS)</th>
                        <th scope="col" style="text-align: center;">Bono</th>
                        </tr>
                        @if(isset($pegado))     
                    @foreach ($pegado as $pegador)
                    <tr>
                    <td style="text-align: center;">{{$pegador->U_EmpGiro}}</td>
                    <td>{{$pegador->firstName}}, {{$pegador->lastName}}</td>
                    <td style="text-align: center;">{{number_format($pegador->U_VS,2)}}</td>
                    <td style="text-align: center;">{{"$ ".number_format($pegador->bono,2)}}</td>                    
                    </tr>  
                    @endforeach
                    @endif
            </table>
            </div> 
<footer>
                <script type="text/php">
                $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
                $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                $tittle = 'Siz_RH_Reporte_Bonos_Corte.Pdf';
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