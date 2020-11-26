@extends('home')
@section('homecontent')
        <div class="container" >

            <!-- Page Heading -->
            <div class="row">

                    <div class="visible-xs"><br><br></div>
                    <h4 class="page-header">
                      CALIDAD EN PRODUCCIÓN  
                      <small>Semana: {{date("W", strtotime("now"))}} <i data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-question-sign"  title="Ayuda Shift+F1"></i></small>   
                    </h4>
                    <div id="login" data-field-id="{{$enviado}}" >
                       <div class= "col-lg-6.5 col-md-12 col-sm-7 hidden-xs hidden-sm">

                        <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="{!! url('home') !!}">Inicio</a>
                        </li>
                    </ol>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                    @include('partials.alertas')
              </div>       
         </div>     
            {!! Form::open(['url' => 'home/CALIDAD POR DEPTO', 'method' => 'POST']) !!}           
            <div class="row">
                      <div class="form-group col-md-3">
                         <label>Corte</label>
                         <input name="cor_in" type="number" class="form-control" required min = "1" autofocus value="{{ old('cor_in') }}">
                        </div>
                      <div class="form-group col-md-3">
                          <label>Costura</label>
                          <input name="cos_in" type="number" class="form-control" required min="1" value="{{ old('cos_in') }}">
                        </div> 
                      <div class="form-group col-md-3">
                            <label>Cojineria</label>
                            <input name="coj_in" type="number" class="form-control" required min="1" value="{{ old('coj_in') }}">
                      </div>
                   </div>
                <div class="row">
                      <div class="form-group col-md-3">
                            <label>Tapiceria</label>
                            <input name="tap_in" type="number" class="form-control" required min="1" value="{{ old('tap_in') }}">
                      </div>
                      <div class="form-group col-md-3">
                           <label>Carpinteria</label>
                            <input name="car_in" type="number" class="form-control" required min="1" value="{{ old('car_in') }}">
                      </div>
                      <div class="form-group col-md-1">
                            <label>Semana</label>
                            <input name="semana_in" type="number" class="form-control" required autofocus min="1" max="{{date("W", strtotime("now"))}}" value="{{date("W", strtotime("now"))}}">
                      </div>           
                      <div class="form-group col-md-2">
                            <label>Año</label>
                            <input name="anio_in" type="number" class="form-control" readonly value="{{date("Y")}}">
                       </div>
                </div>
  <div class="row">
      <div class="col-md-0 col-md-offset-8">
<button type="submit" class="btn btn-primary">Agregar</button>
      </div>    
   </div>
{!! Form::close() !!}
         <!-- <div class="row">
            <div class="col-md-12">
             <div class="text-right">
            <a class="btn btn-danger btn-sm" href="bonosPdf" target="_blank"><i class="fa fa-file-pdf-o"></i>  Descarga PDF</a>
            </div>
            <h3>Producción</h3>
            <table>-->
                <br><br>

        <div class="row">
        <div class="col-md-11">
        <div class="table-responsive">
            <table  class="table table-striped header-fixed">
                    <thead class="thead-dark">
                                 <tr>
                                                    <th style="text-align: center;">Semana</th>
                                                    <th style="text-align: center;">Corte</th>
                                                    <th style="text-align: center;">Costura</th>
                                                    <th style="text-align: center;">Cojineria</th>
                                                    <th style="text-align: center;">Tapiceria</th>
                                                    <th style="text-align: center;">Carpinteria</th>      
                                                    <th style="text-align: center;">Año</th>                                              
                                  </tr>
                                  </thead>
                    <tbody> 
                       
                    @foreach ($Indatos as $Indato)
                                  <tr>
                                                        <th style="text-align: center;" scope="row">{{$Indato->Semana}}</th>
                                                        <td style="text-align: center;">{{$Indato->CorteIn}}</td>
                                                        <td style="text-align: center;"> {{$Indato->CostIn}}</td>
                                                        <td style="text-align: center;"> {{$Indato->CojiIn}}</td>
                                                        <td style="text-align: center;"> {{$Indato->TapIn}}</td>
                                                        <td style="text-align: center;"> {{$Indato->CarpIn}}</td>
                                                        <td style="text-align: center;"> {{$Indato->anio}}</td>                                                    
                                                    </tr>
                        @endforeach
              
                                                    </tbody>
                    </table>
            </div>  
        </div>  
            
                    </div>
                </div>
            </div>
@endsection
@section('homescript')
window.TrelloBoards.load(document, { allAnchors: false });
@section('homescript')
document.onkeyup = function(e) {
   if (e.shiftKey && e.which == 112) {
    window.open("ayudas_pdf/AyM07_03.pdf","_blank");
  } 
};
@endsection
@endsection
