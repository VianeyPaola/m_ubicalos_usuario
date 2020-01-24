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

		$categorias_rand = $this->bases->obtener_categorias_rand();
		$informacion_negocio['categorias_rand'] = $categorias_rand;

		$total_categorias = count($categorias_rand);

		$sucursales_rand = Array($total_categorias);
		$publicidad_categoria_rad = Array($total_categorias);
		$fotos_publicidad_categoria  = Array($total_categorias);

		for($i=0; $i<$total_categorias; $i++)
		{
			/* Obtenemos todas las sucursales de una categoria */
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

		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('inicio');
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
					<div class="col pl-0 pr-0" style="flex: 0 0 80%; max-width: 80%">
							<img id="imagen_banner" style=" height:50px;border-radius:0px" class="card-img-top m-0 p-0"
								src="'.$this->config->item('url_publicidad_banner').$publicidad[0]->foto.'">
					</div>
					<div  class="col pl-0 pr-0" style="background-color: '.$publicidad[0]->color_div.'; flex: 0 0 20%; max-width: 20%">
						<a href="#" id="boton_banner" class="btn btn-link btn-promocion-2 centrar" style="background-color: '.$publicidad[0]->color_boton.'" >Ir a perfil</a>
					</div>
				';
			}
		}
	}

}	
