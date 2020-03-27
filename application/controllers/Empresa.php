<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('bases');
		/*$this->load->helper('url');
		$this->load->library('Services_JSON');*/
		$this->load->helper(array('download', 'file', 'url', 'html', 'form'));
        $this->folder = 'archivos/';
	}

    public function index()
	{
		redirect('/Empresa/Inicio');
	}
	/* Inicio de EMPRESA */
	public function Inicio()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 0;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		/* Galeria y videos */
		$informacion_negocio['galeria_sesion'] = $this->bases->obtener_galeria($id_empresa);
		$informacion_negocio['videos_sesion'] = $this->bases->obtener_videos($id_empresa);

		/* Informacion gnral */
		$informacion_negocio['informacion_sesion'] = $informacion_negocio_query[0]->info_general;
		$informacion_negocio['servicios_sesion'] = $informacion_negocio_query[0]->servicios_productos;

		/* Horario */
		
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$horarios_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
		if($horarios_query != FALSE)
		{
			foreach($horarios_query as $horarios)
			{
				$dia = $horarios->dia.$horarios->horario_num;
				$informacion_negocio[$dia] = $horarios;
			}
		}

		/* Promociones */
		$informacion_negocio['promociones']  = $this->bases->obtener_promociones($informacion_negocio['id_empresa']);
		/*Array de promociones */
		$promocion_S = array();
		if($informacion_negocio['promociones'] != False){
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

		/* Obtenemos todos los eventos */
		$eventos_todos_query = $this->bases->obtener_eventos_todos($informacion_negocio['id_empresa']);
		$array_eventos = array();
		$array_fechas_evento = array();
		$array_concepto_evento = array();
		if($eventos_todos_query != FALSE)
		{
			foreach($eventos_todos_query as $eventos_todos_q)
			{
				array_push($array_eventos, $eventos_todos_q);
				/* Obtenemos la fecha mas cercana del evento */
				$id_evento = $eventos_todos_q->id_evento;
				$fecha_evento_query = $this->bases->obtener_fecha_evento($id_evento);
				if($fecha_evento_query != FALSE)
				{
					foreach($fecha_evento_query as $fecha_evento_q)
					{
						array_push($array_fechas_evento, $fecha_evento_q);
					}
				}else{
					array_push($array_fechas_evento, "-");
				}
				/* Obtenemos el primer concepto */
				$concepto_evento_query = $this->bases->obtener_concepto_evento($id_evento);
				if($concepto_evento_query != FALSE)
				{
					foreach($concepto_evento_query as $concepto_evento_q)
					{
						array_push($array_concepto_evento, $concepto_evento_q);
					}
				}
			}
		}
		$informacion_negocio['eventos'] = $array_eventos;
		$informacion_negocio['fecha_evento'] = $array_fechas_evento;
		$informacion_negocio['concepto_evento'] = $array_concepto_evento;
		
		/*Obtiene la información de blogs  */
		$informacion_negocio['blogs']  = $this->bases->obtener_blogs($informacion_negocio['id_empresa']);

		
		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_inicio');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');


	}

	public function Informacion()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 1;

		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
		$informacion_negocio['info_general'] = $informacion_negocio_query[0]->info_general;
		$informacion_negocio['servicios_productos'] = $informacion_negocio_query[0]->servicios_productos;
		$informacion_negocio['ademas'] = $informacion_negocio_query[0]->ademas;
		$informacion_negocio['total_subcategorias'] = $informacion_negocio_query[0]->total_subcategorias;

		$informacion_sucursal_query = $this->bases->obtener_sucursal($id_sucursal);
		
		$informacion_negocio['id_direccion'] = $informacion_sucursal_query[0]->id_direccion;
		$informacion_negocio['latitud'] = $informacion_sucursal_query[0]->latitud;
		$informacion_negocio['longitud'] = $informacion_sucursal_query[0]->longitud;

		$informacion_direccion_query = $this->bases->obtener_direccion($informacion_negocio['id_direccion']);
		$informacion_negocio['calle'] = $informacion_direccion_query[0]->calle;
		$informacion_negocio['colonia'] = $informacion_direccion_query[0]->colonia;
		$informacion_negocio['tipo_vialidad'] = $informacion_direccion_query[0]->tipo_vialidad;
		$informacion_negocio['num_ext'] = $informacion_direccion_query[0]->num_ext;
		$informacion_negocio['num_inter'] = $informacion_direccion_query[0]->num_inter;
		$informacion_negocio['cp'] = $informacion_direccion_query[0]->cp;
		$informacion_negocio['municipio'] = $informacion_direccion_query[0]->municipio;
		$informacion_negocio['estado'] = $informacion_direccion_query[0]->estado;
		$informacion_negocio['id_zona'] = $informacion_direccion_query[0]->id_zona;
		/* Obtenemos las secciones y categorias */
		$informacion_negocio['subcategoria'] = "";
		$informacion_negocio['seccion'] = "";
		$informacion_negocio['subcategoria_id'] = "";
		$informacion_negocio['seccion_id'] = "";
		$informacion_negocio['seccion_1_1'] = -1;
		$informacion_negocio['seccion_1_2'] = -1;
		$informacion_negocio['seccion_1_3'] = -1;
		$informacion_negocio['seccion_1_4'] = -1;
		$informacion_negocio['seccion_1_5'] = -1;
		$informacion_negocio['subcategoria_1'] = -1;
		$informacion_negocio['subcategoria_2'] = -1;
		$informacion_negocio['subcategoria_3'] = -1;
		$informacion_negocio['subcategoria_4'] = -1;
		$informacion_negocio['subcategoria_5'] = -1;
		$i = 1;
		$categoria_subcategoria_seccion_query = $this->bases->obtener_categoria_subcategoria_secciones_empresa($informacion_negocio['id_empresa']);
		foreach($categoria_subcategoria_seccion_query as $categoria_subcategoria_seccion_result)
		{
			$informacion_negocio['categoria_id'] = $categoria_subcategoria_seccion_result->id_categoria;
			$informacion_negocio['categoria'] = $categoria_subcategoria_seccion_result->categoria;
			switch($i){
				case 1:
					$informacion_negocio['seccion_1_1'] = $categoria_subcategoria_seccion_result->secciones;
					$informacion_negocio['subcategoria_1'] = $categoria_subcategoria_seccion_result->subcategoria;
					break; 
				case 2:
					$informacion_negocio['seccion_1_2'] = $categoria_subcategoria_seccion_result->secciones;
					$informacion_negocio['subcategoria_2'] = $categoria_subcategoria_seccion_result->subcategoria;
					break;
				case 3:
					$informacion_negocio['seccion_1_3'] = $categoria_subcategoria_seccion_result->secciones;
					$informacion_negocio['subcategoria_3'] = $categoria_subcategoria_seccion_result->subcategoria;
					break;
				case 4:
					$informacion_negocio['seccion_1_4'] = $categoria_subcategoria_seccion_result->secciones;
					$informacion_negocio['subcategoria_4'] = $categoria_subcategoria_seccion_result->subcategoria;
					break;
				case 5:
					$informacion_negocio['seccion_1_5'] = $categoria_subcategoria_seccion_result->secciones;
					$informacion_negocio['subcategoria_5'] = $categoria_subcategoria_seccion_result->subcategoria;
					break;
			}
			$i++;
		}
		$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
		/* Obtenemos el horario */
		$horarios_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
		if($horarios_query != FALSE)
		{
			foreach($horarios_query as $horarios)
			{
				$dia = $horarios->dia.$horarios->horario_num;
				$informacion_negocio[$dia] = $horarios;
			}
		}
		/* Obtenemos los contactos de la sucursal */
		$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_negocio['id_sucursal']);
		if($contactos_query != FALSE)
		{
			foreach($contactos_query as $contactos_q)
			{
				$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
				$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
			}
		}
		/* Obtenemos las redes sociales */
		$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_negocio['id_sucursal']);
		if($redes_sociales_query != FALSE)
		{
			foreach($redes_sociales_query as $redes_sociales)
			{
				$tipo_red_social = $redes_sociales->red_social;
				$informacion_negocio[$tipo_red_social] = $redes_sociales->usuario;
			} 
		}
		/* Obtenemos los servicios */
		$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_negocio['id_sucursal']);
		if($servicios_query != FALSE)
		{
			foreach($servicios_query as $servicios)
			{
				$tipo_servicio = $servicios->servicio;
				$informacion_negocio[$tipo_servicio] = $servicios->servicio;
			}
		}
		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_informacion');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');

	}

	public function Sucursales()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 2;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

		$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
					
		$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
		/* Obtenemos el horario */
		$horarios_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
		if($horarios_query != FALSE){
			foreach($horarios_query as $horarios){
				$dia = $horarios->dia.$horarios->horario_num;
				$informacion_negocio[$dia] = $horarios;
			}
		}
		/* Obtenemos los contactos de la matriz */
		$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_negocio['id_sucursal']);
		if($contactos_query != FALSE){
			foreach($contactos_query as $contactos_q){
				$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
				$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
			}
		}
		/* Obtenemos las redes sociales */
		$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_negocio['id_sucursal']);
		if($redes_sociales_query != FALSE){
			foreach($redes_sociales_query as $redes_sociales){
				$tipo_red_social = $redes_sociales->red_social;
				$informacion_negocio[$tipo_red_social] = $redes_sociales->usuario;
			} 
		}
		/* Obtenemos los servicios */
		$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_negocio['id_sucursal']);
		if($servicios_query != FALSE){
			foreach($servicios_query as $servicios){
				$tipo_servicio = $servicios->servicio;
				$informacion_negocio[$tipo_servicio] = $servicios->servicio;
			}
		}
		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		/* Obtiene horario actual */
        date_default_timezone_set('America/Mexico_City');
            $hoy = getdate();
	        /*Representacion numérica de las horas	0 a 23*/
	        $h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
	        $horaActual = new DateTime($h);
	
	        /*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
	        $d = $hoy['wday'];
            /*Array que almacena el horario*/
            $abierto=array();
            /*Array que almacena el horario*/
            $horario_matriz=array();	          	          
            $horario_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
            $abierto["matriz"] = "FALSE";
            $horario_matriz["matriz"] = " ";
			if($horario_query != FALSE){
				foreach ($horario_query as $horario) {
					$dia = $horario -> dia;
					$hora_apertura = $horario -> hora_apertura;
					$hora_cierre = $horario -> hora_cierre;
					$horaA= new DateTime($hora_apertura);
					$horaC =  new DateTime($hora_cierre);
					$horaAS = $horaA->format('H:i');
					$horaCS = $horaC->format('H:i');
           			           			
            	   	switch ($dia) {
	            		case 'Lunes':
	            			if($d == '1'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							} 	      				
		            		break;
		            	case 'Martes':
		            		if($d == '2'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}
		            		break;
		            	case 'Miércoles':
		            		if($d == '3'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}		 	 
		            		break;
		            	case 'Jueves':
		            		if($d == '4'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}
		           			break;
		            	case 'Viernes':
		            		if($d == '5'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}  
		            		break;
		            	case 'Sábado':
		            		if($d == '6'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							} 
		            		break;
		            	case 'Domingo':
		            		if($d == '0'){
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							} 
		            		break;	
		            	}		            	
					}
				}
	        $informacion_negocio['abierto_matriz'] = $abierto;
	        $informacion_negocio['horario_matriz'] = $horario_matriz;
			$informacion_negocio['sucursales'] = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);

			/*Función para verificar si las sucursales estan abiertas*/
			
				/*Array que almacena el horario*/
	            $abierto_sucursal=array();
	            /*Array que almacena el horario*/
				$horario_sucursal=array();
				
				$cont = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
				$informacion_negocio['num_sucursales'] = $cont;
	            	foreach($informacion_negocio['sucursales'] as $sucursal){
	            		$id_sucursal = $sucursal -> id_sucursal;
						$horario_query = $this->bases->obtener_horarios($id_sucursal);
	            		$ID = $id_sucursal."";
	            		$abierto_sucursal[$ID] = "FALSE";
	            		$horario_sucursal[$ID] = " ";
						if($horario_query != FALSE){
							foreach ($horario_query as $horario) {
								$dia = $horario -> dia;
								$hora_apertura = $horario -> hora_apertura;
								$hora_cierre = $horario -> hora_cierre;
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
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
																
										break;
									case 'Martes':
										if($d == '2')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
											
										break;
									case 'Miércoles':
										if($d == '3')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
											
										break;
									case 'Jueves':
										if($d == '4')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
										
										break;
									case 'Viernes':
										if($d == '5')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}  
										break;
									case 'Sábado':
										if($d == '6')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										} 
										break;
									case 'Domingo':
										if($d == '0')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
										break;	
								}
							}
						}
	            	}
			/*Fin abierto sucursal*/
			
            $informacion_negocio['horario_sucursal'] = $horario_sucursal;
            $informacion_negocio['abierto_sucursal'] = $abierto_sucursal;
			$informacion_negocio['num_sucursales'] = $cont;
			
			/*Array de teléfono */
			$contacto=array();
			foreach($informacion_negocio['sucursales'] as $sucursal){
				$id_sucursal = $sucursal -> id_sucursal;
				$contactos_query = $this->bases->obtener_contactos_sucursal($id_sucursal);
				
				if($contactos_query != FALSE)
				{
					$ID = $id_sucursal."";
					$contacto[$ID] = " ";
					$contacto_S= array();
					foreach($contactos_query as $contactos_q)
					{
						if( $contactos_q->tipo =="telefono"){
							$contacto[$ID] = array_push($contacto_S, $contactos_q->valor);
						}					
					}	
					$contacto[$ID] = $contacto_S;
				}
			}
			$informacion_negocio['tel_sucursales'] = $contacto;
			/* Obtenemos los contactos de la matriz */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_negocio['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
				}
			}

			$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
			$this->load->view('m_ubicalos/informacion_negocio_principal');
			$this->load->view('m_ubicalos/sesion_sucursales',$informacion_negocio);
			$this->load->view('m_ubicalos/publicidad');
			$this->load->view('m_ubicalos/footer');
	}

	public function VerMapa_Sucursal(){
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal'])){
			redirect(base_url().'/Welcome');
		}
		///
		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];
		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);
		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}
		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 2.2;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
		/*******************************/
		$informacion_negocio['sucursales'] = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);

		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		/* */

		$informacion_negocio['sucursales']  = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);

		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_sucursal_ver_mapa',$informacion_negocio);
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');
	}

	public function Galeria()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 3;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		$informacion_negocio['galeria'] = $this->bases->obtener_galeria($informacion_negocio['id_empresa']);
		$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
		if($galeria_query != FALSE)
		{
			foreach($galeria_query as $galeria)
			{
				$informacion_negocio['total_img'] = $galeria->num_img;
			}

		}

		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_galeria');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');


	}

	public function Videos()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 4;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		$informacion_negocio['galeria'] = $this->bases->obtener_videos($informacion_negocio['id_empresa']);
		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
		
		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_videos');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');
	}

	public function Promociones()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 5;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);	

		$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);

		$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			/* Obtenemos el horario */
			$horarios_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
			if($horarios_query != FALSE)
			{
				foreach($horarios_query as $horarios)
				{
					$dia = $horarios->dia.$horarios->horario_num;
					$informacion_negocio[$dia] = $horarios;
				}
			}
			/* Obtenemos los contactos de la matriz */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_negocio['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
				}
			}
			/* Obtenemos las redes sociales */
			$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_negocio['id_sucursal']);
			if($redes_sociales_query != FALSE)
			{
				foreach($redes_sociales_query as $redes_sociales)
				{
					$tipo_red_social = $redes_sociales->red_social;
					$informacion_negocio[$tipo_red_social] = $redes_sociales->usuario;
				} 
			}
			/* Obtenemos los servicios */
			$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_negocio['id_sucursal']);
			if($servicios_query != FALSE)
			{
				foreach($servicios_query as $servicios)
				{
					$tipo_servicio = $servicios->servicio;
					$informacion_negocio[$tipo_servicio] = $servicios->servicio;
				}
			}
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			/* Obtiene horario actual */
			date_default_timezone_set('America/Mexico_City');
			$hoy = getdate();

			/*Representacion numérica de las horas	0 a 23*/
			$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
			$horaActual = new DateTime($h);
			
			/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
			$d = $hoy['wday'];

			/*Array que almacena el horario*/
			$abierto=array();
			/*Array que almacena el horario*/
			$horario_matriz=array();	          	          
			$horario_query = $this->bases->obtener_horarios($informacion_negocio['id_sucursal']);
			$abierto["matriz"] = "FALSE";
			$horario_matriz["matriz"] = " ";

			if($horario_query != FALSE){
				foreach ($horario_query as $horario) {
					$dia = $horario -> dia;
					$hora_apertura = $horario -> hora_apertura;
					$hora_cierre = $horario -> hora_cierre;
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
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}
													
							break;
						case 'Martes':
							if($d == '2')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}
							break;
						case 'Miércoles':
							if($d == '3')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}		 	 
							break;
						case 'Jueves':
							if($d == '4')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}
							break;
						case 'Viernes':
							if($d == '5')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							}  
							break;
						case 'Sábado':
							if($d == '6')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							} 
							break;
						case 'Domingo':
							if($d == '0')
							{
								if($horaC>$horaA){
									if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}else{
									if($horaActual >= $horaA &&  $horaActual >= $horaC){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
									}
								}
							} 
							break;	
					}		            	
				}
			}
	        $informacion_negocio['abierto_matriz'] = $abierto;
	        $informacion_negocio['horario_matriz'] = $horario_matriz;
			$informacion_negocio['sucursales'] = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);

			/*Función para verificar si las sucursales estan abiertas*/
			
			/*Array que almacena el horario*/
			$abierto_sucursal=array();
			/*Array que almacena el horario*/
			$horario_sucursal=array();
			
			$cont = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			$informacion_negocio['num_sucursales'] = $cont;
			foreach($informacion_negocio['sucursales'] as $sucursal){
				$id_sucursal = $sucursal -> id_sucursal;
				$horario_query = $this->bases->obtener_horarios($id_sucursal);
				$ID = $id_sucursal."";
				$abierto_sucursal[$ID] = "FALSE";
				$horario_sucursal[$ID] = " ";
				if($horario_query != FALSE){
					foreach ($horario_query as $horario) {
						$dia = $horario -> dia;
						$hora_apertura = $horario -> hora_apertura;
						$hora_cierre = $horario -> hora_cierre;
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
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}
														
								break;
							case 'Martes':
								if($d == '2')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}
									
								break;
							case 'Miércoles':
								if($d == '3')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}
									
								break;
							case 'Jueves':
								if($d == '4')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}
								
								break;
							case 'Viernes':
								if($d == '5')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}  
								break;
							case 'Sábado':
								if($d == '6')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								} 
								break;
							case 'Domingo':
								if($d == '0')
								{
									if($horaC>$horaA){
										if($horaActual >= $horaA && $horaC >= $horaActual){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}else{
										if($horaActual >= $horaA &&  $horaActual >= $horaC){
											$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
											$abierto_sucursal[$ID]  = "TRUE";
										}
									}
								}
								break;	
						}
					}
				}
			}
			/*Fin abierto sucursal*/
			
            $informacion_negocio['horario_sucursal'] = $horario_sucursal;
            $informacion_negocio['abierto_sucursal'] = $abierto_sucursal;
			$informacion_negocio['num_sucursales'] = $cont;
			
			/*Array de teléfono */
			$contacto=array();
			foreach($informacion_negocio['sucursales'] as $sucursal){
				$id_sucursal = $sucursal -> id_sucursal;
				$contactos_query = $this->bases->obtener_contactos_sucursal($id_sucursal);
				
				if($contactos_query != FALSE)
				{
					$ID = $id_sucursal."";
					$contacto[$ID] = " ";
					$contacto_S= array();
					foreach($contactos_query as $contactos_q)
					{
						if( $contactos_q->tipo =="telefono"){
							$contacto[$ID] = array_push($contacto_S, $contactos_q->valor);
						}					
					}	
					$contacto[$ID] = $contacto_S;
				}
			}
			$informacion_negocio['tel_sucursales'] = $contacto;
			/* Obtenemos los contactos de la matriz */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_negocio['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
				}
			}

			$informacion_negocio['sucursales'] = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
			$informacion_negocio['promociones']  = $this->bases->obtener_promociones($informacion_negocio['id_empresa']);
			/*Array de promociones */
			$promocion_S=array();
			if($informacion_negocio['promociones'] != False){
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
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
		
			/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_promocion');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');



	}

	public function Promocion_Sucursales()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_promociones	= $_GET['id_promociones'];
		$sucursales 	= $_GET['sucursales'];

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 5;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			$informacion_negocio['sucursales'] 			= $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
			$informacion_negocio['sucursales_promo']	= $sucursales;
			/** */			
			$informacion_promo = $this->bases->obtener_promocion_id($id_promociones);
			foreach($informacion_promo as $informacion){
				$informacion_negocio['promocion']=$informacion;
			}		            

			$informacion_negocio['sucursales'] 			= $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
			$informacion_negocio['sucursales_promo']	= $sucursales;
			
			/*Función para verificar si las sucursales estan abiertas*/
			date_default_timezone_set('America/Mexico_City');
	            $hoy = getdate();

	            /*Representacion numérica de las horas	0 a 23*/
	            $h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
	            $horaActual = new DateTime($h);
				
	            /*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
	            $d = $hoy['wday'];
			
				/*Array que almacena el horario*/
	            $abierto_sucursal=array();
	            /*Array que almacena el horario*/
	            $horario_sucursal=array();
				$cont = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
				$informacion_negocio['num_sucursales'] = $cont;
	            	          
	            	foreach($informacion_negocio['sucursales'] as $sucursal){
	            		$id_sucursal = $sucursal -> id_sucursal;
	            		$horario_query = $this->bases->obtener_horarios($id_sucursal);
	            		$ID = $id_sucursal."";
	            		$abierto_sucursal[$ID] = "FALSE";
	            		$horario_sucursal[$ID] = " ";
						if($horario_query != FALSE){
							foreach ($horario_query as $horario) {
								$dia = $horario -> dia;
								$hora_apertura = $horario -> hora_apertura;
								$hora_cierre = $horario -> hora_cierre;
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
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}																
										break;
									case 'Martes':
										if($d == '2')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}											
										break;
									case 'Miércoles':
										if($d == '3')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}											
										break;
									case 'Jueves':
										if($d == '4')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}										
										break;
									case 'Viernes':
										if($d == '5')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										} 
										break;
									case 'Sábado':
										if($d == '6')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										} 
										break;
									case 'Domingo':
										if($d == '0')
										{
											if($horaC>$horaA){
												if($horaActual >= $horaA && $horaC >= $horaActual){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}else{
												if($horaActual >= $horaA &&  $horaActual >= $horaC){
													$horario_sucursal[$ID] = $horaAS." - ".$horaCS;
													$abierto_sucursal[$ID]  = "TRUE";
												}
											}
										}
										break;	
								}
							}
						}
	            	}
            /*Fin abierto sucursal*/
            $informacion_negocio['horario_sucursal'] = $horario_sucursal;
            $informacion_negocio['abierto_sucursal'] = $abierto_sucursal;
			$informacion_negocio['num_sucursales'] = $cont;

			/*Array de teléfono */
			$contacto=array();
			foreach($informacion_negocio['sucursales'] as $sucursal){
				$id_sucursal = $sucursal -> id_sucursal;
				$contactos_query = $this->bases->obtener_contactos_sucursal($id_sucursal);
				
				if($contactos_query != FALSE)
				{
					$ID = $id_sucursal."";
					$contacto[$ID] = " ";
					$contacto_S= array();
					foreach($contactos_query as $contactos_q)
					{
						if( $contactos_q->tipo =="telefono"){
							$contacto[$ID] = array_push($contacto_S, $contactos_q->valor);
						}					
					}	
					$contacto[$ID] = $contacto_S;
	
				}
			}
			$informacion_negocio['tel_sucursales'] = $contacto;
			/*Fin array teléfono*/

			/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_promocion_ver_mas');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');


	}

	public function Eventos()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 6;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		/* Obtenemos todos los eventos */
		$eventos_todos_query = $this->bases->obtener_eventos_todos($informacion_negocio['id_empresa']);
		$array_eventos = array();
		$array_fechas_evento = array();
		$array_concepto_evento = array();
		if($eventos_todos_query != FALSE)
		{
			foreach($eventos_todos_query as $eventos_todos_q)
			{
				array_push($array_eventos, $eventos_todos_q);
				/* Obtenemos la fecha mas cercana del evento */
				$id_evento = $eventos_todos_q->id_evento;
				$fecha_evento_query = $this->bases->obtener_fecha_evento($id_evento);
				if($fecha_evento_query != FALSE)
				{
					foreach($fecha_evento_query as $fecha_evento_q)
					{
						array_push($array_fechas_evento, $fecha_evento_q);
					}
				}else{
					array_push($array_fechas_evento, "-");
				}
				/* Obtenemos el primer concepto */
				$concepto_evento_query = $this->bases->obtener_concepto_evento($id_evento);
				if($concepto_evento_query != FALSE)
				{
					foreach($concepto_evento_query as $concepto_evento_q)
					{
						array_push($array_concepto_evento, $concepto_evento_q);
					}
				}
			}
		}
		$informacion_negocio['eventos'] = $array_eventos;
		$informacion_negocio['fecha_evento'] = $array_fechas_evento;
		$informacion_negocio['concepto_evento'] = $array_concepto_evento;
		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_eventos');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');



	}

	public function Mostrar_Evento()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']) || empty($_GET['evento']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 6;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		/* Obtenemos la informacion del evento */
		$id_evento = $_GET['evento'];

		$evento_query = $this->bases->obtener_evento_id($id_evento, $informacion_negocio['id_empresa']);
		if($evento_query != FALSE)
		{
			foreach($evento_query as $evento_q)
			{
				$informacion_negocio['eventos'] = $evento_q;
				$fecha_principal_query = $this->bases->obtener_fecha_evento($evento_q->id_evento);
				$informacion_negocio['fecha_evento'] = $fecha_principal_query[0];

				$concepto_principal_query = $this->bases->obtener_concepto_evento($evento_q->id_evento);
				$informacion_negocio['concepto_evento'] = $concepto_principal_query[0];

				$informacion_negocio['fechas'] = $this->bases->obtener_fechas_evento($evento_q->id_evento);
				$informacion_negocio['conceptos'] = $this->bases->obtener_conceptos_evento($evento_q->id_evento);
				
				$informacion_negocio['fotos_evento'] = $this->bases->obtener_galeria_evento($evento_q->id_evento);
				$informacion_negocio['video_evento'] = $this->bases->obtener_video_evento($evento_q->id_evento);
			}
		}else{
			redirect(base_url().'/Welcome');
		}

		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
		

		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_mostrar_evento');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');
	}

	public function Blogs()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 7;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		foreach ($informacion_negocio_query as $informacion_negocio_q)
		{
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
			$informacion_negocio['foto_perfil'] = $informacion_negocio_q->foto_perfil;
		}
		/*Obtiene la información de blogs  */
		$informacion_negocio['blogs']  = $this->bases->obtener_blogs($informacion_negocio['id_empresa']);

		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_blogs');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');
	}

	public function VerMas_Blog()
	{
		if(empty($_GET['id_empresa']) || empty($_GET['id_sucursal']) || empty($_GET['id_blog']))
		{
			redirect(base_url().'/Welcome');
		}
		
		///

		$id_empresa = $_GET['id_empresa'];
		$id_sucursal = $_GET['id_sucursal'];

		$informacion_negocio_query = $this->bases->obtener_todo_empresa($id_empresa);

		if($informacion_negocio_query == FALSE || $this->bases->sucursal_empresa($id_empresa, $id_sucursal) == FALSE){
			redirect(base_url().'/Welcome');
		}

		///

		$informacion_negocio['id_empresa'] = $id_empresa;
		$informacion_negocio['id_sucursal'] = $id_sucursal;
		$informacion_negocio['position_nav'] = 7;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;


		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($id_empresa);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($id_empresa);

		/*Obtiene la información de blogs  */
		$id_blog = $_GET['id_blog'];
		$blog_query  = $this->bases->obtener_blog($id_blog);
		foreach ($blog_query as $blog_q){
		  $informacion_negocio['blog'] = $blog_q;
		  break;
		}
		
		$informacion_negocio['galeria_blog'] = $this->bases->obtener_galeria_blog($id_blog);
		$informacion_negocio['total_img']	 = $this->bases->num_fotos_blog($id_blog);


		/* */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;

		
		$this->load->view('m_ubicalos/nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal');
		$this->load->view('m_ubicalos/sesion_mostrar_blog');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('m_ubicalos/footer');
	}

	/*FIN M_UBICALOS*/

	/* Publicidad */
	
	function getPublicidadCascada()
	{
		$publicidad = $this->bases->obtener_cascada();
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

	function getPublicidadPrincipal()
	{
		$publicidad_principal = $this->bases->obtener_publicidad_perfil();

		if($publicidad_principal != FALSE)
		{
			$nombre_empresa = str_replace("´", "'",$publicidad_principal[0]->nombre);
			$foto_perfil = str_replace("´", "'",$publicidad_principal[0]->foto_perfil);
			$id_empresa = $publicidad_principal[0]->id_empresa;
			$publicidad = str_replace("´", "'",$publicidad_principal[0]->publicidad);
			$zona = $publicidad_principal[0]->zona;

			if(strlen($nombre_empresa) > 18 )
			{
				$nombre_empresa = substr($nombre_empresa, 0, 18);
				$nombre_empresa .= "...";
			}
			if(strlen($zona) > 15)
			{
				$zona = substr($zona, 0, 13);
				$zona .= "...";
			}

			echo '<div class="card " style="height: 58px; max-width: 940px;">
            		<div class="row no-gutters" style="height: 58px">
               			<div class="col-3" style="height: 58px">';

			if($publicidad_principal[0]->tipo == "video")
			{
				echo '
					<div class="embed-responsive embed-responsive-1by1" style="height: 100% !important;">
						<video id="video-publicidad" autoplay loop muted style="background-color: black; border-radius: 5px 0px 0 0;">
							<source src="'.$this->config->item('url_publicidad').$publicidad.'" type="video/mp4">
						</video> 
					</div>
				';
			}else{
				echo '
					<img class="card-img " style="border-radius: 10px 0px 0 0; height: 100% !important;"
					src="'.$this->config->item('url_publicidad').$publicidad.'">
				';
			}

			echo '	</div>
					<div class="col-7"  style="height:58px">
						<div class="card-body ml-2">
							<p class="card-text mb-0 pb-0 color-black f-13">
							'.$nombre_empresa.'
							</p>                       
							<p class="card-text mb-0 pb-0 mt-n1 pt-0 color-blue-ubicalos f-11">En: Zona '.$zona.'</p>                      
						</div>
					</div>
					<div class="col-2 ">
						<button onclick="MostrarPublicidadPantalla('.$publicidad_principal[0]->id_tarjeta.');" class="btn-publicidad centrar"> </button>
					</div>
				</div>
			</div>';
		}
	}

	function getPublicidadPrincipalPantalla()
	{
		$id_tarjeta = $_POST['id_tarjeta'];
		$publicidad = $this->bases->obtener_publicidad_perfil_id($id_tarjeta);

		if($publicidad != FALSE)
		{

			$nombre_p = str_replace("´", "'",$publicidad[0]->nombre);
			$id_empresa = $publicidad[0]->id_empresa;
			$foto_perfil = "";

			if($publicidad[0]->foto_perfil == "")
			{
				$foto_perfil = base_url()."img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png";
			}else{
				$foto_perfil = $this->config->item('url_ubicalos')."FotosPerfilEmpresa/".$id_empresa."/".str_replace("´", "'",$publicidad[0]->foto_perfil);
			}

			echo '<div class="row">';
			if($publicidad[0]->tipo == "video")
			{
				echo '
				<div class="embed-responsive embed-responsive-1by1">
					<video id="video-pantalla-c-p" controls autoplay loop style="background-color: black;">
						<source src="'.$this->config->item('url_publicidad').$nombre_p.'" type="video/mp4">
					</video> 
				</div>
				';

			}else{
				echo '
				<img style="border-radius: 0px; width: 100%; height: 100%;" class="img-fluid"
				src="'.$this->config->item('url_publicidad').$nombre_p.'">
				';
			}

			echo '</div>';

			echo '

				<div class="row mt-3">
					<div class="col-auto mr-n4">
						<img style="border-radius: 50%; width: 35px; height: 35px;" src="'.$foto_perfil.'" >
					</div>
					<div class="col-8">
						<p class="mt-2 ml-1" style="color: white; font-size: 12pt; font-weight:bold; vertical-align: middle">'.$publicidad[0]->nombre_empresa.'</p>
					</div>
				</div>
			';

		}



	}


	/* Informacion principal que se repite en todas las vistas, forma dinamica */

	public function getFotoPerfil()
	{
		$id_empresa = $_POST['id_empresa'];
		$foto_perfil = $this->bases->obtener_foto_perfil($id_empresa);
		$div_foto = '<img class="img-fluid" style="width: 105px; height: 105px;"';
		if($foto_perfil[0]->foto_perfil == NULL)
		{
			$div_foto .= 'src="'.base_url().'img/PERFIL_ IMAGEN_FOTO_DE_PERFIL.png" >';
		}else{
			$foto_perfil = str_replace("´", "'",$foto_perfil[0]->foto_perfil);
			$div_foto .= 'src="'.$this->config->item('url_ubicalos').'FotosPerfilEmpresa/'.$id_empresa.'/'.$foto_perfil.'" >';
		}

		echo $div_foto;
	}

	public function get_abierto_cerrado_horario()
	{
		date_default_timezone_set('America/Mexico_City');
		$hoy = getdate();

		/*Representacion numérica de las horas	0 a 23*/
		$h = $hoy['hours'].':'.$hoy['minutes'].':'.$hoy['seconds'];
		$horaActual = new DateTime($h);
		
		/*Obtiene el día de la semana Representacion numérica del día de la semana	0 (para Domingo) hasta 6 (para Sábado)*/
		$d = $hoy['wday'];

		/*Array que almacena el horario*/
		$abierto=array();
		/*Array que almacena el horario*/
		$horario_matriz=array();	          	          
		$horario_query = $this->bases->obtener_horarios($_POST['id_sucursal']);
		$abierto["matriz"] = "FALSE";
		$horario_matriz["matriz"] = " ";
		if($horario_query != FALSE){
			foreach ($horario_query as $horario) {
				$dia = $horario -> dia;
				$hora_apertura = $horario -> hora_apertura;
				$hora_cierre = $horario -> hora_cierre;
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
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}	
						break;
					case 'Martes':
						if($d == '2')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}
						break;
					case 'Miércoles':
						if($d == '3')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}		 	 
							break;
					case 'Jueves':
						if($d == '4')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}
						break;
					case 'Viernes':
						if($d == '5')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}  
						break;
					case 'Sábado':
						if($d == '6')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}
						break;
					case 'Domingo':
						if($d == '0')
						{
							if($horaC>$horaA){
								if($horaActual >= $horaA && $horaC >= $horaActual){
										$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
										$abierto["matriz"]  = "TRUE";
								}
							}else{
								if($horaActual >= $horaA &&  $horaActual >= $horaC){
									$horario_matriz["matriz"] = $horaAS." - ".$horaCS;
									$abierto["matriz"]  = "TRUE";
								}
							}
						}
						break;	
				}		            	
			}
		}

		$div_abierto_ahora = '<div class="row"><div class="col-auto" >';

		if($abierto["matriz"] == "TRUE")
		{
			$div_abierto_ahora .= '<p style="color: green; font-size: 11pt;">Abierto ahora</p></div>';
		}else{
			$div_abierto_ahora = '<p style="color: red; font-size: 11pt;">Cerrado</p></div>';
		}

		$div_abierto_ahora .= '<div class="col-auto float-md-left">
								<p style="font-size: 11pt">'.$horario_matriz["matriz"].'</p>
							   </div>
							</div>';

		echo $div_abierto_ahora;

	}

	
	public function get_Nombre_Empresa()
	{
		$nombre_negocio = $this->bases->obtener_nombre_empresa($_POST['id_empresa']);
		if($nombre_negocio != FALSE)
		{
			//'.$nombre_negocio[0]->nombre.'
			echo '
			<p style="font-size: 18pt; color: black;">'.$nombre_negocio[0]->nombre.'
				<img class="w-1 h-1 ml-0 mt-n1" style="width: 16px;" src="'.$this->config->item('url_mubicalos').'img/PERFIL_DATOS_VERIFICADOS.svg">
			</p>
			';
		}
	}

	public function get_Categorias_Sub_Secciones()
	{
		$query = $this->bases->obtener_categoria_subcategoria_secciones_empresa($_POST['id_empresa']);
		$info = '';
		if($query != FALSE)
		{
			$total = count($query);
			$info .= '
			<div>
				<p style="color: #22751D;">
				'.
					$query[0]->categoria.' - '.$query[0]->subcategoria.' - '.$query[0]->secciones;
			
			if($total > 1){
				$info .= '<a style="color: #3f6ad8;" href="#" onclick="masCSS();"> Más...</a>';
			}
			$info .= '</p>
			</div>
			';

			if($total > 1)
			{
				$info .= '<p id="toggle_ca_d" class="col-12 collapse" style="margin-top: -13px; color: #22751D;">
				';
					for($i=1; $i<$total; $i++)
					{
						$info .= $query[$i]->categoria.' - '.$query[$i]->subcategoria.' - '.$query[$i]->secciones.'<br>';
					}
				$info .= '</p>
				';
			}


		}

		echo $info;
	}

	public function get_Calificacion_Empresa()
	{
		echo '
		<div class="estrellas">
			<form>
				<p class="clasificacion mb-0">
				<input id="radio1" type="radio" name="estrellas" value="5"><!--
				--><label for="radio1">★</label><!--
				--><input id="radio2" type="radio" name="estrellas" value="4"><!--
				--><label for="radio2">★</label><!--
				--><input id="radio3" type="radio" name="estrellas" value="3"><!--
				--><label for="radio3">★</label><!--
				--><input id="radio4" type="radio" name="estrellas" value="2"><!--
				--><label for="radio4">★</label><!--
				--><input id="radio5" type="radio" name="estrellas" value="1"><!--
				--><label for="radio5">★</label>
				</p>
			</form>
		</div>
		';
	}

	public function get_Direccion_Empresa_Actualizacion()
	{
		$sucursales = $this->bases->obtener_sucursal($_POST['id_sucursal']);
		$total_suc = $this->bases->obtener_sucursales($_POST['id_empresa']);
		
		$direccion_empresa = $this->bases->obtener_direccion_sucursal($_POST['id_sucursal']);

		$direccion_empresa = $this->bases->obtener_direccion($direccion_empresa[0]->id_direccion);
		if($direccion_empresa != FALSE)
		{
			if($sucursales[0]->latitud != null && $sucursales[0]->longitud != null){ 
				echo '<a href="https://maps.google.com/?q='.$sucursales[0]->latitud.','.$sucursales[0]->longitud.'" target="_blank" >';
			}else{
				echo '<a href="https://www.google.com/maps/search/?api=1&query='.$sucursales[0]->calle.'" target="_blank" >';
			}

			echo '
				<p class ="color-blue-ubicalos" style="font-size: 11pt;">
						'.$direccion_empresa[0]->tipo_vialidad.' '.$direccion_empresa[0]->calle.', Col. '.$direccion_empresa[0]->colonia.', Núm ext.'.$direccion_empresa[0]->num_ext; 
			if(!empty($direccion_empresa[0]->num_inter))
			{
				echo ", Núm. int.".$direccion_empresa[0]->num_inter." ";
			}

			echo	'<a href="Sucursales?id_empresa='.$_POST['id_empresa'].'&id_sucursal='.$_POST['id_sucursal'].'">
						(+'.count($total_suc).' Sucursales)
					</a>
				</p>
			</a>';
					
		}
		$sucursales = $this->bases->obtener_sucursales($_POST['id_empresa']);
		if($sucursales != FALSE){
			$telefonos = $this->bases->obtener_contactos_sucursal($sucursales[0]->id_sucursal);
			if($telefonos != FALSE)
			{
				$numero_tel = "";
				$band_t = FALSE;
				if($telefonos[0]->tipo == "telefono" && $telefonos[0]->valor != "" )
				{
					$numero_tel = "<p class='mt-n3 '>Tel. <a  class='color-blue-ubicalos'>";
					$band_t = TRUE;
				}else{
					$numero_tel = "<p class='mt-n3'>Cel. <a  class='color-blue-ubicalos'>";
				}
				$cont = 1;
				for($i=0; $i<count($telefonos); $i++)
				{
					if($band_t == TRUE && $telefonos[$i]->tipo == "telefono" && $telefonos[$i]->valor != "")
					{
						$string = $telefonos[$i]->valor;
						$string_ = chunk_split($string, 3, '.');
						$string_e = explode(".",$string_) ;
						
						$numero_tel .= '<a href="tel:'.$string = $telefonos[$i]->valor.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
						if($cont != 3 && $telefonos[$i+1]->tipo == "telefono" && $i < count($telefonos)-1)
						{
							$numero_tel .= " / ";
						}
						$cont = $cont + 1;
					}
					if($band_t == FALSE && $telefonos[$i]->tipo == "celular" && $telefonos[$i]->valor != ""){
						$string = $telefonos[$i]->valor;
						$string_ = chunk_split($string, 3, '.');
						$string_e = explode(".",$string_) ;
						$numero_tel .= '<a href="tel:'.$string = $telefonos[$i]->valor.'">'.$string_e[0].".".$string_e[1].".".$string_e[2].$string_e[3].'</a>';
						if($i < count($telefonos)-1)
						{
							if($cont != 3 && $telefonos[$i+1]->tipo == "celular")
							{
								$numero_tel .= " / ";
							}
						}
						$cont = $cont + 1;
					}
					
				}
				echo $numero_tel.'</a></p>';
			}
		}
		echo '<p class="mt-n3" style="font-size: 11pt">Ult. Vez: '.$sucursales[0]->actualizacion.'</p>';
		
		
	}

	public function get_Boton_Direccion()
	{
		$sucursales = $this->bases->obtener_sucursal($_POST['id_sucursal']);
		
		if($sucursales != FALSE)
		{
			$direccion_empresa = $this->bases->obtener_direccion($sucursales[0]->id_direccion);
			if($direccion_empresa != FALSE)
			{
				if($sucursales[0]->latitud != null && $sucursales[0]->longitud != null){ 

					echo '<a href="https://maps.google.com/?q='.$sucursales[0]->latitud.','.$sucursales[0]->longitud.'" target="_blank" >';

				}else{
					echo '<a href="https://www.google.com/maps/search/?api=1&query='.$sucursales[0]->calle.'" target="_blank" >';
				}	 
			}else{
				echo '<a>';
			}
		}else{
			echo '<a>';
		}
		echo '<button type="button" class="btn btn-outline-ubicalos btn-block" style="font-size: 11pt;">¿Como llegar?</button>
			</a>';
	}

	public function get_Boton_Llamar()
	{
		$sucursales = $this->bases->obtener_sucursal($_POST['id_sucursal']);
		if($sucursales != FALSE)
		{
			$telefonos = $this->bases->obtener_contactos_sucursal($sucursales[0]->id_sucursal);
			if($telefonos != FALSE)
			{
				if($telefonos[0]->valor != "")
				{
					echo '<a href="tel:'.$telefonos[0]->valor.'">';
				}else{
					echo '<a href="tel:'.$telefonos[1]->valor.'">';
				}
			}else{
				echo '<a>';
			}
		}else{
			echo '<a>';
		}

		echo '<button  type="button" class="btn btn-ubicalos btn-block ml-n2" style="font-size:11pt;">Llamar</button>
		</a>';
	}

	public function get_Video()
	{
		$video = $this->bases->obtener_video($_POST['id_video']);
		if($video != FALSE)
		{
			$video_src = str_replace("´", "'",$video[0]->nombre);
			echo '
			<video id="video-seleccionado" class="embed-responsive-item" style="background-color: black;" loop="true" controls>
				<source id="video-src" src="'.$this->config->item('url_ubicalos').'VideosEmpresa/'.$video[0]->id_empresa.'/'.$video_src.'" type="video/mp4">
			</video>
				';
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

}
