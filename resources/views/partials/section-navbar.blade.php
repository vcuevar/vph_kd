<li class="hidden-md hidden-lg hidden-sm">
    <a href="javascript:;" data-toggle="collapse" data-target="#usuario"><i class="fa fa-fw fa-user"></i>

        @if ( Auth::check())
            {{ Auth::user()->firstName.' '.Auth::user()->lastName }}
        @else
            Invitado
        @endif
        <i class="fa fa-fw fa-caret-down">
       </i></a>
    <ul id="usuario" class="">
        <li>
            <a href="#"><i class="fa fa-fw fa-gear"></i> Configuración</a>
        </li>
        <li>
            <a href="{!! url('/auth/logout') !!}"><i class="fa fa-fw fa-power-off"></i> Cerrar Sesión</a>
        </li>
    </ul>
</li>