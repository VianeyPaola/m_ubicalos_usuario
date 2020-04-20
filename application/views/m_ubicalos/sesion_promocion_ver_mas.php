<?php if($promocion->porcentaje) { ?>
<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 940px;">
            <div class="row no-gutters">
                <div class="col-8">
                    <div class="card-body">
                        <p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
                            <?php echo $promocion->titulopromo; ?> </p>
                        <p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11">
                        <?php 
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
                <div class="col-4">
                    <div class="align-items-center d-flex justify-content-center img-cards bg-porcentaje">
                        <p class="card-text text-porcentaje" id="porcentaje_valor">
                            <?php echo $promocion->porcentaje?>%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
</div>
<!-- Fin card promocion -->
<?php } else{ ?>
<!-- Card con imagen -->
<div class="row">
    <div class="col-12">
        <div class="card" style="max-width: 940px;">
            <div class="row no-gutters">
                <div class="col-8">
                    <div class="card-body  mt-0 pt-0">
                        <p class="mb-0 pb-0 color-black f-12" style="font-weight: bold;">
                            <?php echo $promocion->titulopromo; ?> </p>
                        <p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11">
                        <?php 
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
    <hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
</div>
<?php }?>
<!-- Fin card con imagen -->
<div class="row">
    <div class="form-group col-12">
        <label class="mb-n4 color-black f-14" for="titulo_promocionE" >Promoción</label><br>
        <span for="titulo_promocion" class="f-11"><?php echo $promocion->titulopromo?></span>
    </div>
</div>
<div class="row">
    <div class="form-group col-12">
        <label class="mb-n4 f-14 color-black" for="descripcionE" >Descripción de
            promoción</label><br>
        <span for="descripcion" class="f-11"><?php echo $promocion->descripcion?></span>
    </div>
</div>
<div class="row">
    <div class="form-group col-12">
        <label for="donde_aplicaE" class="f-14 color-black">¿En qué sucursales aplica esta
            promoción?</label><br>
    </div>
</div>
<?php $cont=0; foreach($sucursales as $sucursal){ 
                $band = FALSE;
                
                for($i=0; $i<count($sucursales_promo);$i++){
                    if($sucursales_promo[$i] == $sucursal->id_sucursal){
                    $band = TRUE;
                    break;}
                }
                if($band){
                ?>
                <div class="row ">
                    <!--Informacion principal-->
                    <div class="col-md-10 mt-0 mb-0 pt-0 pb-0">
                        <div class="card" style="border-radius: 7px; background-color: #fcfcfc;">
                            <div class="card-body">
                                <!--Nombre del negocio-->
                                <h5 class="color-black" ><?php echo ($cont+1).'.-'.$nombre_negocio; if($cont==0) echo " (Matriz)"; ?></h5>
                                <div class="row">
                                    <!--Calificacion-->
                                    <div class="col-sm-12 col-md-12">
                                        <div class="estrellas">
                                            <form>
                                                <p class="clasificacion  mt-0 mb-0 pb-0 pt-0">
                                                    <?php 
                                                        $calificacion = $sucursal->calificacion; 
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
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--Abierto-->
                                    <div class="col-auto ">
                                        <?php if($abierto_sucursal[$sucursal ->id_sucursal] == "TRUE"){?>
                                        <p class="mt-0 mb-0 pb-0 pt-0" style="color: green;font-size: 11pt">Abierto ahora</p>
                                        <?php }else{?>
                                        <p class="mt-0 mb-0 pb-0 pt-0" style="color: red;font-size: 11pt">Cerrado</p>
                                        <?php }?>
                
                                    </div>
                                    <!--Horario-->
                                    <div class="col-auto mt-0 mb-0 pb-0 pt-0">
                                        <p class="mt-0 mb-0 pb-0 pt-0" style="font-size: 11pt"><?php echo $horario_sucursal[$sucursal ->id_sucursal];?></p>
                                    </div>
                                </div>
                                <!--Direccion-->
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                    <?php if($sucursal->latitud != null && $sucursal->longitud != null){?>
                                        <a class="color-blue-ubicalos"
                                            href="https://maps.google.com/?q=<?php echo $sucursal->latitud.','.$sucursal->longitud; ?>"
                                            target="_blank">
                                            <p class="cart-text mt-0 mb-0 pb-0 pt-0" style="font-size: 11pt">
                                                <?php echo $sucursal->calle; 
                                                                        if(!empty($num_inter))
                                                                        {
                                                                            echo ", Núm. int.".$num_inter;
                                                                        }
                                                                    ?>
                                            </p>
                                        </a>
                                    <?php }else{?>                    
                                        <a class="color-blue-ubicalos"
                                            href="https://www.google.com/maps/search/?api=1&query=<?php echo $sucursal->calle; ?>"
                                            target="_blank">
                                            <p class="cart-text mt-0 mb-0 pb-0 pt-0">
                                                <?php echo $sucursal->calle; 
                                                                        if(!empty($num_inter))
                                                                        {
                                                                            echo ", Núm. int.".$num_inter;
                                                                        }
                                                                    ?>
                                            </p>
                                        </a>
                                    <?php }?>
                                    </div>
                                </div>
                                <!--Zona(s)-->
                                <div class="row">
                                    <div class="col-md-auto">
                                        <p class="cart-text mt-0 mb-0 pb-0 pt-0 color-blue-ubicalos">En zona :
                                            <?php echo $sucursal->zona; ?> </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--Ultima actualizacion-->
                                    <div class="col-sm-auto col-md-auto">
                                        <p>Ultima actualizacion: <?php echo $sucursal ->actualizacion; ?> </p>
                                    </div>
                                </div>
                
                                <div class="row">
                                    <div class="col-6">
                                        <form action="Inicio">
                                            <input type="hidden" name="id_sucursal" id="id_sucursal"
                                                value="<?php echo $sucursal->id_sucursal;?>">
                                            <input type="hidden" name="id_empresa" id="id_empresa"
                                                value="<?php echo $id_empresa;?>">
                                            <button type="submit" class="btn btn-block btn-outline-secondary">Ver mas</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <?php if($sucursal->latitud != null && $sucursal->longitud != null){?>
                                            <button type="button" class="btn btn-block btn-outline-secondary"
                                            onClick="window.open('https://www.google.com/maps/search/?api=1&query=<?php echo $sucursal->latitud ?>,<?php echo $sucursal->longitud ?>');">¿Cómo
                                            llegar?</button>
                                        <?php }else{ ?>
                                            <button type="button" class="btn btn-block btn-outline-secondary"
                                            onClick="window.open('https://www.google.com/maps/search/?api=1&query=<?php echo $sucursal -> calle; ?>');">¿Cómo
                                            llegar?</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
<?php $cont++;}
}?>

