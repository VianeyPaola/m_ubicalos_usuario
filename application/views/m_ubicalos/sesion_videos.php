
        <?php if($galeria == FALSE){ ?>
        <div class="text-center mt-4 mb-5" style="resize:none;">
            <img class="mb-4 mt-4" style="width: 22%;" src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_VIDEOS_SUBIR_VIDEOS_Vacio.png">
            <p class="mb-n1" style="font-size: 18pt;">Sin videos</h1>
            <p class="mt-3" style="font-size: 10pt;">Este negocio aún no cuenta con videos.</p>
        </div>
        <?php }else{ ?>
            <div class="row mr-n3 mb-1">
                <?php foreach($galeria as $g){ ?>
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

        <div class="modal" id="carruselModal"  style="z-index: 10;">
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
        