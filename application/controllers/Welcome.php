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
		redirect('/Welcome/getCoordenadas');
	}

	public function getCoordenadas()
	{
		$this->load->view('getCoordenadas');
	}

	
	public function Inicio(){

		if(empty($_POST['latUser']))
		{
			redirect('/Welcome/getCoordenadas');
		}

		$latUser = $_POST['latUser'];
		$longUser = $_POST['longUser'];

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
			$sucursales = $this->bases->get_sucursales_categorias_lat_long($categorias_rand[$i]->id_categorias,$latUser,$longUser);

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

		if(empty($_GET['categoria']) || empty($_GET['sub_cat']))
		{
			redirect('/Welcome/getCoordenadas');
		}

		/* nav lateral */
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] = $secciones;
		/* */

		$informacion_negocio['categoria'] = $this->bases->get_categoria($_GET['categoria']);
		if($informacion_negocio['categoria'] == FALSE)
		{
			redirect('/Welcome/getCoordenadas');
		}

		$informacion_negocio['subcategorias_filtro'] = $this->bases->obtener_subcategorias($_GET['categoria']);
		$informacion_negocio['nombre_subcategoria'] = $this->bases->obtener_nombre_subcategoria($_GET['sub_cat']);
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
		$div_secciones = "";
		if($secciones != FALSE)
		{
			for($i=0; $i<count($secciones); $i++){
				$div_secciones .= '
					<div class="form-check">
						<input onclick="tipo_seccion(this)" class="form-check-input position-static" type="checkbox" name="s_'.$i.'" value="'.$secciones[$i]->id_secciones.'">
						<font class="color-black">'.$secciones[$i]->secciones.'</font>
					</div>
				';
			}
		}
		echo $div_secciones;
	}

	function get_Zonas()
	{
		$zonas = $this->bases->obtener_zonas_puebla();
		$div_zonas = "";
		for($i=0; $i<count($zonas); $i++)
		{
			$div_zonas .= '
			<div class="form-check">
				<input onclick="zona_seleccionada(this)" class="form-check-input position-static" type="radio" style="display: inline;" name="zona" value="'.$zonas[$i]->id_zona.'">
				<font class="color-black">'.$zonas[$i]->zona.'</font>
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
