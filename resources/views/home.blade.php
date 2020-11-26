@extends('app')
@section('titulo')
<title>{{ env('EMPRESA_NAME'). ' '}} @yield('page_name')</title>
@endsection
@section('content')

<?php
$bnd = null;
$bnd2 = null;
$index = 0;
        ?>
        
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav ">
            @foreach($actividades as $n1)
                <?php
                 $index = $index + 1;
                ?>

                    @if ($bnd == null)
                        <!-- primer elemento, se crea el primer modulo, el primer menu y la primera tarea, NO se cierran las etiquetas (puede que haya una tarea más) -->
                            <?php
                            $bnd = $n1->id_modulo;
                            $bnd2 = $n1->id_menu;
                            ?>

                            <li><a href="javascript:;" data-toggle="collapse"  data-target="#mo{{$n1->id_modulo}}" ><i class="fa fa-fw fa-dashboard"></i> {{$n1->modulo}} <i class="fa fa-fw fa-caret-down"></i></a>
                                <ul id="mo{{$n1->id_modulo}}" class="collapse ">
                                    <li><a href="javascript:;" data-toggle="collapse" data-target="#me{{$n1->id_menu}}"><i class="fa fa-fw fa-tasks"></i> {{$n1->menu}} <i class="fa fa-fw fa-caret-down"></i></a>
                                        <ul id="me{{$n1->id_menu}}" class="collapse">
                                            <a href="{!! url('home/'.$n1->ruta) !!}"><li>
                                                {{$n1->tarea}}
                                            </li></a>
                                        


                    @elseif($bnd == $n1->id_modulo)
                            <!-- si es el mismo modulo, pregunto si es el mismo menu -->
                            @if($bnd2 == $n1->id_menu)
                                <!-- si modulo y menu son iguales, solo agrego la tarea -->
                                    <a href="{!! url('home/'.$n1->ruta) !!}"><li>
                                        {{$n1->tarea}} 
                                    </li></a>   
                                @if($ultimo == $index)
                                  <!--cerrar menu y modulo -->
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                                @endif
                            @else <!-- si es otro menu -->
                                <?php
                                $bnd2 = $n1->id_menu;
                                ?>
                                <!--  cierro ese menu-->
                                        </ul>
                                    </li>
                                    <!-- abro otro menu nuevo y agrego la tarea -->
                                    <li><a href="javascript:;" data-toggle="collapse" data-target="#me{{$n1->id_menu}}"><i class="fa fa-fw fa-tasks"></i> {{$n1->menu}} <i class="fa fa-fw fa-caret-down"></i></a>
                                        <ul id="me{{$n1->id_menu}}" class="collapse">
                                            <a href="{!! url('home/'.$n1->ruta) !!}"><li>
                                                {{$n1->tarea}}
                                            </li></a>
                                    @if($ultimo == $index)
                                                    <!--cerrar menu y modulo -->
                                                    </ul>
                                                </li>
                                             </ul>
                                        </li>
                                    @endif
                            @endif
                    @else <!-- si no es el mismo modulo -->
                            <?php
                            $bnd = $n1->id_modulo;
                            $bnd2 = $n1->id_menu;
                            ?>
                             <!-- cierro el modulo anterior-->
                                          </ul>
                                      </li>
                                    </ul>
                                </li>

                        <li><a href="javascript:;" data-toggle="collapse" data-target="#mo{{$n1->id_modulo}}" ><i class="fa fa-fw fa-dashboard"></i> {{$n1->modulo}} <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="mo{{$n1->id_modulo}}" class="collapse ">
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#me{{$n1->id_menu}}"><i class="fa fa-fw fa-tasks"></i> {{$n1->menu}} <i class="fa fa-fw fa-caret-down"></i></a>
                                    <ul id="me{{$n1->id_menu}}" class="collapse">
                                        <a href="{!! url('home/'.$n1->ruta) !!}"><li>
                                            {{$n1->tarea}}
                                        </li></a>

                             @if($ultimo == $index)
                                                <!--cerrar menu y modulo -->
                                    </ul>
                                </li>
                            </ul>
                        </li>
                            @endif

                    @endif

@endforeach




@if(isset($isAdmin))
@if ($isAdmin)
<li>
    <a href="{!! url('/MOD00-ADMINISTRADOR') !!}">Administrador</a>
</li>
@endif
@endif


                @include('partials.section-navbar')
        </ul>
    </div>
    <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper2" ng-app='app'>
        <style>
            td {
                font-family: 'Helvetica';
                font-size: 12px;
            }
            th {
                font-family: 'Helvetica';
                font-size: 12px;
            }
            .btn-group>.btn {
                float: none;
            }
            .btn {//botones redondeados
                border-radius: 4px;
            }
            .btn-group>.btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
                border-radius: 4px;
            }
            .btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
                border-top-right-radius: 4px;
                border-bottom-right-radius: 4px;
            }
            .btn-group>.btn:last-child:not(:first-child),
            .btn-group>.dropdown-toggle:not(:first-child) {
                border-top-left-radius: 4px;
                border-bottom-left-radius: 4px;
            }
          
        </style>
        @yield('homecontent')

        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->
    </div>
    </div>
    <!-- /#wrapper -->
@endsection

@section('script')
@yield('homescript')
@endsection

