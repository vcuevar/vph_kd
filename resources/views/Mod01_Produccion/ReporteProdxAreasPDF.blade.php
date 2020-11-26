<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Producción</title>
    <style>
        /*
                Generic Styling, for Desktops/Laptops
                */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: arial;
        }

        th {
            color: white;
            font-weight: bold;
            color: white;
            font-family: 'Helvetica';
            font-size: 65%;
            background-color: #333333;
        }

        td {
            font-family: 'Helvetica';
            font-size: 60%;            
        }

        img {
            display: block;     
            margin-top:3.8%;                    
            width: 670;
            height: 45;
            position: absolute;
            right: 2%;
        }

        h5 {
            font-family: 'Helvetica';
            margin-bottom: 15;            
        }     

        .fz{
            font-size: 100%;
            margin-top: 7px;
        }

        #header {
            position: fixed;
            margin-top: 2px;
        
        }

        #content {         
            position: relative;
            top: 16%
        }
        table, th, td {
            text-align: center;
            border: 1px solid black;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
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
        .zrk-dimgray{
            background-color: #514d4a;
            color: white;
        }
        .zrk-gamal{
            background-color: #78866B; color: white;
        }
        .zrk-gamalobs{
            background-color: #2352A0; color: white;
        }
    </style>
</head>
<body>
    <div id="header">
        <img src="images/Mod01_Produccion/siz1.png">
        <!--empieza encabezado, continua cuerpo-->
        <table border="1px" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <td colspan="6" align="center" bgcolor="#fff">
                        <div class="fz"><b>{{env('EMPRESA_NAME')}}, S.A de C.V.</b><br>
                            <b>Mod01 - Producción</b></div>
                        <h2>Reporte de Producción x Areas</h2>
                        <h3><b>Del:</b> {{\AppHelper::instance()->getHumanDate($fi)}} <b>al:</b> {{\AppHelper::instance()->getHumanDate($ff)}}</h3>
                        
                    </td>

                </tr>
            </thead>
        </table>
       
    </div>
    <!--Cuerpo o datos de la tabla-->
    <div id="content">
       
<div >

        <!-- Page Heading -->        
        <div class="row">
            <div class="col-md-11">
                
                <h5>Reporte de Fundas</h5>
            </div>
            <div id="t1" class="col-md-11 table-scroll">
                <div class="pane">
                    <table id="main-table" class="table table-striped main-table" style="margin-bottom:8px">
    
                        <thead class="table-condensed">
                            <tr class="encabezado">
                                <th scope="col">Fecha</th>
                                <th class="zrk-teal" scope="col">Planeación</th>
                                <th class="zrk-teal" scope="col">Preparado Entrega</th>
                                <th class="zrk-tejelet" scope="col">Anaquel Corte</th>
                                <th class="zrk-olivo" scope="col">Corte Piel</th>

                                <th class="zrk-olivo" scope="col">Inspección Corte</th>
                                <th class="zrk-olivo" scope="col">Pegado Costura</th>
                                <th class="zrk-tejelet" scope="col">Anaquel Costura</th>
                                <th class="zrk-cafe" scope="col">Costura Recta</th>
                                <th class="zrk-cafe" scope="col">Armado de Costura</th>

                                <th class="zrk-cafe" scope="col">Pespunte o Doble</th>
                                <th class="zrk-cafe" scope="col">Terminado de Costura</th>
                                <th class="zrk-cafe" scope="col">Inspección Costura</th>
                             
                            </tr>
                        </thead>
                        <tbody> <?php
                              $sum_1 = 0;
                              $sum_2 = 0;
                              $sum_3 = 0;
                              $sum_4 = 0;
                              $sum_5 = 0;
                              $sum_6 = 0;
                              $sum_7 = 0;
                              $sum_8 = 0;
                              $sum_9 = 0;
                              $sum_10 = 0;
                              $sum_11 = 0;
                              $sum_12 = 0;
                            
                            ?>
                            @if(count($data)>0)                             
                           
                            @foreach ($data as $rep)
                             <?php
                              $sum_1 = $sum_1  + $rep->VST100;
                              $sum_2 = $sum_2  + $rep->VST106;
                              $sum_3 = $sum_3  + $rep->VST109;
                              $sum_4 = $sum_4  + $rep->VST112;
                              $sum_5 = $sum_5  + $rep->VST115;
                              $sum_6 = $sum_6  + $rep->VST118;
                              $sum_7 = $sum_7  + $rep->VST121;
                              $sum_8 = $sum_8  + $rep->VST124;
                              $sum_9 = $sum_9  + $rep->VST127;
                              $sum_10 = $sum_10 + $rep->VST130;
                              $sum_11 = $sum_11 + $rep->VST133;
                              $sum_12 = $sum_12 + $rep->VST136;
                             
                             ?>
                            <tr>
                                <th id="f0" scope="row" class="table-condensed" style="min-width:200px">
                                    {{\AppHelper::instance()->getHumanDate($rep->Fecha)}}
                                </th>
                                <td id="f1" scope="row">
                                    {{number_format($rep->VST100,2)}}
                                </td>
                                <td id="f2" scope="row">
                                    {{number_format($rep->VST106,2)}}
                                </td>
                                <td id="f3" scope="row">
                                    {{number_format($rep->VST109,2)}}
                                </td>
                                <td id="f4" scope="row">
                                    {{number_format($rep->VST112,2)}}
                                </td>
                                <td id="f5" scope="row">
                                    {{number_format($rep->VST115,2)}}
                                </td>
                                <td id="f6" scope="row">
                                    {{number_format($rep->VST118,2)}}
                                </td>
                                <td id="f7" scope="row">
                                    {{number_format($rep->VST121,2)}}
                                </td>
                                <td id="f8" scope="row">
                                    {{number_format($rep->VST124,2)}}
                                </td>
                                <td id="f9" scope="row">
                                    {{number_format($rep->VST127,2)}}
                                </td>
                                <td id="f10" scope="row">
                                    {{number_format($rep->VST130,2)}}
                                </td>
                                <td id="f11" scope="row">
                                    {{number_format($rep->VST133,2)}}
                                </td>
                                <td id="f12" scope="row">
                                    {{number_format($rep->VST136,2)}}
                                </td>
                               
                            </tr>
                            @endforeach @endif
                        </tbody>
                        <tfoot>
                            <tr class="total1">
                                <th scope="row" class="table-condensed">SUMA DE FUNDAS:</th>
                                <td class="zrk-gamal">{{number_format($sum_1 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_2 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_3 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_4 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_5 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_6 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_7 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_8 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_9 , 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_10, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_11, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_12, 2)}}</td>
                              
                            </tr>
                            @if (strtotime($ff) == strtotime(date("Y-m-d"))) 
                                <tr  class="encabezado">
                                    <th scope="row" class="table-condensed">INVENTARIO:</th>
                                    @for ($i = 0; $i < 12; $i++)                                                    
                                    <td scope="row" class="zrk-gamalobs">
                                        {{number_format($data2[$i]->SVS,2)}}
                                    </td>                             
                                @endfor                                     
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                    <table class="table table-striped main-table" style="margin-bottom:0px">
                    
                    </table>
                
                </div>
            </div>
            <div id="t1" class="col-md-11 table-scroll">
                <div class="pane">
                    <table id="main-table" class="table table-striped main-table" style="margin-bottom:0px">
    
                        <thead class="table-condensed">
                            <tr class="encabezado">
                                <th scope="col" style="min-width:150px;">Fecha</th>                                
                                <th class="zrk-cafe" scope="col">Series Incompletas</th>
                                <th class="zrk-teal" scope="col">Pegado Delcrón</th>

                                <th class="zrk-teal" scope="col">Llenado Cojin</th>
                                <th class="zrk-teal" scope="col">Acojinado</th>
                                <th class="zrk-tejelet" scope="col">Fundas Terminadas</th>
                                <th class="zrk-tejelet" scope="col">Kitting</th>
                                <th class="zrk-olivo" scope="col">Enfundado Tapiz</th>

                                <th class="zrk-olivo" scope="col">Tapizar</th>
                                <th class="zrk-olivo" scope="col">Armado de Tapiz</th>
                                <th class="zrk-tejelet" scope="col">Empaque</th>
                                <th class="zrk-tejelet" scope="col">Inspeción Final</th>
                            </tr>
                        </thead>
                        <tbody> 
                             <?php                             
                              $sum_13 = 0;
                              $sum_14 = 0;
                              $sum_15 = 0;
                              $sum_16 = 0;
                              $sum_17 = 0;
                              $sum_18 = 0;
                              $sum_19 = 0;
                              $sum_20 = 0;
                              $sum_21 = 0;
                              $sum_22 = 0;
                              $sum_23 = 0;
                            ?>
                            @if(count($data)>0)                             
                          
                            @foreach ($data as $rep)
                             <?php
                         
                              $sum_13 = $sum_13 + $rep->VST139;
                              $sum_14 = $sum_14 + $rep->VST140;
                              $sum_15 = $sum_15 + $rep->VST142;
                              $sum_16 = $sum_16 + $rep->VST145;
                              $sum_17 = $sum_17 + $rep->VST148;
                              $sum_18 = $sum_18 + $rep->VST151;
                              $sum_19 = $sum_19 + $rep->VST154;
                              $sum_20 = $sum_20 + $rep->VST157;
                              $sum_21 = $sum_21 + $rep->VST160;
                              $sum_22 = $sum_22 + $rep->VST172;
                              $sum_23 = $sum_23 + $rep->VST175;
                             ?>
                            <tr>
                                <th id="f0" scope="row" class="table-condensed">
                                    {{\AppHelper::instance()->getHumanDate($rep->Fecha)}}
                                </th>
                             
                                <td id="f13" scope="row">
                                    {{number_format($rep->VST139,2)}}
                                </td>
                                <td id="f13" scope="row">
                                    {{number_format($rep->VST140,2)}}
                                </td>
                                <td id="f13" scope="row">
                                    {{number_format($rep->VST142,2)}}
                                </td>
                                <td id="f14" scope="row">
                                    {{number_format($rep->VST145,2)}}
                                </td>
                                <td id="f15" scope="row">
                                    {{number_format($rep->VST148,2)}}
                                </td>
                                <td id="f16" scope="row">
                                    {{number_format($rep->VST151,2)}}
                                </td>
                                <td id="f17" scope="row">
                                    {{number_format($rep->VST154,2)}}
                                </td>
                                <td id="f18" scope="row">
                                    {{number_format($rep->VST157,2)}}
                                </td>
                                <td id="f19" scope="row">
                                    {{number_format($rep->VST160,2)}}
                                </td>
                                <td id="f20" scope="row">
                                    {{number_format($rep->VST172,2)}}
                                </td>
                                <td id="f21" scope="row">
                                    {{number_format($rep->VST175,2)}}
                                </td>
                            </tr>
                            @endforeach @endif
                        </tbody>
                        <tfoot>
                            <tr class="total1">
                                <th scope="row" class="table-condensed">SUMA DE FUNDAS:</th>                               
                                <td class="zrk-gamal">{{number_format($sum_13, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_14, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_15, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_16, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_17, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_18, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_19, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_20, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_21, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_22, 2)}}</td>
                                <td class="zrk-gamal">{{number_format($sum_23, 2)}}</td>
                            </tr>
                            @if (strtotime($ff) == strtotime(date("Y-m-d"))) 
                                <tr  class="encabezado">
                                    <th scope="row" class="table-condensed">INVENTARIO:</th>
                                
                                    @for ($i = 12; $i < count($data2); $i++)                                                    
                                    <td scope="row" class="zrk-gamalobs">
                                        {{number_format($data2[$i]->SVS,2)}}
                                    </td>                             
                                @endfor                                           
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                    <table class="table table-striped main-table" style="margin-bottom:0px">
                    
                    </table>
                
                </div>
            </div>
            <!-- /.col-md-8 -->
        </div>
        <!-- /.row -->
        <div class="row">
                <div class="col-md-11">
                    <h5>Reporte de Cascos</h5>
                </div>
                <div id="t2" class="col-md-11 table-scroll">
                    <div class="pane">
                        <table id="main-table" class="table table-striped main-table" style="margin-bottom:0px">
        
                            <thead class="table-condensed">
                                <tr class="encabezado">
                                    <th scope="col" style="min-width:150px;">Fecha</th>
                                    <th scope="col">Planeación</th>
                                    <th scope="col">Habilitado</th>
                                    <th scope="col">Armado</th>
                                    <th scope="col">Tapado</th>
                                    <th scope="col">Pegado</th>
                                    <th scope="col">Inspección Casco</th>                              
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data3)>0) 
                                <?php
                                    $sum_1 = 0;
                                    $sum_2 = 0;
                                    $sum_3 = 0;
                                    $sum_4 = 0;
                                    $sum_5 = 0;
                                    $sum_6 = 0;
                                ?>
                                @foreach ($data3 as $rep3)
                                <?php
                                    $sum_1 = $sum_1  + $rep3->VST400;
                                    $sum_2 = $sum_2  + $rep3->VST403;
                                    $sum_3 = $sum_3  + $rep3->VST406;
                                    $sum_4 = $sum_4  + $rep3->VST409;
                                    $sum_5 = $sum_5  + $rep3->VST415;
                                    $sum_6 = $sum_6  + $rep3->VST418;
                                ?>
                                <tr>
                                    <th id="f0" scope="row" class="table-condensed">
                                        {{\AppHelper::instance()->getHumanDate($rep3->Fecha)}}
                                    </th>
                                    <td id="f1" scope="row">
                                        {{number_format($rep3->VST400,2)}}
                                    </td>
                                    <td id="f2" scope="row">
                                        {{number_format($rep3->VST403,2)}}
                                    </td>
                                    <td id="f3" scope="row">
                                        {{number_format($rep3->VST406,2)}}
                                    </td>
                                    <td id="f4" scope="row">
                                        {{number_format($rep3->VST409,2)}}
                                    </td>
                                    <td id="f5" scope="row">
                                        {{number_format($rep3->VST415,2)}}
                                    </td>                               
                                    <td id="f6" scope="row">
                                        {{number_format($rep3->VST418,2)}}
                                    </td>                               
                                </tr>
                                @endforeach @endif
                            </tbody>
                            <tfoot>
                                <tr class="total2">
                                    <th scope="row" class="table-condensed">SUMA DE CASCOS:</th>
                                    <td class="zrk-gamal">{{number_format($sum_1, 2) }}</td>
                                    <td class="zrk-gamal">{{number_format($sum_2, 2) }}</td>
                                    <td class="zrk-gamal">{{number_format($sum_3, 2) }}</td>
                                    <td class="zrk-gamal">{{number_format($sum_4, 2) }}</td>
                                    <td class="zrk-gamal">{{number_format($sum_5, 2) }}</td>
                                    <td class="zrk-gamal">{{number_format($sum_6, 2) }}</td>                                
                                </tr>
                                  @if (strtotime($ff) == strtotime(date("Y-m-d"))) 
                                        <tr  class="encabezado">
                                                <th scope="row" class="table-condensed">INVENTARIO:</th>
                                                @foreach ($data7 as $item)                          
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->P400,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->H403,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->A406,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T409,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->PR415,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                       {{number_format($item->I418, 2)}}
                                                    </td>                             
                                                @endforeach                                          
                                            </tr>
                                    @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.col-md-8 -->
            </div>
            <!-- /.row -->
            <div class="row">
                    <div class="col-md-11">
                        <h5>Movimientos de Cascos</h5>
                    </div>
                    <div id="t3" class="col-md-11 table-scroll">
                        <div class="pane">
                            <table id="main-table" class="table table-striped main-table" style="margin-bottom:0px">
            
                                <thead class="table-condensed">
                                    <tr class="encabezado">
                                        <th scope="col" style="min-width:150px;">Fecha</th>
                                        <th scope="col">Aduana Carpintería</th>
                                        <th scope="col">Almacén</th>
                                        <th scope="col">Camión</th>
                                        <th scope="col">Kitting</th>
                                        <th scope="col">Tapiz</th>
                                        <th scope="col">Ajuste</th>                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data4)>0)
                                    <?php
                                    $sum_1 = 0;
                                    $sum_2 = 0;
                                    $sum_3 = 0;
                                    $sum_4 = 0;
                                    $sum_5 = 0;
                                    $sum_6 = 0;
                                 
                                ?>
                                   
                                @foreach ($data4 as $rep4)
                                    <?php
                                    $sum_1 = $sum_1  + $rep4->S_CARP;
                                    $sum_2 = $sum_2  + $rep4->S_TRAS;
                                    $sum_3 = $sum_3  + $rep4->S_KITT;
                                    $sum_4 = $sum_4  + $rep4->S_TAPI;
                                    $sum_6 = $sum_6  + $rep4->Consumo;
                                    $sum_5 = $sum_5  + ((($rep4->S_TAPI + $rep4->S_KITT + $rep4->S_TRAS + $rep4->S_CARP)*-1) + $rep4->S_VST);
                                    
                                    ?> <tr>
                                        <th id="f0" scope="row" class="table-condensed">
                                            {{\AppHelper::instance()->getHumanDate($rep4->Fecha)}}
                                        </th>
                                        <td id="f1" scope="row">
                                            {{number_format($rep4->S_CARP,2)}}
                                        </td>
                                        <td id="f2" scope="row">
                                            {{number_format($rep4->S_TRAS,2)}}
                                        </td>
                                        <td id="f3" scope="row">
                                            {{number_format($rep4->S_KITT,2)}}
                                        </td>
                                        <td id="f4" scope="row">
                                            {{number_format($rep4->S_TAPI,2)}}
                                        </td>
                                        <td id="f5" scope="row">
                                            {{number_format($rep4->Consumo,2)}}
                                        </td>
                                        <td  scope="row">
                                            {{number_format((($rep4->S_TAPI + $rep4->S_KITT + $rep4->S_TRAS + $rep4->S_CARP)*-1) + $rep4->S_VST  ,2)}}
                                        </td>                                                                   
                                    </tr>                                 
                                    @endforeach @endif
                                </tbody>
                                <tfoot>
                                    <tr class="total3">
                                        <th scope="row" class="table-condensed">SUMA DE CASCOS:</th>
                                        <td class="zrk-gamal">{{number_format($sum_1, 2) }}</td>
                                        <td class="zrk-gamal">{{number_format($sum_2, 2) }}</td>
                                        <td class="zrk-gamal">{{number_format($sum_3, 2) }}</td>
                                        <td class="zrk-gamal">{{number_format($sum_4, 2) }}</td>
                                        <td class="zrk-gamal">{{number_format($sum_6, 2) }}</td>
                                        <td class="zrk-gamal">{{number_format($sum_5, 2) }}</td>
                                       
                                    </tr>
                                    @if (strtotime($ff) == strtotime(date("Y-m-d"))) 
                                             <tr  class="encabezado">
                                                <th scope="row" class="table-condensed">INVENTARIO CASCO:</th>
                                                @foreach ($data5 as $item)                          
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T_CARP,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T_ALMA,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T_CAMI,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T_KITT,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                        {{number_format($item->T_TAPIZ,2)}}
                                                    </td>                             
                                                    <td class="zrk-gamalobs" scope="row">
                                                    {{''}}
                                                    </td>                             
                                                @endforeach                                          
                                            </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <!-- /.col-md-8 -->
                </div><!-- /.row -->
    </div>
  
    </div>


    <footer>
            <script type="text/php">
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif","normal"); 

                $empresa = 'Sociedad: <?php echo 'SALOTTO S.A. de C.V.'; ?>';
                $date = 'Fecha de impresion:  <?php echo $hoy = date("d-m-Y H:i:s"); ?>';
                $text = 'Pagina: {PAGE_NUM} / {PAGE_COUNT}'; 
                $tittle = 'Siz_Reporte_ProdxAreas.Pdf'; 
                
                $pdf->page_text(40, 23, $empresa, $font, 9);
                $pdf->page_text(585, 23, $date, $font, 9);  

                $pdf->page_text(35, 580, $text, $font, 9);                         
                $pdf->page_text(620, 580, $tittle, $font, 9);                                                 
            </script>
    </footer>
    @yield('subcontent-01')

</body>

</html>