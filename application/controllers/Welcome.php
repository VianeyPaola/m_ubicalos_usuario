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
					
				}else{
					$publicidad_categoria_rad[$i] = FALSE;
				}
			}else{
				$sucursales_rand[$i] = FALSE;
			}
			
		}

		$informacion_negocio['sucursales_rand'] = $sucursales_rand;
		$informacion_negocio['publicidad_categoria_rad'] = $publicidad_categoria_rad;
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

		//$total_pages = $this->bases->count_filtro_resultados($latitud, $longitud, $id_categoria, $secciones_filtro, $servicios_fitro, $zonas_filtro, $ordenar);
		if($empresas != FALSE)
		{
			$total_pages = count($empresas) / 10;
		}else{
			$total_pages = 0;
		}

		$informacion_negocio['total_paginas'] = $total_pages;
		
		$informacion_negocio['empresas'] = $empresas;
		$informacion_negocio['horario_array'] = $horario_array;
		$informacion_negocio['horario_matriz_array'] = $horario_matriz_array;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('filtrado_empresas');
		$this->load->view('publicidad');
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
			$empresas = $this->bases->get_sucursales_categorias_inicio_lat_long($categoria,$latUser,$longUser);
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


				$div_empresas .= '<div class="row mb-n2 mt-2">
					<div class="col-12 ml-1 pl-2 mr-0 pr-0">
						<a>
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
												<font class="estrellas mt-2 ml-n1">
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
			echo $div_empresas;
		}else{
			echo "
			<div class='container'>
				<p>No hay información</p>
			</div>";
		}

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
			<div class="col-12 d-flex justify-content-center">
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


}	
