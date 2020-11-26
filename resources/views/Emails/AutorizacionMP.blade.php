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
        <h3>Autorización de Material #{{$id}}</h3>
        <h4>Requerido por: {{ $nombreCompleto }}</h4>
      <table class="paleBlueRows">
        <thead>
          <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>UM</th>    
            <th>Cant Solicitada</th>          
            <th>Cant Autorizada</th>    
            <th>Destino</th>    
            <th>Observación de Autorización </th>
                   
          </tr>
        </thead>
        <tbody>
         
         @foreach($arts as $art)
            <tr>     
            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->InvntryUom}}</td>
            <td>{{number_format($art->Cant_Requerida, 2)}}</td>          
            <td>{{number_format($art->Cant_Autorizada, 2)}}</td>
            <td>{{$art->Destino}}</td>
            @if (is_null($art->Razon_NoAutorizado) && is_null($art->Razon_AutorizaCantMenor))
                <td>-</td>
            @else
                @if(is_null($art->Razon_NoAutorizado))
                <td>{{$art->Razon_AutorizaCantMenor}}</td>
                @else
                <td>{{$art->Razon_NoAutorizado}}</td>
                @endif
            @endif
          </tr>
        @endforeach
         
        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->

</div>
    

      
 
</body>
</html>
    