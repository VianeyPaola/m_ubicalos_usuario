<style>
    .img-fluid{
        border-radius: 50%;
    }
    
</style>

<div class="app-main__outer">
    <div class="app-main__inner">
        
        <!-- Aqui va lo demás-->
            <div class="row mt-3 mr-n3">
                <div class="col-12 text-center mb-4" style="margin-right: -20px;">
                    <div class="container">
                        <div id="div_foto_perfil" class="img-container">
                            <img class="img-fluid" style="width: 105px; height: 105px;" src="<?php echo $this->config->item('url_mubicalos');?>img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png" >
                        </div>
                    </div>
                </div>

                <button class="btn-perfil-change"></button>

                <div id="div_nombre_empresa" class="col-12 mt-n3 text-center">
                    <div class="container mb-2" align="center">
                        <div class="col-8 etiqueta-info-carga" style="width: 100%; height: 25px; ">  
                        </div>
                    </div>
                </div>

                <div id="div_categorias_sub_seccion" class="col-12 mt-n3 text-center">
                    <div class="container mt-3 mb-2" align="center">
                        <div class="col-8 etiqueta-info-carga" style="width: 100%; height: 15px;">  
                        </div>
                    </div>
                </div>
                <!--Calificacion-->
                <div id="div_calificacion" class="col-12 mt-n3 text-center"> 
                        <div class="estrellas mt-2">
                            <p class="clasificacion mb-0">
                            <input id="radio1" type="radio" name="estrellas" value="5"><!--
                            --><label for="radio1">★</label><!--
                            --><input id="radio2" type="radio" name="estrellas" value="4"><!--
                            --><label for="radio2">★</label><!--
                            --><input id="radio3" type="radio" name="estrellas" value="3"><!--
                            --><label for="radio3">★</label><!--
                            --><input id="radio4" type="radio" name="estrellas" value="2"><!--
                            --><label for="radio4">★</label><!--
                            --><input id="radio5" type="radio" name="estrellas" value="1"><!--
                            --><label for="radio5">★</label>
                            </p>
                        </div>
                </div>

                <div id="div_abierto_ahora" class="col-12 mt-2 ml-0 mb-n3">
                    <div class="col-3 mb-2 etiqueta-info-carga" style="width: 100%; height: 15px;">
                    </div>
                </div>
                
                <div id="div_direccion_empresa_actualizacion" class="col-12 mb-0">
                    <div class="col-8 mt-3 mb-2 etiqueta-info-carga" style="width: 64%; height: 15px;">
                    </div>
                    <div class="col-8 mb-2 etiqueta-info-carga" style="width: 64%; height: 15px;">
                    </div>
                    <div class="col-4 mb-2 etiqueta-info-carga" style="width: 100%; height: 15px;">
                    </div>
                </div>

                <div id="div_boton_direccion" class="col-8">
                    <button type="button" class="btn btn-outline-ubicalos btn-block" style="font-size: 11pt;">¿Como llegar?</button>
                </div>

                <div id="div_boton_llamar" class="col-4">
                    <button  type="button" class="btn btn-ubicalos btn-block ml-n2" style="font-size:11pt;">Llamar</button>
                </div>
                
            </div>
        <!-- Aqui termina lo demás -->

        <div class="row mt-2">
            <hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
        </div>
        
        <!-- las opciones -->
        <div class="row mr-n3 mb-n4">
            
            <div id="nav-navegacion" class="owl-carousel">
                <a href="Inicio?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>"><div><img id="img_0" src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==0){echo 'R';} ?>01.- INICIO.svg"></div></a>
                <a href="Informacion?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>"><div><img id="img_1"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==1){echo 'R';} ?>02.- INFORMACION.svg"></div></a>
                <a href="Sucursales?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>"><div><img id="img_2"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==2 || $position_nav==2.1){echo 'R';} ?>03.- SUCURSALES.svg"></div></a>
                <a href="Sesion_Galeria"><div><img id="img_3"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==3){echo 'R';} ?>04.- GALERIA.svg"></div></a>
                <a href="Sesion_Videos"><div><img id="img_4"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==4){echo 'R';} ?>05.- VIDEOS.svg"></div></a>
                <a href="Sesion_Promocion"><div><img id="img_5"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==5 || $position_nav==5.1 ){echo 'R';} ?>06.- PROMOCIONES.svg"></div></a>
                <a href="Sesion_Eventos"><div><img id="img_6"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==6 || $position_nav==6.5){echo 'R';} ?>07.- EVENTOS.svg"></div></a>
                <a href="Sesion_Blogs"><div><img id="img_7"  src="<?php echo $this->config->item('url_mubicalos'); ?>img/<?php if($position_nav==7 || $position_nav==7.1){echo 'R';} ?>08.- BLOGS.svg"></div></a>              
            </div>
            
        </div>
        <div class="row">
            <hr style="border: 0.5px solid #DBDBDB; width: 100%;"/>
        </div>

