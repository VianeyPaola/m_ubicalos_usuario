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
		$informacion_negocio_query =$this->bases->obtener_informacion_negocio(4);
		$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
		$informacion_negocio['position_nav'] = 0;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
		$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
		$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
		/* Galeria y videos */
		$informacion_negocio['galeria_sesion'] = $this->bases->obtener_galeria($informacion_negocio['id_empresa']);
		$informacion_negocio['videos_sesion'] = $this->bases->obtener_videos($informacion_negocio['id_empresa']);
		/* Informacion gnral */
		$informacion_negocio['informacion_sesion'] = $informacion_negocio_query[0]->info_general;
		$informacion_negocio['servicios_sesion'] = $informacion_negocio_query[0]->servicios_productos;
		/* Horario */
		$informacion_matriz = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
		$informacion_negocio['id_sucursal'] = $informacion_matriz[0]->id_sucursal;
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
		$informacion_negocio['subcategorias'] 	= $secciones;
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('m_ubicalos/informacion_negocio_principal.php');
		$this->load->view('m_ubicalos/sesion_inicio');
		$this->load->view('m_ubicalos/publicidad');
		$this->load->view('footer');
	}
	/*FIN M_UBICALOS*/
}