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
        <h3>Traslado SIZ #{{$id}}</h3>
        <h4>Requerido por: {{ Auth::user()->firstName.' '.Auth::user()->lastName }}</h4>
        <h4>Almacén Origen: {{$origen}}</h4>
      <table class="paleBlueRows">
        <thead>
          <tr>
            <th>#</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>UM</th>
            <th>Destino</th>
          
          </tr>
        </thead>
        <tbody>
          <?php $i=0;?>
          @foreach ($arts as $art)
            <tr>
            <?php $i++;?>
            <td>{{ $i }}</td>
            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->CA}}</td>
            <td>{{$art->UM}}</td>
            <td>{{$art->Destino}}</td>
          </tr>
          @endforeach
         
        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  <br>
  @if(strlen($comentario) > 0)
                    <div>
                    <table class="mytable">
                        <thead>
                        <th style="width:10%;">Observaciones del Traslado:</th>
                        </thead>
                        <tbody>
                        <tr>
                        <td style="width:90%;" class="text-left" >{{$comentario}}</td>
                        </tr>
                        </tbody>
                    </table></div>
   @endif
</div>
    

      
 
</body>
</html>
    