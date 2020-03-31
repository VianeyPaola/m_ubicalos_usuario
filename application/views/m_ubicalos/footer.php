
    </div>
</div>

<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>js/main.js"></script>

<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/popper.min.js"></script>
<script src="<?php echo base_url();?>js/owl.carousel.js"></script>

<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/bootstrap4-toggle.min.js"></script>
<link href="<?php echo base_url();?>css/bootstrap4-toggle.min.css" rel="stylesheet">
</body>

<script>

    $(document).ready(function(){

		if(screen.width >= 1024){location.href="http://192.168.1.69/ubicalos/Welcome/Sesion"; }
		window.addEventListener("orientationchange", function() { if(screen.width >= 1024){location.href="http://192.168.1.69/ubicalos/Welcome/Sesion"; } }, false);

        var band = true;
        document.getElementById("logotipo").style.display = "block";
        $('#barritas').click(function () {
            if (band != true) {
                document.getElementById("logotipo").style.display = "none";
            } else {
                document.getElementById("logotipo").style.display = "block";
            }
            band = !band;

        });

		var position_nav = <?php echo $position_nav; ?>;
		
		/* funciones dinamicas para informacion principal */
        var id_empresa = <?php echo $id_empresa; ?>;
		var id_sucursal = <?php echo $id_sucursal; ?>;

		if(position_nav == 0)
        {
            $.getScript("<?php echo base_url();?>js/m_ubicalos/sesion_inicio.js", function(){
            });

            <?php if(!empty($galeria_sesion)){ ?>
                <?php if($galeria_sesion != FALSE){ ?>
                    $.getScript("<?php echo base_url();?>js/m_ubicalos/galeria_sesion.js", function(){});
                <?php } ?>
            <?php } ?>   
        }
        if(position_nav == 2.2)
        {
            $.getScript("<?php echo base_url();?>js/m_ubicalos/ver_mapa_sucursal.js", function(){
            });            
            position_nav = 2;
        }

		if(position_nav == 3 || position_nav == 4 || position_nav == 6 || position_nav == 7)
        {
            $.getScript("<?php echo base_url();?>js/m_ubicalos/modal_carrusel.js", function(){
            });  
		}

		$.getScript("<?php echo base_url();?>js/autocompletado.js", function(){});

		switch(position_nav)
        {
            case 1: position_nav = 0; break;
            case 2: position_nav = 1; break;
            case 3: position_nav = 2; break;
            case 4: position_nav = 3; break;
            case 5: position_nav = 3; break;
            case 6: position_nav = 4; break;
            case 7: position_nav = 4; break;
            case 8: position_nav = 3; break;
        }

        $("#nav-navegacion").owlCarousel({
            autoWidth: true,
            startPosition: position_nav,
            margin: 15
        });

		function publicidadCascada()
		{
			if( $("#publicidad_cascada").length )
			{
				$.ajax({
					type: 'POST',
					url: 'getPublicidadCascada'
				})
				.done(function(publicidad_cascada){
					$('#publicidad_cascada').html(publicidad_cascada)
				})
				.fail(function(){
					location.reload();
				})

			}
		}

		if( $("#publicidad-principal").length ){
		
			$.ajax({
				type: 'POST',
				url: 'getPublicidadPrincipal'
			})
			.done(function(publicidad_principal){
				$('#publicidad-principal').html(publicidad_principal)
			})
			.fail(function(){
				location.reload();
			})

			$.getScript("<?php echo base_url();?>js/m_ubicalos/mostrar_publicidad.js", function(){
            });

		}

		publicidadCascada();
		setInterval(publicidadCascada, 5000);


		$.ajax({
            type: 'POST',
            url: 'getFotoPerfil',
            data: {'id_empresa': id_empresa }
        })
        .done(function(div_foto_perfil){
            $('#div_foto_perfil').html(div_foto_perfil)
        })
        .fail(function(){
            location.reload();
        })

		$.ajax({
            type: 'POST',
            url: 'get_abierto_cerrado_horario',
            data: {'id_sucursal': id_sucursal }
        })
        .done(function(div_abierto_ahora){
            $('#div_abierto_ahora').html(div_abierto_ahora)
        })
        .fail(function(){
            location.reload();
        })

		$.ajax({
            type: 'POST',
            url: 'get_Nombre_Empresa',
            data: {'id_empresa': id_empresa }
        })
        .done(function(div_nombre_empresa){
            $('#div_nombre_empresa').html(div_nombre_empresa)
        })
        .fail(function(){
            location.reload();
        })

		$.ajax({
            type: 'POST',
            url: 'get_Categorias_Sub_Secciones',
            data: {'id_empresa': id_empresa }
        })
        .done(function(div_categorias_sub_seccion){
            $('#div_categorias_sub_seccion').html(div_categorias_sub_seccion)
        })
        .fail(function(){
            location.reload();
        })

		$.ajax({
            type: 'POST',
            url: 'get_Calificacion_Empresa',
            data: {'id_empresa': id_empresa ,'id_sucursal': id_sucursal }
        })
        .done(function(div_calificacion){
            $('#div_calificacion').html(div_calificacion)
        })
        .fail(function(){
            location.reload();
        })

		$.ajax({
            type: 'POST',
            url: 'get_Direccion_Empresa_Actualizacion',
            data: {'id_sucursal': id_sucursal,'id_empresa': id_empresa }
        })
        .done(function(div_direccion){
            $('#div_direccion_empresa_actualizacion').html(div_direccion)
        })
        .fail(function(){
            location.reload();
		})
		
		$.ajax({
            type: 'POST',
            url: 'get_Boton_Direccion',
            data: {'id_sucursal': id_sucursal }
        })
        .done(function(div_boton_direccion){
            $('#div_boton_direccion').html(div_boton_direccion)
        })
        .fail(function(){
            location.reload();
        })

        $.ajax({
            type: 'POST',
            url: 'get_Boton_Llamar',
            data: {'id_sucursal': id_sucursal }
        })
        .done(function(div_boton_llamar){
            $('#div_boton_llamar').html(div_boton_llamar)
        })
        .fail(function(){
            location.reload();
        })
    });

	function masCSS()
    {
        if($('#toggle_ca_d').is(":visible"))
        {
            $('#toggle_ca_d').hide();
        }else{
            $('#toggle_ca_d').show();
        }

	}
	
	function btnBusca()
	{
		$('#logo-principal-ubicalos').hide();
		$('#lupa-ubicalos-search').hide();
		$('#input-ubicalos-search').show();
	}

</script>

</html>
