<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo csrf_token() ?>">
    
    <title>Almacén</title>
    <style>
    html {
margin: 0;
}
body {
margin: 7mm 2mm 0mm 2mm;
}
        img { 
           display: none;
           margin-left: 45%;
           margin-right: auto;
           margin-top: -6pt;
        }
    </style>
</head>

<body>
    <!--Cuerpo o datos de la tabla-->
    <div id="content">
    <img src="data:image/png;base64, <?php echo base64_encode($CodigoQR) ?> ">

        <script type="text/php">
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 
            
            $codigos = '<?php echo $pKey ?>';
            $fecha = '<?php echo $fechar ?>';
            $FACTOR_UM = '<?php echo 'Cant: '.$cant.$separador. ' UM: '.$um; ?>';
            $itemName = '<?php echo $itemName; ?>';
            $cardName = '<?php echo $cardCode.' '.$cardName; ?>';
            $size = 12;
            $size2 = 5.5;
           
            $pdf->page_text(15, 45, $codigos, $font, $size); 
            $pdf->page_text(7, 7, $itemName, $font, $size2); 
            $pdf->page_text(7, 15, $cardName, $font, $size2); 
            $pdf->page_text(7, 22, $FACTOR_UM, $font, $size2); 
            $pdf->page_text(40, 77, $fecha, $font, $size2); 
        </script>
    </div>
</body>

</html>