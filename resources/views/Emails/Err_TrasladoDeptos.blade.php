<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIZ</title>
<style>
  table.paleBlueRows {
  font-family: Arial, Helvetica, sans-serif;
  text-align: center;
  border: 1px solid black;
  width: 94%;
  border-collapse: collapse;
  }
  table.paleBlueRows td, table.paleBlueRows th {
    border: 1px solid black;
  }
  table.paleBlueRows tbody td {
  font-size: 12px;
  }
  table.paleBlueRows tr:nth-child(even) {
  background: #D0E4F5;
  }
  table.paleBlueRows thead {
  background: #0B6FA4;
  }
  table.paleBlueRows thead th {
  font-size: 13px;
  font-weight: bold;
  color: #FFFFFF;
  text-align: center;
  }
</style>      
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <h3>Problema con la Entrega - Artículo #{{$art->ItemCode}}</h3>
       
      <table class="paleBlueRows">
        <thead>
          <tr>
            <th>Num Entrega</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>UM</th>
            <th>Cant de Entrega</th>          
            <th>Cant real a Recibir</th>            
            <th>Motivo Cantidad menor</th>            
          </tr>
        </thead>
        <tbody>
         
         
            <tr>                 
            <td>{{$art->Id_Solicitud}}</td>
            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->InvntryUom}}</td>
            <td>{{$art->Cant_PendienteA}}</td>
            <td>{{number_format($art->Cant_ASurtir_Origen_A , 2)}}</td>          
            <td>{{$art->Razon_CantMenor}}</td>
          </tr>
     
         
        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
</div>
    

      
 
</body>
</html>
    