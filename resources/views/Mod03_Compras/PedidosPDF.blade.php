<!DOCTYPE html>
<html lang="en">

        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>{{ 'Ordenes de Compras' }}</title>
                <style>
                /*
                Generic Styling, for Desktops/Laptops
                */
                img {
                display: block;
                margin-left:40px;
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
                    font-size:70%;
                }
                td{
                    font-family: 'Helvetica';
                    font-size:70%;
                }

                    img{
                    width:500;
                        height: 20;
                        position: absolute;right:3%;
                        align-content:;
                    }
                    h3{
                        font-family: 'Helvetica';
                    }
                    b{
                        font-size:100%;
                    }
                #header  {position: fixed; margin-top:1px; }
                #content {position: relative; top:10%}
            </style>
        </head>
<body>
<div id="header" >
<img src="images/Mod01_Produccion/siz1.png" >
<!--empieza encabezado, continua cuerpo-->
            <table border="1px" class="table table-striped">
            <td style="text-align: center;">
                    <b>SALOTTO S.A. de C.V.</b>
                    <br><small>EMILIANO ZAPATA #7 INT 1-B</small>
                    <br><small>PARQUE INDUSTRIAL LERMA</small>
                    <br><small>LERMA, ESTADO DE MÉXICO</small>
                    <br><small>C.P. 52004</small>
                    <br><small>R.F.C. SAL1701094N6</small>
                    <!--<h3>ORDEN DE COMPRA</h3>-->
                    </td>                                          
            </table>
            </div>
<div id="content">
  @if (isset($pedido))
  <br>
  <?php $date=date_create($pedido[0]->FechOC);?> 

<table>
    <td style="text-align: right;">Fecha de Orden: {{date_format($date, 'd-m-Y')}} </td>   
  </table> 
<h4>{{$pedido[0]->CodeProv.'  '.$pedido[0]->NombProv}}</h4>
                    
 <h3 style="text-align: center;">Orden de Compra {{$pedido[0]->NumOC}}@if($pedido[0]->CANCELED == 'Y')
                   <small>(Cancelada)</small>
                    @elseif($pedido[0]->DocStatus == 'O') 
                    <small>(Abierta)</small>
                    @else
                    <small>(Cerrada)</small>
                    @endif                        </h3>                 
    <table border= "1px">
                    <tr>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Descripción</th>
                        <th style="text-align: center;">UM</th>
                        <th style="text-align: center;">Cantidad Total</th>
                        <th style="text-align: center;">Cant. Pendiente</th>
                        <th style="text-align: center;">Precio Unitario</th>                                             
                        <th style="text-align: center;">Total</th>  
                        <th style="text-align: center;">Entrega</th>  
                    </tr> 
                    <?php 
                    $suma=0;
                     ?>
            @foreach ($pedido as $pedi)
                    <tr>
                    <?php 
                     $dat=date_create($pedido[0]->FechEnt);                  
                     $total= $pedi->CantPend * $pedi->Price;
                     $suma = $suma + $total;
                     $moneda = $pedi->Currency;                    
                     ?>
                        <td style="text-align: center;">{{$pedi->Codigo}}</td>
                        <td>{{$pedi->Descrip}}</td>
                        <td style="text-align: center;">{{$pedi->BuyUnitMsr}}</td>
                        <td style="text-align: center;">{{number_format($pedi->CantTl,2)}}</td>
                        <td style="text-align: center;">{{number_format($pedi->CantPend,2)}}</td>
                        <td style="text-align: right;">{{number_format($pedi->Price,4)}} {{$pedi->Currency}}</td>
                        <td style="text-align: right;">{{number_format($total,2)." ".$moneda}}</td>     
                        <td style="text-align: center;">{{date_format($dat, 'd-m-Y')}}</td>                     
                    </tr>
            @endforeach
    </table>
    <br>
    <br>
    <table  border= "1px"  style="width: auto;" align="right">
    <tr>
    <th style="text-align: center;">TOTALES</th></tr>
    <tr>
    <td  style="text-align: right;">Subtotal: {{number_format($suma,2)." ".$moneda}}</td>  </tr>
    <tr>
    <td style="text-align: right;">Impuesto: {{number_format($suma * 0.16,2)." ".$moneda}}</td>  </tr>
    <tr>
    <td  style="text-align: right;"> Total: {{number_format(($suma * 0.16) + $suma,2)." ".$moneda}}</td>
    </tr>   
</table>
    
    <table  style="width: auto;">
                            <tr>
                                <td>Contacto: </td>
                                <td>{{$pedido[0]->Elaboro}}</td>
                            </tr>
                            <tr>
                                <td>Comentarios: </td>
                                <td>{{$pedido[0]->Comments}}</td>                    
                            </tr>
                        </tbody>                     
                    </table>     
                    @endif     
                    </div>
 <footer>
                <script type="text/php">
                $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
                $date = 'Fecha de impresión : <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                $tittle = 'Siz_Orden_de_Compra.Pdf';
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