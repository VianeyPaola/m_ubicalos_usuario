
<?php if($blogs == FALSE){ ?>
<div class="text-center mt-4 mb-5" style="resize:none;">
    <img class="mb-4 mt-4" style="width: 22%;" src="<?php echo $this->config->item('url_ubicalos');?>img/PERFIL_BLOGS_VACIO.svg">
    <p class="mb-n1" style="font-size: 18pt;">Sin blogs</h1>
    <p class="mt-3" style="font-size: 10pt;">Este negocio aún no cuenta con blogs. </p>
</div>

<?php
    }else{  
        foreach ($blogs as $blog){
?>
<!-- Card para porcentaje -->

<a href="<?php echo base_url();?>Empresa/VerMas_Blog?<?php echo "id_empresa=".$id_empresa."&id_sucursal=".$id_sucursal; ?>&id_blog=<?php echo $blog->id_blog?>">
    <div class="row">
        <div class="col-12">
            <div class="card" style="max-width: 940px;">
                <div class="row no-gutters">
                    <div class="col-8">
                        <div class="card-body  mt-0 pt-0 pb-0 mb-1">
                            <p class="color-black f-12 mb-0 pb-0" style="font-weight: bold;">
                            <?php echo $blog->titulo; ?></p>
                            <p class="card-text mb-0 pb-0 mt-0 pt-0 color-black f-11"><?php 
                        $descripcion = "";
                        $descripcion .= $blog->blog ."";
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
                                <font class="f-11" class="color-black">
                                    <?php
                                     date_default_timezone_set('America/Mexico_City');
                                     $hoy = getdate();
                     
                                     /*Representacion numérica de las horas	0 a 23*/
                                     $h = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'].' '.$hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
                                     $Fecha_Actual = new DateTime($h);
                                     $Fecha_blog   = new DateTime($blog->fecha);
                                     $interval = date_diff($Fecha_blog, $Fecha_Actual);
                                     echo $interval->format('Hace %d días');
                                    ?>
                                </font>  
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <?php if (!empty($blog->imagen)) {?>
                                <img  class="card-img img-cards"  src="<?php echo $this->config->item('url_ubicalos');?>FotosBlogEmpresa/<?php echo $id_empresa.'/'.str_replace("´", "'",$blog->imagen);?>"  alt="...">
                        <?php }else{ ?>
                                <img  class="card-img img-cards" src="<?php echo base_url();?>img/IMAGEN EVENTOS Y BLOGS.png"  alt="...">
                        <?php }?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <img class="img-fluid ml-2 img-blogs" 
                            src="<?php 
                            if(!empty($foto_perfil))
                                {
                                    echo $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$id_empresa.'/'.str_replace("´", "'",$foto_perfil);
                                }else{
                                    echo $this->config->item('url_ubicalos')."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";
                                }
                        ?>">  <font class="ml-1 color-black f-11"> <?php echo $nombre_negocio; ?> </font>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-0 pt-0" style="border: 0.5px solid #DBDBDB; width: 100%;" />
    </div>
</a>
<?php }
}  
?>
