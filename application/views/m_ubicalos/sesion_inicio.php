        
        
<?php $div_galeria = false; if($galeria_sesion != FALSE){  $div_galeria = true;    ?>

<div class="row mb-n2" style="margin-top: -32px;">
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Galería</p></b>
	</div>
	<div class="col-4">
		<a onclick="galeria_div();"><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="galeria_div" class="row mt-n2 ml-n2 mr-n3">

	<div class="col-12 pl-0 pr-0 ml-n2">
		<div id="galeria-owl" class="owl-carousel owl-theme p-0">
			<?php $img_number = 0;
				foreach($galeria_sesion as $g){ ?>
				
				<div class="item">
					<div class="col-2 mu-r mu-b">
						<div class="card card-ubicalos-img" onClick="verModal('<?php echo $img_number; ?>');">
							<div class="img-container">
								<img class="card-img-top card-img-galeria" src="
								<?php echo $this->config->item('url_ubicalos');?>ImagenesEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$g->nombre);?>
								">
							</div>
						</div>
					</div>

				</div>

			<?php $img_number++; } ?>        
		</div>
	</div>

</div>

<?php } ?>

<?php if($informacion_sesion != ""){ ?>

<div class="row <?php if($div_galeria){ echo ' mt-n4';} ?> mb-n2" <?php if(!$div_galeria){ echo 'style="margin-top: -32px;"';} ?>>
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Información</p></b>
	</div>
	<div class="col-4">
		<a onclick="informacion_div();" ><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="informacion_div" class="row mt-n2 mb-n2 ml-n3 mr-n3">
	<div class="col-12"> 
		<p class="mb-0" style="font-size: 13pt;">Información general</p>
		<p style="font-size: 11pt;">
		<?php 
			if(strlen($informacion_sesion) < 170)
			{
				$informacion_sesion = substr($informacion_sesion, 0, strlen($informacion_sesion));
				echo nl2br($informacion_sesion);
			}else{
				$info_c = substr($informacion_sesion, 0, 170);
				$info_c .= '...';
				echo nl2br($info_c);
			}
		?>
		</p>
	</div>
	<div class="col-12 mt-2"> 
		<p class="mb-0" style="font-size: 13pt;">Servicios y productos</p>
		<p style="font-size: 11pt;">
		<?php
			if(strlen($servicios_sesion) < 170)
			{
				$servicios_sesion = substr($servicios_sesion, 0, strlen($servicios_sesion));
				echo nl2br($servicios_sesion);
			}else{
				$info_c = substr($servicios_sesion, 0, 170);
				$info_c .= '...';
				echo nl2br($info_c);
			}
		?>
		</p>
	</div>
	<?php if(!empty($Lunes1) || !empty($Martes1) || !empty($Miércoles1) || !empty($Jueves1) || !empty($Viernes1) || !empty($Sábado1) || !empty($Domingo1)){ ?>
		<div class="col-12 mt-2"> 
			<p class="mb-0" style="font-size: 13pt;">Horario</p>
			<?php 
			$total_horario = 0;
			for($i=1; $i<=7; $i++){

				if($total_horario > 2){
					break;
				}
				switch($i)
				{
					case 1:
						if(!empty($Lunes1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Lunes</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;' >".substr($Lunes1->hora_apertura,0,5)." - ".substr($Lunes1->hora_cierre,0,5);
									if(!empty($Lunes2)){
										echo " / ".substr($Lunes2->hora_apertura,0,5)." - ".substr($Lunes2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";
						}
						break;
					case 2:
						if(!empty($Martes1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Martes</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Martes1->hora_apertura,0,5)." - ".substr($Martes1->hora_cierre,0,5);
									if(!empty($Martes2)){
										echo " / ".substr($Martes2->hora_apertura,0,5)." - ".substr($Martes2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";
						}
						break;
					case 3:
						if(!empty($Miércoles1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Miércoles</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Miércoles1->hora_apertura,0,5)." - ".substr($Miércoles1->hora_cierre,0,5);
									if(!empty($Miércoles2)){
										echo " / ".substr($Miércoles2->hora_apertura,0,5)." - ".substr($Miércoles2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";
						}
						break;
					case 4:
						if(!empty($Jueves1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Jueves</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Jueves1->hora_apertura,0,5)." - ".substr($Jueves1->hora_cierre,0,5);
									if(!empty($Jueves2)){
										echo " / ".substr($Jueves2->hora_apertura,0,5)." - ".substr($Jueves2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";
						}
						break;
					case 5:
						if(!empty($Viernes1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Viernes</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Viernes1->hora_apertura,0,5)." - ".substr($Viernes1->hora_cierre,0,5);
									if(!empty($Viernes2)){
										echo " / ".substr($Viernes2->hora_apertura,0,5)." - ".substr($Viernes2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							"; 
						}
						break;
					case 6:
						if(!empty($Sábado1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Sábado</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Sábado1->hora_apertura,0,5)." - ".substr($Sábado1->hora_cierre,0,5);
									if(!empty($Sábado2)){
										echo " / ".substr($Sábado2->hora_apertura,0,5)." - ".substr($Sábado2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";  
						}    
						break;
					case 7:
						if(!empty($Domingo1)){
							$total_horario++;
							echo "
							<div class='row mb-n3'>
								<div class='col-2 mr-3'>
									<p style='font-size: 11pt; color: #262626;'>Domingo</p>
								</div>
								<div class='col-9'>
									<p style='font-size: 11pt; color: #262626;'>".substr($Domingo1->hora_apertura,0,5)." - ".substr($Domingo1->hora_cierre,0,5);
									if(!empty($Domingo2)){
										echo " / ".substr($Domingo2->hora_apertura,0,5)." - ".substr($Domingo2->hora_cierre,0,5);
									}
							echo "</p>
								</div>
							</div>
							";  
						}
						break;
				}
				
			} ?>
			
		</div>
	<?php } ?>

</div>
<?php } ?>

<?php if($promociones != FALSE){ ?>

<div class="row  mb-n2">
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Promociones</p></b>
	</div>
	<div class="col-4">
		<a onclick="promociones_div();"><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="promociones_div" class="row mt-n2 mb-n4 ml-n3 mr-n3">

	<div class="col-12">
	<?php $promo_cont_ = 0;
		foreach ($promociones as $promocion){
			$promo_cont_++;
			if( $promo_cont_ == 3)
			{
				break;
			}
			if($promocion->foto == null){
			?>
			<!-- Card para porcentaje -->
			<?php 
				$sucursales ="";
				for($i=0; $i<count($promociones_sucursales[$promocion->id_promociones]); $i++){
					$sucursales .= "sucursales[]=".$promociones_sucursales[$promocion->id_promociones][$i]."&";
				}
			?>
			<a href="<?php echo base_url();?>Welcome/Promocion_Sucursales?id_promociones=<?php echo $promocion->id_promociones."&i=".$i."&".$sucursales ?>">
				<div class="row">
					<div class="col-12">
						<div class="card" style="max-width: 940px;">
							<div class="row no-gutters">
								<div class="col-8">
								<div class="card-body mt-0 pt-0">
									<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
										<?php echo $promocion->titulopromo; ?> </p>
									<p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11"><?php 
									$descripcion = "";
									$descripcion .= $promocion->descripcion ."";
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
										$finaliza= new DateTime($promocion->fecha_fin);
										
										$dteDiff  = $fechaActual->diff($finaliza);
										print $dteDiff->format("%dd %Hh %Im");
										?>
											</p>
										</div>
									</div>
								</div>
								<div class="col-4">
									<div class="align-items-center d-flex justify-content-center img-cards bg-porcentaje">
										<p class="card-text text-porcentaje" id="porcentaje_valor">
											<?php echo $promocion->porcentaje?>%</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if(count($promociones) != 1){ 
						if($promo_cont_<2) { ?>
							<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
						<?php }
					}?>
				</div>
			</a>
			<!-- Fin card para porcentaje -->
			<?php }else{ ?>
			<?php 
				$sucursales ="";
				for($i=0; $i<count($promociones_sucursales[$promocion->id_promociones]); $i++){
					$sucursales .= "sucursales[]=".$promociones_sucursales[$promocion->id_promociones][$i]."&";
				}
			?>
			<!-- Card para imagen -->
			<a href="<?php echo base_url();?>Welcome/Promocion_Sucursales?id_promociones=<?php echo $promocion->id_promociones."&i=".$i."&".$sucursales ?>">
				<div class="row">
					<div class="col-12">
						<div class="card" style="max-width: 940px;">
							<div class="row no-gutters">
								<div class="col-8">
								<div class="card-body mt-0 pt-0">
									<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
										<?php echo $promocion->titulopromo; ?> </p>
									<p class="card-text mb-0 pb-0 mt-0 pt-0 color-black"><?php 
									$descripcion = "";
									$descripcion .= $promocion->descripcion ."";
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
										$finaliza= new DateTime($promocion->fecha_fin);
										
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
										src="<?php echo $this->config->item('url_ubicalos');?>PromocionesEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$promocion->foto);?>"
										alt="...">
								</div>
							</div>
						</div>
					</div>
					<?php if(count($promociones) != 1){
							if($promo_cont_<2) { ?>
								<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
							<?php }
						} ?>
				</div>
			</a>
			<!-- Fin card para imagen -->
			<?php    }
		}  ?>
	</div>

</div>

<?php } ?>

<?php if($blogs != FALSE){ ?>

<div class="row mt-n4 mb-n2">
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Blogs</p></b>
	</div>
	<div class="col-4">
		<a onclick="blogs_div();" ><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="blogs_div" class="row mt-n2 mb-n2 ml-n3 mr-n3">
	<div class="col-12">
		<?php $blog_cont_= 0;
		foreach ($blogs as $blog){ 
			$blog_cont_++;
			if( $blog_cont_ == 3)
			{
				break;
			} ?>
			<a href="<?php echo base_url();?>Welcome/Sesion_VerMas_Blog?id_blog=<?php echo $blog->id_blog?>">
				<div class="row">
					<div class="col-12">
						<div class="card" style="max-width: 940px;">
							<div class="row no-gutters">
								<div class="col-8">
								<div class="card-body mt-0 pt-0 pb-0 mb-1">
									<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
										<?php echo $blog->titulo; ?></p>
									<p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11"><?php 
									$descripcion = "";
									$descripcion .= $blog->blog ."";
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
											<font class="f-11" class="color-black">
												<?php
												date_default_timezone_set('America/Mexico_City');
												$hoy = getdate();
								
												/*Representacion numérica de las horas	0 a 23*/
												$h = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
												$Fecha_Actual = new DateTime($h);
												$Fecha_blog   = new DateTime($blog->fecha);
												$interval = date_diff($Fecha_blog, $Fecha_Actual);
												echo $interval->format('Hace %d días');
												?>
											</font>  
										</div>
									</div>
								</div>
								<div class="col-4">
									<?php if (!empty($blog->imagen)) {?>
											<img class="card-img img-cards" src="<?php echo $this->config->item('url_ubicalos');?>FotosBlogEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$blog->imagen);?>"  alt="...">
									<?php }else{ ?>
											<img class="card-img img-cards"   src="<?php echo base_url();?>img/IMAGEN EVENTOS Y BLOGS.png" alt="...">
									<?php }?>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-12">
									<img class="img-fluid ml-2 img-blogs" 
										src="<?php 
										if(!empty($foto_perfil))
											{
												echo $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$id_empresa.'/'.str_replace("´", "'",$foto_perfil[0]->foto_perfil);
											}else{
												echo $this->config->item('url_ubicalos')."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";
											}
									?>">  <font class="ml-1 color-black f-11"> <?php echo $nombre_empresa[0]->nombre; ?> </font>
								</div>
							</div>
						</div>
					</div>
					<?php if(count($blogs) != 1){
						if($blog_cont_<2) { ?>
							<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
						<?php
						}
					 }?>
				</div>
			</a>
		<?php } ?>
	</div>
</div>

<?php } ?>

<?php if(!empty($eventos)){ ?>

<div class="row  mb-n2">
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Eventos</p></b>
	</div>
	<div class="col-4">
		<a onclick="eventos_div();"><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="eventos_div" class="row mt-n2 mb-n4 ml-n3 mr-n3">
	<div class="col-12">
	<?php for($i=0; $i<count($eventos); $i++){
		if($i == 2)
			break;
	?>
			<a href="<?php echo base_url();?>Welcome/Sesion_Mostrar_Evento?evento=<?php echo $eventos[$i]->id_evento;?>" style="color: black">
			<div class="row">
				<div class="col-12">
					<div class="card" style="max-width: 940px;">
						<div class="row no-gutters">
							<div class="col-8">
								<div class="card-body mt-0 pt-0">
									<p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;"><?php echo $eventos[$i]->nombre; ?></p>
									<p class="card-text  mt-0 mb-0 pb-0 pt-0 f-11 color-green" >
										<?php   
										setlocale(LC_TIME, 'es_ES.UTF-8');
										if(!empty($fecha_evento[$i]->fecha))
										{
										echo ucfirst(strftime("%A %d,%b,%y", strtotime(strftime($fecha_evento[$i]->fecha))))." ".date("H:i", strtotime($fecha_evento[$i]->hora) )." hrs<br>";    
										}else{
											echo "<font class='f-11' style='color:red;'>Evento finalizado </font>";
										}
										?>
									</p>
									<p class="card-text  mt-0 mb-0 pb-0 pt-0 f-11 color-black">
										<?php echo $concepto_evento[$i]->concepto.": ".$concepto_evento[$i]->precio; ?>
									</p>
									<p  class="cart-text mt-0 mb-0 pb-0 pt-0 f-11 color-blue-ubicalos">
										En zona : <?php echo $eventos[$i]->zona; ?>
									</p>
									<div class="card-text mb-0 pb-0 mt-0 pt-0 ">
										<p class="f-11" style="color:black;">
										<?php 
												$descripcion = "";
												$descripcion .= $eventos[$i]->sinopsis ."";
												if(strlen($descripcion) < 60)
												{   
													$descripcion_C = substr($descripcion, 0, strlen($descripcion));
													$descripcion_C .="...";
													echo $descripcion_C;
												}
												else{
													$descripcion_C = substr($descripcion, 0, 60);
													$descripcion_C .="...";
													echo $descripcion_C;

												} 
										?>
										</p>
									</div>
								</div>
							</div>
							<!-- Imagen -->
							<div class="col-4">
								<?php if($eventos[$i]->imagen != "sin_imagen"){ ?>
									<img class="card-img img-cards" src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$eventos[$i]->imagen;?>" >
								<?php }else{ ?>
									<img class="card-img img-cards"  src="<?php echo base_url();?>img/IMAGEN EVENTOS Y BLOGS.png"  alt="...">
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php if(count($eventos) != 1){ ?>
					<?php if($i<1) { ?>
						<hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;"/>
					<?php }?>
				<?php }?>
			</div>
			</a>
		<?php } ?>
	</div>
</div>

<?php } ?>

<?php if($videos_sesion != FALSE){ ?>
<div class="row  mb-n2">
	<hr style="border: 5px solid #e8eef1; width: 100%;"/>
</div>

<div class="row mb-n4 ml-n3 mr-n3">
	<div class="col-8">
		<b><p class="f-12">Videos</p></b>
	</div>
	<div class="col-4">
		<a><i style="font-size: 20pt; margin-top: -1px; float:right;" class="metismenu-state-icon pe-7s-angle-down caret-left"></i></a>
	</div>
</div>

<div class="row mb-2">
	<hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
</div>

<div id="" class="row mt-n2 mb-2">

		<?php $t_v = 0; foreach($videos_sesion as $g){  if($t_v == 3){break;} $t_v++;?>
			<div class="col-2 mu-r mu-b">
				<div class="card card-ubicalos-img" onClick="mostrarModalVideo('<?php echo $g->id_imagen; ?>');">
					<div class="embed-responsive embed-responsive-1by1" >
						<video class="embed-responsive-item" style="background-color: black; border-radius: 3px;">
							<source src="<?php echo $this->config->item('url_ubicalos');?>VideosEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$g->nombre);?>" type="video/mp4">
						</video> 
					</div>
				</div>
			</div>
		<?php } ?>
</div>

<?php } ?>

<div class="modal" id="carruselModal" style="z-index: 10;">
<div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
	<div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1);">
		<button type="button" class="close close-modal" data-dismiss="modal" onClick="cerrarModalGV();"
			style="color: white;">&times;</button>
	</div>
	<div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;"">

		<div id="carrusel_imagenes" class="owl-carousel owl-theme">

			<?php if($galeria_sesion != FALSE){ 
						$i=0;
						foreach($galeria_sesion as $g){ ?>
						<div class="row ml-n1">
							<div class="item">
								<div class="img-container">
									<img style="border-radius: 2px; width: 100%; height: 100%;" class="img-fluid"
										src="<?php echo $this->config->item('url_ubicalos'); ?>ImagenesEmpresa/<?php echo $id_empresa; ?>/<?php echo str_replace("´", "'",$g->nombre); ?> ">
								</div>
							</div>
							<div class="col-12 mt-3 mr-2">
								<div class="row">
									<div class="col-auto mr-n4">
										<img style="border-radius: 50%; width: 35px; height: 35px;" id="imagenprincipal-modal"
											<?php if($foto_perfil == FALSE){ ?>
											src="<?php echo base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";?>"
											<?php }else{ ?>
											src="<?php echo $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".str_replace("´", "'",$foto_perfil[0]->foto_perfil);?>"
											<?php } ?>>
									</div>
									<div class="col-8">
										<p class="mt-2 ml-1" style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle">
											<?php echo $nombre_empresa[0]->nombre; ?></p>
									</div>
								</div>
							</div>
							<div class="col-12">
								<p style="color: white; font-size: 11pt;">
									<?php
										if(strlen($g->descripcion) == 0){
											echo "Sin descripción";
										}else{
											echo $g->descripcion;
										}
										
									?>
								</p>
							</div>
						</div>
			<?php $i++; } 
					} ?>

		</div>
	</div>
</div>
</div>

<div class="modal" id="carruselModalV"  style="z-index: 10;">
<div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
	<div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1);">
		<button type="button" class="close close-modal" data-dismiss="modal" onClick="cerrarModalGV();" style="color: white;">&times;</button>
	</div>
	<div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;"">
		<div class="container">
			<div class="row">
				<div id="div_video" class="embed-responsive embed-responsive-1by1">
				
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-auto mr-n4">
					<img style="border-radius: 50%; width: 35px; height: 35px;" id="imagenprincipal-modal"
						<?php if($foto_perfil == FALSE){ ?>
							src="<?php echo base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";?>"
						<?php }else{ ?>
							src="<?php echo $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".str_replace("´", "'",$foto_perfil[0]->foto_perfil);?>"
						<?php } ?>
					>
				</div>
				<div class="col-8">
					<p class="mt-2 ml-1" style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle"><?php echo $nombre_empresa[0]->nombre; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
