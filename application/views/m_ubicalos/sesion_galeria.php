
<?php if($galeria == FALSE){ ?>
<div class="text-center mt-4 mb-5" style="resize:none;">
    <img class="mb-4 mt-4" style="width: 22%;" src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_GALERIA_VACIO.png">
    <p class="mb-n1" style="font-size: 18pt;">Sin fotos</h1>
        <p class="mt-3" style="font-size: 10pt;">Este negocio aún no cuenta con fotos.</p>
</div>
<?php
            }else{?>
<div class="row mr-n3 mb-1">
    <?php $img_number = 0;
                    foreach($galeria as $g){ ?>

    <div class="col-2 mu-r mu-b">
        <div class="card card-ubicalos-img"
            onClick="mostrarModal('<?php echo $img_number; ?>','<?php echo $total_img; ?>');">
            <div class="img-container">
                <img class="card-img-top card-img-galeria" src="
                                    <?php echo $this->config->item('url_ubicalos');?>ImagenesEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$g->nombre);?>
                                    ">
            </div>
        </div>
    </div>

    <?php $img_number++; } ?>
</div>
<?php  } ?>

<div class="modal" id="carruselModal" style="z-index: 10;">
    <div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
        <div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1);">
            <button type="button" class="close close-modal" data-dismiss="modal" onClick="cerrarModalGV();">&times;</button>
        </div>
        <div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;">

            <div id="carrusel_imagenes" class="owl-carousel owl-theme">

                <?php if($galeria != FALSE){ 
                            $i=0;
                            foreach($galeria as $g){ ?>
                <div class="row">
                    <div class="item">
                        <div class="img-container">
                            <img style="border-radius: 0px; width: 100%; height: 100%;" class="img-fluid"
                                src="<?php echo $this->config->item('url_ubicalos'); ?>ImagenesEmpresa/<?php echo $id_empresa; ?>/<?php echo str_replace("´", "'",$g->nombre); ?> ">
                        </div>
                    </div>
                    <div class="col-12 ml-2 mt-3 mr-2">
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
                                <p class="mt-2 " style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle">
                                    <?php echo $nombre_empresa[0]->nombre; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 ml-2 ">
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
