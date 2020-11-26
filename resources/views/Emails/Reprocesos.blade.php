<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Correo</title>
</head>
<body>
<style>

body { 
  font: 14px/1.4 Georgia, Serif; 
}


  /* 
  Generic Styling, for Desktops/Laptops 
  */
  table { 
    width: 50%; 
    border-collapse: collapse; 
  }
  /* Zebra striping */
  tr:nth-of-type(odd) { 
    background: #eee; 
  }
  th { 
    background: #ccc; 
    color: black; 
    font-weight: bold; 
    font-family: monospace;
  }
  td, th { 
    padding: 6px; 
    border: 1px solid #000; 
    text-align: left; 
      font-family:monospace;
  }
    p{
        color:#5499C7;
        font-family: verdana;
      font-size: 20px;  
    }
    pP{
        color: red;
        font-family: Verdana;
        font-size: 15px;
    }
</style>      
<p>• Se llevó a cabo el siguiente Reproceso</p>
                     
        <table border="1px" id="usuarios" class="table table-striped table-bordered table-condensed">
             <tr>
            <th>Fecha del reproceso</th>
              <td>{{$dt}}</td>
         </tr>
        <tr>
            <th>Usuario</th>
            <td>{{$No_Nomina}}&nbsp;&nbsp;{{$Nom_User}}</td> 
          
        </tr>
         <tr>
            <th>No.Orden</th>
             <td>{{$orden}}</td>
         </tr>
          <tr>
            <th>Cantidad</th>
             <td>{{$cant_r}}</td>
         </tr>
          <tr>
            <th>Estacion de Origen</th>  
               <td bgcolor="#ABEBC6">{{$Nombre_Actual}}</td>
         </tr>
          <tr>
            <th>Estacion de Destino</th>
              <td bgcolor="#F5B7B1">{{$Nombre_Destino}}</td>
         </tr>
          <tr>
            <th>Motivo</th>
               <td>{{$reason}}</td>
         </tr>
          <tr>
            <th>Descripcion de la falla</th>
              <td>{{$nota}}</td>
         </tr>
          <tr>
            <th>Autorizado por:</th>
              <td>{{$autorizo}}</td>
            </tr>
          
                          
        </table>
    <br>
 <pP>• El usuario que hizo este movimiento debe entregar el producto a la estacion de Destino y verificar que se acepte</pP>
        </body>
</html>
    