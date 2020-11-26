<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ 'Reporte de Rechazos' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    
    <style>
    /*
	Generic Styling, for Desktops/Laptops 
	*/
    img {
    display: block;
    margin-left:50px;
    margin-right:50px;
    width: 700%;
    margin-top:4%;
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
         width:640;
            height: 20;
            position: absolute;right:0%;
            align-content:;
        }
        h3{
            font-family: 'Helvetica';
            
        }
        b{
            font-size:80%;
        }       
    #header  {position: fixed; margin-top:2px; }
    #content {position: relative; top: 17%}
</style>



</head>
<body>

        <div id="header" >
        
                <img src="images/Mod01_Produccion/siz1.png" >
                    <table  border="1px" class="table table-striped">
                            <thead class="thead-dark">  
                        <tr>
                         <td colspan="5" align="center" bgcolor="#fff">
                         <b><?php echo $sociedad ?></b><br>    
                         <b>Mod07-Calidad</b>
                         <h3>Recepción de Materiales</h3></td>
                         </tr>
                         </thead>
                         <tbody>
                         <tr>
                         <th align="center">De la fecha</th>
                         <td align="center">{{$fechaIni}}</td>
                         <th align="center">A la fecha:</th>
                         <td align="center"colspan="2">{{$fechaFin}}</td>
                         </tr>
                         </tbody>
                   </table>
            </div>
           
            <div id="content" >
    <table border="1px" class="table table-striped" >
        <thead class="thead-dark">
                <tr>
                
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">Fecha Revisión</td>
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">Proveedor</td>
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">Código </td>
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">Descripcion de Material</td>
                        <td colspan="5" align="center" bgcolor="#474747" style="color:white"; scope="col">Cantidad</td>
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">Nombre del Inspector</td>
                        <td rowspan="2" align="center" bgcolor="#474747" style="color:white"; scope="col">No.Factura</td>
                         </tr>
                         <tr>
                         <td align="center"bgcolor="#474747" style="color:white";>Recibido</td>
                         <td align="center"bgcolor="#474747" style="color:white";>Revisada</td>
                         <td align="center"bgcolor="#474747" style="color:white";>Aceptada</td>
                         <td align="center"bgcolor="#474747" style="color:white";>Rechazada</td>
                         <td align="center"bgcolor="#474747" style="color:white";>%</td>
                         </tr>
            </thead>
            <tbody>
            
            @foreach($rechazo as $rep)
                        <tr>
                            <td scope="row"align="center">
                            <?php echo date('d-m-Y', strtotime($rep->fechaRevision));  ?>
                            </td>

                            <td scope="row"align="center">
                            {{$rep->proveedorNombre}}
                            </td>
                            <td scope="row"align="center">
                            {{$rep->materialCodigo}}
                            </td>

                            <td scope="row"align="center">
                            {{$rep->materialDescripcion}}
                            </td>
                            <td scope="row"align="center">
                            {{number_format($rep->cantidadRecibida, 4)}}
                            </td>

                            <td scope="row"align="center">
                                {{number_format($rep->cantidadRevisada, 4)}}
                            </td>

                            <td scope="row"align="center">
                            {{number_format($rep->cantidadAceptada, 4)}}
                            </td>

                            <td scope="row"align="center">
                            {{number_format($rep->cantidadRechazada, 4)}}
                            </td>
                            <td scope="row"align="center">
                            {{number_format(($rep->cantidadAceptada / $rep->cantidadRecibida)*100, 1)}}
                            </td>
                           

                            <td scope="row"align="center">
                            {{$rep->InspectorNombre}}
                            </td>
                            
                            <td scope="row"align="center">
                            {{$rep->DocumentoNumero}}
                            </td>
  
                    <tr>
                <td colspan="1">
                <b>  Motivo rechazo: </b>
                </td>
                <td colspan="10">
                &nbsp;<?php echo ($rep->DescripcionRechazo);?>
             </td>
             </tr>
             <tr>
             <td colspan="1">
               <b>Observaciones : </b>
                </td>
                <td colspan="10">
                &nbsp;<?php echo ($rep->Observaciones);?>
             </td>
             </tr>

                    @endforeach 
            
                </tbody>
       
    </table>
</div>
<footer>
<script type="text/php">
 $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
 $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s");?>';
 $tittle = 'Siz_Calidad_Recepcion_Materiales.Pdf';
 $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
 $pdf->page_text(40, 580, $text, $font, 9);
 $pdf->page_text(603, 23, $date, $font, 9);
 $pdf->page_text(630, 580, $tittle, $font, 9);
 $empresa = 'Sociedad: <?php echo $sociedad ?>';
 $pdf->page_text(40, 23, $empresa, $font, 9); 
</script> 
    </footer>
</body>

</html>
