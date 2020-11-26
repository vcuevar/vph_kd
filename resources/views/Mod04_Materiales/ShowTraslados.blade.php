@extends('home')
@section('homecontent')

<style>
  th {
    font-size: 12px;
  }

  td {
    font-size: 11px;
  }

  th,
  td {
    white-space: nowrap;
  }



  .dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
    visibility: visible;
  }
</style>
<div class="container">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-md-12" style="margin-bottom: -20px;">
      <div class="visible-xs visible-sm"><br><br></div>
      <h3 class="page-header">
        Traslados <small>Solicitudes</small>
      </h3>
    </div>
  </div>

  <style>
    td {
      font-family: 'Helvetica';
      font-size: 80%;
    }

    th {
      font-family: 'Helvetica';
      font-size: 90%;
    }
  </style>
  <!-- /.row -->
  <div class="row">
    <div class="col-md-12 ">
      @include('partials.alertas')
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">
          <i class="fa fa-file-pdf-o"></i> Impresión de Traslados
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a role="button" data-toggle="modal" data-target="#pdfsol">Con Num. Solicitud</a></li>
          <li><a role="button" data-toggle="modal" data-target="#pdftraslado">Con Num. Traslado SAP</a></li>
        </ul>
      </div>

    </div><!-- /.col-md-12 -->
  </div><!-- /.row -->
  <div class="">
    <div class="">
      <div class="">
        <table id="tsolicitudes" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>#Folio</th>
              <th>Usuario</th>
              <th>Area</th>
              <th>Fecha de Creación</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>

      </div>
    </div>

  </div>
  @yield('subcontent-01')
</div>

<div class="modal fade" id="pdftraslado" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">

        {!! Form::open(['target' => '_blank', 'url' => 'home/PDF/traslado', 'method' => 'POST']) !!}

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Impresión de Traslados</h4>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <label for="transfer">Número de Traslado SAP</label>
              <input id="transfer" name="transfer" type="number" class="form-control" min="1" step="1" required
                autofocus>
            </div>

          </div>
        </div>
        <div class="modal-footer">

          <button type="submit" class="btn btn-primary">PDF</button>
          <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
{!! Form::close() !!}

<div class="modal fade" id="pdfsol" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">

        {!! Form::open(['url' => 'home/PDF/solicitud', 'method' => 'POST']) !!}

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Impresión de Traslados</h4>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <label for="sol">Número de Solicitud</label>
              <input id="sol" name="sol" type="number" class="form-control" min="1" step="1" required autofocus>
            </div>

          </div>
        </div>
        <div class="modal-footer">

          <button type="submit" class="btn btn-primary">PDF</button>
          <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
{!! Form::close() !!}
@endsection

@section('script')



var table = $('#tsolicitudes').DataTable({
dom: 'frtip',
"order": [[ 1, "desc" ]],
orderCellsTop: true,
scrollX: true,
paging: true,
processing: true,
responsive: true,
deferRender: true,
ajax: {
url: '{!! route('datatables.solicitudesTraslados') !!}',
data: function () {

}
},
columns: [
{ data: 'folio'},
{ data: 'user_name'},
{ data: 'area'},
{ data: 'FechaCreacion',
render: function(data){
if (data === null){return data;}
var d = new Date(data);
return moment(d).format("DD-MM-YYYY HH:mm:ss");
}
},
],
"language": {
"url": "{{ asset('assets/lang/Spanish.json') }}",
},
columnDefs: [
],
//revision

});
@endsection

<script>
  document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM00_00.pdf","_blank");
  }
  } 
</script>