<div class="app-main__outer">
	<div class="app-main__inner">
		<!-- publicidad banner -->
		<div class="row" id="publicidad-home-banner" style="margin-top:-15px">
		</div>
		
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
				<b><p class="f-12" style="color: #495057;"><?php echo $categorias_rand[$i]->categoria; ?></p></b>
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
														<div class="carousel-caption promocion">
															<p class="color-black f-9">Promoción</p>
														</div>
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
														<font class="estrellas mt-2 ml-n1">
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

		<div id="publicidad_cascada" class="row">
			<div class="col-12 d-flex justify-content-center">
				<div class="float text-center">
					<div class="row" style="width:218px ">
						<div class="col-3 pr-0">
							<img class="img-fluid" style="width: 35px; height: 35px; border: 1px solid white" 
							src="<?php echo base_url() ?>img/IMAGEN EVENTOS Y BLOGS.png">
						</div>
						<div class="col-auto ml-0 pl-0 "><b><font class="f-11 ml-1">Tacos Tacos</font></b></div>
						<div class="w-100"></div>
						<div class="col-auto offset-3 pl-0 " style="margin-top: -1.3rem !important">
							<font class="f-10 ml-1"> En zona: Angelopolis</font>
						</div>
					</div>
				</div>
			</div>

		</div>
