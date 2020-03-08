    <?php if(empty($eventos)){ ?>
        <div class="text-center mt-4 mb-5" style="resize:none;">
            <img class="mb-4 mt-4" style="width: 22%;" src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_EVENTOS_VACIO.svg">
            <p class="mb-n1" style="font-size: 18pt;">Sin eventos</h1>
            <p class="mt-3" style="font-size: 10pt;">Este negocio aún no cuenta con eventos. </p>
        </div>
    <?php }else{ 
            for($i=0; $i<count($eventos); $i++){
    ?>
        <a href="<?php echo base_url();?>Empresa/Mostrar_Evento?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>&evento=<?php echo $eventos[$i]->id_evento;?>" style="color: black">
        <div class="row mt-2 mb-2">
            <div class="col-12 mb-n4">
                <div class="card mb-3" style="max-width: 940px;">
                    <div class="row no-gutters">
                        <div class="col-8">
                            <div class="card-body mt-0 pt-0">
                                <p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;"><?php echo $eventos[$i]->nombre; ?></p>
                                <p class="card-text  mt-0 mb-0 pb-0 pt-0 f-11 color-green">
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
                                <p class="card-text color-black f-11">
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
                        <!-- Imagen -->
                        <div class="col-4">
                            <?php if($eventos[$i]->imagen != "sin_imagen"){ ?>
                                <img class="card-img img-cards" src="<?php echo $this->config->item('url_ubicalos');?>EventosEmpresa/<?php echo $id_empresa.'/'.$eventos[$i]->imagen;?>" >
                            <?php }else{ ?>
                                <img class="card-img img-cards"  src="<?php echo base_url();?>img/IMAGEN EVENTOS Y BLOGS.png" alt="...">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-n3">
                <hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
            </div>
        </div>
        </a>
    <?php } 
        }
    ?>
