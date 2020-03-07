
<div class="row">
    <div class="col-12" style="height: 200px">
        <?php if(empty($blog->imagen) ){?>
        <img style="width: 100%; height:100% ; border-radius: 1%"
            src="<?php echo $this->config->item('url_ubicalos');?>img/IMAGEN BLOGS.png">
        <?php }else{ ?>
        <img style="width: 100%; height:100% "
            src="<?php echo $this->config->item('url_ubicalos');?>FotosBlogEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$blog->imagen);?>">
        <?php }?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <p style="font-size: 15pt; color: black;"><b><?php echo $blog ->titulo?></b></p>
        <p class="f-11 mb-n2" style="color: #262626; position: relative; top: -15px;"><i><?php echo $blog ->subtitulo?></i>
        </p>
        <p class="f-11" style="color: #262626;"><?php echo nl2br($blog ->blog);?></p>
        <?php if($galeria_blog != FALSE){?>
        <p class="f-15 color-black mb-1">Galería de blog</p>
        <?php } ?>
    </div>
</div>

<div class="row mr-n3 mb-1">
    <?php if($galeria_blog != FALSE){
            $img_number=0;
            foreach($galeria_blog as $g){ ?>
    <div class="col-2 mu-r mu-b">
        <div class="card card-ubicalos-img"
            onClick="mostrarModal('<?php echo $img_number; ?>','<?php echo $total_img; ?>');">
            <div class="img-container">
                <img class="card-img-top card-img-galeria" src="
                    <?php echo $this->config->item('url_ubicalos');?>FotosBlogEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$g->foto);?>
                ">
            </div>
        </div>
    </div>
    <?php  
        $img_number++; }
    } ?>
</div>

<div class="modal" id="carruselModal" style="z-index: 10;">
    <div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
        <div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1); ">
            <button type="button" class="close close-modal " data-dismiss="modal" onClick="cerrarModalGV();"
                style="color: white;">&times;</button>
        </div>
        <div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;"">
            <div id="carrusel_imagenes" class="owl-carousel owl-theme">

                <?php if($galeria_blog != FALSE){
                        $img_number=0;
                        foreach($galeria_blog as $g){ ?>
                <div class="row">
                    <div class="item">
                        <div class="img-container">
                            <img style="border-radius: 0px; width: 100%; height: 100%;" class="img-fluid"
                                src="<?php echo $this->config->item('url_ubicalos'); ?>FotosBlogEmpresa/<?php echo $id_empresa; ?>/<?php echo str_replace("´", "'",$g->foto); ?> ">
                        </div>
                    </div>
                    <div class="col-12 ml-2 mt-3 mr-2">
                        <div class="row">
                            <div class="col-auto mr-n4">
                                <img style="border-radius: 50%; width: 35px; height: 35px;" id="imagenprincipal-modal"
                                    <?php if($foto_perfil == FALSE){ ?>
                                    src="<?php echo base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";?>"
                                    <?php }else{ ?>
                                    src="<?php echo $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".$foto_perfil[0]->foto_perfil;?>"
                                    <?php } ?>>
                            </div>
                            <div class="col-8">
                                <p class="mt-2 ml-1" style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle">
                                    <?php echo $nombre_empresa[0]->nombre; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 ml-2">
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
                <?php } 
                        } ?>

            </div>
        </div>
    </div>
</div>
