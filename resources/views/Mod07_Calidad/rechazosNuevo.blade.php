
@extends('home')
@section('homecontent')

        <div class="container" >

<!-- Page Heading -->
<div class="row">
<div class="col-lg-8 col-md-12 col-xs-12">
    <div class="hidden-lg"><br><br></div>
        <h3 class="page-header">
           Recepción de Materiales
            <small>Calidad <i data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-question-sign"  title="Ayuda Shift+F1"></i></small>
        </h3>
        <div class="visible-lg">
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard">  <a href="{!! url('home') !!}">Inicio</a></i>
            </li>
            <li>
                <i class= "fa fa-archive"> <a href="traslados">Rechazos</a></i>
            </li>
        </ol>
        </div>
    </div>

    
</div>
@include('partials.alertas')
<iframe class="col-md-12 " scrolling="" height="150%" width="90%"src="{!! url('getAutocomplete') !!}" frameborder="0"></iframe>
@endsection
@section ('homescript')
document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM07_01.pdf","_blank");
  } 
};
@endsection

<script>

 var Proveedores = <?php echo json_encode($var);?>;
 var Materiales = <?php echo json_encode($Material);?>;
 var Codeprov= <?php echo json_encode($CodeP);?>;
 var Nameprov= <?php echo json_encode($NameP);?>;
 var CodeMaterial= <?php echo json_encode($CodeMat);?>;
 var NameMaterial= <?php echo json_encode($NameM);?>;

</script>
  