<div class="app-main__outer">
	<div class="app-main__inner">
		<!--Promociones -->
		<div class="row" style="margin-top:-15px">
			<div class="col-md-12 pl-0 pr-0">
				<div id="div_icono_grande">
					<div class="row">
						<div class="img-container">
							<img id="logo_banner" class="card-img-top img-promocion-1"
								src="<?php echo base_url(); ?>img/H IMAGEN PRUEBA PUBLICIDAD.png">
						</div>
					</div>
					<div align="center">
						<a href="" id="boton_banner" class="btn btn-link btn-promocion-1">Ir a perfil</a>
					</div>
				</div>
				<img id="imagen_banner" style="height:110px; border-radius:0px" class="card-img-top m-0 p-0"
					src="<?php echo base_url(); ?>img/IMAGEN PRUEBA PUBLICIDAD.png">
			</div>
		</div>

		<!--Fin promociones -->
		<div class="row  mb-n2">
			<hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
		</div>

		<?php

		$total_categorias_rand = count($categorias_rand);
		for($i=0; $i < $total_categorias_rand; $i++){ 
			if($sucursales_rand[$i] != FALSE){
		?>
		<!--Categorias-->
		<div class="row mb-n4 ml-n3 mr-n3">
			<div class="col-11">
				<p class="f-14" style="color:#171719"><?php echo$categorias_rand[$i]->categoria; ?></p>
			</div>
			<div class="col-1">
				<a onclick=""><i style="font-size: 20pt; margin-top: -1px; float:right;"
						class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
			</div>
		</div>

		<div class="row mb-n3">
			<hr style="border: 0.5px solid #E8EEF1; width: 100%;" />
		</div>

		<div class="owl-carousel">
			<?php 
				
				$suc = $sucursales_rand[$i];
				$total_suc = count($suc);
				$j = 0;

				
				while($j < $total_suc){  ?>

			<div class="mt-2">

				<div class="row mb-n3 ml-n3 mr-n3">
					<div class="col-12">
						<a href="">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">
											<!-- <div class="col-4"> -->
											<div class="col-auto">
												<img class="card-img img-cards"
													src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQitlGoJWaNQol27JmJS0aHldTR3-YfEawQlvEe2XLDbc5d0ut_">
											</div>
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $suc[$j]->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													Restaurante pizza</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $suc[$j]->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" <?php
																		if($suc[$j]->foto_perfil == NULL)
																		{
																		echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																		}else{
																			$foto_perfil = str_replace("´", "'",$suc[$j]->foto_perfil);
																			echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$suc[$j]->id_empresa.'/'.$foto_perfil.'"';
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
															<img class="img-add" src="<?php echo base_url();?>img/ICONO AD.png" style="display:true!important; height: 5px; " >
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
						<div class="w-100">
							<div class="col-12">
								<hr style="border: 0.5px solid #DBDBDB; width: 100%;" />
							</div>
						</div>
					<?php }?>
					
				</div>

				<?php if($j+1 != $total_suc){ ?>
				
				
				<div class="row mb-n3 ml-n3 mr-n3">
					<div class="col-12">
						<a href="">
							<div class="row">
								<div class="col-12">
									<div class="card" style="max-width: 940px;">
										<div class="row no-gutters">
											<!-- <div class="col-4"> -->
											<div class="col-auto">
												<img class="card-img img-cards"
													src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQitlGoJWaNQol27JmJS0aHldTR3-YfEawQlvEe2XLDbc5d0ut_">
											</div>
											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">
													<?php echo $suc[$j]->nombre; ?> </p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">
													Restaurante pizza</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En
													zona
													: <?php echo $suc[$j+1]->zona ?></p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" style="display:true!important" <?php
																			if($suc[$j]->foto_perfil == NULL)
																			{
																			echo 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png"';
																			}else{
																				$foto_perfil = str_replace("´", "'",$suc[$j]->foto_perfil);
																				echo 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$suc[$j]->id_empresa.'/'.$foto_perfil.'"';
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

		<div class="row mt-2 mb-n2">
			<hr class="mt-0 pt-0" style="border: 3px solid #e8eef1; width: 100%;" />
		</div>

		<?php } } ?>
