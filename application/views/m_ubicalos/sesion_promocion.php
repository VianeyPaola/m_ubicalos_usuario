
<?php if($promociones == FALSE){ ?>

<div class='text-center' style='resize:none;'>
    <img class="mb-3 mt-4" style="width: 10%;" src="<?php echo $this->config->item('url_ubicalos');?>img/PERFIL_PROMOCIONES_VACIO.png">
    <p class="mb-n1" style="font-size: 18pt;">Sube tus promociones</h1>
        <p style="font-size: 12pt;">Agrega más promociones a tu perfil
            <br>
            para atraer a más clientes</p>
</div>

<?php
    }else{ 
    foreach ($promociones as $promocion){
        if($promocion->foto == null){
        ?>
<!-- Card para porcentaje -->
<?php 
    $sucursales ="";
    for($i=0; $i<count($promociones_sucursales[$promocion->id_promociones]); $i++){
        $sucursales .= "sucursales[]=".$promociones_sucursales[$promocion->id_promociones][$i]."&";
    }
?>
<a href="<?php echo base_url();?>Empresa/Promocion_Sucursales?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>&id_promociones=<?php echo $promocion->id_promociones."&i=".$i."&".$sucursales ?>">
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
                        <div class="align-items-center d-flex justify-content-center  img-cards bg-porcentaje">
                            <p class="card-text text-porcentaje" id="porcentaje_valor">
                                <?php echo $promocion->porcentaje?>%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
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
<a href="<?php echo base_url();?>Empresa/Promocion_Sucursales?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>&id_promociones=<?php echo $promocion->id_promociones."&i=".$i."&".$sucursales ?>">
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
</a>
<!-- Fin card para imagen -->
<?php    }
}  
} ?>

