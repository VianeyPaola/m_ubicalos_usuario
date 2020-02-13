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
			<form action="filtro_resultado" method="GET" style="width: 100%;">
				<div class="col-12">
					<input type="hidden" name="categoria" value="<?php echo $id_categoria; ?>">
				</div>
				<div class="col-12 ml-0 pl-0 mr-0 pr-0">
					<div class="collapse" id="collapseFiltro">
					
						<div id="accordion" class="accordion-wrapper mb-3">
							<!--Subcategorias-->
							<div class="card">
								<div id="headingSubcategoria" class="b-radius-0 card-header">
									<button type="button" data-toggle="collapse" data-target="#collapseSubcategoria" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-11">Subcategorias: <span id="sub_categoria_name"><?php
										if(strlen($nombre_subcategoria) > 20){
											echo substr($nombre_subcategoria, 0, 17)."...";
										}else{
											echo $nombre_subcategoria; 
										}
										?></span>
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
										<p class="m-0 p-0 color-black f-11">Serv. adicionales: <span id="nombre_servicio"></span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
								</div>
								<div data-parent="#accordion" id="collapseServicios" class="collapse">
									<div class="card-body" style="padding-left:1.5rem">
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_1" value="1">
											<font class="color-black" id="serv_1">Silla de ruedas</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_2" value="2">
											<font class="color-black" id="serv_2">Cajero automatico</font>
										</div>
										<div>
											<font class="color-black">Estacionamiento</font>
										</div>
										<ul class="list-group ml-3">
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_3" value="3">
													<font class="color-black" id="serv_3">Privado</font>
												</div>
											</li>
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_4" value="4">
													<font class="color-black" id="serv_4">Valet Parking</font>
												</div>
											</li>
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_5" value="5">
													<font class="color-black" id="serv_5">Calle</font>
												</div>
											</li>
										</ul>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_6" value="6">
											<font class="color-black" id="serv_6">Mesas al aire libre</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_7" value="7">
											<font class="color-black" id="serv_7">Pantallas</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_8" value="8">
											<font class="color-black" id="serv_8">Reservaciones</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_9" value="9">
											<font class="color-black" id="serv_9">Sanitarios</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_10" value="10">
											<font class="color-black" id="serv_10">Servicio a domicilio</font>
										</div>
										<div>
											<font class="color-black">Tarjetas</font>
										</div>
										<ul class="list-group ml-3">
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" onclick="serv_seleccionado(this)" name="serv_11" type="checkbox" value="11">
													<font class="color-black" id="serv_11">Visa</font>
												</div>
											</li>
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_12" value="12">
													<font class="color-black" id="serv_12">Master Card</font>
												</div>
											</li>
											<li>
												<div class="form-check">
													<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_13" value="13">
													<font class="color-black" id="serv_13">Amex</font>
												</div>
											</li>
										</ul>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_14" value="14">
											<font class="color-black" id="serv_14">WiFi</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_15" value="15">
											<font class="color-black" id="serv_15">Zona de cigarrillos</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="serv_seleccionado(this)" name="serv_16" value="16">
											<font class="color-black" id="serv_16">Zona de niños</font>
										</div>
									</div>
								</div>
							</div>
							<!-- Fin Seccion -->
							<!-- Zona -->
							<div class="card">
								<div id="headingZona" class="b-radius-0 card-header">
									<button type="button" data-toggle="collapse" data-target="#collapseZona" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-11">Zona: <span id="zona_nombre"></span>
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
										<p class="m-0 p-0 color-black f-11">Ordenar por: <span id="nombre_o"></span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
								</div>
								<div data-parent="#accordion" id="collapseOrdenar" class="collapse">
									<div class="card-body" style="padding-left:1.5rem">
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="nombre_ordenar(this)" name="o_1" value="1">
											<font class="color-black" id="o_1">Ubicación</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="nombre_ordenar(this)" name="o_2"  value="2">
											<font class="color-black" id="o_2">Mejor Calificación</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="nombre_ordenar(this)" name="o_3"  value="3">
											<font class="color-black" id="o_3">Abierto ahora</font>
										</div>
										<div class="form-check">
											<input class="form-check-input position-static" type="checkbox" onclick="nombre_ordenar(this)" name="o_4" value="4">
											<font class="color-black" id="o_4">Ultima actualización</font>
										</div>
									</div>
								</div>
							</div>
							<!-- Fin Ordenar por -->
						</div>
						<div class="container-fluid pl-2 pr-2">
							<button type="submit" class="btn btn-ubicalos btn-block">Aplicar filtro</button>
						</div>

					</div>
				</div>
				
			</forn>
        </form>



        <div class="row mb-n2 mt-3">
            <div class="col-12 ml-0 pl-2 mr-0 pr-0">
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
                                        <img class="img-fluid img-home-categorias" src="<?php echo base_url(); ?>img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png">
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
                <div class="col-12 ml-0 pl-2 mr-0 pr-0" style="background-color: rgba(225, 48, 36,0.2) ">
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

		<div id="empresas_sub" class="container-fluid pl-2">								

		</div>
    </div>

</div>
