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
                margin-right:50px;
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
                         <b><?php echo $sociedad ?></b><br>    
                         <b>Mod00 - Administrador</b>
                         <h3>Plantilla de Personal</h3>
                         <h3>{{$clave}}</h3></td>

                         </tr>
                         </thead>                      
</table>
<br>
<!--Cuerpo o datos de la tabla-->
    <table  border="1px" class="table table-striped">
        <THead class="thead-dark">
  <tr>
                <th>Funciones</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>No. Nomina</th>
                <th>Estaciones</th>
                </tr>
        </THead><TBody>
        @foreach($users as $P_user)
         
            <tr>              
                <td>{{$P_user->jobTitle}}</td>                
                <td>{{$P_user->firstName}}</td>
                <td>{{$P_user->lastName}}</td>
                <td>{{$P_user->U_EmpGiro}}</td>
                <td>{{$P_user->U_CP_CT}}</td>
            </tr>
           
         @endforeach
         </TBody>
    </table>
  
        
</div> 


        <footer>
                <script type="text/php">
                $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
                $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s");?>';
                $tittle = 'Siz_Administrador_Plantilla_Personal.Pdf';
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(35, 755, $text, $font, 9);
                $pdf->page_text(405, 23, $date, $font, 9);
                $pdf->page_text(420, 755, $tittle, $font, 9);
                $empresa = 'Sociedad: <?php echo $sociedad ?>';
                $pdf->page_text(40, 23, $empresa, $font, 9); 
                </script> 
        </footer>   
     @yield('subcontent-01')

</body>

</html>