        

        <?php if($info_general == ""){ ?>
        <div class="text-center mt-4 mb-5" style="resize:none;">
            <img class="mb-4 mt-4" style="width: 22%;" src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_INFORMACION_VACIO.png">
            <p class="mb-n1" style="font-size: 18pt;">Sin información</h1>
            <p class="mt-3" style="font-size: 10pt;">Este negocio aún no cuenta con información.
            </p>
        </div>
        <?php
            }else{ ?>
                <div class="row mt-0 mr-n3 mb-1">
                    <div class="col-12">
                        <p style="font-size: 14pt; color: black;">Información general</p>
                        <p class="mt-n3" style="color: #262626; font-size: 11pt;"><?php echo nl2br($info_general); ?></p>
                    </div>
                </div>
                <div class="row mr-n3 mb-1">
                    <div class="col-12">
                        <p style="font-size: 14pt; color: black;">Servicios y productos</p>
                        <p class="mt-n3" style="color: #262626; font-size: 11pt;"><?php echo nl2br($servicios_productos); ?></p>
                    </div>
                </div>
                <div class="row mr-n3 mb-1">
                    <div class="col-12">
                        <p style="font-size: 14pt; color: black;">Además</p>
                        <p class="mt-n3" style="color: #262626; font-size: 11pt;"><?php echo nl2br($ademas); ?></p>
                    </div>
                </div>

                <div class="row mr-n3 mb-3">
                    <div class="col-12">
                    <?php if(!empty($Lunes1) || !empty($Martes1) || !empty($Miércoles1) || !empty($Jueves1) || !empty($Viernes1) || !empty($Sábado1) || !empty($Domingo1)){ ?>
                        <p class="mb-0" style="font-size: 14pt; color: black;">Horario</p>
                        <?php for($i=1; $i<=7; $i++){
                            
                            switch($i)
                            {
                                case 1:
                                    if(!empty($Lunes1)){
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
                    <?php } ?>            
                    </div>
                </div>

                <div class="row mr-n3 mb-2">
                    <div class="col-12">
                        <p class="mb-n3" style="font-size: 14pt; color: black;">Contacto<p>
                        <?php
                            $telefonos = "";
                            if(!empty($telefono1)){
                                $tel_band = true;
                                $string = $telefono1;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $telefonos = '<a href="tel:'.$telefono1.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'<a>';
                            }
                            if(!empty($telefono2)){
                                $string = $telefono2;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $telefonos .= " / ".'<a href="tel:'.$telefono2.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
                            }
                            if(!empty($telefono3)){
                                $string = $telefono3;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $telefonos .= " / ".'<a href="tel:'.$telefono3.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
                            }
                            if($telefonos != ""){
                        ?>
                        <div class="row mb-n2">
                            <div class="col-1 mr-2" style="margin-top: 2px;">
                                <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_TELEFONO.svg" alt="" style=" margin-left: 4px;">
                            </div>
                            <div class="col-9 mt-3">
                                <p class="color-blue-ubicalos" style="margin-top: -15px; font-size: 11pt;">
                                    <?php echo $telefonos; ?>
                                </p>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                            $celulares = "";
                            if(!empty($celular1)){
                                $string = $celular1;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $celulares = '<a href="tel:'.$celular1.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
                            }
                            if(!empty($celular2)){
                                $string = $celular2;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $celulares .= " / ".'<a href="tel:'.$celular2.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
                            }
                            if(!empty($celular3)){
                                $string = $celular3;
                                $string_ = chunk_split($string, 3, '.');
                                $string_e = explode(".",$string_) ;
                                $celulares .= " / ".'<a href="tel:'.$celular3.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
                            }
                            if($celulares != ""){
                        ?>
                        <div class="row">
                            <div class="col-1 mr-2">
                                <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_CELULAR.svg" alt="" style="padding-left: 5px;">
                            </div>
                            <div class="col-9 mt-2">
                                <p class="mt-n2 color-blue-ubicalos" style="color: blue; font-size: 11pt;">
                                    <?php echo $celulares; ?>
                                </p>
                            </div>
                        </div>
                        <?php } ?>
                        <?php
                            $correos = "";
                            if(!empty($correo1)){
                                $correos = '<a href="mailto:'.$correo1.'">'.$correo1.'</a>';
                            }
                            if(!empty($correo2)){
                                $correos .= " / ".'<a href="mailto:'.$correo2.'">'.$correo2.'</a>';
                            }
                            if(!empty($correo3)){
                                $correos .= " / ".'<a href="mailto:'.$correo3.'">'.$correo3.'</a>';
                            }
                            if($correos != ""){
                        ?>
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 2px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_CORREO.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-10 mt-3">
                                    <p class="color-blue-ubicalos" style="margin-top: -15px; font-size: 11pt;">
                                    <?php echo $correos; ?>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                        <?php
                            if(!empty($Pagina_Web)){
                        ?>
                        <a href="http://<?php echo $Pagina_Web; ?>" target="_blank">
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_PAGINA_WEB.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt;">
                                        <?php echo $Pagina_Web; ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php } ?>

                        <?php
                            if(!empty($Facebook)){
                        ?>
                        <a href= "http://<?php echo $Facebook; ?>" target="_blank">
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_FACEBOOK.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt;">
                                        <?php echo $Facebook; ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php } ?>

                        <?php
                            if(!empty($Instagram)){
                        ?>
                        <a href="http://<?php echo $Instagram; ?>"  target="_blank">
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_INSTAGRAM.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt;">
                                        <?php echo $Instagram; ?>      
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php } ?>

                        <?php
                            if(!empty($Youtube)){
                        ?>
                        <a href="http://<?php echo $Youtube; ?>" target="_blank">
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_ YOUTUBE.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt;">
                                        <?php echo $Youtube; ?>      
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php } ?>

                        <?php
                            if(!empty($Twitter)){
                        ?>
                        <a href="http://<?php echo $Twitter; ?>" target="_blank">
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_TWITTER.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt;">
                                        <?php echo $Twitter; ?>      
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php } ?>

                        <?php
                            if(!empty($Snapchat)){
                        ?>
                            <div class="row mt-n2">
                                <div class="col-1 mr-2" style="margin-top: 0px;">
                                    <img src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_SUCURSALES_SNAPCHAT.svg" alt="" style=" margin-left: 4px;">
                                </div>
                                <div class="col-9 mt-3">
                                    <p class="" style="margin-top: -15px; font-size: 11pt; color:#3f6ad8;">
                                        <?php echo $Snapchat; ?>      
                                    </p>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <div class="row mr-n3 mb-4">
                    <div class="col-12">
                        <form action="<?php echo base_url(); ?>/Empresa/Sucursales?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>">
                            <button type="submit" class="btn btn-secondary btn-block btn-lg">Ver <?php echo $num_sucursales; ?> sucursales mas</button>
                        </form>
                    </div>
                </div>

                <div class="row mr-n3 mb-1">
                    <div class="col-12">
                    <p style="font-size: 14pt; color: black; ">Servicios adicionales</p>
                    <ul class="ml-n3" style="margin-top: -10px; font-size: 11.5pt;">
                        <?php if(!empty($Silla_ruedas)){ echo "<li style='color: #262626'>Acceso a silla de ruedas"; }?>
                        <?php if(!empty($Cajero_automatico)){ echo "<li style='color: #262626'>Cajero automático"; }?>
                        <?php if(!empty($Privado) || !empty($Valet_Parking) || !empty($Calle)){
                            echo "<li style='color: #262626'>Estacionamiento";
                            echo "<ul>";
                            if(!empty($Privado)){ echo "<li style='color: #262626'>Privado";}
                            if(!empty($Valet_Parking)){ echo "<li>Valet Parking";}
                            if(!empty($Calle)){ echo "<li style='color: #262626'>Calle";}
                            echo "</ul>";
                        }
                        ?>
                        <?php if(!empty($Mesas_aire_libre)){ echo "<li style='color: #262626'>Mesas al aire libre"; }?>
                        <?php if(!empty($Pantallas)){ echo "<li style='color: #262626'>Pantallas (TV)"; }?>
                        <?php if(!empty($Reservaciones)){ echo "<li style='color: #262626'>Reservaciones"; }?>
                        <?php if(!empty($Sanitarios)){ echo "<li style='color: #262626'>Sanitarios"; }?>
                        <?php if(!empty($Servicio_domicilio)){ echo "<li style='color: #262626'>Servicio a domicilio"; }?>
                        <?php
                        if(!empty($Visa) || !empty($Master_Card) || !empty($Amex) )
                        {
                            echo "<li style='color: #262626'>Tarjetas";
                            echo "<ul>";
                            if(!empty($Visa)){ echo "<li>Visa";}
                            if(!empty($Master_Card)){ echo "<li style='color: #262626'>Master Card";}
                            if(!empty($Amex)){ echo "<li style='color: #262626'>Amex";}
                            echo "</ul>";
                        }
                        ?>
                        <?php if(!empty($WiFi)){ echo "<li style='color: #262626'>WiFi"; }?>
                        <?php if(!empty($Zona_cigarrillo)){ echo "<li style='color: #262626'>Zona de cigarrillo"; }?>
                        <?php if(!empty($Zona_ninios)){ echo "<li style='color: #262626'>Zona para niños"; }?>
                    </ul>
                    </div>
                </div>

                <div class="row ml-1 mr-n3 mb-0">
					
                    <div class="container ml-0">
						<div class="row mb-0">
							<p style="font-size: 14pt; color: black;">Categorías</p>
						</div>
                        <div class="row ">
                            <div class="mr-2 pb-4">
                                <a class="categorias-info">
                                <?php echo $categoria; ?></a>
                            </div>
                            <div class="mr-2 pb-4">
                                <a class="categorias-info">
                                <?php echo $subcategoria_1; ?></a>
                            </div>
                            <div class="mr-2 pb-4">
                                <a class="categorias-info">
                                <?php echo $seccion_1_1; ?></a>
                            </div>
                        </div>
                        <!---->
						
                        <?php if(!empty($subcategoria_2)){
                            if($subcategoria_2 != -1){?>
                                <div class="row">
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $categoria; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $subcategoria_2; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $seccion_1_2; ?></a>
                                    </div>
                                </div>
                                
                        <?php } } ?>
                        
                        <?php if(!empty($subcategoria_3)){
                            if($subcategoria_3 != -1){?>
                                <div class="row">
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $categoria; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $subcategoria_3; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $seccion_1_3; ?></a>
                                    </div>
                                </div>
                        <?php } } ?>

                        <?php if(!empty($subcategoria_4)){
                            if($subcategoria_4 != -1){?>
                                <div class="row">
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $categoria; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $subcategoria_4; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $seccion_1_4; ?></a>
                                    </div>
                                </div>
                                
                        <?php } } ?>
                        <?php if(!empty($subcategoria_5)){
                            if($subcategoria_5 != -1){?>
                                <div class="row ">
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $categoria; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $subcategoria_5; ?></a>
                                    </div>
                                    <div class="mr-2 pb-4">
                                        <a class="categorias-info">
                                        <?php echo $seccion_1_5; ?></a>
                                    </div>
                                </div>
                                
                        <?php } } ?>

                    </div>
                </div>

        <?php } ?>
        <!-- -->
