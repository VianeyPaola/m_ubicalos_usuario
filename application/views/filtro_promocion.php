<div class="app-main__outer">
    <div class="app-main__inner">
        <!-- publicidad banner -->
        <div class="row" id="publicidad-home-banner" style="margin-top:-15px">
        </div>

        <div class="row  mb-n2">
            <hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
        </div>
        <!--Categorias-->
        <div class="row mb-n4">
            <div class="col-9" id="promociones-show">
                <b><p class="f-12" style="color: #495057;">Promociones</p></b>
            </div>
            <div class="col-3 text-right">
                <a class="color-blue-ubicalos f-12" data-toggle="collapse" href="#collapseFiltro" id="filtro_">Filtro +</a>
            </div>
        </div>

        <div class="row mb-n3">
            <hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
        </div>

        <div class="row mb-2">
            <form action="<?php echo base_url(); ?>Welcome/filtro_promocion_resultados" method="GET" style="width: 100%;">
                <div class="col-12">
                    <input type="hidden" id="latitud" name="latitud">
                    <input type="hidden" id="longitud" name="longitud">
                </div>
                <div class="w-100"></div>
                <div class="col-12 ml-0 pl-0 mr-0 pr-0 mb-2">
                    <div class="collapse" id="collapseFiltro">

                        <div id="accordion" class="accordion-wrapper mb-3">
                            <!--Categorias-->
                            <div class="card">
                                <div id="headingSubcategoria" class="b-radius-0 card-header">
                                    <button type="button" data-toggle="collapse" data-target="#collapseCategoria" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-12">Categorias: <span id="categoria_name"> </span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
                                </div>
                                <div data-parent="#accordion" id="collapseCategoria" aria-labelledby="headingCategoria" class="collapse">
                                    <div class="card-body" style="padding-left:1.5rem">
										<?php for($i=0; $i<count($categorias); $i++){ ?>
											<div class="form-check p-0 ">
												<div class="custom-control custom-radio">
													<input type="radio" onclick="obtener_subcategoria(<?php echo $categorias[$i]->id_categorias ?>)" id="customRadioC<?php echo $categorias[$i]->id_categorias; ?>" class="custom-control-input" value="<?php echo $categorias[$i]->id_categorias; ?>" name="categoria" >
													<label class="custom-control-label color-black f-11" for="customRadioC<?php echo $categorias[$i]->id_categorias; ?>" id="label<?php echo $categorias[$i]->id_categorias; ?>"><?php echo $categorias[$i]->categoria; ?></label>
												</div>
											</div>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!--Subcategorias-->
                            <div class="card">
                                <div id="headingSubcategoria" class="b-radius-0 card-header">
                                    <button type="button" data-toggle="collapse" data-target="#collapseSubcategoria" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-12">Subcategorias: <span id="sub_categoria_name"></span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
                                </div>
                                <div data-parent="#accordion" id="collapseSubcategoria" aria-labelledby="headingSubcategoria" class="collapse">
                                    <div class="card-body" style="padding-left:1.5rem" id="div-subcategorias">
                                        
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Subcategorias -->
                            <!-- Seccion -->
                            <div class="card">
                                <div id="headingSeccion" class="b-radius-0 card-header">
                                    <button type="button" data-toggle="collapse" data-target="#collapseSeccion" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-12">Sección: <span id="seccion_nombre"></span>
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
										<p class="m-0 p-0 color-black f-12">Serv. adicionales: <span id="nombre_servicio"></span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
                                </div>
                                <div data-parent="#accordion" id="collapseServicios" class="collapse">
                                    <div class="card-body" style="padding-left:1.5rem">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_1" onclick="serv_seleccionado('Silla de ruedas')" name="serv_1" value="1">
                                            <label class="custom-control-label f-11 color-black" for="serv_1">Silla de ruedas</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_2" onclick="serv_seleccionado('Cajero automatico')" name="serv_2" value="2">
                                            <label class="custom-control-label f-11 color-black" for="serv_2">Cajero automatico</label>
                                        </div>
                                        <div>
                                            <label class="color-black f-11">Estacionamiento</label>
                                        </div>
                                        <ul class="list-group ml-3">
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_3" onclick="serv_seleccionado('Privado')" name="serv_3" value="3">
                                                    <label class="custom-control-label f-11 color-black" for="serv_3">Privado</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_4" onclick="serv_seleccionado('Valet Parking')" name="serv_4" value="4">
                                                    <label class="custom-control-label f-11 color-black" for="serv_4">Valet Parking</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_5" onclick="serv_seleccionado('Calle')" name="serv_5" value="5">
                                                    <label class="custom-control-label f-11 color-black" for="serv_5">Calle</label>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_6" onclick="serv_seleccionado('Mesas al aire libre')" name="serv_6" value="6">
                                            <label class="custom-control-label f-11 color-black" for="serv_6">Mesas al aire libre</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_7" onclick="serv_seleccionado('Pantallas')" name="serv_7" value="7">
                                            <label class="custom-control-label f-11 color-black" for="serv_7">Pantallas</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_8" onclick="serv_seleccionado('Reservaciones')" name="serv_8" value="8">
                                            <label class="custom-control-label f-11 color-black" for="serv_8">Reservaciones</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_9" onclick="serv_seleccionado('Sanitarios')" name="serv_9" value="9">
                                            <label class="custom-control-label f-11 color-black" for="serv_9">Sanitarios</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_10" onclick="serv_seleccionado('Servicio a domicilio')" name="serv_10" value="10">
                                            <label class="custom-control-label f-11 color-black" for="serv_10">Servicio a domicilio</label>
                                        </div>
                                        <div>
                                            <label class="color-black f-11">Tarjetas</label>
                                        </div>
                                        <ul class="list-group ml-3">
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_11" onclick="serv_seleccionado('Visa')" name="serv_11" type="checkbox" value="11">
                                                    <label class="custom-control-label f-11 color-black" for="serv_11">Visa</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_12" onclick="serv_seleccionado('Master Card')" name="serv_12" value="12">
                                                    <label class="custom-control-label f-11 color-black" for="serv_12">Master Card</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="serv_13" onclick="serv_seleccionado('American Express')" name="serv_13" value="13">
                                                    <label class="custom-control-label f-11 color-black" for="serv_13">American Express</label>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_14" onclick="serv_seleccionado('WiFi')" name="serv_14" value="14">
                                            <label class="custom-control-label f-11 color-black" for="serv_14">WiFi</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_15" onclick="serv_seleccionado('Zona de cigarrillos')" name="serv_15" value="15">
                                            <label class="custom-control-label f-11 color-black" for="serv_15">Zona de cigarrillos</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="serv_16" onclick="serv_seleccionado('Zona de niños')" name="serv_16" value="16">
                                            <label class="custom-control-label f-11 color-black" for="serv_16">Zona de niños</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Seccion -->
                            <!-- Zona -->
                            <div class="card">
                                <div id="headingZona" class="b-radius-0 card-header">
                                    <button type="button" data-toggle="collapse" data-target="#collapseZona" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-12">Zona: <span id="zona_nombre"></span>
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
										<p class="m-0 p-0 color-black f-12">Ordenar por: <span id="nombre_o"></span>
										<i style="font-size: 20pt; margin-top: -1px; float:right;"	class="metismenu-state-icon pe-7s-angle-right caret-left"></i></p>
									</button>
                                </div>
                                <div data-parent="#accordion" id="collapseOrdenar" class="collapse">
                                    <div class="card-body" style="padding-left:1.5rem">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="o_1" onclick="nombre_ordenar(this)" name="o_1" value="1" checked>
                                            <label class="custom-control-label f-11 color-black" for="o_1">Ubicación</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="o_2" onclick="nombre_ordenar(this)" name="o_2" value="2">
                                            <label class="custom-control-label f-11 color-black" for="o_2">Mejor Calificación</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="o_3" onclick="nombre_ordenar(this)" name="o_3" value="3">
                                            <label class="custom-control-label f-11 color-black" for="o_3">Abierto ahora</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="o_4" onclick="nombre_ordenar(this)" name="o_4" value="4">
                                            <label class="custom-control-label f-11 color-black" for="o_4">Ultima actualización</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ordenar por -->
                        </div>
                        <div class="container-fluid pl-3 pr-3">
                            <button type="submit" class="btn btn-ubicalos btn-block">Aplicar filtro</button>
                        </div>

                    </div>
                </div>
            </form>
		</div>	
		<?php
    	if($promociones != FALSE){


			$total = count($promociones);

			for($_pagina=1; $_pagina<=$total_paginas; $_pagina++)
			{
				$_inicio = ($_pagina*10)-10;

				if($_pagina!=$total_paginas)
				{
					$_fin = $_inicio + 10; 
				}else{
					$_fin = $_inicio + $ultimas;
				}

				if($_pagina==1)
				{
					$display = "block";
				}else{
					$display = "none";
				}

				echo '<div id="p'.$_pagina.'" class="container-fluid pl-0" style="display:'.$display.'">';
				
				for($p=$_inicio; $p<$_fin; $p++){
					if($promociones[$p]->foto == null){
					?>
					<!-- Card para porcentaje -->
					<?php 
						$sucursales ="";
						for($i=0; $i<count($promociones_sucursales[$promociones[$p]->id_promociones]); $i++){
							$sucursales .= "sucursales[]=".$promociones_sucursales[$promociones[$p]->id_promociones][$i]."&";
						}
						$id_sucursal = $promociones_sucursales[$promociones[$p]->id_promociones][0];
					?>
					<a href="<?php echo base_url();?>Empresa/Promocion_Sucursales?<?php echo "id_empresa=".$promociones[$p]->id_empresa."&id_sucursal=".$id_sucursal; ?>&id_promociones=<?php echo $promociones[$p]->id_promociones."&i=".$i."&".$sucursales ?>">
						<div class="row">
							<div class="col-12 pl-1">
								<div class="card" style="max-width: 940px;">
									<div class="row no-gutters">
										<div class="col-8">
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
													<?php echo $promociones[$p]->titulopromo; ?> </p>
												<p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11"><?php 
											$descripcion = "";
											$descripcion .= $promociones[$p]->descripcion ."";
											if(strlen($descripcion) < 50)
											{   for($i=strlen($descripcion);$i<50;$i++){
													$descripcion .= "&nbsp";
												}
												echo $descripcion;
											}
											else{
												$descripcion_C = substr($descripcion, 0, 50);
												$descripcion_C .="...";
												echo $descripcion_C;

											} ?></p>
												<div class="card-text mb-0 pb-0 mt-0 pt-0">
													<p class="text-danger f-11" >Vigencia: 
														<?php 
												/* Función tiempo restante para los anuncios */
												date_default_timezone_set('America/Mexico_City');
												$hoy = getdate();
												$h = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
												$fechaActual = new DateTime($h);
												$finaliza= new DateTime($promociones[$p]->fecha_fin);
												
												$dteDiff  = $fechaActual->diff($finaliza);
												print $dteDiff->format("%dd %Hh %Im");
												?>
													</p>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div class="align-items-center d-flex justify-content-center  img-cards bg-porcentaje">
												<p class="card-text text-porcentaje" id="porcentaje_valor">
													<?php echo $promociones[$p]->porcentaje?>%</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
						</div>
					</a>
					<!-- Fin card para porcentaje -->
					<?php }else{ ?>
					<?php 
						$sucursales ="";
						for($i=0; $i<count($promociones_sucursales[$promociones[$p]->id_promociones]); $i++){
							$sucursales .= "sucursales[]=".$promociones_sucursales[$promociones[$p]->id_promociones][$i]."&";
						}
						$id_sucursal = $promociones_sucursales[$promociones[$p]->id_promociones][0];
					?>
					<!-- Card para imagen -->
					<a href="<?php echo base_url();?>Empresa/Promocion_Sucursales?<?php echo "id_empresa=".$promociones[$p]->id_empresa."&id_sucursal=".$id_sucursal; ?>&id_promociones=<?php echo $promociones[$p]->id_promociones."&i=".$i."&".$sucursales ?>">
						<div class="row">
							<div class="col-12 pl-1">
								<div class="card" style="max-width: 940px;">
									<div class="row no-gutters">
										<div class="col-8">
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
													<?php echo $promociones[$p]->titulopromo; ?> </p>
												<p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11"><?php 
											$descripcion = "";
											$descripcion .= $promociones[$p]->descripcion ."";
											if(strlen($descripcion) < 50)
											{   for($i=strlen($descripcion);$i<50;$i++){
													$descripcion .= "&nbsp";
												}
												echo $descripcion;
											}
											else{
												$descripcion_C = substr($descripcion, 0, 50);
												$descripcion_C .="...";
												echo $descripcion_C;

											} ?></p>
												<div class="card-text mb-0 pb-0 mt-0 pt-0">
													<p class="text-danger f-11">Vigencia: 
														<?php 
												/* Función tiempo restante para los anuncios */
												date_default_timezone_set('America/Mexico_City');
												$hoy = getdate();
												$h = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
												$fechaActual = new DateTime($h);
												$finaliza= new DateTime($promociones[$p]->fecha_fin);
												
												$dteDiff  = $fechaActual->diff($finaliza);
												print $dteDiff->format("%dd %Hh %Im");
												?>
													</p>
												</div>
											</div>
										</div>
										<!-- Imagen -->
										<div class="col-4">
											<img class="card-img img-cards"
												src="<?php echo $this->config->item('url_ubicalos');?>PromocionesEmpresa/<?php echo $promociones[$p]->id_empresa.'/'.str_replace("´", "'",$promociones[$p]->foto);?>"
												alt="...">
										</div>
									</div>
								</div>
							</div>
							<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
						</div>
					</a>
					<!-- Fin card para imagen -->
					<?php    }
				}  

				echo '</div>';
			}
		} 
		?>
