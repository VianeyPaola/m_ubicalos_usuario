<!-- Lo de la vista -->
<div class="row mt-2 mb-4 ml-n3 mr-n3">
    <div class="col-12">
        <form action="Sucursales" method="get">
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo $id_empresa?>">
            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?php echo $id_sucursal?>">
            <button type="submit" class="btn  btn btn-outline-secondary btn-block">Ver sucursales</button>
        </form>
    </div>
</div>

<div class="row mt-0 mr-n3 mb-1">
    <div class="col-12">
        <div class="col" id="map" style="padding: 500px 10px 80px 10px; "></div>
    </div>
</div>

<script type="text/javascript">
    var nombre_negocio = "<?php echo $nombre_negocio ?>";
    var foto = "<?php echo $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".$foto_perfil[0]->foto_perfil;?>";
    var sucursales = [
    <?php 
        $cont = 0;
        foreach ($sucursales as $sucursal){
            $cont ++; ?>
            [ <?php echo $sucursal->latitud ?> , <?php echo $sucursal->longitud ?> , "<?php echo  $sucursal->calle ?>" ]
            <?php if($cont < count($sucursales)){ echo ','; } ?>
    <?php }?>
    ];
    var icono = "<?php echo $this->config->item('base_url');?>/img/PIN.png";
</script>