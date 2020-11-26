
<TITLE>Inventario de equipo</TITLE>


<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <style>
    /*
	Generic Styling, for Desktops/Laptops 
	*/
    img {
    display: block;
    margin-left:10px;
    margin-right:50px;
    width: 700%;
    margin-top:1.8%;
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
        font-size:70%;
    }

        img{
         width:200;
            height: 20;
            position: absolute;left:0%;
            align-content:;
        }
        h3{
            font-family: 'Helvetica';
        }
        h4{
            font-family: 'Helvetica';
            font-size:80%;
        }
        h6{
            font-family: 'monospace';
        }
        b{
            font-size:100%;
        }
</style>
<body>
    <div id="app">
        <div id="wrapper">

<div class="container" >
<img src="images/ZARKIN_0.png" >
     <div class="row">
        <div class="col-6">
        <BR>
             <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $inv)
                    
                        <tr>
                            <td scope="row">
                                      
                            </td>
                            <td scope="row">
                                    
                            </td>
                            <td  align="right">
                            <H2><B>Tecnologías de la Información</B></H2>
                          
                            Asignación de equipos de Cómputo
                            </td>
                        </tr>    
                        <tr>
                            <td scope="row" colspan="5"   scope="col"> 
                            <b>Información del Usuario</b><hr>
                            </td>
                           
                        </tr>
                        <tr>
                            <td scope="row"colspan="2">
                                Número de Equipo
                            </td>
                            <td scope="row">
                                {{ $inv->numero_equipo }}      
                            </td>
                        </tr> 
                        <tr>
                            <td scope="row"colspan="2">
                                Equipo
                            </td>
                            <td scope="row">
                                {{ $inv->nombre_equipo }}  
                            </td>
                        </tr>   
                        <tr>
                            <td scope="row"colspan="2">
                                Monitor
                            </td>
                            <td scope="row">
                                {{ $inv->nombre_monitor }}
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"colspan="2">
                                Equipo Asignado a
                            </td>
                            <td scope="row">
                                {{ $inv->nombre_usuario}}
                            </td>
                        </tr> 
                        <tr>
                            <td scope="row"colspan="2">
                                Correo
                            </td>
                            <td scope="row">
                                {{ $inv->correo}}
                            </td>
                        </tr>    
                        <tr>
                            <td scope="row"colspan="2">
                                Tipo de Equipo
                            </td>
                            <td scope="row">
                                {{ $inv->tipo_equipo }}
                            </td>
                        </tr>       
                    @endforeach 
                    </tbody>
                </table>
        </div>
     <div class="col-6">
     <h4>Informacion de Confidencialidad y Responsabilidad de uso.</h4><hr>
        <h6>1. ES RESPONSABILIDAD DEL USUARIO ACATAR LA PRESENTE NORMATIVIDAD EN EL USO DE LOS BIENES INFORMÁTICOS</h6>
        <h6>2. SERÁ RESPONSABILIDAD DEL USUARIO EL USO Y CUIDADO DEL EQUIPO DE CÓMPUTO</h6>
        <h6>3. QUEDA ESTRICTAMENTE PROHIBIDO LA DESCARGA, INSTALACIÓN O DISTRIBUCIÓN DE SOFTWARE QUE NO SEA PROPORCIONADO POR EL ÁREA DE SISTEMAS</h6>
        <h6>4. EL ADMINISTRADOR DE SISTEMAS CUENTA CON LA FACULTAD DE AUDITAR LOS EQUIPOS EN EL MOMENTO QUE LO CONSIDERE OPORTUNO SIN QUE EXISTA LA OBLIGACIÓN DE INFORMAR AL USUARIO DE LA ACTIVIDAD A REALIZAR</h6>
        <h6>5. LA CONSERVACIÓN Y USO OPTIMO DEL BIEN INFORMÁTICO, ES RESPONSABILIDAD DIRECTA DEL ENCARGADO, EL CUAL DEBERÁ DAR AVISO A SISTEMAS EN CASO DE EXTRAVÍO O DE DAÑO AL EQUIPO</h6>
        <h6>6. AL TERMINO DE LAS LABORES EL RESPONSABLE VERIFICARÁ QUE EL EQUIPO SE ENCUENTREN DEBIDAMENTE APAGADO</h6>
        <h6>7. LOS USUARIOS DE LAS COMPUTADORAS NO PODRÁN CAMBIAR LA CONFIGURACIÓN DE LOS EQUIPOS</h6>
        <h6>8. LOS DAÑOS O SINIESTROS QUE OCURRAN AL EQUIPO DEBERAN NOTIFICARSE AL ÁREA DE SISTEMAS POR MEDIO DE UN ESCRITO PARA EVALUAR EL DAÑO</h6>
        <h6>9. TODOS LOS USUARIOS QUE REQUIERAN DE UN ADITAMENTO PARA SU EQUIPO, DEBERÁN SOLICITARLO AL DEPARTAMENTO DE SISTEMAS A TRAVÉS DE UN CORREO ELECTRÓNICO</h6>
        <h6>10. EL USUARIO DE UN BIEN INFORMÁTICO, CON FACULTADES DE ACUERDO A SUS FUNCIONES , MANIFIESTA QUE CONOCE, ACEPTA Y CUMPLIRÁ LAS OBLIGACIONES Y RESPONSABILIDADES QUE IMPLICA, RESUMIDAS EN LO YA DESCRITO, DE CONFORMIDAD PARA LA ADMINISTRACIÓN.</h6> 
        <h6>11. ESTE FORMATO REMPLAZA A LAS CARTAS DE ASIGNACIÓN DE EQUIPOS ANTERIORES AL MES DE AGOSTO 2018.</h6> 

        <br>
        <br>
        <br>
        <br>
        <center>______________________</center>
        <center>Nombre y Firma</center>
     </div>
     </div>
     @yield('subcontent-01')
</div>
<!-- /.container-fluid -->

<!-- Pie de pagina fecah de impresion y nombre de archivo -->
<footer>
<script type="text/php">
 $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}';
 $date = 'Fecha de impresion : <?php echo $hoy = date("d-m-Y H:i:s");?>';
 $tittle = 'SIZ_Sistemas_FormatoResponsiva.pdf';
 $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
 $pdf->page_text(40, 740, $text, $font, 9);
 $pdf->page_text(420, 23, $date, $font, 9);
 $pdf->page_text(420, 740, $tittle, $font, 9);
 $sociedad = 'Sociedad: <?php echo $db ?>';
 $pdf->page_text(40, 23, $sociedad, $font, 9);


</script> 
</footer>





</div>
<!-- /#page-wrapper -->
</div>
</div>

</body>
</html>