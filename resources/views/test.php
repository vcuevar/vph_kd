<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Fusion Confort' }}</title>
    <!-- Styles -->
<!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />-->
    <!--{!! Html::style('assets/css/Mod00_Admiistrador/bootstrap.daterangepicker.css') !!}
     Material Design fonts -->
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">-->
    {!! Html::style('assets/css/roboto.css') !!}
   <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->

    <!--<link rel="stylesheet" href="http://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">-->
    {!! Html::style('assets/css/jquery.dataTables.min.css') !!}
    
    <!--<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>-->
    {!! Html::script('assets/js/dataTables.min.js') !!}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

    <![endif]-->
{!! Html::style('assets/css/bootstrap.css') !!}
{!! Html::style('assets/css/bootstrap-switch.min.css') !!}
{!! Html::style('assets/css/bootstrap-switch.css') !!}

{!! Html::style('assets/css/font-awesome.css') !!}

{!! Html::style('assets/css/sb-admin.css') !!}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css">
    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .side-nav>li>ul>li>ul>li>a {
            display: block;
            color: #e8e8e8;
            padding: 8px 26px 0% 25%;
            text-decoration: none;
        }

        /* Change the link color on hover */
        .side-nav>li>ul>li>ul>li>a:hover {
            background-color: #555;
            color: white;
        }
     

    </style>


    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>



</head>
<body>
    <div id="app">
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top"  style="background-color: #3a3327;" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="{!! url('home') !!}" style="color: white">
                      <div  style=" display: inline-block;
    padding: 0px 8px;
    height: 28px;
    font-size: 18px;
    line-height: 28px;
    border-radius: 35px;
    background-color: #f1f1f1;" ><img src="{{ asset('/images/zain_b.png') }}" width="12px" height="23px" ></div>
                       &nbsp; SIZ
                    </a>
                </div>
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav hidden-xs">

                    <li class="dropdown">

                    @if (Auth::guest())
                     <a href="{{ url('/auth/login') }}" style="color: white">Login</a>
                        <!--  <li><a href="url('/register') ">Register</a></li>  -->
                    @else
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white"><i class="fa fa-user"></i>
                                &nbsp;{{ Auth::user()->firstName.' '.Auth::user()->lastName }} &nbsp;
                                <b class="caret"></b></a>


                        <ul class="dropdown-menu">
                            <li>
                                <a href="#"><i class="fa fa-fw fa-gear"></i> Configuración</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{!! url('/auth/logout') !!}"><i class="fa fa-fw fa-power-off"></i> Cerrar Sesión</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>


            @yield('content')


            </nav>
        </div>
            </div>



</body>


<script
src="https://code.jquery.com/jquery-3.2.1.js">
</script>
       
 {!! Html::script('assets/js/bootstrap-datepicker.js') !!}
 {!! Html::script('assets/js/bootstrap-datepicker.es.min.js') !!}
 

{!! Html::script('assets/js/bootstrap-switch.js') !!}
<!--<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>-->
{!! Html::script('assets/js/jquery.dataTables.min.js') !!}
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
{!! Html::script('assets/js/bootstrap.min.js') !!}
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->
{!! Html::script('assets/js/moment.min.js') !!}
<!-- Include Date Range Picker -->


<script>

$(document).ready(function (event) {
   $('.toggle').bootstrapSwitch();
   @yield('script')

   $( function() {
    $( "#datepicker" ).datepicker({
        format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
    locale: "es",
    
    });
       
  } );
 


  
    });


</script>

</html>
