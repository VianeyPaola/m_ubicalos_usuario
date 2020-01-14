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

	
	public function Inicio(){
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('inicio');
		$this->load->view('footer');
	}	
}	