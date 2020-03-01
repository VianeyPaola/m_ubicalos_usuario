
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
		
		if(window.location.pathname == '/m_ubicalos_usuario/Welcome/Inicio'){
			add_shadow()
		}else{
			delete_shadow()
		}

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

		$('#categorias-buscadas').owlCarousel({
			autoWidth: true,
            margin: 0
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
		/*m_ubicalos*/
		var position_nav = <?php echo $position_nav; ?>;
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

        /* funciones dinamicas para informacion principal */
        var id_empresa = <?php echo $id_empresa; ?>;

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
            data: {'id_empresa': id_empresa }
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
            data: {'id_empresa': id_empresa }
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
            data: {'id_empresa': id_empresa }
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
            data: {'id_empresa': id_empresa }
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
            data: {'id_empresa': id_empresa }
        })
        .done(function(div_boton_llamar){
            $('#div_boton_llamar').html(div_boton_llamar)
        })
        .fail(function(){
            location.reload();
        })

        /* fin */

    /*FIN M_UBICALOS */


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
		
		var latUser = 19.0438393;
		var longUser = -98.2004204;

		$('#longitud').val(longUser);
		$('#latitud').val(latUser);

		obtenerEmpresas(latUser, longUser, 1);
		
		/* Obtenemos las empresas */
		if($('#empresas_sub').length)
		{
			getLocation();
		}

		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else {
				x.innerHTML = "Geolocation is not supported by this browser.";
			}
		}

		/* Cargamos las empresas una vez obtenido la geolocalización */
		function showPosition(position) {
			latUser = position.coords.latitude;
			longUser = position.coords.longitude;
			//let params = new URLSearchParams(location.search);
			$('#longitud').val(longUser);
			$('#latitud').val(latUser);
			obtenerEmpresas(latUser, longUser, 1);	
		}

		/* función para cargar las empresas de las subcategoria */
		function obtenerEmpresas(lat_User, long_User, pagina){
			let params = new URLSearchParams(location.search);
			var categoria = params.get('categoria');
			var sub_cat = params.get('sub_cat');

			$.ajax({
				type: 'POST',
				url: 'get_EmpresasSub',
				data:{'latUser':lat_User, 'longUser':long_User, 'categoria': categoria, 'sub_cat': sub_cat, 'pagina': pagina}
			})
			.done(function(empresas){
				$('#empresas_sub').html(empresas);
			})
		}
		/* */

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

		var filtro_ = true;
		$('#filtro_').on("click", function(){
			if(filtro_){
				$('#filtro_').text("Filtro -");
			}else{
				$('#filtro_').text("Filtro +");
			}

			filtro_ = !filtro_;
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
		
		$('#nombre_servicio').text(s);
		
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

	
	function delete_shadow()
	{
		var elemt = document.getElementById("navbar");
		elemt.classList.remove("header-shadow");
	}
	function add_shadow()
	{
		var elemt = document.getElementById("navbar");
		elemt.classList.add("header-shadow");
	}

	



</script>

</html>
