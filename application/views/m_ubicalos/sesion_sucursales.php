<!-- Lo de la vista -->
<div class="row mt-2 mb-4 ml-n3 mr-n3">
    <div class="col-12">
        <form action="VerMapa_Sucursal" method="get">
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo $id_empresa?>">
            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?php echo $id_sucursal?>">
            <button type="submit" class="btn  btn btn-outline-secondary btn-block">Ver mapa</button>
        </form>
    </div>
</div>

<?php
    $cont=0;
    foreach($sucursales as $sucursal)
    { ?>
<div class="row ">
    <!--Informacion principal-->
    <div class="col-md-10 mt-0 mb-0 pt-0 pb-0">
        <div class="card" style="border-radius: 7px; background-color: #fcfcfc;">
            <div class="card-body">
                <!--Nombre del negocio-->
                <h5 class="color-black"><?php echo ($cont+1).'.-'.$nombre_negocio; if($cont==0) echo " (Matriz)"; ?></h5>
                <div class="row">
                    <!--Calificacion-->
                    <div class="col-sm-12 col-md-12">
                        <div class="estrellas">
                            <form>
                                <p class="clasificacion  mt-0 mb-0 pb-0 pt-0">
                                    <input id="radio1" type="radio" name="estrellas" value="5">
                                    <!--
                                                    --><label for="radio1">★</label>
                                    <!--
                                                    --><input id="radio2" type="radio" name="estrellas" value="4">
                                    <!--
                                                    --><label for="radio2">★</label>
                                    <!--
                                                    --><input id="radio3" type="radio" name="estrellas" value="3">
                                    <!--
                                                    --><label for="radio3">★</label>
                                    <!--
                                                    --><input id="radio4" type="radio" name="estrellas" value="2">
                                    <!--
                                                    --><label for="radio4">★</label>
                                    <!--
                                                    --><input id="radio5" type="radio" name="estrellas" value="1">
                                    <!--
                                                    --><label for="radio5">★</label>
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
                        <p class="cart-text mt-0 mb-0 pb-0 pt-0 color-blue-ubicalos" >En zona :
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
                            <input type="hidden" name="id_empresa" id="id_empresa"
                                value="<?php echo $id_empresa;?>">
                            <input type="hidden" name="id_sucursal" id="id_sucursal"
                                value="<?php echo $sucursal->id_sucursal;?>">
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
<div class="row mt-0 mb-0 pt-0 pb-0">
    <div class="col-md-10 mt-0 mb-0 pt-0 pb-0" >
        <div class="mt-0 mb-0 pt-0 pb-0" style="padding: .50rem;">
            <hr style="border: 1px solid #e1e1e1; ">
        </div>
    </div>                      
</div>
<?php $cont++; }?>


    
                                    
                                  