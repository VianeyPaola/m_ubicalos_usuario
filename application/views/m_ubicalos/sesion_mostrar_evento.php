		<?php
            setlocale(LC_TIME, 'es_ES.UTF-8');
        ?>

        <div class="row ml-n4 mt-2 mb-2">
            <div class="col-12 mb-n4">
                <div class="card mb-3" style="max-width: 940px;">
                    <div class="row no-gutters">
                        <div class="col-9">
                            <div class="card-body  mt-0 pt-0">
                            <p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;"><?php echo $eventos->nombre; ?></p>
                                <p class="card-text  mt-0 mb-0 pb-0 pt-0 f-11 color-green">
                                    <?php   
                                    setlocale(LC_TIME, 'es_ES.UTF-8');
                                    if(!empty($fecha_evento->fecha))
                                    {
                                    echo ucfirst(strftime("%A %d,%b,%y", strtotime(strftime($fecha_evento->fecha))))." ".date("H:i", strtotime($fecha_evento->hora) )." hrs<br>";    
                                    }else{
                                        echo  "<font class='f-11' style='color:red;'>Evento finalizado </font>";
                                    }
                                     ?>
                                </p>
                                <p class="card-text  mt-0 mb-0 pb-0 pt-0 f-11 color-black">
                                    <?php echo $concepto_evento->concepto.": ".$concepto_evento->precio; ?>
                                </p>
                                <p  class="cart-text mt-0 mb-0 pb-0 pt-0 f-11 color-blue-ubicalos">
                                    En zona : <?php echo $eventos->zona; ?>
                                </p>
                                <p class="card-text  f-11 color-black" >
                                <?php 
                                        $descripcion = "";
                                        $descripcion .= $eventos->sinopsis ."";
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
                        <!-- Imagen -->
                        <div class="col-3">
                            <?php if($eventos->imagen != "sin_imagen"){ ?>
                                <img style="border-radius: 4px; width:80px; height:80px;float:right; margin-right:10px" src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$eventos->imagen;?>" class="card-img">
                            <?php }else{ ?>
                                <img style="border-radius: 4px; width:80px; height:80px;float:right; margin-right:10px"  src="<?php echo base_url();?>img/IMAGEN EVENTOS Y BLOGS.png" class="card-img" alt="...">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-n3">
                <hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
            </div>
        </div>

        <div class="row mr-n3 mb-1">
            <div class="col-12">
                <p class="f-14 color-black">Nombre de evento</p>
                <p class="mt-n3" style="color: #262626; font-size: 11pt;"><?php echo $eventos->nombre;  ?></p>
            </div>
        </div>

        <div class="row mr-n3 mb-1">
            <div class="col-12">
                <p class="f-14 color-black">Sinopsis de evento</p>
                <p class="mt-n3" style="color: #262626; font-size: 11pt;"><?php echo nl2br($eventos->sinopsis);  ?></p>
            </div>
        </div>

        <div class="row mr-n3 mb-1">
            <div class="col-12">
                <p class="f-14 color-black">Fecha y hora de evento</p>
                <div class="row">
                    <div class="col-5">
                        <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo "Fecha";  ?></p>
                    </div>
                    <div class="col-4">
                        <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo "Hora";  ?></p>
                    </div>
                
                    <?php for($i=0; $i<count($fechas); $i++){ ?>
                        <div class="col-5 pb-n5">
                            <p class="color-blue-ubicalos" style="position: relative; margin-top: -15px; font-size: 11pt;">
                            <?php echo ucfirst(strftime("%a %d, %b %y", strtotime($fechas[$i]->fecha)));  ?>
                            </p>
                        </div>
                        <div class="col-4 pb-n5">
                            <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo date("H:i", strtotime($fechas[$i]->hora) )." hrs";  ?></p>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>

        <div class="row mr-n3 mb-1">
            <div class="col-12">
                <p class="f-14 color-black">Precios</p>
                <div class="row">
                        <div class="col-5">
                            <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo "Concepto";  ?></p>
                        </div>
                        <div class="col-4">
                            <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo "Precio";  ?></p>
                        </div>
                    
                        <?php for($i=0; $i<count($conceptos); $i++){ ?>
                            <div class="col-5 pb-n5">
                                <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo $conceptos[$i]->concepto;  ?></p>
                            </div>
                            <div class="col-4 pb-n5">
                                <p style="color: #262626; position: relative; margin-top: -15px; font-size: 11pt;"><?php echo $conceptos[$i]->precio;  ?></p>
                            </div>
                        <?php } ?>

                    </div>
            </div>
        </div>

        <div class="row mr-n3 mb-1">
            <div class="col-12">
                <p class="f-14 color-black">Ubicación de evento</p>
				<a href='https://maps.google.com/?q=<?php echo$eventos->latitud; ?>,<?php echo $eventos->longitud; ?>' target="_blank" > 
                    <p class="color-blue-ubicalos" style="margin-top: -15px; margin-bottom: 0px;">
                        <?php echo $eventos->tipo_vialidad." ".$eventos->calle." ".$eventos->colonia.", Núm ext.".$eventos->num_ext; 
                            if(!empty($eventos->num_inter))
                            {
                                echo ", Núm. int.".$eventos->num_inter;
							}

                        ?>
                    </p>
                </a>

                <p  class="cart-text mt-0 pt-0 f-11 color-blue-ubicalos">
                    En zona : <?php echo $eventos->zona; ?>
                </p>
                
                <button type="button" class="btn btn-outline-secondary" onClick="window.open('https://maps.google.com/?q=<?php echo$eventos->latitud; ?>,<?php echo $eventos->longitud; ?>');">¿Cómo llegar?</button>
            </div>
        </div>

        <?php if($fotos_evento != FALSE){ ?>

            <div class="row mr-n3 mb-n3">
                <div class="col-12">
                    <p class="f-14 color-black">Galería de evento</p>
                </div>
            </div>
            
            <div class="row mr-n3">
                <?php
                $total_fevento = count($fotos_evento);
                for($j=0; $j < $total_fevento; $j++){ ?>
                    <div class="col-2 mu-r mu-b">
                        <div class="card card-ubicalos-img" onClick="mostrarModal('<?php echo $j; ?>','<?php echo $total_fevento ; ?>');">
                            <div class="img-container">
                                <img class="card-img-top card-img-galeria"
                                    src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$fotos_evento[$j]->foto;?>">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>


        <?php } ?>

        <?php if($video_evento != FALSE){ ?>

            <div class="row mr-n3 mb-n3">
                <div class="col-12">
                    <p class="f-14 color-black">Video de evento</p>
                </div>
            </div>

            <div class="row mr-n3 mb-n3">
                <div class="col-2 mu-r mu-b">
                    <div class="card card-ubicalos-img" onClick="mostrarModalVE();">
                        <div class="embed-responsive embed-responsive-1by1">
                            <video class="embed-responsive-item" style="background-color: black; border-radius: 3px;">
                                <source src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$video_evento[0]->foto;?>" type="video/mp4">
                            </video> 
                        </div>
                    </div>
                </div>
            </div>


        <?php } ?>

        <div class="row ml-n4 mt-n2">
            <div class="col-12 mb-n3">
                <hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
            </div>
        </div>


        <!--Modals-->
        <div class="modal" id="carruselModal"  style="z-index: 10;">
            <div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
                <div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1);">
                    <button type="button" class="close close-modal" data-dismiss="modal" onClick="cerrarModalGE();" style="color: white;">&times;</button>
                </div>
                <div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;"">
                    
                    <div id="carrusel_imagenes" class="owl-carousel owl-theme">

                        <?php if($fotos_evento != FALSE){ 
                            $i=0;
                            foreach($fotos_evento as $g){ ?>
                            <div class="row">
                                <div class="item">
                                    <div class="img-container">
                                        <img style="border-radius: 0px; width: 100%; height: 100%;" class="img-fluid" src="<?php echo $this->config->item('url_ubicalos'); ?>EventosEmpresa/<?php echo $id_empresa; ?>/<?php echo $g->foto; ?> ">
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
                                                <?php } ?>
                                            >
                                        </div>
                                        <div class="col-8">
                                            <p class="mt-2 ml-1" style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle"><?php echo $nombre_empresa[0]->nombre; ?></p>
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
                            
                        <?php $i++; } 
                            } ?>

                    </div>
                </div>
            </div>
        </div>

        <?php if($video_evento != FALSE){ ?>
            <div class="modal" id="carruselModal1"  style="z-index: 10;">
                <div class="modal-content" style="background-color: rgba(1,1,1,1); border:none; border-radius: 0px;">
                    <div class="modal-header" style="border-bottom: 0px; background-color: rgba(1,1,1,1);">
                        <button type="button" class="close close-modal" data-dismiss="modal" onClick="cerrarModalGE();" style="color: white;">&times;</button>
                    </div>
                    <div class="modal-body" style="background-color: rgba(1,1,1,1); padding: 0px;"">
                        <div class="container">
                            <div class="row">
                                <div class="embed-responsive embed-responsive-1by1">
                                    <video id="video-seleccionado" class="embed-responsive-item" style="background-color: black; border-radius: 3px;" loop="true" controls>
                                        <source src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$video_evento[0]->foto;?>" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-auto mr-n4">
                                    <img style="border-radius: 50%; width: 35px; height: 35px;" id="imagenprincipal-modal"
                                        <?php if($foto_perfil == FALSE){ ?>
                                            src="<?php echo base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";?>"
                                        <?php }else{ ?>
                                            src="<?php echo $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".$foto_perfil[0]->foto_perfil;?>"
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
        <?php } ?>
