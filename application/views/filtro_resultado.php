<div class="app-main__outer">
    <div class="app-main__inner">
        <!-- publicidad banner -->
        <div class="row" id="publicidad-home-banner" style="margin-top:-15px">
        </div>

        <div class="row  mb-n2">
            <hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
        </div>
        <!--Categorias-->
        <div class="row mb-n4 ml-n3 mr-n3">
            <div class="col-9">
                <b><p class="f-12" style="color: #495057;"><?php echo $categoria[0]->categoria; ?></p></b>
            </div>
            <div class="col-3 text-right">
                <a class="color-blue-ubicalos" data-toggle="collapse" href="#collapseFiltro">Filtro +</a>
            </div>
        </div>

        <div class="row mb-n3">
            <hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
        </div>
        <div class="row mb-n2">
            <div class="col-12 ml-0 pl-0 mr-0 pr-0">
                <div class="collapse" id="collapseFiltro">
                    <div id="accordion" class="accordion-wrapper mb-3">
                        <!--Subcategorias-->
                        <div class="card">
                            <div id="headingSubcategoria" class="b-radius-0 card-header">
                                <button type="button" data-toggle="collapse" data-target="#collapseSubcategoria" class="text-left m-0 p-0 btn btn-link btn-block">
                                    <p class="m-0 p-0 color-black f-11">Subcategorias: <span id="sub_categoria_name"><?php echo $nombre_subcategoria; ?></span>
                                    <i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
                                </button>
                            </div>
                            <div data-parent="#accordion" id="collapseSubcategoria" aria-labelledby="headingSubcategoria" class="collapse">
                                <div class="card-body" style="padding-left:1.5rem">
									<?php for($i=0; $i <count($subcategorias_filtro); $i++){ ?>
										<div class="form-check">
											<input class="form-check-input position-static" style="display: inline;" type="radio" value="<?php echo $subcategorias_filtro[$i]->id_subcategoria; ?>" name="sub_cat" <?php if($subcategorias_filtro[$i]->id_subcategoria == $_GET['sub_cat']){ echo "checked";} ?>>
											<font class="color-black"><?php echo $subcategorias_filtro[$i]->subcategoria; ?></font>
										</div>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Subcategorias -->
                        <!-- Seccion -->
                        <div class="card">
                            <div id="headingSeccion" class="b-radius-0 card-header">
                                <button type="button" data-toggle="collapse" data-target="#collapseSeccion" class="text-left m-0 p-0 btn btn-link btn-block">
                                    <p class="m-0 p-0 color-black f-11">Sección: <span id="seccion_nombre"></span>
                                    <i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
                                </button>
                            </div>
                            <div data-parent="#accordion" id="collapseSeccion" aria-labelledby="headingSeccion" class="collapse">
                                <div class="card-body" style="padding-left:1.5rem" id="div_secciones">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- Fin Seccion -->
                        <!-- Servicios -->
                        <div class="card">
                            <div id="headingServicios" class="b-radius-0 card-header">
                                <button type="button" data-toggle="collapse" data-target="#collapseServicios" class="text-left m-0 p-0 btn btn-link btn-block">
                                    <p class="m-0 p-0 color-black f-11">Serv. adicionales: Zona de cigarrillo
                                    <i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
                                </button>
                            </div>
                            <div data-parent="#accordion" id="collapseServicios" class="collapse">
                                <div class="card-body" style="padding-left:1.5rem">
                                    <div class="form-check">
                                        <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
                                        <font class="color-black">Restaurantes</font>
                                    </div>
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="switch1">
										<label class="custom-control-label" for="switch1">Toggle me</label>
									</div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Seccion -->
                        <!-- Zona -->
                        <div class="card">
                            <div id="headingZona" class="b-radius-0 card-header">
                                <button type="button" data-toggle="collapse" data-target="#collapseZona" class="text-left m-0 p-0 btn btn-link btn-block">
                                    <p class="m-0 p-0 color-black f-11">Zona: Angelopólis
                                    <i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
                                </button>
                            </div>
                            <div data-parent="#accordion" id="collapseZona" class="collapse">
                                <div class="card-body" style="padding-left:1.5rem" id="div_zonas">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- Fin Zona -->
                        <!-- Ordenar por -->
                        <div class="card">
                            <div id="headingOrdenar" class="b-radius-0 card-header">
                                <button type="button" data-toggle="collapse" data-target="#collapseOrdenar" class="text-left m-0 p-0 btn btn-link btn-block">
                                    <p class="m-0 p-0 color-black f-11">Ordenar por: Ubicación
                                    <i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
                                </button>
                            </div>
                            <div data-parent="#accordion" id="collapseOrdenar" class="collapse">
                                <div class="card-body" style="padding-left:1.5rem">
                                    <div class="form-check">
                                        <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
                                        <font class="color-black">Restaurantes</font>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
                                        <font class="color-black">Restaurantes</font>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
                                        <font class="color-black">Restaurantes</font>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Ordenar por -->
                    </div>
                </div>
            </div>
        </div>



        <div class="row mb-n2 mt-3">
            <div class="col-12 ml-0 pl-0 mr-0 pr-0">
                <a>
                    <div class="card ml-3 mr-3" style="max-width: 940px;">
                        <div class="row no-gutters">
                            <!-- <div class="carousel slide" data-ride="carousel" style="margin-right:10px">
                                <div class="carousel-inner" style="border-radius: 4px !important;">
                                    <div class="carousel-item active" style="border-radius: 4px !important; width: 88px !important; height: 88px !important; ">
                                        <img style="width: 88px !important; height: 88px !important;" <?php echo 'src="'.base_url(). 'img/IMAGEN EVENTOS Y BLOGS.png"'; ?>>
                                        <div class="carousel-caption promocion">
                                            <p class="color-black f-9">Promoción</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item" style="border-radius: 4px !important; width: 88px !important; height: 88px !important; ">
                                        <img style="width: 88px !important; height: 88px !important;" <?php echo 'src="'.base_url(). 'img/IMAGEN EVENTOS Y BLOGS.png"'; ?>>
                                        <div class="carousel-caption promocion">
                                            <p class="color-black f-9">Promoción</p>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!--Segunda publicidad-->

                            <div class="col-auto">
                                    <img class="card-img img-cards" <?php echo 'src="'.base_url(). 'img/IMAGEN EVENTOS Y BLOGS.png"'; ?>>
                                </div>

                            <div class="card-body mt-0 pt-0">
                                <p class="mb-0 pb-0 color-black f-13">Nombre</p>
                                <p class="card-text mb-0 pb-0 mt-n1 color-green f-10">Gastronomía, bebidas </p>
                                <p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En zona : Angelopolis </p>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <img class="img-fluid img-home-categorias">
                                        <font class="estrellas mt-2 ml-n1">
                                            <font class="clasificacion mb-0">
                                                <input id="radio1" type="radio" name="estrellas" value="5">
                                                <label for="radio1">★</label>
                                                <input id="radio2" type="radio" name="estrellas" value="4">
                                                <label for="radio2">★</label>
                                                <input id="radio3" type="radio" name="estrellas" value="3">
                                                <label for="radio3">★</label>
                                                <input id="radio4" type="radio" name="estrellas" value="2">
                                                <label for="radio4">★</label>
                                                <input id="radio5" type="radio" name="estrellas" value="1">
                                                <label for="radio5">★</label>

                                            </font>
                                            <img class="img-add" src="<?php echo base_url();?>img/ICONO AD.png" style="display:true!important; height: 5px; ">
                                        </font>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Fin card para porcentaje -->
                <div class="w-100 mt-n2">
                    <div class="col-12 ml-0 pl-0 mr-0 pr-0">
                        <div class="card ml-3 mr-3" style="max-width: 940px;">
                            <div class="row no-gutters">
                                <p class="card-body m-0 p-0">
                                    <font class="color-green f-11 arial">Abierto ahora: </font>
                                    <font class="color-black f-11 arial"> 13:00 </font>
                                    <p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Blvrd Hermanos Serdán #270, Int. 05,</p>
                                    <p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Col. Posadas C.P. 72160 (+12 sucursales)</p>
                                    <p class="color-grey f-11 arial mb-0 pb-0 mt-n1 pt-0">Ult. Vez: 05-Jun-2019</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 mt-0">
                <div class="col-12 ml-0 pl-0 mr-0 pr-0" style="background-color: rgba(225, 48, 36,0.2) ">
                    <div class="card ml-3 mr-3" style="max-width: 940px; height: 26px; background-color: transparent !important;">
                        <div class="row no-gutters" style="margin-top:3px">
                            <div class="col-auto">
                                <img class="card-img img-cards-promocion" <?php echo 'src="'.base_url(). 'img/IMAGEN EVENTOS Y BLOGS.png"'; ?>>
                            </div>
                            <p class="card-body color-red m-0 p-0"> Ven a festejar tu cumpleaños y come gratis </p>
                        </div>
                    </div>
                </div>
                <hr class="linea-division p-0 mt-2" />
            </div>
        </div>
    </div>

</div>
