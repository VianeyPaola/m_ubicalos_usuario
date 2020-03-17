<!Doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="theme-color" content="#eb4646" />
    <link rel="icon" href="<?php echo base_url();?>img/LOGOWEBSFW.png" type="image/x-icon" />
    <title>Ubícalos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
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
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>css/owl.theme.default.min.css"> -->

    <link href="<?php echo base_url();?>css/main.css" rel="stylesheet">

    <style>
        a:link {
            text-decoration: none;
        }
        
        .slide {
            border-radius: 4px !important;
            width: 88px !important;
            height: 88px !important;
        }
        
        .etiqueta-info-carga {
            color: rgb(237, 237, 237);
            background-color: rgb(237, 237, 237);
            border-radius: 1px;
        }
        
        .img-cards {
            border-radius: 4px!important;
            width: 88px !important;
            height: 88px !important;
            float: right;
            margin-right: 10px
        }
        
        .img-cards-promocion {
            border-radius: 0px !important;
			width: 25px !important;
			height: 120px !important;
			float: right;
			margin-right: 10px;
			position: relative;
			top: -50px;
        }
        
        .app-header.header-shadow {
            box-shadow: 0 1px 0 #E8EEF1;
        }
        
        .publicidad-banner {
            padding-top: 36px;
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
        
        .color-grey {
            color: #D3D6D2
        }
        
        .color-blue-ubicalos {
            color: #3C61A6 !important;
        }
        
        .color-red {
            color: red !important;
        }
        
        .bg-color-grey {}
        
        .collapse .card-body {
            background-color: #fafafa;
        }
        
        .form-check .custom-control-label,
        .custom-checkbox .custom-control-label {
            margin-bottom: 6px;
        }
        
        .img-blogs {
            width: 26px !important;
            height: 26px !important;
        }
        
        .img-add {
            padding-left: 5px;
            width: 25px !important;
            height: 10px !important;
        }
        
        .img-home-categorias {
            width: 24px !important;
            height: 24px !important;
            border: 1px solid #E6E6E6
        }
        
        .f-9 {
            font-size: 9pt;
        }
        
        .f-10 {
            font-size: 10pt;
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
        
        .btn-promocion-1 {
            font-size: 8pt;
            padding: 4px 14px 4px 14px;
            background-color: rgb(129, 189, 73);
            color: white;
            border-radius: 80px;
        }
        
        .btn:active {
            color: white;
        }
        
        .btn-promocion-2 {
            font-size: 6pt;
            padding: 3px 14px 4px 14px;
            background-color: red;
            color: white;
            border-radius: 80px;
            height: 40%;
            width: 90%;
        }
        
        .img-promocion-1 {
            margin-left: 16px;
            border-radius: 3px;
            width: 94px;
            height: 78px;
            padding: 5px 12px 3px 12px;
        }
        
        .btn-perfil-change {
            z-index: 2;
            width: 30px;
            height: 30px;
            position: relative;
            margin-top: -62px;
            margin-left: 213px;
            background-image: url(<?php echo base_url();
            ?>/img/FP.svg);
            background-position: center;
            background-color: transparent;
            background-repeat: no-repeat;
            border-color: transparent;
            background-size: contain;
            /* Hace que la imagen sea del tamaño del boton*/
            border-radius: 30px;
        }
        
        .btn-publicidad {
            background-image: url('<?php echo base_url();?>/img/flechita publicidad.svg');
            width: 30px;
            height: 30px;
            background-position: center;
            background-color: transparent;
            background-repeat: no-repeat;
            border-color: transparent;
            background-size: contain;
        }
        
        .btn-search {
            border-radius: 0px 50px 50px 0px;
            background: rgb(231, 236, 241);
            border-color: transparent;
        }
        
        .input-search {
            border-radius: 50px 50px 50px 50px;
            background: rgb(231, 236, 241);
            border-color: transparent;
            color: rgb(105, 121, 133);
            width: 120%;
            height: calc(2.25rem + 5px);
        }
        
        .input-search:active {
            background: rgb(231, 236, 241);
            color: rgb(105, 121, 133);
        }
        
        .categorias-info {
            text-decoration: none;
            padding: 10px;
            font-weight: 600;
            font-size: 15px;
            color: #ffffff;
            background-color: #b6bdc0;
            border-radius: 6px;
        }
        
        .close-modal {
            color: white !important;
            font-size: 2rem;
            font-weight: 100;
            line-height: 1;
            opacity: 1 !important;
            margin-bottom: -0.5rem !important;
        }
        
        .button-image-añadir {
            background-image: url(<?php echo base_url();
            ?>/img/AGREGAR.png);
            background-position: center;
            background-size: contain;
            /* Hace que la imagen sea del tamaño del boton*/
            background-color: transparent;
            width: 20px;
            height: 20px;
            margin: 5px;
            border-radius: 70px 70px 70px 70px;
        }
        
        .button-image-añadir:hover {
            opacity: 0.50;
            -moz-opacity: .50;
            filter: alpha (opacity=50);
        }
        
        .button-image-quitar {
            background-image: url(<?php echo base_url();
            ?>/img/SIMBOLO_MENOS.png);
            background-position: center;
            background-size: contain;
            /* Hace que la imagen sea del tamaño del boton*/
            background-color: transparent;
            width: 20px;
            height: 20px;
            margin: 5px;
            border-radius: 70px 70px 70px 70px;
        }
        
        .button-image-quitar:hover {
            opacity: 0.50;
            -moz-opacity: .50;
            filter: alpha (opacity=50);
        }
        
        .radio-btn-position {
            margin-top: 35px;
        }
        
        .input-eventos {
            width: 145px;
        }
        
        .bg-light {
            background-color: white !important;
        }
        
        .card {
            background-color: #FFFFFF !important;
            box-shadow: none;
        }
        
        .mu-r {
            margin-right: 3.3rem;
        }
        
        .mu-b {
            margin-bottom: 0.6rem;
        }
        
        .card-ubicalos {
            width: 6.5rem;
            border-radius: 3px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
        }
        
        .card-img {
            height: 100px;
            border-radius: 3px 3px 0 0;
        }
        
        .card-ubicalos-img {
            width: 6.5rem;
            border-radius: 3px;
        }
        
        .card-img-galeria {
            height: 100px;
            border-radius: 3px;
        }
        
        .float {
            position: fixed;
            bottom: 9px;
            background-color: rgb(235, 70, 70);
            color: #fff;
            border-radius: 40px;
            text-align: center;
            box-shadow: 2px 2px 3px rgba(0, 0, 0, .3);
            padding: 3px;
            z-index: 5;
        }
        
        .float-video {
            position: fixed;
            bottom: 0;
            background-color: white;
            color: #fff;
            border-radius: 5px 5px 0 0;
            box-shadow: 2px 2px 3px rgba(0, 0, 0, .3);
            padding: 0px;
            z-index: 5;
        }
        
        .text-porcentaje {
            font-size: 25pt!important;
            color: white!important;
        }
        
        .bg-porcentaje {
            background-color: #5C6369
        }
        
        @media screen and (min-width: 375px) {
            .btn-perfil-change {
                margin-top: -59px;
                margin-left: 215px;
            }
            .mu-r {
                margin-right: 3.5rem;
            }
            .mu-b {
                margin-bottom: 0.7rem;
            }
            .card-ubicalos {
                width: 6.8rem;
                border-radius: 3px;
            }
            .card-img {
                height: 100px;
                border-radius: 3px 3px 0 0;
            }
            .card-ubicalos-img {
                width: 6.8rem;
                border-radius: 3px;
            }
            .card-img-galeria {
                height: 100px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 414px) {
            .btn-perfil-change {
                margin-top: -59px;
                margin-left: 235px;
            }
            .mu-r {
                margin-right: 3.8rem;
            }
            .mu-b {
                margin-bottom: 0.4rem;
            }
            .card-ubicalos {
                width: 7.7rem;
                border-radius: 3px;
                margin-bottom: 0.1rem;
            }
            .card-img {
                height: 100px;
                border-radius: 3px 3px 0 0;
            }
            .card-ubicalos-img {
                width: 7.8rem;
                border-radius: 3px;
            }
            .card-img-galeria {
                height: 100px;
                border-radius: 3px;
            }
        }
        /* Celular chico acostado */
        
        @media screen and (min-width: 640px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 350px;
            }
            .card-ubicalos {
                width: 11.9rem;
                height: 16rem;
                border-radius: 3px;
            }
            .card-img {
                height: 160px;
            }
            .mu-r {
                margin-right: 6.3rem;
            }
            .mu-b {
                margin-bottom: 1rem;
            }
            .card-ubicalos-img {
                width: 12.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 667px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 365px;
            }
            .card-ubicalos {
                width: 12.6rem;
                height: 16rem;
                border-radius: 3px;
            }
            .card-img {
                height: 160px;
            }
            .mu-r {
                margin-right: 6.6rem;
            }
            .mu-b {
                margin-bottom: 1.5rem;
            }
            .card-ubicalos-img {
                width: 12.7rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 688px) {
            .btn-perfil-change {
                margin-top: -75px;
                margin-left: 380px;
            }
            .card-ubicalos {
                width: 12.9rem;
                height: 17rem;
                border-radius: 3px;
            }
            .card-img {
                height: 180px;
            }
            .mu-b {
                margin-bottom: 0.8rem;
            }
            .mu-r {
                margin-right: 6.8rem;
            }
            .card-ubicalos-img {
                width: 13.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        /* Modificar para galeria */
        
        @media screen and (min-width: 710px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 385px;
            }
            .card-ubicalos {
                width: 12.9rem;
                height: 17rem;
                border-radius: 3px;
            }
            .card-img {
                height: 180px;
            }
            .mu-b {
                margin-bottom: 0.8rem;
            }
            .mu-r {
                margin-right: 6.8rem;
            }
            .card-ubicalos-img {
                width: 13.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 736px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 400px;
            }
            .card-ubicalos {
                width: 14rem;
                height: 17rem;
                border-radius: 3px;
            }
            .card-img {
                height: 180px;
            }
            .mu-b {
                margin-bottom: 1.3rem;
            }
            .mu-r {
                margin-right: 7.3rem;
            }
            .card-ubicalos-img {
                width: 14.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        /* Celular mediano acostado */
        
        @media screen and (min-width: 740px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 400px;
            }
            .card-ubicalos {
                width: 14.2rem;
                height: 17.4rem;
                border-radius: 3px;
                margin-bottom: 0.2rem;
            }
            .card-img {
                height: 180px;
            }
            .mu-r {
                margin-right: 7.3rem;
            }
            .mu-b {
                margin-bottom: 0.8rem;
            }
            .card-ubicalos-img {
                width: 14.4rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 195px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 768px) {
            .card-ubicalos {
                margin-top: -10px;
                margin-left: 215px;
                border-radius: 3px;
                margin-bottom: -0.4rem;
            }
            .card-img {
                height: 180px;
            }
            .mu-r {
                margin-right: 7.3rem;
            }
            .mu-b {
                margin-bottom: 1rem;
            }
            .card-ubicalos-img {
                width: 14.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        
        @media screen and (min-width: 812px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 424px;
            }
            .card-ubicalos {
                width: 15rem;
                height: 17.4rem;
                border-radius: 3px;
                margin-bottom: -0.2rem;
            }
            .card-img {
                height: 180px;
            }
            .mu-r {
                margin-right: 7.8rem;
            }
            .mu-b {
                margin-bottom: 1.3rem;
            }
            .card-ubicalos-img {
                width: 15.2rem;
                height: 12rem;
            }
            .card-img-galeria {
                height: 200px;
                border-radius: 3px;
            }
        }
        /* Ipad acostada */
        
        @media screen and (min-width: 1024px) {
            .btn-perfil-change {
                margin-top: -60px;
                margin-left: 387px;
            }
            .card-ubicalos {
                width: 17.2rem;
                height: 20rem;
                border-radius: 3px;
            }
            .card-img {
                height: 220px;
            }
            .mu-r {
                margin-right: 9rem;
            }
            .mu-b {
                margin-bottom: 1.5rem;
            }
        }
        
        input[type="file"] {
            display: none;
        }
        
        .modal-backdrop {
            position: relative;
        }
        
        .theme-white .app-header {
            background-color: #ffffff;
        }
        /*Boton con imagen*/
        
        .div-img-button {
            margin-top: 40px;
        }
        
        .toggle.ios,
        .toggle-on.ios,
        .toggle-off.ios {
            border-radius: 20rem;
        }
        
        .toggle.ios .toggle-handle {
            border-radius: 20rem;
        }
        
        .btn-ubicalos {
            color: white;
            background-color: #4369b1;
            font-size: 11pt;
        }
        
        .btn-ubicalos:hover {
            color: white;
        }
        
        .btn-outline-ubicalos {
            color: #4369b1;
            border-color: #4369b1;
            font-size: 11pt;
        }
        
        .btn-outline-ubicalos:hover {
            color: #4369b1;
        }
        
        .btn-ubicalos-group {
            margin-left: -28px;
            margin-right: -28px;
        }
        
        .input-text-ubicalos {
            font-size: 17px;
        }
        
        .arial {
            font-family: Arial !important;
        }
        
        li {
            list-style: none;
        }
        /*Promoción*/
        
        .promocion {
            position: absolute;
            height: 16px;
            width: 75px;
            top: 6px;
            left: 7px;
            right: 7px;
            background-color: #FFC30F;
            padding: 0px;
            border-radius: 3px;
            text-align: center;
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
            font-size: 16.5pt;
            color: #3A5161;
        }
        
        .linea {
            border-top: 1px solid black;
        }
        
        .linea-division {
            border: 0.5px solid #E8EEF1;
            width: 100%;
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
            background-color: rgba(26, 26, 26, 0.7);
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
            background-color: rgba(26, 26, 26, 0.7);
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
            margin-left: 18px;
            width: 94px;
            height: 110px;
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
        
        .padding-card {
            padding-left: 10px;
            padding-right: 10px
        }
        
        .not-shadow {
            box-shadow: none;
        }
        
        .centrar {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
        
        .owl-carousel .owl-item img {
            display: inline;
            width: 100%;
        }
        /* Paginación */
        
        .pagination {
            display: inline-block;
        }
        
        .pagination a {
            color: black;
            float: left;
            padding: 6px 14px;
            text-decoration: none;
            border: 1px solid #C8CAC6;
            border-radius: 5px;
            margin-right: 1px;
            margin-left: 1px;
        }
        
        .pagination a.active {
            background-color: #C8CAC6;
            color: white;
            border-radius: 5px;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #ddd;
            border-radius: 5px;
        }
        /* FIN Paginación */

		/* .typeahead{
			top: calc(100% - 290px) !important;
		} */

    </style>
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header body-tabs-line">
        <div class="app-header header-shadow bg-light header-text-dark" id="navbar">

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
                <form action="<?php echo base_url(); ?>Welcome/buscador" method="GET">
                    <div class="row">
                        <div class="col-10" style="margin-left: -12%">
                            <input id="buscador-ubicalos" name="buscador-ubicalos" class="form-control input-search" placeholder="Buscar en Ubícalos..." autocomplete="nope" list="resultados">
							<datalist id="resultados">
							
							</datalist>
							
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
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn" id="logotipo" style="display:none;">
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

        <div class="app-main" style="background-color: white;">
            <!-- Menú lateral-->
            <div class="app-sidebar">
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
						<ul class="vertical-nav-menu">
                            
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=1&sub_cat=<?php echo $subcategorias['1'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=2&sub_cat=<?php echo $subcategorias['2'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=3&sub_cat=<?php echo $subcategorias['3'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=4&sub_cat=<?php echo $subcategorias['4'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=5&sub_cat=<?php echo $subcategorias['5'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=6&sub_cat=<?php echo $subcategorias['6'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=7&sub_cat=<?php echo $subcategorias['7'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=8&sub_cat=<?php echo $subcategorias['8'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=9&sub_cat=<?php echo $subcategorias['9'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=10&sub_cat=<?php echo $subcategorias['10'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=11&sub_cat=<?php echo $subcategorias['11'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=12&sub_cat=<?php echo $subcategorias['12'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=13&sub_cat=<?php echo $subcategorias['13'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=14&sub_cat=<?php echo $subcategorias['14'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=15&sub_cat=<?php echo $subcategorias['15'][$i]->id_subcategoria; ?>">
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
                                        <a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=16&sub_cat=<?php echo $subcategorias['16'][$i]->id_subcategoria; ?>">
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
