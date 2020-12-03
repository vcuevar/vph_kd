<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ Session::token() }}"> 

    
    @yield('titulo')
    <!-- Styles -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  
    <!-- Material Design fonts -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">      
    
                                                         
    <script data-require="jquery" data-semver="3.3.1" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    
    <!-- Input date Safari -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
    <script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
    <script>
        webshims.setOptions('waitReady', false);
        webshims.setOptions('forms-ext', {type: 'date'});
        webshims.setOptions('forms-ext', {type: 'time'});
        webshims.polyfill('forms forms-ext');
    </script>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/fixedColumns.bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js" type="text/javascript"></script>
    {!! Html::script('assets/js/jquery.dataTables.yadcf.js') !!}
    <script src="{{ URL::asset('assets/DataTables/js/dataTables.tableTools.js')}}"></script>
    {!! Html::script('assets/js/dataTables.editor.min.js') !!}
    
   
    <script src="{{ URL::asset('assets/bootbox/bootbox.min.js' )}}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src=" https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap.min.js"></script>
            <script src=" https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css
    <![endif]-->
{!! Html::style('assets/css/bootstrap.css') !!}
{!! Html::style('assets/css/bootstrap-switch.min.css') !!}
{!! Html::style('assets/css/bootstrap-switch.css') !!}
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css" type="text/css">

<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap.min.css" type="text/css">
{!! Html::style('assets/css/sbadmin.css') !!}
{!! Html::style('assets/css/responsive.css') !!}
{!! Html::script('assets/js/headerdatatables.js') !!}
{!! Html::style('assets/css/jquery.datatables.yadcf.css') !!}
<!-- Bootstrap Date-Picker Plugin -->
{!! Html::script('assets/datepicker/js/js/bootstrap-datepicker.min.js') !!}
{!! Html::script('assets/datepicker/js/locales/bootstrap-datepicker.es.min.js') !!}
{!! Html::style('assets/datepicker/js/css/bootstrap-datepicker.min.css') !!}

<style>
.zrk-gris{
    background-color: #4c4c4c;
     color: white;
}
.zrk-teal{
    background-color: #167170;
    color: white;
}
.zrk-cafe{
    background-color: #665431;
    color: white;    
}
.zrk-tejelet{
    background-color: #54758d;
    color: white;
}
.zrk-olivo{
    background-color: #577345;
    color: white;
}
.zrk-silver{
    background-color: #AFB0AE;
    color: black;
}
.zrk-dimgray{
    background-color: #514d4a;
    color: white;
}
.zrk-gris-claro{
    background-color: #eeeeee;
    color: black;
}
.zrk-silver-w{
    background-color: #656565;
    color: white;
}
.container {
width: 100%;
height: 100%;
}
</style>


    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .side-nav>li>ul>li>ul>li>a {
            display: block;
            color: #b5b5b5;
            padding: 8px 26px 0% 25%;
            text-decoration: none;
        }
        /* Change the link color on hover */
        .side-nav>li>ul>li>ul>li>a:hover {
            background-color: #3e3e3e;
            color: white;
        }
        thead input {
        width: 100%;
    }
    .dataTables_processing {
        top: 64px !important;
        z-index: 11000 !important;
    }
    .dataTables_wrapper .dataTables_filter {
float: right;
text-align: right;
visibility: hidden;
}
li.dt-button.active a,
li.dt-button.active a:focus{
	color: #337ab6;
	background-color: transparent;
	font-weight: bold;
}
li.dt-button.active a::before{
	content: '✔ ';
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
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top"  style="background-color: #181510;" role="navigation">
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
                    
  position: absolute;
  top:  10px; 
  left: 10px;
    ">
    <img src="{{ asset('/images/lZRK2.png') }}" width="160px" height="35px"></div>
                    
                    </a>
                </div>
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav hidden-xs">
                @if (Auth::guest())
                     <a href="{{ url('/auth/login') }}" >Login</a>
                        <!--  <li><a href="url('/register') ">Register</a></li>  -->
                    @else
                    <li>
                    <a href="#"> {{Auth::user()->getPuesto()}}</a>
                    </li>
                    <li>
                    <a href="{!! url('Mod01_Produccion/Noticias') !!}"><i class="fa fa-bell"></i> <span class="badge badge-danger"> {{Auth::user()->getCountNotificacion()}}</span></a>
                    </li>  
                    <li class="dropdown">

                    
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


        

            </nav>    @yield('content')

        </div>
           



</body>
 
{!! Html::script('assets/js/bootstrap-switch.js') !!}

<!--<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>-->

{!! Html::script('assets/js/bootstrap.min.js') !!}
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->
{!! Html::script('assets/js/moment.min.js') !!}
{!! Html::script('assets/js/shortcut.js') !!}
<!-- Include Date Range Picker -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function (event) {
       // $.fn.dataTable.moment( 'HH:mm MMM D, YY' );
            $('.toggle').bootstrapSwitch();
            $('[data-toggle="tooltip"]').tooltip();
            $('.boot-select').selectpicker();
$('.dropdown-toggle').dropdown();
        @yield('script');
        setTimeout(function() {
    $('#infoMessage').fadeOut('fast');
}, 5000); // <-- time in milliseconds

    });
</script>
  
</html>