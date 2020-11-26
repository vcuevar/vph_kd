@extends('app')

@section('content')

@include('partials.menu-admin')


    <div >

        <div class="container" >

            <!-- Page Heading -->
            <div class="row">
                <div >
                    <div class="visible-xs"><br><br></div>
                    <h3 class="page-header col-lg-6.5 col-md-8 col-sm-7">
                          Detalle de Notificación
                    </h3>
                    
                       <div class= "col-lg-6.5 col-md-8 col-sm-7">
                        <div class="hidden-xs">
                       <div class="hidden-sm">
                       
                </div>
            </div>
            @include('partials.alertas')
              <!-- /.row -->
            {!! Form::open(['url' => 'admin/Mod_Noti2', 'method' => 'POST']) !!}
  <div class="form-group">
    <label for="exampleFormControlInput1">Autor</label>
    <input type="text" class="form-control" id="Autor" name="Autor" value="{{$Mod_Noti[0]->Autor}}"placeholder="Nombre del Autor">
    <input type="hidden" class="form-control" id="Id_Autor" name="Id_Autor" value="{{$Mod_Noti[0]->Id}}"  placeholder="Nombre del Autor">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Dirigida a:</label>
    <input type="text" class="form-control" id="Asunto" name="Asunto" value="{{$Mod_Noti[0]->Destinatario}}" placeholder="{{$Mod_Noti[0]->Destinatario}}" >
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Noticia</label>
    <textarea class="form-control" id="Descripcion"name="Descripcion" value="{{$Mod_Noti[0]->Descripcion}}" rows="3">{{$Mod_Noti[0]->Descripcion}}</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Enviar</button> 
  {!! Form::close() !!} 
</div>
@endsection

@section('script')





@endsection
