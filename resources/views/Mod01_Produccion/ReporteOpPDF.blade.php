<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ 'Historial OP' }}</title>
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
<table border="1px"class="table table-striped">
    <thead>
        <tbody>
        <tr>
      <td colspan="5" align="center" bgcolor="#fff">
            @if(count($data)>0)
      <b><?php echo  $data[0]->CompanyName?></b><br>    
     
      <b>Mod06-Producción</b>
      <h3>Historial por OP</h3></td>
      </tr>
            <tr>
            <th align="center">Orden de fabricación:</th>
            <td align="center"colspan="1"><?php echo $op ?></td>         
            <th align="center">V S:</th>
            <td align="center"colspan="2"><?php echo number_format($data[0]->VS, 3, '.', ','); ?></td>
            </tr>
            <tr>
            <th align="center">Descripción:</th>
            <td align="center"colspan="4"><?php echo $data[0]->ItemCode ?> - <?php echo $data[0]->ItemName ?></td>   
        @else           
        <b>Mod06-Producción</b>
        <h3>Historial por OP</h3></td>
        </tr>
              <tr>
              <th align="center">Orden de fabricación:</th>
              <td align="center"colspan="1"><?php echo $op ?></td>         
              <th align="center">V S:</th>
              </tr>
              <tr>
              <th align="center">Descripción:</th>
              <td align="center"colspan="4">La orden no tiene historial</td>
            @endif 
        </tbody>
    </thead> 
    </table>
    </div>
     <div id="content">
             <table  border="1px"class="table table-striped">
                    <thead class="table table-striped table-bordered table-condensed" >
                        <tr>                      
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Fecha</th>
                        <th align="center" bgcolor="#474747" style="color:white"; scope="col">Estación</th>
                        <th align="center" bgcolor="#474747" style="color:white";scope="col">Empleado</th>
                        <th align="center" bgcolor="#474747" style="color:white"; scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data)>0)
                    @foreach ($data as $rep)
                        <tr>                            
                            <td scope="row">
                                <?php echo date('d-m-Y', strtotime($rep->FechaF));  ?> 
                            </td>
                            <td scope="row">
                                {{$rep->NAME}}
                            </td>
                            <td scope="row">
                                {{ $rep->Empleado }}
                            </td>
                            <td align="center"scope="row">
                                {{ $rep->U_CANTIDAD }}
                            </td>
                        </tr>    
                    @endforeach ´
                    @endif
                    </tbody>
                </table>
        </div>
     </div>
     @yield('subcontent-01')
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
</div>
@if(count($data)>0)
<footer>
<script type="text/php">
 $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
 $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s");?>';
 $tittle = 'Siz_Producción_Reporte_OP.Pdf';
 $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
 $pdf->page_text(35, 755, $text, $font, 9);
 $pdf->page_text(405, 23, $date, $font, 9);
 $pdf->page_text(420, 755, $tittle, $font, 9);
 $empresa='<?php echo  $data[0]->CompanyName?>';
 $pdf->page_text(35, 23, $empresa, $font, 9);
</script> 
</footer>
@endif
</div>

</body>
</html>