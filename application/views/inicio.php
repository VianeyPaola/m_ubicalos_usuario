<div class="app-main__outer">
	<div class="app-main__inner">
		<!-- publicidad banner -->

		<?php if($mas_buscados != FALSE){ ?>
			<div class="row mb-n4" style="padding-top: 0px; margin-top: -2px; margin-bottom: -47px !important;">
            
				<div id="categorias-buscadas" class="owl-carousel" style="background-color: white; z-index: 0 !important;">
					<a href="#"><div><img id="img_0" src="<?php echo base_url(); ?>img/00.- +BUSCADO.svg"></div></a>
					
					<?php for($i=0; $i<count($mas_buscados); $i++){ ?>
						<a href="<?php echo base_url(); ?>Welcome/filtro_resultado?categoria=<?php echo $mas_buscados[$i]->id_categorias; ?>&sub_cat=0"><div><img id="img_0" src="<?php echo base_url(); ?>img/<?php echo $mas_buscados[$i]->id_categorias;  ?>.svg"></div></a>
					<?php } ?>
				</div>
				
			</div>
		<?php } ?>

		<div class="row publicidad-banner" id="publicidad-home-banner" style="padding-top: 38px">
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
				<a href="filtro_resultado?categoria=<?php echo $categorias_rand[$i]->id_categorias; ?>&sub_cat=0"><b><p class="f-12" style="color: #495057;"><?php echo $categorias_rand[$i]->categoria; ?></p></b></a>
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
						<a href="<?php echo base_url();?>Empresa/Inicio?<?php echo "id_empresa=".$publicidad_cat->id_empresa."&id_sucursal=".$publicidad_cat->id_sucursal ?>">
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
														
														<?php if($tiene_promocion[$i] == TRUE){ ?>
															<div class="carousel-caption promocion">
																<p class="color-black f-9">Promoción</p>
															</div>
														<?php } ?>

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
																<?php 
																	$calificacion = $sucursal['suc']->calificacion; 
																	$estrellas = '';
																	for($cont_calificacion=5; $cont_calificacion>0; $cont_calificacion-- )
																	{	
																		if($cont_calificacion <= $calificacion){
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" class="estrellas checked" >★</label>';
																		}else {
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" >★</label>';
																		}
																	}
																	echo $estrellas;
																	
																?>
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
							<hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
						</div>
					</div>
				</div>

				<div class="row mt-n2 mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="<?php echo base_url();?>Empresa/Inicio?<?php echo "id_empresa=".$sucursal['suc']->id_empresa."&id_sucursal=".$sucursal['suc']->id_sucursal ?>">
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
																<?php 
																	$calificacion = $sucursal['suc']->calificacion; 
																	$estrellas = '';
																	for($cont_calificacion=5; $cont_calificacion>0; $cont_calificacion-- )
																	{	
																		if($cont_calificacion <= $calificacion){
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" class="estrellas checked" >★</label>';
																		}else {
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" >★</label>';
																		}
																	}
																	echo $estrellas;
																	
																?>
																	
																	<!-- <label for="radio5" class="estrellas checked" >★</label>
																	<label for="radio4" >★</label>
																	<label for="radio3" >★</label>
																	<label for="radio2" >★</label>
																	<label for="radio1" >★</label> -->
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
						<a href="<?php echo base_url();?>Empresa/Inicio?<?php echo "id_empresa=".$sucursal['suc']->id_empresa."&id_sucursal=".$sucursal['suc']->id_sucursal ?>">
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
																<?php 
																	$calificacion = $sucursal['suc']->calificacion; 
																	$estrellas = '';
																	for($cont_calificacion=5; $cont_calificacion>0; $cont_calificacion-- )
																	{	
																		if($cont_calificacion <= $calificacion){
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" class="estrellas checked" >★</label>';
																		}else {
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" >★</label>';
																		}
																	}
																	echo $estrellas;
																	
																?>
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
							<hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
						</div>
					</div>
					<?php } ?>

				</div>

				<?php if($j+1 != $total_suc){ 
							$sucursal = $sucursales_array[$j+1];
						?>

				<div class="row mt-n2 mb-n2 ml-0 mr-n3">
					<div class="col-12">
						<a href="<?php echo base_url();?>Empresa/Inicio?<?php echo "id_empresa=".$sucursal['suc']->id_empresa."&id_sucursal=".$sucursal['suc']->id_sucursal ?>">
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
																<?php 
																	$calificacion = $sucursal['suc']->calificacion; 
																	$estrellas = '';
																	for($cont_calificacion=5; $cont_calificacion>0; $cont_calificacion-- )
																	{	
																		if($cont_calificacion <= $calificacion){
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" class="estrellas checked" >★</label>';
																		}else {
																			$estrellas .= '<label for="radio'.$cont_calificacion.'" >★</label>';
																		}
																	}
																	echo $estrellas;
																?>
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
