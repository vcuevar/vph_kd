    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">

            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo">MOD-Administrador<i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="">

                        <li>
                            <a href="{!! url('admin/grupos/1') !!}"><i class="fa fa-fw fa-users"></i>   Gestión de Grupos</a>
                        </li>
                    <li>
                        <a href="{!! url('admin/users') !!}"><i class="fa fa-fw fa-user"></i> Usuarios SIZ</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#inventario">Gestion de Inventario   <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="inventario" class="">
                            <li>
                                <a href="{!! url('admin/inventario') !!}"><i class="fa fa-archive"></i> Inventario</a>
                            </li>
                            <li>
                                <a href="{!! url('admin/monitores') !!}"><i class="fa fa-desktop"></i> Monitores</a>
                            </li>
                            <li>
                                <a href="{!! url('admin/inventarioObsoleto') !!}"><i class="fa fa-recycle"></i> Inventario Obsoleto</a>
                            </li>
                        </ul>   
                    </li>
                    <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#inventario">Notificaciones<i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="inventario" class="">
                            <li>
                                <a href="{!! url('admin/Nueva') !!}"><i class="glyphicon glyphicon-pencil"></i> Nueva</a>
                            </li>
                            <li>
                                <a href="{!! url('admin/Notificaciones') !!}"><i class="glyphicon glyphicon-list-alt"></i> Log Notificaciones</a>
                            </li>
                            <li>
                                <a href="{!! url('admin/emails') !!}"><i class="fa fa-envelope"></i> Conf. Correo</a>
                            </li>
                         
                        </ul>  
                    </li>
                </ul>
            </li>
            @include('partials.section-navbar')
        </ul>
    </div>
    <!-- /.navbar-collapse -->
    </nav>