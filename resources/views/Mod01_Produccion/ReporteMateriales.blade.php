<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ 'Reporte de Materiales' }}</title>
    <style>
    /*
	Generic Styling, for Desktops/Laptops 
	*/
    img {
    display: block;
    margin-left:50px;
    margin-right:50px;
    width: 700%;
    margin-top:3.5%;
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
<table  border="1" class="table table-striped">
    <thead>   
        </thead>
        <tbody>
         <tr>
      <td colspan="5" align="center" bgcolor="#fff">
      <b><?php echo  $db?></b><br>    
      <b>Mod01-Producción</b>
      <h3>Reporte de Materiales</h3></td>
      </tr>
            <tr>
            <th align="center">Código: </th>
            <td colspan="2"align='center'><?php echo $data[0]->ItemCode ?> - <?php echo $data[0]->ItemName ?></td>        
            <td colspan="1"align="center"><b>O.P:</b></td>
            <td align="center"colspan="1"><b><?php echo $op ?></b></td>
            </tr>
            <tr>
            <th align="center">Cliente:</th>
            <td align="center"colspan="2"><?php echo $data[0]->CardCode ?> - <?php echo $data[0]->CardName ?></td>         
            <th align="center">V.S:</th>
            <td align="center"><?php echo number_format($data[0]->VS, 3); ?></td>
            </tr>
            <tr>
            <th align="center">Fecha de Entrega: </th>
            <td align='center'><?php echo date_create($data[0]->FechaEntrega)->format('Y-m-d'); ?></td>
            <th align='center'>Cantidad Planeada:</th>
            <td colspan="2"align='center'><?php echo number_format($data[0]->plannedqty,0); ?></td>
            </tr>
        </tbody>
        
    </table>
        
        
</div> 

        <table>   
<div id="content">
             <table  border="1px" class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th align="center" bgcolor="#474747" style="color:white"; scope="col">Fecha de entrega</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Código</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Descripción</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Unidad Medida</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Solicitada</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $bandera=false;
                    ?>
                    @foreach ($data as $rep)
                          <?php 
                          if($bandera==false){
                              $bandera=true;
                              $EstacionO=$rep->Estacion;
                              ?>
                              <tr><td colspan="5" align="center" bgcolor="#ccc"> <?php echo $EstacionO ?> </td></tr>

                              <?php
                          }
                          
                           $temporal=$rep->Estacion;
                          //dd($EstacionO);
                           if($EstacionO==$temporal){ 
                            ?>
                            <tr>
                            <td scope="row">
                               <?php echo date('d-m-Y', strtotime($rep->FechaEntrega));  ?>
                            </td>
                            
                            <td scope="row">
                                {{ $rep->Codigo }}
                            </td>
                            <td scope="row">
                                {{$rep->Descripcion}}
                            </td>
                            <td align="center"scope="row">
                                {{ $rep->InvntryUom }}
                            </td>
                            <td align="center"scope="row">
                            <?php echo number_format($rep->Cantidad,2); ?>
                            </td>
                        </tr>
                           <?php
                           }else{
                            $EstacionO=$temporal;
                            ?>  
                           <tr><td colspan="5"align="center" bgcolor="#ccc"> <?php echo $EstacionO ?> </td></tr>
                           <tr>
                            <td scope="row">
                               <?php echo date('d-m-Y', strtotime($rep->FechaEntrega));  ?>
                            </td>
                            
                            <td scope="row">
                                {{ $rep->Codigo }}
                            </td>
                            <td scope="row">
                                {{$rep->Descripcion}}
                            </td>
                            <td align="center"scope="row">
                                {{ $rep->InvntryUom }}
                            </td>
                            <td align="center"scope="row">
                                <?php echo number_format($rep->Cantidad,2); ?>

                            </td>
                        </tr>
                            <?php
                        }
                           ?>
                           
                    @endforeach 
                    </tbody>
                </table>        
                </div>

       
        <footer>
<script type="text/php">
 $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
 $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s");?>';
 $tittle = 'Siz_Producción_Reporte_Materiales.Pdf';
 $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
 $pdf->page_text(35, 755, $text, $font, 9);
 $pdf->page_text(405, 23, $date, $font, 9);
 $pdf->page_text(420, 755, $tittle, $font, 9);
 $sociedad = 'Sociedad: <?php echo $db ?>';
 $pdf->page_text(35, 23, $sociedad, $font, 9);
</script> 
</footer>
     @yield('subcontent-01')

</body>

</html>