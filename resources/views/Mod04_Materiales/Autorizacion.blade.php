@extends('home')
@section('homecontent')

<div class="container">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Autorización<small> Solicitud de Material #{{$id}}</small>
      </h3>
    </div>
  </div>

  <div class="col-md-12 ">
    @include('partials.alertas')
  </div>

  <style>
    td {
      white-space: nowrap;
      font-family: 'Helvetica';
      font-size: 80%;
    }

    th {
      font-family: 'Helvetica';
      font-size: 90%;
    }
  </style>
  <style>
    .table-scroll {
      position: relative;
    }

    .table-scroll thead th {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
    }

    .table-scroll tfoot,
    .table-scroll tfoot th,
    .table-scroll tfoot td {
      position: -webkit-sticky;
      position: sticky;
      bottom: 0;
      z-index: 4;
    }

    th:first-child {
      position: -webkit-sticky;
      position: sticky;
      left: 0;
      z-index: 2;

    }

    thead th:first-child,
    tfoot th:first-child {
      z-index: 5;
    }

    .pane {
      overflow: auto;
      max-height: 300px;
    }
  </style>
  @if (count($articulos_validos)>0)
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{url('home/2 AUTORIZACION') }}"><i class="fa fa-angle-left"></i>
          Atras</a>
        <a class="btn btn-success btn-sm" href="{{'update/'.$id}}"><i class="fa fa-send"></i> Enviar a Picking</a>

      </span>
      <!-- /.row -->

      <h4>Material Autorizado</h4>
      <div id="t1" class="col-md-12 table-scroll">
        <div class="pane">
          <table id="main-table" class="table table-striped main-table" style="margin-bottom:0px">
            <thead>
              <tr>

                <th scope="col">Código</th>
                <th scope="col">Descripción</th>
                <th scope="col">UM</th>
                <th scope="col">Cant. Requerida</th>
                <th scope="col">Cant. Autorizada</th>
                <th>Stock <div style="white-space: nowrap">APG-PA</div>
                </th>
                <th>Stock <div style="white-space: nowrap">AMP-ST</div>
                </th>
                <th scope="col">Total Disponible</th>
                <th scope="col">Destino</th>

                <th scope="col">Acciones</th>

              </tr>
            </thead>
            <tbody>

              @foreach ($articulos_validos as $art)
              <tr <?php ?>>

                <th scope="row" style="background-color:white" nowrap><a
                    href="{{url('home/DATOS MAESTROS ARTICULO/'.$art->ItemCode)}}"><i
                      class="fa fa-hand-o-right"></i> {{$art->ItemCode}}</a></th>
                <td scope="row">{{$art->ItemName}}</td>
                <td scope="row">{{$art->UM}}</td>
                <td scope="row">{{$art->Cant_Requerida}}</td>
                <td scope="row">{{$art->Cant_Autorizada}}</td>
                <td scope="row">{{number_format($art->APGPA, 2)}}</td>
                <td scope="row">{{number_format($art->AMPST, 2)}}</td>
                <td scope="row">{{number_format($art->Disponible, 2)}}</td>
                <td scope="row">{{$art->Destino}}</td>
                <td scope="row">
                  <a role="button" data-toggle="modal" data-target="#edit" data-id="{{$art->Id}}"
                    data-cantr="{{$art->Cant_Requerida}}" data-canta="{{$art->Cant_Autorizada}}"
                    class="btn btn-default"><i class="fa fa-pencil fa-lg" style="color:#007BFF"></i></a>
                  <a role="button" data-toggle="modal" data-target="#remove" data-id="{{$art->Id}}"
                    class="btn btn-default"><i class="fa fa-arrow-circle-o-down fa-lg" style="color:red"></i></a>
                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> <!-- /.row -->
  @endif
  @if (count($articulos_novalidos)>0)
  <div class="row">
    <div class="col-md-12">
      <span class="pull-right">
        <a class="btn btn-primary btn-sm" href="{{url('home/2 AUTORIZACION') }}"><i class="fa fa-angle-left"></i>
          Atras</a>
      </span>
      <h4>Material NO Autorizado</h4>
      <table>
        <thead>
          <tr>

            <th>Código</th>
            <th>Descripción</th>
            <th>Destino</th>
            <th>Cant. Requerida</th>
            <th>Cant. Disponible</th>
            <th>Regresar</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($articulos_novalidos as $art)
          <tr>

            <td>{{$art->ItemCode}}</td>
            <td>{{$art->ItemName}}</td>
            <td>{{$art->Destino}}</td>
            <td>{{$art->Cant_Requerida}}</td>
            <td>{{number_format($art->Disponible, 2)}}</td>
            <td><a @if ($art->Disponible < $art->Cant_Requerida)
                  {{'disabled'}}
                  @endif
                  role="button" href="{{'articulos/return/'.$art->Id}}" class="btn btn-default"><i
                    class="fa fa-arrow-circle-o-up fa-lg" style="color:royalblue"></i></a></td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div> <!-- /.row -->
  @endif
  <br>
  @if(strlen($comentario) > 0)

  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label for="comment">Observaciones:</label>
        <textarea class="form-control" rows="3" id="comment" readonly>{{$comentario}}</textarea>
      </div>
    </div>
  </div>
  @endif
  <!-- .Model quitar -->

  <div class="modal fade" id="remove" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

          {!! Form::open(['url' => 'home/AUTORIZACION/solicitud/articulos/remove', 'method' => 'POST']) !!}

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Quitar Artículo</h4>
          <div class="modal-body">

            <input type="hidden" id="articulo-id" name="articulo">
            <h4>¿Cuál es la razón por la que no está autorizando este material?</h4>
            <input type="radio" name="reason" value="Pendiente por Autorizar" checked>
            <i class="fa fa-hand-paper-o" aria-hidden="true"></i> Pendiente por Autorizar<br>
            <input type="radio" name="reason" value="Error Captura Solicitud" required>
            <i class="fa fa-trash" aria-hidden="true"></i> Error de Captura en Solicitud<br>
            <input type="radio" name="reason" value="Solicitud no Procede">
            <i class="fa fa-trash" aria-hidden="true"></i> La Solicitud no Procede<br>

          </div>
          <div class="modal-footer">

            <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Quitar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}


  <div class="modal fade" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">


          {!! Form::open(['url' => 'home/AUTORIZACION/solicitud/articulos/edit', 'method' => 'POST']) !!}
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Autorizar Otra Cantidad </h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="cantr">Cantidad Requerida</label>
              <input id="cantr" type="number" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
              <label for="canta">Cantidad Autorizada</label>
              <input id="canta" name="canta" type="number" class="form-control" min="0.1" step="0.01" required>
            </div>
            <div class="form-group col-md-12">
              <input type="hidden" id="articulo-id" name="articulo">
              <h5>Motivo por el que se autoriza otra cantidad :</h5>

              <input type="radio" name="reason" value="Error Captura Solicitud" required checked>
              Error de Captura en Solicitud
            </div>
          </div>


        </div>
        <br>
        <div class="modal-footer">

          <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
  @endsection

  @section('homescript')
  $('#remove').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes

  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods
  var modal = $(this)
  modal.find('#articulo-id').val(id)
  });
  $('#edit').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  var cantr = button.data('cantr')
  var canta = button.data('canta')
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods
  var modal = $(this)
  modal.find('#articulo-id').val(id)
  modal.find('#cantr').val(cantr)
  modal.find('#canta').val(canta)
  //modal.find('#canta').attr("max",cantr)

  });
  @endsection
  <script>
    document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
  
  </script>