<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="theme-color" content="#eb4646" />
    <link rel="icon" href="<?php echo base_url();?>img/LOGOWEBSFW.png" type="image/x-icon" />
    <title>Ubícalos</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->

    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="<?php echo base_url()?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>css/owl.theme.default.min.css">

    <link href="<?php echo base_url();?>css/main.css" rel="stylesheet">

    <style>
        /*Promoción*/
        .promocion {
            position: absolute;
            height: 25px;
            width: 80px;
            top: 16px;
            left: 12px;
            background-color: #FFC30F;
            padding: 0px;
            padding-left: 6px;
            border-radius: 3px;
            text-align: left;
        }

        /*Fin Promoción*/

        /*Carousel*/
        .carousel-item img {
            max-height: 100%;
            max-width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .carousel-item {
            height: 200px;
        }

        .carousel-inner {
            background-color: black;
        }

        /**/

        /*Card categorias*/
        .card_empresa {
            font-size: 14pt;
            color: black;
        }

        .card_categoria {
            font-size: 12pt;
            color: Green;
        }

        .card_zona {
            font-size: 12pt;
            color: #3C61A6;
        }

        /*Fin card */

        .categoria {
            font-size: 14pt;
        }

        .linea {
            border: .5px solid black;
            border-radius: 3px;
        }

        .carousel-indicators li {
            width: 10px;
            height: 10px;
            border-radius: 100%;
        }

        .carousel-control-prev {
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 28px;
            width: 28px;
            text-align: center;
            margin: auto;
            background-color: #c9cdcf;
            border-radius: 50%;
            box-shadow: inset 0 -1px rgba(0, 0, 0, .1), 0 1px 1px 0 rgba(0, 0, 0, .06), 0 2px 5px 0 rgba(0, 0, 0, .2);
            margin-left: 5px;
            opacity: 0
        }

        .carousel-control-next {
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 28px;
            width: 28px;
            text-align: center;
            margin: auto;
            background-color: #c9cdcf;
            border-radius: 50%;
            box-shadow: inset 0 -1px rgba(0, 0, 0, .1), 0 1px 1px 0 rgba(0, 0, 0, .06), 0 2px 5px 0 rgba(0, 0, 0, .2);
            margin-right: 5px;
            opacity: 0
        }

        #div_grande_banner {
            z-index: 0;
        }

        #div_icono_grande {
            position: absolute;
            z-index: 1;
            background-color: #FFFFFF;
            margin-left: 50px;
            width: 190px;
            height: 240px;
        }

        #div_icono_pequenio {
            position: absolute;
            z-index: 1;
            background-color: #FFFFFF;
            margin-left: 470px;
            border-radius: 0px 3px 3px 0px;
            width: 260px;
            height: 105px;
        }

        .centrar {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
        
        .color-black {
            color: black
        }

        .color-green {
            color: green
        }

        .color-blue {
            color: blue
        }

        .color-blue-ubicalos {
            color: #3C61A6
        }

        .color-red {
            color: red !important;
        }

        .f-11 {
            font-size: 11pt;
        }

        .f-12 {
            font-size: 12pt;
        }

        .f-13 {
            font-size: 13pt;
        }

        .f-14 {
            font-size: 14pt;
        }

        .f-15 {
            font-size: 15pt;
        }
    </style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header body-tabs-line">
        <div class="app-header header-shadow bg-light header-text-dark">

            <div class="app-header__mobile-menu bg-light header-text-dark">

                <div>
                    <button id="barritas" type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>

                <div id="logo-principal-ubicalos">
                    <a href="<?php echo base_url(); ?>">
                        <div style="
								background-image:url(<?php echo base_url();?>img/LOGOTIPO_NAV.svg);
								background-repeat: no-repeat;
								background-size: auto auto;
								height: 23px;
								width: 107px;
								margin: auto;
								position: absolute;
								top: 0;
								bottom: 0;
								left: 0;
								right:0;
								display: flex;
							">
                        </div>
                    </a>
                </div>

            </div>

            <div id="lupa-ubicalos-search" class="app-header__menu bg-light header-text-dark">
                <span>
                    <button onclick="btnBusca();" type="button" class="btn-icon btn-icon-only btn btn-sm">
                        <span class="btn-icon-wrapper" style="font-size: 1.1rem;">
                            <i class="icon-ICONO-LUPA"></i>
                        </span>
                    </button>
                </span>
            </div>

            <div id="input-ubicalos-search" align="center" class="app-header__menu" style="display: none;">
                <form action="<?php echo base_url(); ?>">
                    <div class="row">
                        <div class="col-10" style="margin-left: -12%">
                            <input type="text" class="form-control input-search" placeholder="Buscar en Ubícalos...">
                            <!---
						<div class="input-group-append">
							<button type="submit" class="btn-search">
								<span class="btn-icon-wrapper" style="font-size: 1.1rem;">
									<i class="icon-ICONO-LUPA"></i>
								</span>
							</button>
						</div>
						-->
                        </div>
                        <div class="col-2 mt-1" style="margin-left: 1.2rem !important">
                            <button type="submit" type="button" class="btn-icon btn-icon-only btn btn-sm">
                                <span class="btn-icon-wrapper" style="font-size: 1.1rem;">
                                    <i class="icon-ICONO-LUPA"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="app-header__content" style="display: none;">
                <div class="app-header-left">
                    <div class="btn-group">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn"
                            id="logotipo" style="display:none;">
                            <img width="130" src="<?php echo base_url();?>img/logo.svg" alt="">
                        </a>
                    </div>
                </div>
            </div>



        </div>

        <div class="ui-theme-settings">
            <div class="theme-settings__inner">
                <div class="scrollbar-container">

                </div>
            </div>
        </div>

        <div class="app-main">
            <!-- Menú lateral-->
            <div class="app-sidebar sidebar-shadow">
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <!--Puebla-->
                            <li>
                                <a href="#">
                                    <span class="metismenu-icon icon-UBICACION" style="font-size:13pt"></span>
                                    <font class="arial">Puebla</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                            </li>
                            <!--Puebla-->
                            <li>
                                <hr class="mt-0 pt-0 mb-0 pb-0" style="border: 0.5px solid #DBDBDB; width: 95%;" />
                            <li>
                            <li class="app-sidebar__heading arial">Categorías</li>
                            <!--Promociones-->
                            <li>
                                <a href="#">
                                    <span class="metismenu-icon icon-PROMOCIONES "><span class="path1"></span><span
                                            class="path2"></span></span>
                                    <font class="arial"> Promociones</font>
                                </a>
                            </li>
                            <!--Promociones-->
                            <!--Compras-->
                            <li>
                                <a href="#">
                                    <span class="metismenu-icon icon-COMPRAS"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span><span class="path11"></span><span
                                            class="path12"></span><span class="path13"></span><span
                                            class="path14"></span><span class="path15"></span><span
                                            class="path16"></span></span>
                                    <font class="arial"> Compras</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['1']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['1'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Compras-->
                            <!--Construcción y edificación-->
                            <li>
                                <a href="#">
                                    <span class="metismenu-icon icon-CONSTRUCCION"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span><span class="path11"></span><span
                                            class="path12"></span><span class="path13"></span><span
                                            class="path14"></span><span class="path15"></span><span
                                            class="path16"></span><span class="path17"></span><span
                                            class="path18"></span><span class="path19"></span><span
                                            class="path20"></span><span class="path21"></span><span
                                            class="path22"></span><span class="path23"></span><span
                                            class="path24"></span><span class="path25"></span><span
                                            class="path26"></span><span class="path27"></span><span
                                            class="path28"></span></span>
                                    <font class="arial">Construcción y edificación</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['2']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['2'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Construcción y edificación-->
                            <!--Cuidado personal-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-CUIDADO-PERSONAL"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span><span class="path11"></span><span
                                            class="path12"></span><span class="path13"></span><span
                                            class="path14"></span><span class="path15"></span></span>
                                    <font class="arial">Cuidado personal</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['3']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['3'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Cuidado personal-->
                            <!--Educación-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-EDUCACION-Y-APRENDIZAJE"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span></span>
                                    <font class="arial">Educación</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['4']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['4'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Educación-->
                            <!--Estilo de vida y entretenimiento-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-ESTILO-DE-VIDA-Y-ENTRETENIMIENTO"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span></span>
                                    <font class="arial">Entretenimiento y ocio</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['5']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['5'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Estilo de vida y entretenimiento-->
                            <!--Finanzas y jurídicos-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-FINANZAS-Y-JURIDICOS"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span><span class="path16"></span><span
                                            class="path17"></span><span class="path18"></span><span
                                            class="path19"></span><span class="path20"></span><span
                                            class="path21"></span><span class="path22"></span><span
                                            class="path23"></span><span class="path24"></span><span
                                            class="path25"></span><span class="path26"></span><span
                                            class="path27"></span><span class="path28"></span><span
                                            class="path29"></span><span class="path30"></span><span
                                            class="path31"></span><span class="path32"></span></span>
                                    <font class="arial">Finanzas y jurídicos</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['6']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['6'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Finanzas y jurídicos-->
                            <!--Gastronomía-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-GASTRONOMIA"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span></span>
                                    <font class="arial">Gastronomía</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['7']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['7'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Gastronomía-->
                            <!--Industria-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-INDUSTRIA"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span><span class="path11"></span><span
                                            class="path12"></span><span class="path13"></span><span
                                            class="path14"></span><span class="path15"></span><span
                                            class="path16"></span><span class="path17"></span><span
                                            class="path18"></span><span class="path19"></span><span
                                            class="path20"></span><span class="path21"></span><span
                                            class="path22"></span><span class="path23"></span><span
                                            class="path24"></span><span class="path25"></span><span
                                            class="path26"></span><span class="path27"></span><span
                                            class="path28"></span><span class="path29"></span><span
                                            class="path30"></span><span class="path31"></span><span
                                            class="path32"></span><span class="path33"></span><span
                                            class="path34"></span><span class="path35"></span><span
                                            class="path36"></span><span class="path37"></span><span
                                            class="path38"></span><span class="path39"></span><span
                                            class="path40"></span><span class="path41"></span><span
                                            class="path42"></span><span class="path43"></span><span
                                            class="path44"></span><span class="path45"></span><span
                                            class="path46"></span><span class="path47"></span><span
                                            class="path48"></span><span class="path49"></span><span
                                            class="path50"></span><span class="path51"></span><span
                                            class="path52"></span><span class="path53"></span><span
                                            class="path54"></span></span>
                                    <font class="arial">Industrial</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['8']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['8'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Industria-->
                            <!--Salud-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-SALUD"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span></span>
                                    <font class="arial">Salud y bienestar</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['9']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['9'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Salud-->
                            <!--Servicios empresariales y oficina-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-SERVICIOS-EMPRESARIALES-Y-OFICINA"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span><span class="path16"></span><span
                                            class="path17"></span><span class="path18"></span><span
                                            class="path19"></span><span class="path20"></span><span
                                            class="path21"></span><span class="path22"></span><span
                                            class="path23"></span><span class="path24"></span><span
                                            class="path25"></span><span class="path26"></span><span
                                            class="path27"></span><span class="path28"></span><span
                                            class="path29"></span><span class="path30"></span><span
                                            class="path31"></span><span class="path32"></span><span
                                            class="path33"></span><span class="path34"></span><span
                                            class="path35"></span><span class="path36"></span></span>
                                    <font class="arial">Servicios</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['10']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['10'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Servicios empresariales y oficina-->
                            <!--Servicios Hogar-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-SERVICIOS-DE-HOGAR"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span></span>
                                    <font class="arial">Servicios del Hogar</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['11']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['11'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Servicios del Hogar-->
                            <!--Servicios Inmobiliarios-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-SERVICIOS-INMOBILIARIOS"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span><span class="path16"></span><span
                                            class="path17"></span><span class="path18"></span><span
                                            class="path19"></span><span class="path20"></span><span
                                            class="path21"></span><span class="path22"></span><span
                                            class="path23"></span><span class="path24"></span><span
                                            class="path25"></span><span class="path26"></span><span
                                            class="path27"></span><span class="path28"></span><span
                                            class="path29"></span><span class="path30"></span><span
                                            class="path31"></span><span class="path32"></span><span
                                            class="path33"></span><span class="path34"></span><span
                                            class="path35"></span><span class="path36"></span><span
                                            class="path37"></span></span>
                                    <font class="arial">Inmuebles</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['12']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['12'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Servicios Inmobiliarios-->
                            <!--Servicios Públicos-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-SERVICIOS-PUBLICOS"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span><span class="path8"></span><span
                                            class="path9"></span><span class="path10"></span><span
                                            class="path11"></span><span class="path12"></span><span
                                            class="path13"></span><span class="path14"></span><span
                                            class="path15"></span><span class="path16"></span><span
                                            class="path17"></span><span class="path18"></span><span
                                            class="path19"></span><span class="path20"></span><span
                                            class="path21"></span><span class="path22"></span><span
                                            class="path23"></span><span class="path24"></span><span
                                            class="path25"></span><span class="path26"></span><span
                                            class="path27"></span><span class="path28"></span><span
                                            class="path29"></span><span class="path30"></span><span
                                            class="path31"></span><span class="path32"></span><span
                                            class="path33"></span><span class="path34"></span><span
                                            class="path35"></span><span class="path36"></span><span
                                            class="path37"></span><span class="path38"></span><span
                                            class="path39"></span><span class="path40"></span><span
                                            class="path41"></span><span class="path42"></span><span
                                            class="path43"></span><span class="path44"></span><span
                                            class="path45"></span><span class="path46"></span><span
                                            class="path47"></span><span class="path48"></span><span
                                            class="path49"></span><span class="path50"></span><span
                                            class="path51"></span><span class="path52"></span><span
                                            class="path53"></span><span class="path54"></span><span
                                            class="path55"></span><span class="path56"></span><span
                                            class="path57"></span><span class="path58"></span><span
                                            class="path59"></span><span class="path60"></span><span
                                            class="path61"></span><span class="path62"></span></span>
                                    <font class="arial">Servicios públicos</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['13']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['13'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Servicios públicos-->
                            <!--Tecnologías-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-TECNOLOGIA"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span><span
                                            class="path6"></span><span class="path7"></span><span
                                            class="path8"></span><span class="path9"></span><span
                                            class="path10"></span><span class="path11"></span><span
                                            class="path12"></span><span class="path13"></span></span>
                                    <font class="arial">Tecnología</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['14']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['14'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Tecnologías-->
                            <!--Transporte y motor-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-TRANSPORTE-Y-MOTOR"><span
                                            class="path1"></span><span class="path2"></span><span
                                            class="path3"></span><span class="path4"></span><span
                                            class="path5"></span><span class="path6"></span><span
                                            class="path7"></span></span>
                                    <font class="arial">Transporte y motor</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['15']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['15'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Transporte y motor-->
                            <!--Turismo y viajes-->
                            <li>
                                <a href="tables-regular.html">
                                    <span class="metismenu-icon icon-TURISMO-Y-VIAJES"><span class="path1"></span><span
                                            class="path2"></span><span class="path3"></span><span
                                            class="path4"></span><span class="path5"></span></span>
                                    <font class="arial">Turismo</font>
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul>
                                    <?php for($i=0;$i< count($subcategorias['16']); $i++){
                                       ?>
                                    <li>
                                        <a href="">
                                            <i class="metismenu-icon"></i>
                                            <font class="arial"><?php echo $subcategorias['16'][$i]->subcategoria; ?>
                                            </font>
                                        </a>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                            <!--Turismo y viajes -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Fin Menú lateral-->