
@extends('home')
@section('homecontent')

        <div class="container" >

<!-- Page Heading -->
<div class="row">
<div class="col-lg-11 col-md-11 col-xs-11">
    <div class="hidden-lg"><br><br></div>
        <h3 class="page-header">
           Historial de Rechazos
            <small>Calidad</small>
        </h3>
        <div class="visible-lg">
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard">  <a href="{!! url('home') !!}">Inicio</a></i>
            </li>
            <li>
                <i class= "fa fa-archive"> <a href="#">Historial</a></i>
            </li>
        </ol>
        </div>
</div>
</div>
    <style>
    th{
        font-family:'Helvetica';
        font-size:90%;
       
        text-align:center;
    }
    td{
        font-family:'Helvetica';
        font-size:80%;
        
    }

    </style>

<div class="row">
        <div class="col-md-11">
                <table>
                    <thead>
                        <tbody>
                        <tr>
                        <th rowspan="2" align="center"  scope="col">Fecha de Revisión</th>
                        <th rowspan="2" style="width:20%;"align="center" scope="col">Proveedor</th>
                        <th rowspan="2" style="width:30%;" align="center"  scope="col">Descripcion de Material</th>
                        <th colspan="3" align="center" scope="col">Cantidad</th>
                        <th rowspan="2" align="center"  scope="col">Descripción Rechazo</th>
                         </tr>
                         <tr>
                         <th align="center">Aceptada</th>
                         <th align="center">Rechazada</th>
                         <th align="center">Revisada</th>
                           
                    </thead>
            @foreach($VerHistorial as $Rechazo)
            <tbody>
            <td>{{date("d-m-Y",strtotime($Rechazo->fechaRevision))}}</td>
            <td>{{$Rechazo->proveedorNombre}}</td>
            <td>{{$Rechazo->materialDescripcion}}</td><s></s>
            <td colspan="1">{{$Rechazo->cantidadAceptada}}&nbsp; {{$Rechazo->materialUM}}</td>
            <td colspan="1">{{$Rechazo->cantidadRechazada}}&nbsp; {{$Rechazo->materialUM}}</td>
            <td colspan="1">{{$Rechazo->cantidadRevisada}} &nbsp;{{$Rechazo->materialUM}}</td>
            <td>{{$Rechazo->DescripcionRechazo}}</td>

            </tbody>
            @endforeach
            </table>
                         </tr>
                        
                        </tbody>
                
            </div>
</div>

@endsection

  