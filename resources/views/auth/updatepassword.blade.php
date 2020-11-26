<!-- Styles -->
<!-- Material Design fonts -->
<!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">-->
{!! Html::style('assets/css/family=reboto.css') !!}

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
{!! Html::style('assets/css/bootstrap.css') !!}
{!! Html::style('assets/css/myMaterial-design.css') !!}
{!! Html::style('assets/css/ripples.css') !!}
{!! Html::style('assets/css/font-awesome.css') !!}
{!! Html::style('assets/css/site_global.css?crc=443350757.css') !!}
{!! Html::style('assets/css/index.css?crc=3185328.css') !!}

<!-- Scripts -->
<!-- 
number bindec ( string $binary_string )

$str = 'In My Cart : 11 12 items';
preg_match_all('!\d+!', $str, $matches);
print_r($matches);

-->

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
    <link rel="icon" href="imagen/IconoZain.png" sizes="32x32" ><link rel="icon">
    <style>
    p.courier{
        font-family:Verdana, Geneva, sans-serif;
    }
    </style>
</head>
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

        <div >
            <div >
                <div class="col-md-4 col-xs-8 col-xs-offset-2 col-md-offset-4">


                    @if (count($errors) > 0)
                        <div class="alert alert-danger text-center" style="opacity: .6; border-radius: 15px; color: white" role="alert">
                            @foreach($errors->getMessages() as $this_error)
                                <strong>Error  &nbsp; {{$this_error[0]}}</strong><br>
                            @endforeach
                        </div>
                    @endif
                    <div class="col-md-12 ">
                        @include('partials.alertas')                        
                    </div>

               
                    <div>                                                                                                    
                        {!! Form::open(['url' => 'passwordUpdate', 'method' => 'POST']) !!}                    
                        <input type="text" min="0" id="userId" name="userId" value="{{Auth::user()->U_EmpGiro}}" hidden>
                                                <font size="5" style="color: white"><p align="center" class="courier">Hola:</p></font> <br>
                        <font size="5" style="color: white"><p align="center" class="courier">{{Auth::user()->firstName}}</p></font> <br>
                            <div class="form-group label-floating">
                                <label class="control-label" for="password">Escribe tu contraseña:</label>
                                <div class="input-group">
                                    <input type="password" min="0" id="password" name="password" class="form-control" style="color: white" value="{{old('id')}}" required autofocus
                                    data-toggle="tooltip" data-placement="bottom" maxlength="20"> 
                                </input><span class="input-group-btn">
                                      </span>
                                </div>
                            </div>
                            <div class="form-group label-floating">
                                <label class="control-label" for="password_confirmation">Confirma contraseña:</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"  style="color: white"  required maxlength="20">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-fab btn-fab-mini">
                                          <i class="material-icons">send</i>
                                        </button>
                                      </span>
                                </div>
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        </p>
    </div>

</div>
<!-- Other scripts -->

<!-- RequireJS script -->
<script src="siz/public/js/require.js?crc=244322403" type="text/javascript" async data-main="siz/public/js/museconfig.js?crc=36584860" onload="if (requirejs) requirejs.onError = function(requireType, requireModule) { if (requireType && requireType.toString && requireType.toString().indexOf && 0 <= requireType.toString().indexOf('#scripterror')) window.Muse.assets.check(); }" onerror="window.Muse.assets.check();"></script>
</body>
</html>

<!-- Scripts -->
{!! Html::script('assets/js/jquery.min.js') !!}
{!! Html::script('assets/js/bootstrap.js') !!}
{!! Html::script('assets/js/material.js') !!}
{!! Html::script('assets/js/ripples.js') !!}
<script>
    $(document).ready(function (event) {

        $.material.init();
    });
</script>

