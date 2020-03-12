
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
		/*M_UBICALOS*/
	

        /* fin */

		/*FIN M_UBICALOS*/
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

		function publicidadtarjeta_ch(){
			$.ajax({
				type: 'POST',
				url: 'get_publicidad_tarjeta_ch'
			})
			.done(function(publicidad_tarjeta_ch){
				$('#publicidad_tarjeta_ch').html(publicidad_tarjeta_ch)
			})
			.fail(function(){
				//location.reload();
			})
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


		obtenerPaginacion(latUser, longUser);


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

	
	function obtenerPaginacion(lat_User, long_User)
	{
		let params = new URLSearchParams(location.search);
		var categoria = params.get('categoria');
		var sub_cat = params.get('sub_cat');

		/* Obtenemos la cantidad de empresas para la paginacion */
		$.ajax({
			type: 'POST',
			url: 'get_Paginacion',
			data:{'categoria': categoria, 'sub_cat': sub_cat, 'latUser' : lat_User, 'longUser' : long_User}
		})
		.done(function(paginas){
			$('#div_paginacion').html(paginas);
		})
	}
	/* */

	var page_anterior = 1;

	function cambiarPagina(pag,lat_User,long_User)
	{
		obtenerEmpresas(lat_User, long_User, pag);
		$('#page_'+page_anterior).removeClass('active');
		$('#page_'+pag).addClass('active');
		page_anterior = pag;
	}

	function cambiarPaginaNext(pag,lat_User,long_User,total_pages)
	{
		if(pag+1 <= total_pages)
		{
			var next = pag+1;
			if($('#page_'+next).length == 0)
			{
				nuevaPaginaNext(next, total_pages, lat_User,long_User);	
			}else{
				cambiarPagina(pag+1,lat_User,long_User)
			}
		}
	}

	function cambiarPaginaLast(pag,lat_User,long_User,total_pages)
	{
		if(pag-1 > 0)
		{
			var last = pag-1;
			if($('#page_'+last).length == 0)
			{
				nuevaPaginaNext(last, total_pages, lat_User,long_User);	
			}else{
				cambiarPagina(pag-1,lat_User,long_User)
			}
		}
	}

	function nuevaPaginaNext(paginai, total_paginas, lat_User,long_User)
	{
		$('#div_paginacion').empty();

		$.ajax({
			type: 'POST',
			url: 'nuevaPaginaNext',
			data:{'paginai': paginai, 'total_paginas': total_paginas, 'latUser' : lat_User, 'longUser' : long_User}
		})
		.done(function(paginas){
			$('#div_paginacion').html(paginas);
			cambiarPagina(paginai,lat_User,long_User)
		})
	}

	function nuevaPaginaLast(paginai, total_paginas, lat_User,long_User)
	{
		$('#div_paginacion').empty();

		$.ajax({
			type: 'POST',
			url: 'nuevaPaginaLast',
			data:{'paginai': paginai, 'total_paginas': total_paginas, 'latUser' : lat_User, 'longUser' : long_User}
		})
		.done(function(paginas){
			$('#div_paginacion').html(paginas);
			cambiarPagina(paginai,lat_User,long_User)
		})
	}



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
