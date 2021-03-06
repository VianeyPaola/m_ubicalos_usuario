<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('bases');
		$this->load->library('session');
	}

	public function index()
	{
		redirect('/Welcome/Inicio');
	}

	public function getCoordenadas()
	{
		$this->load->view('getCoordenadas');
	}

	public function Inicio(){

		// if(empty($_POST['latUser']))
		// {
		// 	redirect('/Welcome/getCoordenadas');
		// }

		// $latUser = $_POST['latUser'];
		// $longUser = $_POST['longUser'];

		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		$categorias_rand = $this->bases->obtener_categorias_rand();
		$informacion_negocio['categorias_rand'] = $categorias_rand;

		$total_categorias = count($categorias_rand);

		$sucursales_rand = Array($total_categorias);
		$publicidad_categoria_rad = Array($total_categorias);
		$tiene_promocion = Array($total_categorias);
		$fotos_publicidad_categoria  = Array($total_categorias);

		for($i=0; $i<$total_categorias; $i++)
		{
			/* Obtenemos todas las sucursales de una categoria */
			// $sucursales = $this->bases->get_sucursales_categorias_lat_long($categorias_rand[$i]->id_categorias,$latUser,$longUser);
			$sucursales = $this->bases->get_sucursales_categorias($categorias_rand[$i]->id_categorias);

			/* Verificamos que tenga sucursales */
			if($sucursales != FALSE)
			{
				$total_sucursales = count($sucursales);
				$sucursales_array = Array($total_sucursales);

				$sucursal = Array('foto','suc');
				
				for($j=0; $j < $total_sucursales; $j++ )
				{
					$sucursal['foto'] = $this->bases->get_Imagen_Empresa($sucursales[$j]->id_sucursal);
					$sucursal['suc'] = $sucursales[$j];
					$sucursales_array[$j] = $sucursal;

				}

				$sucursales_rand[$i] = $sucursales_array;
				
				/* Obtenemos la publicidad por categoria home */
				$publicidad_cat = $this->bases->get_Publicidad_Categoria_Home($categorias_rand[$i]->id_categorias);
				if($publicidad_cat != FALSE)
				{
					$publicidad_categoria_rad[$i] = $publicidad_cat[0];
					$fotos_publicidad_categoria[$i] = $this->bases->get_Img_Publicidad_Categoria($publicidad_cat[0]->id_publicidad);
					$tiene_promocion[$i] = $this->bases->tiene_promo_publicidad($publicidad_cat[0]->id_empresa);
				}else{
					$publicidad_categoria_rad[$i] = FALSE;
				}
			}else{
				$sucursales_rand[$i] = FALSE;
			}
			
		}

		$informacion_negocio['sucursales_rand'] = $sucursales_rand;
		$informacion_negocio['publicidad_categoria_rad'] = $publicidad_categoria_rad;
		$informacion_negocio['tiene_promocion'] = $tiene_promocion;
		$informacion_negocio['fotos_publicidad_categoria'] = $fotos_publicidad_categoria;
		$informacion_negocio['mas_buscados'] = $this->bases->obtener_mas_buscados();
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('inicio');
		$this->load->view('publicidad');
		$this->load->view('footer');
	}

	public function get_publicidad_banner()
	{
		$publicidad = $this->bases->get_publicidad_banner();
		if($publicidad != FALSE)
		{
			if($publicidad[0]->tipo_banner == "grande")
			{
				echo '
					<div class="col-md-12 pl-0 pr-0">
						<div id="div_icono_grande" style="background-color: '.$publicidad[0]->color_div.';">
							<div class="row">
								<div class="img-container">
									<img id="logo_banner" class="card-img-top img-promocion-1"
										src="'.$this->config->item('url_publicidad_banner').$publicidad[0]->logo.'">
								</div>
							</div>
							<div align="center">
								<a href="#" id="boton_banner" class="btn btn-link btn-promocion-1" style="background-color: '.$publicidad[0]->color_boton.'" >Ir a perfil</a>
							</div>
						</div>
						<img id="imagen_banner" style="height:110px; border-radius:0px" class="card-img-top m-0 p-0"
							src="'.$this->config->item('url_publicidad_banner').$publicidad[0]->foto.'">
					</div>
				';
			}else{
				echo '
					<div class="col pl-0 pr-0" style="flex: 0 0 65%; max-width: 65%">
							<img id="imagen_banner" style=" height:50px;border-radius:0px" class="card-img-top m-0 p-0"
								src="'.$this->config->item('url_publicidad_banner').$publicidad[0]->foto.'">
					</div>

					<div class="col pl-0 pr-0" style="flex: 0 0 15%; max-width: 15%;background-color: '.$publicidad[0]->color_div.'">
							<img style="max-width: 100%; height: 100%;border-radius:0px" class="centrar"
								src="'.$this->config->item('url_publicidad_banner').$publicidad[0]->logo.'">
					</div>
		
					<div  class="col pl-0 pr-0" style="background-color: '.$publicidad[0]->color_div.'; flex: 0 0 20%; max-width: 40%">
						<a href="#" id="boton_banner" class="btn btn-link btn-promocion-2 centrar" style="background-color: '.$publicidad[0]->color_boton.'" >Ir a perfil</a>
					</div>
				';
			}
		}
	}

	public function cargar_subcategorias()
	{
		$id_categoria = $this->input->post('id_categoria', TRUE);
		$subcategorias = $this->bases->obtener_subcategorias($id_categoria);
		$subcategorias_lista = "<option value='-1'>-Seleccionar Subcategoría-</option>";

		foreach($subcategorias as $subcategoria)
		{
			$subcategorias_lista .= "<option value='".$subcategoria->id_subcategoria."'>".$subcategoria->subcategoria."</option>";
		}
		
		echo $subcategorias_lista;
	}

	public function filtro_resultado(){

		if(empty($_GET['categoria']))
		{
			redirect('/Welcome/Inicio');
		}

		/* nav lateral */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] = $secciones;
		/* */
		$informacion_negocio['id_categoria'] = $_GET['categoria'];
		$informacion_negocio['categoria'] = $this->bases->get_categoria($_GET['categoria']);
		if($informacion_negocio['categoria'] == FALSE)
		{
			redirect('/Welcome/Inicio');
		}

		if(!empty($_GET['sub_cat']))
		{
			$sub_cat = $_GET['sub_cat'];
		}else{
			$sub_cat = 0;
		}

		$informacion_negocio['subcategorias_filtro'] = $this->bases->obtener_subcategorias($_GET['categoria']);
		$informacion_negocio['nombre_subcategoria'] = $this->bases->obtener_nombre_subcategoria($sub_cat);
		if($informacion_negocio['nombre_subcategoria'] == FALSE){
			$informacion_negocio['nombre_subcategoria'] = " ";
		}else{
			$informacion_negocio['nombre_subcategoria'] = $informacion_negocio['nombre_subcategoria'][0]->subcategoria;
		}

		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('filtro_resultado');
		$this->load->view('publicidad');
		$this->load->view('paginacion');
		$this->load->view('footer');
	}

	public function filtrado()
	{
		/* Obtenemos la categoria y sub_categoria */
		$id_categoria = $_GET['categoria'];

		if(!empty($_GET['sub_cat']))
		{
			$sub_cat = $_GET['sub_cat'];
		}else{
			$sub_cat = 0;
		}

		/* nav lateral */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] = $secciones;
		/* */
		$informacion_negocio['id_categoria'] = $_GET['categoria'];
		$informacion_negocio['categoria'] = $this->bases->get_categoria($_GET['categoria']);
		if($informacion_negocio['categoria'] == FALSE)
		{
			redirect('/Welcome/Inicio');
		}

		$informacion_negocio['subcategorias_filtro'] = $this->bases->obtener_subcategorias($_GET['categoria']);
		$informacion_negocio['nombre_subcategoria'] = $this->bases->obtener_nombre_subcategoria($sub_cat);
		if($informacion_negocio['nombre_subcategoria'] == FALSE){
			$informacion_negocio['nombre_subcategoria'] = " ";
		}else{
			$informacion_negocio['nombre_subcategoria'] = $informacion_negocio['nombre_subcategoria'][0]->subcategoria;
		}

		/* Recuperamos sus coordenadas */
		$latitud = $_GET['latitud'];
		$longitud = $_GET['longitud'];

		/* Recuperamos todas las secciones seleccionadas */
		$secciones_filtro = "";
		if(!empty($_GET['total_secciones']))
		{
			$total_secciones = $_GET['total_secciones'];
			for($i=0; $i<$total_secciones; $i++)
			{
				if(!empty($_GET['s_'.$i]))
				{
					$secciones_filtro .= " c.id_secciones LIKE '".$_GET['s_'.$i]."' OR";
				}
			}
		}
		$secciones_filtro = substr($secciones_filtro, 0, strlen($secciones_filtro)-3);
		if(strlen($secciones_filtro) > 0)
		{
			$secciones_filtro = "AND (".$secciones_filtro.")";
		}

		/* Recuperamos todos los servicios seleccioneados */

		$servicios_fitro = "";
		
		$total_serv = $this->bases->obtener_total_servicios()[0]->total;;
		for($i=0; $i<$total_serv; $i++)
		{
			if(!empty($_GET['serv_'.$i]))
			{
				$servicios_fitro .= "serv.id_servicios LIKE '".$_GET['serv_'.$i]."' OR ";
			}
		}
		$servicios_fitro = substr($servicios_fitro , 0, strlen($servicios_fitro)-3);
		if(strlen($servicios_fitro) > 0)
		{
			$servicios_fitro = "AND (".$servicios_fitro.")";
		}


		/* Recuperamos las zonas seleccionadas */
		$zonas_filtro = "";
		if(!empty($_GET['total_zonas']))
		{
			$total_zonas = $_GET['total_zonas'];
			for($i=0; $i<$total_zonas; $i++)
			{
				if(!empty($_GET['zona_'.$i]))
				{
					$zonas_filtro .= "z.id_zona LIKE '".$_GET['zona_'.$i]."' OR ";
				}
			}
		}
		$zonas_filtro = substr($zonas_filtro, 0, strlen($zonas_filtro)-3);
		if(strlen($zonas_filtro) > 0)
		{
			$zonas_filtro = "AND (".$zonas_filtro.")";
		}

		/* Recuperamos los ordenar o_1 .. o_4 */
		$ordenar = "";

		for($i=1; $i<=4; $i++)
		{
			if(!empty($_GET['o_'.$i]))
			{
				switch($i)
				{
					case 1:
						$ordenar .= "distance ";
						break;
					case 2:
						$ordenar .= "suc.calificacion ";
						break;
					case 4:
						$ordenar .= "suc.actualizacion ";
						break;
				}		
			}
		}

		if(strlen($ordenar) != 0){
			$ordenar = substr($ordenar, 0, strlen($ordenar)-1);
			$ordenar = "ORDER BY ".str_replace(" ",", ", $ordenar);
		}

		$ordenar_abierto = "FALSE";
		if(!empty($_GET['o_3']))
		{
			$ordenar_abierto = "TRUE";
		}

		$empresas = $this->bases->filtro_resultados($latitud, $longitud, $id_categoria, $secciones_filtro, $servicios_fitro, $zonas_filtro, $ordenar);
		$horario_array = Array(); //Nos dira si esta o no abierta la sucursal
		$horario_matriz_array = Array();
		$empresas_filtro_abierto = Array();
		//array_push($horario,"TRUE" or "FALSE")
		if($empresas != FALSE)
		{
			/*Obtenemos el horario*/
			date_default_timezone_set('America/Mexico_City');
			$hoy = getdate();

			/*Representacion numérica de las horas	0 a 23*/
			$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
			$horaActual = new DateTime($h);
			
			/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
			$d = $hoy['wday'];

			for($i=0; $i<count($empresas); $i++)
			{
				$horario_query = $this->bases->obtener_horarios($empresas[$i]->id_sucursal);
				$abierto = "FALSE";
				$horario_matriz = " ";
				if($horario_query != FALSE){
					foreach ($horario_query as $horario) {
						$dia = $horario -> dia;
						$hora_apertura = $horario->hora_apertura;
						$hora_cierre = $horario->hora_cierre;
						$horaA= new DateTime($hora_apertura);
						$horaC =  new DateTime($hora_cierre);
						$horaAS = $horaA->format('H:i');
						$horaCS = $horaC->format('H:i');
													
						switch ($dia) {
							case 'Lunes':
								if($d == '1')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}	
								break;
							case 'Martes':
								if($d == '2')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Miércoles':
								if($d == '3')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}		 	 
									break;
							case 'Jueves':
								if($d == '4')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Viernes':
								if($d == '5')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}  
								break;
							case 'Sábado':
								if($d == '6')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Domingo':
								if($d == '0')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;	
						}		            	
					}
				}

				if($ordenar_abierto == "TRUE" && $abierto == "TRUE")	//Almacenamos solo las empresas que se esncuentran abiertas
				{
					array_push($empresas_filtro_abierto, $empresas[$i]);
					array_push($horario_array, $abierto);
					array_push($horario_matriz_array, $horario_matriz);
				}else{
					if($ordenar_abierto == "FALSE")
					{
						array_push($horario_array, $abierto);
						array_push($horario_matriz_array, $horario_matriz);
					}
				}
				
			}

			if($ordenar_abierto == "TRUE")
			{
				$empresas = "";
				$empresas = $empresas_filtro_abierto;
			}
			
		}

		if($empresas != FALSE)
		{
			$total_pages = count($empresas) / 10;
			//$total_pages = 20;
			$ultimas = ($total_pages - round($total_pages))*10;
			$total_pages = ceil($total_pages);

			$div_paginacion = "";

			if($total_pages > 1){

				$div_paginacion = '<div class="pagination">
				<a onclick="cambiarPaginaLastF(page_anterior,'.$total_pages.')" >❮</a>';
				
				if($total_pages <= 6)
				{
					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';

					for($i=2; $i<=$total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}else{

					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';
					for($i=2; $i<3; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}

					$div_paginacion .= '<a>...</a>';

					for($i=$total_pages-1; $i <= $total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}

				

				$div_paginacion .='
						<a onclick="cambiarPaginaNextF(page_anterior,'.$total_pages.')">❯</a>
					</div>
				';
			}

		}else{
			$total_pages = 0;
			$ultimas = 0;
			$div_paginacion = "";
		}

		$informacion_negocio['total_paginas'] = $total_pages;
		$informacion_negocio['ultimas'] = abs($ultimas);
		$informacion_negocio['div_paginacion'] = $div_paginacion;
		$informacion_negocio['empresas'] = $empresas;
		$informacion_negocio['horario_array'] = $horario_array;
		$informacion_negocio['horario_matriz_array'] = $horario_matriz_array;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('filtrado_empresas');
		$this->load->view('publicidad');
		$this->load->view('paginacion');
		$this->load->view('footer');


	}

	public function filtro_promocion(){
		
		/* nav lateral */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] = $secciones;
		$informacion_negocio['categorias'] = $categorias_query;
		/* */

		$informacion_negocio['promociones'] = $this->bases->obtener_promociones_todas();
		/*Array de promociones */
		$promocion_S = array();
		if($informacion_negocio['promociones'] != FALSE){
			foreach($informacion_negocio['promociones']  as $promocion_sucursal){
				$id_promocion = $promocion_sucursal->id_promociones;           		
				$promocion_query  = $this->bases->obtener_promocion_sucursal($id_promocion);
				$ID = $id_promocion."";
				$promocion_S[$ID] = " ";
				
				if($promocion_query != FALSE)
				{
					$promocion_su= array();
					foreach($promocion_query as $promocion_q)
					{	
						array_push($promocion_su, $promocion_q->id_sucursal);					
					}	
					$promocion_S[$ID] = $promocion_su;
				}
			}
		}
		$informacion_negocio['promociones_sucursales'] = $promocion_S;

		if($informacion_negocio['promociones'] != FALSE)
		{
			$total_pages = count($informacion_negocio['promociones']) / 10;
			//$total_pages = 20;
			$ultimas = ($total_pages - round($total_pages))*10;
			$total_pages = ceil($total_pages);

			$div_paginacion = "";

			if($total_pages > 1){

				$div_paginacion = '<div class="pagination">
				<a onclick="cambiarPaginaLastF(page_anterior,'.$total_pages.')" >❮</a>';
				
				if($total_pages <= 6)
				{
					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';

					for($i=2; $i<=$total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}else{

					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';
					for($i=2; $i<3; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}

					$div_paginacion .= '<a>...</a>';

					for($i=$total_pages-1; $i <= $total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}

				

				$div_paginacion .='
						<a onclick="cambiarPaginaNextF(page_anterior,'.$total_pages.')">❯</a>
					</div>
				';
			}

		}else{
			$total_pages = 0;
			$ultimas = 0;
			$div_paginacion = "";
		}

		$informacion_negocio['total_paginas'] = $total_pages;
		$informacion_negocio['ultimas'] = abs($ultimas);
		$informacion_negocio['div_paginacion'] = $div_paginacion;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('filtro_promocion');
		$this->load->view('publicidad');
		$this->load->view('paginacion');
		$this->load->view('footer');
	}

	public function filtro_promocion_resultados(){
		/* nav lateral */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] = $secciones;
		$informacion_negocio['categorias'] = $categorias_query;
		/* */

		/* Recuperamos sus coordenadas */
		$latitud = $_GET['latitud'];
		$longitud = $_GET['longitud'];

		/* Recuperamos todas las secciones seleccionadas */
		$secciones_filtro = "";
		if(!empty($_GET['total_secciones']))
		{
			$total_secciones = $_GET['total_secciones'];
			for($i=0; $i<$total_secciones; $i++)
			{
				if(!empty($_GET['s_'.$i]))
				{
					$secciones_filtro .= " c.id_secciones LIKE '".$_GET['s_'.$i]."' OR";
				}
			}
		}

		$secciones_filtro = substr($secciones_filtro, 0, strlen($secciones_filtro)-3);
		if(strlen($secciones_filtro) > 0)
		{
			$secciones_filtro = "AND (".$secciones_filtro.")";
		}

		/* Recuperamos todos los servicios seleccioneados */

		$servicios_fitro = "";
		
		$total_serv = $this->bases->obtener_total_servicios()[0]->total;;
		for($i=0; $i<$total_serv; $i++)
		{
			if(!empty($_GET['serv_'.$i]))
			{
				$servicios_fitro .= "serv.id_servicios LIKE '".$_GET['serv_'.$i]."' OR ";
			}
		}

		$servicios_fitro = substr($servicios_fitro , 0, strlen($servicios_fitro)-3);
		if(strlen($servicios_fitro) > 0)
		{
			$servicios_fitro = "AND (".$servicios_fitro.")";
		}

		/* Recuperamos las zonas seleccionadas */
		$zonas_filtro = "";
		if(!empty($_GET['total_zonas']))
		{
			$total_zonas = $_GET['total_zonas'];
			for($i=0; $i<$total_zonas; $i++)
			{
				if(!empty($_GET['zona_'.$i]))
				{
					$zonas_filtro .= "z.id_zona LIKE '".$_GET['zona_'.$i]."' OR ";
				}
			}
		}
		$zonas_filtro = substr($zonas_filtro, 0, strlen($zonas_filtro)-3);
		if(strlen($zonas_filtro) > 0)
		{
			$zonas_filtro = "AND (".$zonas_filtro.")";
		}

		/* Recuperamos los ordenar o_1 .. o_4 */
		$ordenar = "";
		$distance = "";

		for($i=1; $i<=4; $i++)
		{
			if(!empty($_GET['o_'.$i]))
			{
				switch($i)
				{
					case 1:
						$ordenar = "distance ";
						$distance = ", 
									(6371 * ACOS( 
									SIN(RADIANS(suc.latitud)) * SIN(RADIANS(".$latitud.")) 
									+ COS(RADIANS(suc.longitud - (".$longitud."))) * COS(RADIANS(suc.latitud)) 
									* COS(RADIANS(".$latitud."))
									)) AS distance";
						break;
					case 2:
						$ordenar .= "suc.calificacion ";
						break;
					case 4:
						$ordenar .= "suc.actualizacion ";
						break;
				}		
			}
		}

		if(strlen($ordenar) != 0){
			$ordenar = substr($ordenar, 0, strlen($ordenar)-1);
			$ordenar = "ORDER BY ".str_replace(" ",", ", $ordenar);
		}

		$ordenar_abierto = "FALSE";
		if(!empty($_GET['o_3']))
		{
			$ordenar_abierto = "TRUE";
		}

		$promociones_q = $this->bases->obtener_promociones_filtro($distance, $secciones_filtro, $servicios_fitro, $zonas_filtro, $ordenar);
		$informacion_negocio['promociones'] = $promociones_q;

		if($ordenar_abierto == "TRUE"){

			if($promociones_q != FALSE){

				$promociones_filtro_abierto = Array();

				/*Obtenemos el horario*/
				date_default_timezone_set('America/Mexico_City');
				$hoy = getdate();

				/*Representacion numérica de las horas	0 a 23*/
				$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
				$horaActual = new DateTime($h);
				
				/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
				$d = $hoy['wday'];
				for($i=0; $i<count($promociones_q); $i++){
					
					$horario_query = $this->bases->obtener_horarios($promociones_q[$i]->id_sucursal);
					$abierto = "FALSE";
					$horario_matriz = " ";
					if($horario_query != FALSE){
						foreach ($horario_query as $horario) {
							$dia = $horario -> dia;
							$hora_apertura = $horario->hora_apertura;
							$hora_cierre = $horario->hora_cierre;
							$horaA= new DateTime($hora_apertura);
							$horaC =  new DateTime($hora_cierre);
							$horaAS = $horaA->format('H:i');
							$horaCS = $horaC->format('H:i');
														
							switch ($dia) {
								case 'Lunes':
									if($d == '1')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}	
									break;
								case 'Martes':
									if($d == '2')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}
									break;
								case 'Miércoles':
									if($d == '3')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}		 	 
										break;
								case 'Jueves':
									if($d == '4')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}
									break;
								case 'Viernes':
									if($d == '5')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}  
									break;
								case 'Sábado':
									if($d == '6')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}
									break;
								case 'Domingo':
									if($d == '0')
									{
										if($horaC>$horaA){
											if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
											}
										}else{
											if($horaActual >= $horaA &&  $horaActual >= $horaC){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
											}
										}
									}
									break;	
							}		            	
						}
					}

					if($abierto == "TRUE")	//Almacenamos solo las empresas que se encuentran abiertas
					{
						array_push($promociones_filtro_abierto, $promociones_q[$i]);
					}
					
				}

				$informacion_negocio['promociones'] = $promociones_filtro_abierto;
				$promociones_q = $promociones_filtro_abierto;
				
			}

		}

		/*Array de promociones */
		$promocion_S = array();
		if($informacion_negocio['promociones'] != FALSE){
			foreach($informacion_negocio['promociones']  as $promocion_sucursal){
				$id_promocion = $promocion_sucursal->id_promociones;           		
				$promocion_query  = $this->bases->obtener_promocion_sucursal($id_promocion);
				$ID = $id_promocion."";
				$promocion_S[$ID] = " ";
				
				if($promocion_query != FALSE)
				{
					$promocion_su= array();
					foreach($promocion_query as $promocion_q)
					{	
						array_push($promocion_su, $promocion_q->id_sucursal);					
					}	
					$promocion_S[$ID] = $promocion_su;
				}
			}
		}
		$informacion_negocio['promociones_sucursales'] = $promocion_S;

		if($informacion_negocio['promociones'] != FALSE)
		{
			$total_pages = count($informacion_negocio['promociones']) / 10;
			//$total_pages = 20;
			$ultimas = ($total_pages - round($total_pages))*10;
			$total_pages = ceil($total_pages);

			$div_paginacion = "";

			if($total_pages > 1){

				$div_paginacion = '<div class="pagination">
				<a onclick="cambiarPaginaLastF(page_anterior,'.$total_pages.')" >❮</a>';
				
				if($total_pages <= 6)
				{
					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';

					for($i=2; $i<=$total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}else{

					$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';
					for($i=2; $i<3; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}

					$div_paginacion .= '<a>...</a>';

					for($i=$total_pages-1; $i <= $total_pages; $i++)
					{
						$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
					}
				}

				

				$div_paginacion .='
						<a onclick="cambiarPaginaNextF(page_anterior,'.$total_pages.')">❯</a>
					</div>
				';
			}

		}else{
			$total_pages = 0;
			$ultimas = 0;
			$div_paginacion = "";
		}

		$informacion_negocio['total_paginas'] = $total_pages;
		$informacion_negocio['ultimas'] = abs($ultimas);
		$informacion_negocio['div_paginacion'] = $div_paginacion;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('filtro_promocion');
		$this->load->view('publicidad');
		$this->load->view('paginacion');
		$this->load->view('footer');


	}

	public function get_nombreSub()
	{
		$nombre_subcategoria = $this->bases->obtener_nombre_subcategoria($_POST['id_subcategoria']);
		if(strlen($nombre_subcategoria[0]->subcategoria) > 20)
		{
			$nombre_subcategoria[0]->subcategoria = substr($nombre_subcategoria[0]->subcategoria, 0, 17)."...";
		}

		echo $nombre_subcategoria[0]->subcategoria;
	}

	public function get_nombreSec()
	{
		$nombre_seccion = $this->bases->obtener_nombre_seccion($_POST['id_seccion']);
		if($nombre_seccion != FALSE){
			if(strlen($nombre_seccion[0]->secciones) > 20)
			{
				$nombre_seccion = substr($nombre_seccion[0]->secciones, 0, 17)."...";
			}else{
				$nombre_seccion = $nombre_seccion[0]->secciones;
			}
		}else{
			$nombre_seccion = "";
		}

		echo $nombre_seccion;
	}

	public function get_Subcategorias()
	{
		$id_categoria = $_POST['id_categoria'];
		$subcategorias = $this->bases->obtener_subcategorias($id_categoria);

		$div_subcategorias = "";
		for($i=0; $i<count($subcategorias); $i++)
		{
			$div_subcategorias .= 
			'<div class="form-check p-0 ">
				<div class="custom-control custom-radio">
					<input type="radio" onclick="obtener_secciones('.$subcategorias[$i]->id_subcategoria.')" id="customRadioSub'.$i.'" class="custom-control-input" value="'.$subcategorias[$i]->id_subcategoria.'" name="sub_cat" >
					<label class="custom-control-label color-black f-11" for="customRadioSub'.$i.'">'.$subcategorias[$i]->subcategoria.'</label>
				</div>
			</div>';
		}

		echo $div_subcategorias;
	}

	public function get_Secciones()
	{
		$secciones = $this->bases->obtener_secciones($_POST['id_subcategoria']);
		$total = count($secciones);
		$div_secciones = "<input type='hidden' name='total_secciones' value='".$total."'>";
		if($secciones != FALSE)
		{
			for($i=0; $i<$total; $i++){
				$div_secciones .= '
					<div class="custom-control custom-checkbox">
						<input onclick="tipo_seccion(this)" class="custom-control-input" type="checkbox" name="s_'.$i.'" id="customCheck'.$i.'" value="'.$secciones[$i]->id_secciones.'">
						<label class="custom-control-label color-black f-11" for="customCheck'.$i.'">'.$secciones[$i]->secciones.'</label>
					</div>
				';
			}
		}
		echo $div_secciones;
	}

	function get_Zonas()
	{
		$zonas = $this->bases->obtener_zonas_puebla();
		$total = count($zonas);
		$div_zonas = "<input type='hidden' name='total_zonas' value='".$total."' >";
		for($i=0; $i<$total; $i++)
		{
			$div_zonas .= '
			<div class="form-check p-0">
				<div class="custom-control custom-checkbox">
					<input onclick="zona_seleccionada(this)" class="custom-control-input" type="checkbox" name="zona_'.$i.'" id="'.$zonas[$i]->id_zona.'" value="'.$zonas[$i]->id_zona.'">
					<label class="custom-control-label color-black f-11" for="'.$zonas[$i]->id_zona.'">'.$zonas[$i]->zona.'</label>
				</div>
			</div>
			';
			
		}

		echo $div_zonas;
	}

	function get_nombreZona()
	{
		$zona = $this->bases->obtener_zona($_POST['id_zona']);
		if($zona != FALSE){
			if(strlen($zona[0]->zona) > 20)
			{
				$zona = substr($zona[0]->zona, 0, 17)."...";
			}else{
				$zona = $zona[0]->zona;
			}
		}else{
			$zona = "";
		}

		echo $zona;

	}

	function get_EmpresasSub()
	{
		$latUser = $_POST['latUser'];
		$longUser = $_POST['longUser'];
		$categoria = $_POST['categoria'];
		$sub_cat = $_POST['sub_cat'];
		$pagina = $_POST['pagina'];

		$page = ($pagina-1) * 10;

		if($sub_cat == 0)
		{
			$empresas = $this->bases->get_sucursales_categorias_inicio_lat_long($categoria,$latUser,$longUser,$page);
		}else{
			$empresas = $this->bases->get_sucursales_subcategorias_lat_long($sub_cat,$latUser,$longUser,$page);
		}

		
		$nombre_categoria = $this->bases->get_categoria($categoria);
		$nombre_categoria = $nombre_categoria[0]->categoria;

		if($empresas != FALSE)
		{
			$div_empresas = "";
			$total = count($empresas);
			
			/*Obtenemos el horario*/
			date_default_timezone_set('America/Mexico_City');
			$hoy = getdate();

			/*Representacion numérica de las horas	0 a 23*/
			$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
			$horaActual = new DateTime($h);
			
			/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
			$d = $hoy['wday'];

			for($i=0; $i<$total; $i++)
			{

				if($empresas[$i]->foto_perfil != NULL)
				{
					$foto = $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$empresas[$i]->id_empresa.'/'.$empresas[$i]->foto_perfil;
				}else{
					$foto = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
				}

				$sub_sec = $empresas[$i]->subcategoria." / ".$empresas[$i]->secciones;
				if(strlen($sub_sec) > 25)
				{   
					$sub_sec = substr($sub_sec, 0, 25);
					$sub_sec .="...";
				}

				$direccion = $empresas[$i]->tipo_vialidad." ".$empresas[$i]->calle." num. ext. ".$empresas[$i]->num_inter;
				if($empresas[$i]->num_inter == 0){
					$direccion .= ", num. int. ".$empresas[$i]->num_inter;
				}
				if(strlen($direccion) > 50){
					$direccion = substr($direccion, 0, 40);
					$direccion .="...";
				}

				$foto_galeria = $this->bases->get_Imagen_Empresa($empresas[$i]->id_sucursal);
				if($foto_galeria != FALSE){
					$foto_suc = $this->config->item('url_ubicalos')."ImagenesEmpresa/".$empresas[$i]->id_empresa."/".str_replace("´", "'",$foto_galeria[0]->nombre);
				}else{
					$foto_suc = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
				}

				$horario_query = $this->bases->obtener_horarios($empresas[$i]->id_sucursal);
				$abierto = "FALSE";
				$horario_matriz = " ";
				if($horario_query != FALSE){
					foreach ($horario_query as $horario) {
						$dia = $horario -> dia;
						$hora_apertura = $horario->hora_apertura;
						$hora_cierre = $horario->hora_cierre;
						$horaA= new DateTime($hora_apertura);
						$horaC =  new DateTime($hora_cierre);
						$horaAS = $horaA->format('H:i');
						$horaCS = $horaC->format('H:i');
													
						switch ($dia) {
							case 'Lunes':
								if($d == '1')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}	
								break;
							case 'Martes':
								if($d == '2')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Miércoles':
								if($d == '3')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}		 	 
									break;
							case 'Jueves':
								if($d == '4')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Viernes':
								if($d == '5')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}  
								break;
							case 'Sábado':
								if($d == '6')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Domingo':
								if($d == '0')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;	
						}		            	
					}
				}

				if($abierto == "TRUE")
				{
					$abierto = '
					<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
						<font class="color-green ">Abierto ahora: </font><font class="color-black">'.$horario_matriz.'</font>
					</p>';
					
				}else{
					$abierto = '
					<div class="col-12">
						<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
							<font class="color-red">Cerrado </font>
						</p>
					</div>';
				}

				/* */


				$div_empresas .= '
				
					<div class="row mb-n2 mt-1">
						<div class="col-12 ml-1 pl-2 mr-0 pr-0">
							<a href="'.base_url().'Empresa/Inicio?id_empresa='.$empresas[$i]->id_empresa.'&id_sucursal='.$empresas[$i]->id_sucursal.'">
								<div class="card ml-3 mr-3" style="max-width: 940px;">
									<div class="row no-gutters">

										<div class="col-auto">
												<img class="card-img img-cards" src="'.$foto_suc.'">
											</div>

										<div class="card-body mt-0 pt-0">
											<p class="mb-0 pb-0 color-black f-13">'.$empresas[$i]->nombre.'</p>
											<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">'.$sub_sec.'</p>
											<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En: Zona '.$empresas[$i]->zona.'</p>
											<div class="row mb-2">
												<div class="col-12">
													<img class="img-fluid img-home-categorias" src="'.$foto.'">
													<font class="estrellas mt-2">
														<font class="clasificacion mb-0">
															<input id="radio1" type="radio" name="estrellas" value="5">
															<label for="radio1">★</label>
															<input id="radio2" type="radio" name="estrellas" value="4">
															<label for="radio2">★</label>
															<input id="radio3" type="radio" name="estrellas" value="3">
															<label for="radio3">★</label>
															<input id="radio4" type="radio" name="estrellas" value="2">
															<label for="radio4">★</label>
															<input id="radio5" type="radio" name="estrellas" value="1">
															<label for="radio5">★</label>
														</font>
													</font>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
							<div class="w-100 mt-n2">
								<div class="col-12 ml-0 pl-0 mr-0 pr-0">
									<div class="card ml-3 mr-3" style="max-width: 940px;">
										<div class="row no-gutters">
											'.$abierto.'
											<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0">'.$direccion.'</p>
											<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Col. '.$empresas[$i]->colonia.' C.P. '.$empresas[$i]->cp.'</p>
											<div class="col-12">
												<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">Ult. Vez: '.$empresas[$i]->actualizacion.'</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="w-100 mt-0">
							<hr class="linea-division p-0 mt-2" />
						</div>
					</div>
				
				';

				
			}
			echo $div_empresas;
		}else{
			echo "
			<div class='container'>
				<p>No hay mas información</p>
			</div>";
		}

	}

	function get_Paginacion()
	{
		$sub_cat = $_POST['sub_cat'];
		$categoria = $_POST['categoria'];

		$lat_User = $_POST['latUser'];
		$long_User = $_POST['longUser'];

		if($sub_cat == 0)
		{
			$empresas = $this->bases->count_get_sucursales_categorias_inicio_lat_long($categoria);
		}else{
			$empresas = $this->bases->count_get_sucursales_subcategorias_lat_long($sub_cat);
		}

		if($empresas != FALSE)
		{
			$total = $empresas[0]->total;
		}else{
			$total = 0;
		}

		$num_pagina = $total/10;
		$num_pagina = ceil($num_pagina);
		//$num_pagina = 68;

		$div_paginacion = "";

		if($num_pagina > 1){

			$div_paginacion = '<div class="pagination">
			<a onclick="cambiarPaginaLast(page_anterior,'.$lat_User.','.$long_User.', '.$num_pagina.')">❮</a>';
			
			if($num_pagina <= 6)
			{
				$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPagina(1,'.$lat_User.','.$long_User.')">1</a>';

				for($i=2; $i<=$num_pagina; $i++)
				{
					$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPagina('.$i.','.$lat_User.','.$long_User.')">'.$i.'</a>';
				}
			}else{

				$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPagina(1,'.$lat_User.','.$long_User.')">1</a>';
				for($i=2; $i<3; $i++)
				{
					$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPagina('.$i.','.$lat_User.','.$long_User.')">'.$i.'</a>';
				}

				$div_paginacion .= '<a>...</a>';

				for($i=$num_pagina-1; $i <= $num_pagina; $i++)
				{
					$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPagina('.$i.','.$lat_User.','.$long_User.')">'.$i.'</a>';
				}
			}

			

			$div_paginacion .='
					<a onclick="cambiarPaginaNext(page_anterior,'.$lat_User.','.$long_User.', '.$num_pagina.')" >❯</a>
				</div>
			';
		}

		echo $div_paginacion;

	}

	function nuevaPaginaNext()
	{
		$paginai = $_POST['paginai'];
		$total_paginas = $_POST['total_paginas'];
		$lat_User = $_POST['latUser'];
		$long_User = $_POST['longUser'];

		if($paginai==0)
		{
			$paginai = 1;
		}

		$div_paginacion = '<div class="pagination">
			<a onclick="cambiarPaginaLast(page_anterior,'.$lat_User.','.$long_User.', '.$total_paginas.')">❮</a>';

		for($i=$paginai-1; $i<=$paginai; $i++)
		{
			$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPagina('.$i.','.$lat_User.','.$long_User.')">'.$i.'</a>';
		}

		$div_paginacion .= '<a>...</a>';

		for($i=$total_paginas-1; $i <= $total_paginas; $i++)
		{
			$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPagina('.$i.','.$lat_User.','.$long_User.')">'.$i.'</a>';
		}


		$div_paginacion .='
					<a onclick="cambiarPaginaNext(page_anterior,'.$lat_User.','.$long_User.', '.$total_paginas.')" >❯</a>
				</div>
			';

		echo $div_paginacion;

	}

	function nuevaPaginaNextF()
	{
		$paginai = $_POST['paginai'];
		$total_paginas = $_POST['total_paginas'];

		if($paginai==0)
		{
			$paginai = 1;
		}

		$div_paginacion = '<div class="pagination">
			<a onclick="cambiarPaginaLastF(page_anterior, '.$total_paginas.')">❮</a>';

		for($i=$paginai-1; $i<=$paginai; $i++)
		{
			$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
		}

		$div_paginacion .= '<a>...</a>';

		for($i=$total_paginas-1; $i <= $total_paginas; $i++)
		{
			$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
		}


		$div_paginacion .='
					<a onclick="cambiarPaginaNextF(page_anterior, '.$total_paginas.')" >❯</a>
				</div>
			';

		echo $div_paginacion;
	}

	public function get_publicidad_tarjeta_ch()
	{
		$publicidad = $this->bases->get_publicidad_tarjeta_ch();
		if($publicidad != FALSE)
		{
			$nombre = $publicidad[0]->nombre;
			$zona = $publicidad[0]->zona;
			$foto = str_replace("´", "'",$publicidad[0]->foto_perfil);

			if(strlen($zona) > 15)
			{
				$zona = substr($zona, 0, 13);
				$zona .= "...";
			}

			if(strlen($nombre) > 18 )
			{
				$nombre = substr($nombre, 0, 18);
				$nombre .= "...";
			}

			if($foto != "")
			{
				$foto = $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$publicidad[0]->id_empresa.'/'.$foto;
			}else{
				$foto = base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";
			}

			echo '
			<div class="col-12 d-flex justify-content-center mt-5">
				<div class="float text-center">
					<div class="row" style="width:218px ">
						<div class="col-3 pr-0">
							<img class="img-fluid" style="width: 35px; height: 35px; border: 1px solid white" 
							src="'.$foto.'">
						</div>
						<div class="col-auto ml-0 pl-0 "><b><font class="f-11 ml-1">'.$nombre.'</font></b></div>
						<div class="w-100"></div>
						<div class="col-auto offset-3 pl-0 " style="margin-top: -1.3rem !important">
							<font class="f-10 ml-1"> En zona: '.$zona.'</font>
						</div>
					</div>
				</div>
			</div>
			';
			
		}{
			echo '';
		}	
	}

	public function get_publicidad_pagina()
	{
		$id_categoria = $_POST['id_categoria'];
		$pagina = $_POST['pagina'];

		if($pagina <= 3){

			$publicidad = $this->bases->obtener_publicidad_filtro_pagina($id_categoria, $pagina);

		
			if($publicidad != FALSE){

				$info_promo =  $this->bases-> obtener_promocion_filtro_pagina($publicidad[0]->id_sucursal);

				if($publicidad[0]->foto_perfil != NULL)
				{
					$foto = $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$publicidad[0]->id_empresa.'/'.$publicidad[0]->foto_perfil;
				}else{
					$foto = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
				}

				$sub_sec = $publicidad[0]->subcategoria." / ".$publicidad[0]->secciones;
				if(strlen($sub_sec) > 25)
				{   
					$sub_sec = substr($sub_sec, 0, 25);
					$sub_sec .="...";
				}

				$direccion = $publicidad[0]->tipo_vialidad." ".$publicidad[0]->calle." num. ext. ".$publicidad[0]->num_inter;
				if($publicidad[0]->num_inter == 0){
					$direccion .= ", num. int. ".$publicidad[0]->num_inter;
				}
				if(strlen($direccion) > 50){
					$direccion = substr($direccion, 0, 40);
					$direccion .="...";
				}

				$foto_galeria = $this->bases->get_Imagen_Empresa($publicidad[0]->id_sucursal);
				if($foto_galeria != FALSE){
					$foto_suc = $this->config->item('url_ubicalos')."ImagenesEmpresa/".$publicidad[0]->id_empresa."/".str_replace("´", "'",$foto_galeria[0]->nombre);
				}else{
					$foto_suc = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
				}

				/*Obtenemos el horario*/
				date_default_timezone_set('America/Mexico_City');
				$hoy = getdate();

				/*Representacion numérica de las horas	0 a 23*/
				$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
				$horaActual = new DateTime($h);
				
				/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
				$d = $hoy['wday'];

				$horario_query = $this->bases->obtener_horarios($publicidad[0]->id_sucursal);
				$abierto = "FALSE";
				$horario_matriz = " ";
				if($horario_query != FALSE){
					foreach ($horario_query as $horario) {
						$dia = $horario -> dia;
						$hora_apertura = $horario->hora_apertura;
						$hora_cierre = $horario->hora_cierre;
						$horaA= new DateTime($hora_apertura);
						$horaC =  new DateTime($hora_cierre);
						$horaAS = $horaA->format('H:i');
						$horaCS = $horaC->format('H:i');
													
						switch ($dia) {
							case 'Lunes':
								if($d == '1')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}	
								break;
							case 'Martes':
								if($d == '2')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Miércoles':
								if($d == '3')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}		 	 
									break;
							case 'Jueves':
								if($d == '4')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Viernes':
								if($d == '5')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}  
								break;
							case 'Sábado':
								if($d == '6')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;
							case 'Domingo':
								if($d == '0')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
												$horario_matriz = $horaAS." - ".$horaCS;
												$abierto  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_matriz = $horaAS." - ".$horaCS;
											$abierto  = "TRUE";
										}
									}
								}
								break;	
						}		            	
					}
				}

				if($abierto == "TRUE")
				{
					$abierto = '
					<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
						<font class="color-green ">Abierto ahora: </font><font class="color-black">'.$horario_matriz.'</font>
					</p>';
					
				}else{
					$abierto = '
					<div class="col-12">
						<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
							<font class="color-red">Cerrado </font>
						</p>
					</div>';
				}

				$div_empresas = '
					<div class="row mb-n2 mt-1">
						<div class="col-12 ml-1 pl-2 mr-0 pr-0">
							<a href="'.base_url().'Empresa/Inicio?id_empresa='.$publicidad[0]->id_empresa.'&id_sucursal='.$publicidad[0]->id_sucursal.'">
								<div class="card ml-3 mr-3" style="max-width: 940px;">
									<div class="row no-gutters">

										<div class="col-auto">
												<img class="card-img img-cards" src="'.$foto_suc.'">
											</div>

										<div class="card-body mt-0 pt-0">
											<p class="mb-0 pb-0 color-black f-13">'.$publicidad[0]->nombre.'</p>
											<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">'.$sub_sec.'</p>
											<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En: Zona '.$publicidad[0]->zona.'</p>
											<div class="row mb-2">
												<div class="col-12">
													<img class="img-fluid img-home-categorias" src="'.$foto.'">
													<font class="estrellas mt-2 ml-n1">
														<font class="clasificacion mb-0">
															<input id="radio1" type="radio" name="estrellas" value="5" checked>
															<label for="radio1">★</label>
															<input id="radio2" type="radio" name="estrellas" value="4">
															<label for="radio2">★</label>
															<input id="radio3" type="radio" name="estrellas" value="3">
															<label for="radio3">★</label>
															<input id="radio4" type="radio" name="estrellas" value="2">
															<label for="radio4">★</label>
															<input id="radio5" type="radio" name="estrellas" value="1">
															<label for="radio5">★</label>

														</font>
														<img class="img-add" src="'.base_url().'img/ICONO AD.png" style="display:true!important; height: 5px; ">
													</font>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
							<div class="w-100 mt-n2">
								<div class="col-12 ml-0 pl-0 mr-0 pr-0">
									<div class="card ml-3 mr-3" style="max-width: 940px;">
										<div class="row no-gutters">
											'.$abierto.'
											<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0">'.$direccion.'</p>
											<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Col. '.$publicidad[0]->colonia.' C.P. '.$publicidad[0]->cp.'</p>
											<div class="col-12">
												<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">Ult. Vez: '.$publicidad[0]->actualizacion.'</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';

				if($info_promo != FALSE)
				{
					$descripcion = $info_promo[0]->descripcion;

					if(strlen($descripcion) > 45)
					{
						$descripcion = substr($descripcion, 0, 37)."...";
					}

					//$descripcion = "hola";
					$div_empresas .= '
					<div class="w-100 mb-n2 mt-2">
						
						<div class="w-100 mt-0">
							<div class="col-12 ml-0 pl-2 mr-0 pr-0" style="background-color: rgba(225, 48, 36,0.2) ">
								<div class="card ml-1 mr-2" style="max-width: 940px; height: 26px; background-color: transparent !important;">
									<div class="row no-gutters" style="margin-top:3px">
										<div class="col-auto">
											<img class="card-img img-cards-promocion" src="'.base_url().'img/ICONO PROMOCION.svg">
										</div>
										<p class="card-body color-red m-0 p-0">'.$descripcion.'</p>
									</div>
								</div>
							</div>
							<hr class="linea-division p-0 mt-2" />
						</div>

					</div>
					';
				}else{
					$div_empresas .= '<hr class="linea-division p-0 mt-3 mb-2" />';
				}

				echo $div_empresas;

			}else{
				echo '';
			}

		}else{
			echo '';
		}

	}

	public function autocompletado_buscador()
	{
		$resultados = $this->bases->autocompletado_buscador_empresa();


		$datalist = "";
		if($resultados != FALSE)
		{
			for($i=0; $i<count($resultados); $i++)
			{
				$datalist .= '<option value="'.$resultados[$i]->nombre.'"></option>';
			}
		}

		$resultados = $this->bases->autocompletado_buscador_empresa();

		echo $datalist;
	}

	public function buscador()
	{
		if(!empty($_GET['buscador-ubicalos']))
		{
			$buscar = $_GET['buscador-ubicalos'];
			$buscar = str_replace("'","´",$buscar);
			
			$empresas = $this->bases->obtener_busqueda($buscar);

			$horario_array = Array(); //Nos dira si esta o no abierta la sucursal
			$horario_matriz_array = Array();

			$div_empresas = "";

			if($empresas != FALSE)
			{
				/*Obtenemos el horario*/
				date_default_timezone_set('America/Mexico_City');
				$hoy = getdate();

				/*Representacion numérica de las horas	0 a 23*/
				$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
				$horaActual = new DateTime($h);
				
				/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
				$d = $hoy['wday'];

				
				$total_pages = count($empresas) / 10;
		
				$ultimas = ($total_pages - round($total_pages))*10;
				$total_pages = ceil($total_pages);

				for($p=1; $p<=$total_pages;$p++)
				{
					$inicio = ($p*10)-10;

					if($p!=$total_pages)
					{
						$fin = $inicio + 10; 
					}else{
						$fin = $inicio + $ultimas;
					}

					if($p==1)
					{
						$display = "block";
					}else{
						$display = "none";
					}

					$div_empresas .= '<div id="p'.$p.'" class="container-fluid pl-0" style="display:'.$display.'">';

					for($i=$inicio; $i<$fin; $i++)
					{
						$horario_query = $this->bases->obtener_horarios($empresas[$i]->id_sucursal);
						$abierto = "FALSE";
						$horario_matriz = " ";
						if($horario_query != FALSE){
							foreach ($horario_query as $horario) {
								$dia = $horario -> dia;
								$hora_apertura = $horario->hora_apertura;
								$hora_cierre = $horario->hora_cierre;
								$horaA= new DateTime($hora_apertura);
								$horaC =  new DateTime($hora_cierre);
								$horaAS = $horaA->format('H:i');
								$horaCS = $horaC->format('H:i');
															
								switch ($dia) {
									case 'Lunes':
										if($d == '1')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}	
										break;
									case 'Martes':
										if($d == '2')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}
										break;
									case 'Miércoles':
										if($d == '3')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}		 	 
											break;
									case 'Jueves':
										if($d == '4')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}
										break;
									case 'Viernes':
										if($d == '5')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}  
										break;
									case 'Sábado':
										if($d == '6')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}
										break;
									case 'Domingo':
										if($d == '0')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
														$horario_matriz = $horaAS." - ".$horaCS;
														$abierto  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_matriz = $horaAS." - ".$horaCS;
													$abierto  = "TRUE";
												}
											}
										}
										break;	
								}		            	
							}
						}

						if($empresas[$i]->foto_perfil != NULL)
						{
							$foto = $this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$empresas[$i]->id_empresa.'/'.$empresas[$i]->foto_perfil;
						}else{
							$foto = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
						}

						$sub_sec = $empresas[$i]->subcategoria." / ".$empresas[$i]->secciones;
						if(strlen($sub_sec) > 25)
						{   
							$sub_sec = substr($sub_sec, 0, 25);
							$sub_sec .="...";
						}

						$direccion = $empresas[$i]->tipo_vialidad." ".$empresas[$i]->calle." num. ext. ".$empresas[$i]->num_inter;
						if($empresas[$i]->num_inter == 0){
							$direccion .= ", num. int. ".$empresas[$i]->num_inter;
						}
						if(strlen($direccion) > 50){
							$direccion = substr($direccion, 0, 40);
							$direccion .="...";
						}

						$foto_galeria = $this->bases->get_Imagen_Empresa($empresas[$i]->id_sucursal);
						if($foto_galeria != FALSE){
							$foto_suc = $this->config->item('url_ubicalos')."ImagenesEmpresa/".$empresas[$i]->id_empresa."/".str_replace("´", "'",$foto_galeria[0]->nombre);
						}else{
							$foto_suc = base_url().'img/IMAGEN EVENTOS Y BLOGS.png';
						}

						if($abierto == "TRUE")
						{
							$abierto = '
							<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
								<font class="color-green ">Abierto ahora: </font><font class="color-black">'.$horario_matriz.'</font>
							</p>';
							
						}else{
							$abierto = '
							<div class="col-12">
								<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">
									<font class="color-red">Cerrado </font>
								</p>
							</div>';
						}

						/* */


						$div_empresas .= '<div class="row mb-n2 mt-1">
							<div class="col-12 ml-1 pl-2 mr-0 pr-0">
								<a href="'.base_url().'Empresa/Inicio?id_empresa='.$empresas[$i]->id_empresa.'&id_sucursal='.$empresas[$i]->id_sucursal.'">
									<div class="card ml-3 mr-3" style="max-width: 940px;">
										<div class="row no-gutters">

											<div class="col-auto">
													<img class="card-img img-cards" src="'.$foto_suc.'">
												</div>

											<div class="card-body mt-0 pt-0">
												<p class="mb-0 pb-0 color-black f-13">'.$empresas[$i]->nombre.'</p>
												<p class="card-text mb-0 pb-0 mt-n1 color-green f-10">'.$sub_sec.'</p>
												<p class="card-text mb-0 pb-0 mt-n1 f-11 color-blue-ubicalos">En: Zona '.$empresas[$i]->zona.'</p>
												<div class="row mb-2">
													<div class="col-12">
														<img class="img-fluid img-home-categorias" src="'.$foto.'">
														<font class="estrellas mt-2">
															<font class="clasificacion mb-0">
																<input id="radio1" type="radio" name="estrellas" value="5">
																<label for="radio1">★</label>
																<input id="radio2" type="radio" name="estrellas" value="4">
																<label for="radio2">★</label>
																<input id="radio3" type="radio" name="estrellas" value="3">
																<label for="radio3">★</label>
																<input id="radio4" type="radio" name="estrellas" value="2">
																<label for="radio4">★</label>
																<input id="radio5" type="radio" name="estrellas" value="1">
																<label for="radio5">★</label>

															</font>
														</font>
													</div>
												</div>
											</div>
										</div>
									</div>
								</a>
								<div class="w-100 mt-n2">
									<div class="col-12 ml-0 pl-0 mr-0 pr-0">
										<div class="card ml-3 mr-3" style="max-width: 940px;">
											<div class="row no-gutters">
												'.$abierto.'
												<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0">'.$direccion.'</p>
												<p class="color-blue-ubicalos f-11 arial mb-0 pb-0 mt-n1 pt-0"> Col. '.$empresas[$i]->colonia.' C.P. '.$empresas[$i]->cp.'</p>
												<div class="col-12">
													<p class="f-11 arial mb-0 pb-0 mt-n1 pt-0">Ult. Vez: '.$empresas[$i]->actualizacion.'</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="w-100 mt-0">
								<hr class="linea-division p-0 mt-2" />
							</div>
						</div>';
						
						
					}

					$div_empresas.='</div>';
				
				}

				$div_paginacion = "";

				if($total_pages > 1){

					$div_paginacion = '<div class="pagination">
					<a onclick="cambiarPaginaLastF(page_anterior,'.$total_pages.')" >❮</a>';
					
					if($total_pages <= 6)
					{
						$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';

						for($i=2; $i<=$total_pages; $i++)
						{
							$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
						}
					}else{

						$div_paginacion .= '<a class="active" id="page_1" onclick="cambiarPaginaF(1)">1</a>';
						for($i=2; $i<3; $i++)
						{
							$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
						}

						$div_paginacion .= '<a>...</a>';

						for($i=$total_pages-1; $i <= $total_pages; $i++)
						{
							$div_paginacion .= '<a id="page_'.$i.'" onclick="cambiarPaginaF('.$i.')">'.$i.'</a>';
						}
					}

					

					$div_paginacion .='
							<a onclick="cambiarPaginaNextF(page_anterior,'.$total_pages.')">❯</a>
						</div>
					';
				}

			}else{

				$div_empresas =	"<div class='container'>
									<p>No hay mas información</p>
								</div>";
				$total_pages = 0;
				$ultimas = 0;
				$div_paginacion = "";
			}

			/* nav lateral */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] = $secciones;
			/* */

			$informacion_negocio['empresas'] = $div_empresas; 
			$informacion_negocio['total_paginas'] = $total_pages;
			$informacion_negocio['div_paginacion'] = $div_paginacion;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('busqueda');
			$this->load->view('paginacion');
			$this->load->view('publicidad');
			$this->load->view('footer');

		}else{
			redirect('/Welcome/Inicio');
		}
	}

}	
