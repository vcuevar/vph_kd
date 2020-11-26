<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
{!! Html::style('assets/css/bootstrap.css') !!}
{!! Html::style('assets/css/myMaterial-design.css') !!}
{!! Html::style('assets/css/site_global.css?crc=443350757.css') !!}
{!! Html::style('assets/css/index.css?crc=3185328.css') !!}



<!DOCTYPE html>
<html class="nojs html css_verticalspacer" lang="es-ES">
<head>

    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="generator" content="2017.0.0.363"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script type="text/javascript">
        // Update the 'nojs'/'js' class on the html node
        document.documentElement.className = document.documentElement.className.replace(/\bnojs\b/g, 'js');
        // Check that all required assets are uploaded and up-to-date
        if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["museutils.js", "museconfig.js", "webpro.js", "musewpslideshow.js", "jquery.museoverlay.js", "touchswipe.js", "jquery.watch.js", "require.js", "index.css"], "outOfDate":[]};
    </script>
    <title>Inicio</title>
    <link rel="shortcut icon" href="images/IconZrk.ico" type="image/x-icon" >
    <link rel="icon" href="imagen/IconoZain.png" sizes="32x32" ><link rel="icon"

</head>
<style> 
input[type=number]::-webkit-inner-spin-button{ 
  -webkit-appearance: none; 
  margin: 0; 
}

</style>
<body class="container-fluid" style=" background-image: url({{ URL::asset('images/fondo.jpg') }});
        background-repeat:no-repeat;
        background-size:cover;
        background-position:center;">

<div class="" id="page"><!-- group -->
    <div class="" id="slideshowu216"><!-- none box -->
        &nbsp;
    </div>
    <div class="row" ><!-- column -->
        <p align="center"  ><!-- svg -->
            <img class="svg hidden-xs" id="u196" src={{ URL::asset('images/svg-pegado-150982x45.svg') }} alt="" data-mu-svgfallback="/siz/public/images/svg%20pegado%20150982x45_poster_.png?crc=4279418901" width="200" height="200"
           />
            <img class="svg visible-xs" id="u196" src={{ URL::asset('images/svg-pegado-150982x45.svg') }}  alt="" data-mu-svgfallback="/siz/public/images/svg%20pegado%20150982x45_poster_.png?crc=4279418901" width="200" height="200"
            style="margin-top: -30%"/>

        <p style="margin-top: 5%" align="center"><!-- none box -->
            &nbsp;          
            <img class="hidden-xs"  alt="Bienvenido" src={{ URL::asset('images/u343-4.png') }} style="height: 7%"/><!-- rasterized frame -->
        </p>
        <div >
            <div >
                <div class="col-md-4 col-xs-8 col-xs-offset-2 col-md-offset-4">


                    @if (count($errors) > 0)
                        <div class="alert alert-danger text-center" style="opacity: .6; border-radius: 15px; color: white" role="alert">
                            @foreach($errors->getMessages() as $this_error)
                                <strong>Error  &nbsp; {{$this_error[0]}}</strong><br>
                            @endforeach
                        </div>
                    @elseif(Session::has('mensaje'))
                        <div class="alert alert-success text-center"style="opacity: .9; border-radius: 15px; color: white" role="alert">
                            {{ Session::get('mensaje') }}
                        </div>
                    @endif
                 

                    <div >


                        <form class="form-horizontal"  role="form" method="post" action="{{url('/auth/login')}}">
                            {{ csrf_field() }}


                            <div class="form-group label-floating">
                                <label class="control-label" for="id">No. Nómina:</label>
                                <div class="input-group">
                                    <input type="number" min="0" id="id" name="id" class="form-control" style="color: white" value="{{old('id')}}" required autofocus autocomplete="off">
                                    <span class="input-group-btn">

                                      </span>
                                </div>
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label" for="password">Contraseña:</label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control"  style="color: white" name="password" required>
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-fab btn-fab-mini">
                                          <i class="material-icons">send</i>
                                        </button>
                                      </span>
                                </div>
                            </div>


                        </form>
                    </div>


                </div>
            </div>
        </div>
        </p>
    </div>

</div>
<!-- Other scripts -->


</body>
</html>

<!-- Scripts -->


