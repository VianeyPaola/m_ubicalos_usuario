<div class="app-main__outer">
	<div class="app-main__inner">
		<!--Promociones -->
		<!--Promoción 1 -->
		<!-- <div class="row" style="margin-top:-15px">
			<div class="col-md-12 pl-0 pr-0">
				<div id="div_icono_grande">
					<div class="row">
						<div class="img-container">
							<img id="logo_banner" class="card-img-top img-promocion-1"
								src="<?php echo base_url(); ?>img/H IMAGEN PRUEBA PUBLICIDAD.png">
						</div>
					</div>
					<div align="center">
						<a href="#" id="boton_banner" class="btn btn-link btn-promocion-1">Ir a perfil</a>
					</div>
				</div>
				<img id="imagen_banner" style="height:110px; border-radius:0px" class="card-img-top m-0 p-0"
					src="<?php echo base_url(); ?>img/IMAGEN PRUEBA PUBLICIDAD.png">
			</div>
		</div> -->
		<!--Fin Promoción 1 -->
		<!--Promoción 2 -->
		<div class="row" style="margin-top:-15px; ">
			<div class="col pl-0 pr-0" style="flex: 0 0 80%;
			max-width: 80%">
					<img id="imagen_banner" style=" height:50px;border-radius:0px" class="card-img-top m-0 p-0"
						src="<?php echo base_url(); ?>img/IMAGEN PRUEBA PUBLICIDAD.png">
			</div>
			<div  class="col pl-0 pr-0" style="background-color:black;flex: 0 0 20%; max-width: 20%">
				<a href="#" id="boton_banner" class="btn btn-link btn-promocion-2 centrar">Ir a perfil</a>
			</div>
		</div>
		<!--Fin Promoción 2 -->

		<!--Fin promociones -->

		<?php

		$total_categorias_rand = count($categorias_rand);
		for($i=0; $i < $total_categorias_rand; $i++){ 
			if($sucursales_rand[$i] != FALSE){
		?>
		<div class="row  mb-n2">
			<hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
		</div>
		<!--Categorias-->
		<div class="row mb-n4 ml-n3 mr-n3">
			<div class="col-11">
				<p class="f-14" style="color:#171719"><?php echo $categorias_rand[$i]->categoria; ?></p>
			</div>
			<div class="col-1">
				<a onclick=""><i style="font-size: 20pt; margin-top: -1px; float:right;"
						class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
			</div>
		</div>

		<div class="row mb-n3">
			<hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
		</div>

		<div class="owl-carousel owl-theme ml-n3">
			<?php 
					
					$sucursales_array = $sucursales_rand[$i];
					$total_suc = count($sucursales_array);
					$j = 0;
					if($publicidad_categoria_rad[$i] != FALSE)
					{
						$publicidad_cat = $publicidad_categoria_rad[$i];
						$sucursal = $sucursales_array[0];
				?>
			<div class="mt-2">


				<div class="row mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="#">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">
											<?php if($fotos_publicidad_categoria[$i] != FALSE){
														$fotos_publicidad_c = $fotos_publicidad_categoria[$i];
													?>
											<div class="carousel slide" data-ride="carousel" style="margin-right:10px">
												<div class="carousel-inner" style="border-radius: 4px !important;">
													<?php for($k=0; $k<count($fotos_publicidad_c); $k++){ ?>
													<div class="carousel-item <?php if($k==0){ echo "active";} ?>"
														style="border-radius: 4px !important; width: 88px !important; height: 88px !important; ">
														<img style="width: 88px !important; height: 88px !important;" <?php
																				$foto_suc = str_replace("´", "'",$fotos_publicidad_c[$k]->nombre);
																				echo "src='".$this->config->item('url_ubicalos')."ImagenesEmpresa/".$publicidad_cat->id_empresa."/".$foto_suc."'";
																			?>>
													</div>
													<?php } ?>
												</div>
											</div>

											<?php }else{ ?>
											<div class="col-auto">
												<img class="card-img img-cards" <?php
																	echo  'src="'.base_url().'img/IMAGEN EVENTOS Y BLOGS.png"';
																?>>
											</div>

											<?php } ?>

											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $publicidad_cat->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													<?php
															$sub_sec = $publicidad_cat->subcategoria." / ".$publicidad_cat->secciones;
															if(strlen($sub_sec) > 25)
															{   
																$sub_sec = substr($sub_sec, 0, 25);
																$sub_sec .="...";
															}
															echo $sub_sec;
															?>
												</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $publicidad_cat->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" <?php
																				if($publicidad_cat->foto_perfil == NULL)
																				{
																				echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																				}else{
																					$foto_perfil = str_replace("´", "'",$publicidad_cat->foto_perfil);
																					echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$publicidad_cat->id_empresa.'/'.$foto_perfil.'"';
																				}
																				?>>
														<font class="estrellas mt-2">
															<font class="clasificacion mb-0">
																<input id="radio1" type="radio" name="estrellas"
																	value="5">
																<label for="radio1">★</label>
																<input id="radio2" type="radio" name="estrellas"
																	value="4">
																<label for="radio2">★</label>
																<input id="radio3" type="radio" name="estrellas"
																	value="3">
																<label for="radio3">★</label>
																<input id="radio4" type="radio" name="estrellas"
																	value="2">
																<label for="radio4">★</label>
																<input id="radio5" type="radio" name="estrellas"
																	value="1">
																<label for="radio5">★</label>

															</font>
															<img class="img-add"
																src="<?php echo base_url();?>img/ICONO AD.png"
																style="display:true!important; height: 5px; ">
														</font>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- Fin card para porcentaje -->
					</div>
					<div class="w-100 mt-n4">
						<div class="col-12">
							<hr style="border: 0.5px solid #DBDBDB; width: 100%;" />
						</div>
					</div>
				</div>

				<div class="row mt-n2 mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="#">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">

											<div class="col-auto">
												<img class="card-img img-cards" <?php
																if($sucursal['foto'] != FALSE)
																{
																	$foto_suc = str_replace("´", "'",$sucursal['foto'][0]->nombre);
																	echo "src='".$this->config->item('url_ubicalos')."ImagenesEmpresa/".$sucursal['suc']->id_empresa."/".$foto_suc."'";
																}else{
																	echo  'src="'.base_url().'img/IMAGEN EVENTOS Y BLOGS.png"';
																}
															?>>
											</div>
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $sucursal['suc']->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													<?php
															$sub_sec = $sucursal['suc']->subcategoria." / ".$sucursal['suc']->secciones;
															if(strlen($sub_sec) > 25)
															{   
																$sub_sec = substr($sub_sec, 0, 25);
																$sub_sec .="...";
																
															}
															echo $sub_sec;
															?>
												</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $sucursal['suc']->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" <?php
																				if($sucursal['suc']->foto_perfil == NULL)
																				{
																				echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																				}else{
																					$foto_perfil = str_replace("´", "'",$sucursal['suc']->foto_perfil);
																					echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$sucursal['suc']->id_empresa.'/'.$foto_perfil.'"';
																				}
																				?>>
														<font class="estrellas mt-2">
															<font class="clasificacion mb-0">
																<input id="radio1" type="radio" name="estrellas"
																	value="5">
																<label for="radio1">★</label>
																<input id="radio2" type="radio" name="estrellas"
																	value="4">
																<label for="radio2">★</label>
																<input id="radio3" type="radio" name="estrellas"
																	value="3">
																<label for="radio3">★</label>
																<input id="radio4" type="radio" name="estrellas"
																	value="2">
																<label for="radio4">★</label>
																<input id="radio5" type="radio" name="estrellas"
																	value="1">
																<label for="radio5">★</label>

															</font>
														</font>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- Fin card para porcentaje -->
					</div>
				</div>

			</div>

			<?php
						$j = 1;
					}
				?>


			<?php
					while($j < $total_suc){  
						$sucursal = $sucursales_array[$j];
					?>

			<div class="mt-2">

				<div class="row mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="#">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">

											<div class="col-auto">
												<img class="card-img img-cards" <?php
																if($sucursal['foto'] != FALSE)
																{
																	$foto_suc = str_replace("´", "'",$sucursal['foto'][0]->nombre);
																	echo "src='".$this->config->item('url_ubicalos')."ImagenesEmpresa/".$sucursal['suc']->id_empresa."/".$foto_suc."'";
																}else{
																	echo  'src="'.base_url().'img/IMAGEN EVENTOS Y BLOGS.png"';
																}
															?>>
											</div>
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $sucursal['suc']->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													<?php
															$sub_sec = $sucursal['suc']->subcategoria." / ".$sucursal['suc']->secciones;
															if(strlen($sub_sec) > 25)
															{   
																$sub_sec = substr($sub_sec, 0, 25);
																$sub_sec .="...";
															}
															echo $sub_sec;
															?>
												</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $sucursal['suc']->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" <?php
																				if($sucursal['suc']->foto_perfil == NULL)
																				{
																				echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																				}else{
																					$foto_perfil = str_replace("´", "'",$sucursal['suc']->foto_perfil);
																					echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$sucursal['suc']->id_empresa.'/'.$foto_perfil.'"';
																				}
																				?>>
														<font class="estrellas mt-2">
															<font class="clasificacion mb-0">
																<input id="radio1" type="radio" name="estrellas"
																	value="5">
																<label for="radio1">★</label>
																<input id="radio2" type="radio" name="estrellas"
																	value="4">
																<label for="radio2">★</label>
																<input id="radio3" type="radio" name="estrellas"
																	value="3">
																<label for="radio3">★</label>
																<input id="radio4" type="radio" name="estrellas"
																	value="2">
																<label for="radio4">★</label>
																<input id="radio5" type="radio" name="estrellas"
																	value="1">
																<label for="radio5">★</label>

															</font>
														</font>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- Fin card para porcentaje -->
					</div>
					<?php if($j+1 != $total_suc){ ?>
					<div class="w-100 mt-n4">
						<div class="col-12">
							<hr style="border: 0.5px solid #DBDBDB; width: 100%;" />
						</div>
					</div>
					<?php } ?>

				</div>

				<?php if($j+1 != $total_suc){ 
							$sucursal = $sucursales_array[$j+1];
						?>

				<div class="row mt-n2 mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="#">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">

											<div class="col-auto">
												<img class="card-img img-cards" <?php
																	if($sucursal['foto'] != FALSE)
																	{
																		$foto_suc = str_replace("´", "'",$sucursal['foto'][0]->nombre);
																		echo "src='".$this->config->item('url_ubicalos')."ImagenesEmpresa/".$sucursal['suc']->id_empresa."/".$foto_suc."'";
																	}else{
																		echo  'src="'.base_url().'img/IMAGEN EVENTOS Y BLOGS.png"';
																	}
																?>>
											</div>
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $sucursal['suc']->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													<?php
																$sub_sec = $sucursal['suc']->subcategoria." / ".$sucursal['suc']->secciones;
																if(strlen($sub_sec) > 25)
																{   
																	$sub_sec = substr($sub_sec, 0, 25);
																	$sub_sec .="...";
																	
																}
																echo $sub_sec;
																?>
												</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $sucursal['suc']->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" <?php
																					if($sucursal['suc']->foto_perfil == NULL)
																					{
																					echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																					}else{
																						$foto_perfil = str_replace("´", "'",$sucursal['suc']->foto_perfil);
																						echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$sucursal['suc']->id_empresa.'/'.$foto_perfil.'"';
																					}
																					?>>
														<font class="estrellas mt-2">
															<font class="clasificacion mb-0">
																<input id="radio1" type="radio" name="estrellas"
																	value="5">
																<label for="radio1">★</label>
																<input id="radio2" type="radio" name="estrellas"
																	value="4">
																<label for="radio2">★</label>
																<input id="radio3" type="radio" name="estrellas"
																	value="3">
																<label for="radio3">★</label>
																<input id="radio4" type="radio" name="estrellas"
																	value="2">
																<label for="radio4">★</label>
																<input id="radio5" type="radio" name="estrellas"
																	value="1">
																<label for="radio5">★</label>

															</font>
														</font>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- Fin card para porcentaje -->
					</div>
				</div>

				<?php } ?>

			</div>

			<?php 
						$j = $j+2;
					} 
				?>

		</div>

		<?php } 
		} ?>