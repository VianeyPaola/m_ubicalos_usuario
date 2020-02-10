
    </div>
</div>

<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>js/main.js"></script>

<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/popper.min.js"></script>
<script src="<?php echo base_url();?>js/owl.carousel.js"></script>

<!-- <script src="<?php echo base_url();?>js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url();?>js/bootstrap4-toggle.min.js"></script>
<link href="<?php echo base_url();?>css/bootstrap4-toggle.min.css" rel="stylesheet">
</body>

<script>

    $(document).ready(function(){

		if(screen.width >= 1024){location.href="<?php echo $this->config->item('url_ubicalos'); ?>Welcome/Sesion"; }
		window.addEventListener("orientationchange", function() { if(screen.width >= 1024){location.href="<?php echo $this->config->item('url_ubicalos'); ?>Welcome/Sesion"; } }, false);

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

        $('.logo-src').show();

		$('.owl-theme').owlCarousel({
			margin: 10,
			nav: false,
			dots: false,
			autoWidth: false,
			items: 1,
			loop:false,
			responsiveClass:true,
			responsive:{
				600:{
					items: 2
				}
			}
		})

        /* funciones dinamicas para informacion principal */
       
        /* fin */

		if($('#publicidad-home-banner').length)
		{
			$.ajax({
				type: 'POST',
				url: 'get_publicidad_banner'
			})
			.done(function(publicidad_banner){
				$('#publicidad-home-banner').html(publicidad_banner)
			})
			.fail(function(){
				//location.reload();
			})
		}

		function publicidadtarjeta_ch()
		{
			if( $("#publicidad_tarjeta_ch").length )
			{
				$.ajax({
					type: 'POST',
					url: 'get_publicidad_tarjeta_ch'
				})
				.done(function(publicidad_tarjeta_ch){
					$('#publicidad_tarjeta_ch').html(publicidad_tarjeta_ch)
				})
				.fail(function(){
					location.reload();
				})

			}
		}

		publicidadtarjeta_ch();
		setInterval(publicidadtarjeta_ch, 5000);
		
		/* Cargamos las secciones */
		let params = new URLSearchParams(location.search);
		$.ajax({
			type: 'POST',
			url: 'get_Secciones',
			data: {'id_subcategoria': params.get('sub_cat')}
		})
		.done(function(nombreSub){
			$('#div_secciones').html(nombreSub);
		})

		/* Cargamos las zonas */
		$.ajax({
			type: 'POST',
			url: 'get_Zonas'
		})
		.done(function(zonas){
			$('#div_zonas').html(zonas);
		})
		
		

		/* para el filtro de las subcategorias */
		$("input[name=sub_cat]").change(function () {	 
			
			var id_subcategoria = $(this).val();

			$.ajax({
				type: 'POST',
				url: 'get_nombreSub',
				data: {'id_subcategoria': id_subcategoria}
			})
			.done(function(nombreSub){
				$('#sub_categoria_name').html(nombreSub);
			})

			$.ajax({
				type: 'POST',
				url: 'get_Secciones',
				data: {'id_subcategoria': $(this).val()}
			})
			.done(function(nombreSub){
				$('#div_secciones').html(nombreSub);
			})
		});

		

    });
    
	function tipo_seccion(s)
	{
		$.ajax({
			type: 'POST',
			url: 'get_nombreSec',
			data: {'id_seccion': s.value}
		})
		.done(function(nombreSec){
			$('#seccion_nombre').html(nombreSec);
		})
	}

	function zona_seleccionada(z)
	{
		$.ajax({
			type: 'POST',
			url: 'get_nombreZona',
			data: {'id_zona': z.value}
		})
		.done(function(nombreZona){
			$('#zona_nombre').html(nombreZona);
		})
	}

	function serv_seleccionado(s){
		var id_servicio = "#" + s.name;
		$('#nombre_servicio').text($(id_servicio).text());
	}

	function nombre_ordenar(o){
		var id_ordenar = "#" + o.name;
		$('#nombre_o').text($(id_ordenar).text());
	}

	function btnBusca()
	{
		$('#logo-principal-ubicalos').hide();
		$('#lupa-ubicalos-search').hide();
		$('#input-ubicalos-search').show();
	}


</script>

</html>
