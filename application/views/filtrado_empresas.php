<div class="app-main__outer">
    <div class="app-main__inner">
        <!-- publicidad banner -->
        <div class="row" id="publicidad-home-banner" style="margin-top:-15px">
        </div>

        <div class="row  mb-n2">
            <hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
        </div>
        <!--Categorias-->
        <div class="row mb-n4 pl-n3 pr-n3">
            <div class="col-9">
                <b><p class="f-12" style="color: #495057;"><?php echo $categoria[0]->categoria; ?></p></b>
            </div>
            <div class="col-3 text-right">
                <a class="color-blue-ubicalos f-12" data-toggle="collapse" href="#collapseFiltro" id="filtro_" >Filtro +</a>
            </div>
        </div>

        <div class="row mb-n3">
            <hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
        </div>
        <div class="row mb-n2">
			<form action="filtrado" method="GET" style="width: 100%;">
                <div class="col-12">
					<input type="hidden" name="categoria" value="<?php echo $id_categoria; ?>">
					<input type="hidden" id="latitud" name="latitud">
					<input type="hidden" id="longitud" name="longitud">
                </div>
                <div class="col-12 ml-0 pl-0 mr-0 pr-0">
                    <div class="collapse" id="collapseFiltro">

                        <div id="accordion" class="accordion-wrapper mb-3">
                            <!--Subcategorias-->
                            <div class="card">
                                <div id="headingSubcategoria" class="b-radius-0 card-header">
                                    <button type="button" data-toggle="collapse" data-target="#collapseSubcategoria" class="text-left m-0 p-0 btn btn-link btn-block">
										<p class="m-0 p-0 color-black f-12">Subcategorias: <span id="sub_categoria_name"><?php
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
                                        <div class="form-check p-0 ">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio<?php echo $i;?>" class="custom-control-input" value="<?php echo $subcategorias_filtro[$i]->id_subcategoria; ?>" name="sub_cat" 
												<?php 
													if(!empty($_GET['sub_cat']))
													{
														if($subcategorias_filtro[$i]->id_subcategoria == $_GET['sub_cat']){ echo "checked";}
													}
												?>>
                                                <label class="custom-control-label color-black f-11" for="customRadio<?php echo $i;?>"><?php echo $subcategorias_filtro[$i]->subcategoria; ?></label>
                                            </div>
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

                </forn>
            </form>

			<div id="publicidad_pagina" class="mt-2">

			</div>
            
			<?php

				if($empresas != FALSE)
				{
					$div_empresas = "";
					$total = count($empresas);
					
					for($p=1; $p<=$total_paginas;$p++)
					{
						$inicio = ($p*10)-10;

						if($p!=$total_paginas)
						{
							$fin = $inicio + 10; 
						}else{
							$fin = $inicio + $ultimas;
						}

						if($p==1)
						{
							$display = "block";
						}else{
							$display = "none";
						}
						
						$div_empresas .= '<div id="p'.$p.'" class="container-fluid pl-0" style="display:'.$display.'">';

						for($i=$inicio; $i<$fin; $i++)
						{

							if($empresas[$i]->foto_perfil != NULL)
							{
								$foto = $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$empresas[$i]->id_empresa.'/'.$empresas[$i]->foto_perfil;
							}else{
								$foto = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
							}

							$sub_sec = $empresas[$i]->subcategoria." / ".$empresas[$i]->secciones;
							if(strlen($sub_sec) > 25)
							{   
								$sub_sec = substr($sub_sec, 0, 25);
								$sub_sec .="...";
							}

							$direccion = $empresas[$i]->tipo_vialidad." ".$empresas[$i]->calle." num. ext. ".$empresas[$i]->num_inter;
							if($empresas[$i]->num_inter == 0){
								$direccion .= ", num. int. ".$empresas[$i]->num_inter;
							}
							if(strlen($direccion) > 50){
								$direccion = substr($direccion, 0, 40);
								$direccion .="...";
							}

							$foto_galeria = $this->bases->get_Imagen_Empresa($empresas[$i]->id_sucursal);
							if($foto_galeria != FALSE){
								$foto_suc = $this->config->item('url_ubicalos')."ImagenesEmpresa/".$empresas[$i]->id_empresa."/".str_replace("´", "'",$foto_galeria[0]->nombre);
							}else{
								$foto_suc = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
							}

							if($horario_array[$i] == "TRUE")
							{
								$abierto = '
								<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
									<font class="color-green ">Abierto ahora: </font><font class="color-black">'.$horario_matriz_array[$i].'</font>
								</p>';
								
							}else{
								$abierto = '
								<div class="col-12">
									<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
										<font class="color-red">Cerrado </font>
									</p>
								</div>';
							}

                            /* */
                            
                            $calificacion = $empresas[$i]->calificacion;
                            $estrellas = '';
                            for($cont_calificacion=5; $cont_calificacion>0; $cont_calificacion-- )
                            {	
                                if($cont_calificacion <= $calificacion){
                                    $estrellas .= '<label for="radio'.$cont_calificacion.'" class="estrellas checked" >★</label>';
                                }else {
                                    $estrellas .= '<label for="radio'.$cont_calificacion.'" >★</label>';
                                }
                            }

							$div_empresas .= '<div class="row mb-n2 mt-1">
								<div class="col-12 ml-1 pl-2 mr-0 pr-0">
									<a href="'.base_url().'Empresa/Inicio?id_empresa='.$empresas[$i]->id_empresa.'&id_sucursal='.$empresas[$i]->id_sucursal.'">
										<div class="card ml-3 mr-3" style="max-width: 940px;">
											<div class="row no-gutters">

												<div class="col-auto">
														<img class="card-img img-cards" src="'.$foto_suc.'">
													</div>

												<div class="card-body mt-0 pt-0">
													<p class="mb-0 pb-0 color-black f-13">'.$empresas[$i]->nombre.'</p>
													<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">'.$sub_sec.'</p>
													<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En: Zona '.$empresas[$i]->zona.'</p>
													<div class="row mb-2">
														<div class="col-12">
															<img class="img-fluid img-home-categorias" src="'.$foto.'">
															<font class="estrellas mt-2">
																<font class="clasificacion mb-0">'
                                                                    .$estrellas.
																'</font>
															</font>
														</div>
													</div>
												</div>
											</div>
										</div>
									</a>
									<div class="w-100 mt-n2">
										<div class="col-12 ml-0 pl-0 mr-0 pr-0">
											<div class="card ml-3 mr-3" style="max-width: 940px;">
												<div class="row no-gutters">
													'.$abierto.'
													<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0">'.$direccion.'</p>
													<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Col. '.$empresas[$i]->colonia.' C.P. '.$empresas[$i]->cp.'</p>
													<div class="col-12">
														<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">Ult. Vez: '.$empresas[$i]->actualizacion.'</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="w-100 mt-0">
									<hr class="linea-division p-0 mt-2" />
								</div>
							</div>';

							
						}

						$div_empresas.='</div>';
					}
					echo $div_empresas;
				}

			?>
            
			
		</div>
		
    
