<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('bases');
		$this->load->library('session');
	}

	public function VerRuta()
	{
		$ruta = $this->config->item('url_archivos_ubicalos');
		$gestor = opendir($ruta);
        echo "<ul>";

        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {
                
            $ruta_completa = $ruta . "/" . $archivo;

            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
                // Si es un directorio se recorre recursivamente
                if (is_dir($ruta_completa)) {
                    echo "<li>" . $archivo . "</li>";
                    //obtener_estructura_directorios($ruta_completa);
                } else {
                    echo "<li>" . $archivo . "</li>";
                }
            }
        }
        
        // Cierra el gestor de directorios
        closedir($gestor);
        echo "</ul>";
	}

	public function index()
	{
		redirect('/Welcome/Login');
	}

	public function Inicio(){
		$categorias_query = $this->bases->obtener_categorias_todas();
		$secciones = array();
		foreach ($categorias_query as $categorias_q){
			$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
		} 
		$informacion_negocio['subcategorias'] 	= $secciones;
		$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
		$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
		$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
		
		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('inicio');
		$this->load->view('footer');
	}

	/* Inicio de Registro */
	public function RegistroPropietario()
	{
		$this->load->view('administrador/header_registro');
		$this->load->view('administrador/registro_propietario');
		$this->load->view('administrador/footer_registro');
	}
	
	public function EnviarCodigo()
	{
		$correo = str_replace("'", "´", $this->input->post('correo_celular', TRUE) );
		$correo_outlook = explode("@", $correo);
		if($correo_outlook[1] == "outlook.com")
		{
			$correo = $correo_outlook[0]."@".ucfirst(strtolower($correo_outlook[1]));
		}

		/* Verificamos si el correo ya esta usado en una cuenta*/
		$existe = $this->bases->propietario_existe($correo);
		

		if($existe)
		{
			redirect('Welcome/index/');
		}else{

			$nombre = str_replace("'", "´", $this->input->post('nombre', TRUE) );
			$apellidos = str_replace("'", "´", $this->input->post('apellidos', TRUE) );
			$num_celular = str_replace("'", "´", $this->input->post('num_celular', TRUE) );
			$fecha_nacimiento = $this->input->post('fecha_nacimiento', TRUE);
			$sexo = $this->input->post('sexo', TRUE);
			$contrasenia = str_replace("'", "´", $this->input->post('contrasenia', TRUE));
			$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

			/* Encriptamos la contraseña */
			$contrasenia = md5($contrasenia);

			$informacion_propietario = [
				'nombre' => $nombre,
				'apellidos' => $apellidos,
				'num_celular' => $num_celular,
				'fecha_nacimiento' => $fecha_nacimiento,
				'sexo' => $sexo,
				'correo' => $correo,
				'contrasenia' => $contrasenia,
				'codigo' => $codigo,
			];

			/* Creamos al nuevo propietario */
			$this->bases->agregar_propietario($informacion_propietario);

			/* Campos para poder enviar un correo */
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://mail.ubicalos.mx',
				'smtp_port' => 465,
				'smtp_user' => 'notificaciones@ubicalos.mx',
				'smtp_pass' => 'Ubi$%ca=)los/&123',
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'newline' => "\r\n"
			);
		
			$CI = & get_instance();
			$CI->load->helper('url');
			$CI->load->library('session');
			$CI->config->item('base_url');
		
			$CI->load->library('email');
		
			$CI->email->initialize($config);
		
			$subject = 'Ubicalos.mx| Código de confirmación de cuenta';
			$msg = 'Código: '.$codigo;
		
			/* Envia el código al correo electronico */
			$CI->email->from('notificaciones@ubicalos.mx')->to($correo)->subject($subject)->message($msg)->send();
			/* Redireccionamos para validar el codigo */
			redirect('Welcome/VerificarCodigo?correo='.$correo);
		}

	}

	public function VerificarCodigo()
	{
		$data['correo_celular'] = $_GET['correo'];
		$this->load->view('administrador/header_registro');
		$this->load->view('administrador/registro_propietario');
		$this->load->view('administrador/footer_registro');
		$this->load->view('administrador/confirmar_correo', $data);
	}

	public function CambiarDatos()
	{
		$correo_celular = $this->input->post('correo_celular',TRUE);
		
		$id_propietairo_query = $this->bases->obtener_id_propietario($correo_celular);
		if($id_propietairo_query == FALSE)
		{
			redirect('/Welcome/RegistroPropietario');
		}
		foreach( $id_propietairo_query  as $id_propietairo_q)
		{
			$id_propietario = $id_propietairo_q->id_propietario;
		}
		
		$datos_query = $this->bases->obtener_propietario($id_propietario);
		
		foreach($datos_query as $datos)
		{
			$id_propietario = $datos->id_propietario;
			$informacion['nombre'] = $datos->nombre;
			$informacion['apellidos'] = $datos->apellidos;
			$informacion['celular'] = $datos->celular;
			$informacion['nacimiento'] = $datos->nacimiento;
			$informacion['sexo'] = $datos->sexo;
			$informacion['correo'] = $datos->correo;
		}
		$this->bases->borrar_propietario($id_propietario);

		$this->load->view('administrador/header_registro');
		$this->load->view('administrador/registro_propietario',$informacion);
		$this->load->view('administrador/footer_registro');
		
	}

	public function ReenviarCodigo()
	{
		$correo = $_GET['correo'];
		$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
		/* Actualizamos el valor del codigo */
		$this->bases->modificar_codigo($correo, $codigo);

		/* Campos para poder enviar un correo */
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://mail.ubicalos.mx',
			'smtp_port' => 465,
			'smtp_user' => 'notificaciones@ubicalos.mx',
			'smtp_pass' => 'Ubi$%ca=)los/&123',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);
	
		$CI = & get_instance();
		$CI->load->helper('url');
		$CI->load->library('session');
		$CI->config->item('base_url');
	
		$CI->load->library('email');
	
		$CI->email->initialize($config);
	
		$subject = 'Ubicalos.mx| Código de confirmación de cuenta';
		$msg = 'Código: '.$codigo;
	
		/* Envia el código al correo electronico */
		$CI->email->from('notificaciones@ubicalos.mx')->to($correo)->subject($subject)->message($msg)->send();
		
		/* Redireccionamos para validar el codigo */
		redirect('Welcome/VerificarCodigo?correo='.$correo);

	}

	public function VerificacionCodigoCorrecto()
	{
		$correo_celular = $this->input->post('correo_celular', true);
		$codigo_recibido = $this->input->post('codigo',true);
		$codigo = $this->bases->obtener_codigo($correo_celular);
		foreach($codigo as $c)
		{
			$codigo_verificacion = $c->codigo;
		}
		if($codigo_verificacion == $codigo_recibido)
		{
			redirect('Welcome/ValidarCodigo?correo='.$correo_celular);
		}else{
			redirect('Welcome/VerificarCodigo?correo='.$correo_celular);
		}

	}

	public function ValidarCodigo()
	{
		$correo_celular = $_GET['correo'];
		$query = $this->bases->obtener_id_propietario($correo_celular);
		foreach($query as $q)
		{
			$id_propietario = $q->id_propietario;
		}
		$this->bases->confirmar_codigo($id_propietario);
		$celular_query = $this->bases->obtener_telefono($id_propietario);
		foreach($celular_query as $celular_q)
		{
			$celular = $celular_q->celular;
		}
		$nombre_query = $this->bases->obtener_nombre_propietario($id_propietario);
		foreach($nombre_query as $nombre_q)
		{
			$nombre = $nombre_q->nombre;
		}
		redirect('Welcome/RegistrarNegocio?d='.$id_propietario,'location');
	}
	/* Fin de Registro */

	/* Inicio Registro de Negocio */

	public function RegistrarNegocio()
	{
		$data['id_propietario'] = $_GET['d'];
		$this->load->view('administrador/header_registro');
		$this->load->view('administrador/registro_negocio',$data);
		$this->load->view('administrador/footer_registro');
	}

	public function RegistraNegocioPropietario()
	{	
			//Aqui generamos el registro de la empresa
			//Si hay un ' lo cambia por ´ ya que causa conflictos al agregar a la bd
			$datos_empresa['id_propietario'] = str_replace("'", "´", $_GET['id_propietario']);
			$datos_empresa['nombre_negocio'] = str_replace("'", "´", $_GET['nombre_negocio']);
			$datos_empresa['num_subcategorias'] = $_GET['num_subcategorias'];
			$this->bases->insertar_empresa($datos_empresa);
			//Obtenemos el id del estado
			$estado = str_replace("'", "´", $_GET['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_GET['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}
			//Agregamos la direccion de la empresa
			$direccion_empresa['id_municipio'] = $id_municipio;
			$direccion_empresa['zona'] = $_GET['zona'];
			$direccion_empresa['calle'] = str_replace("'", "´", $_GET['calle']);
			$direccion_empresa['colonia'] = str_replace("'", "´", $_GET['colonia']);
			$direccion_empresa['vialidad'] = $_GET['vialidad'];
			$direccion_empresa['num_exterior'] = str_replace("'", "´", $_GET['num_exterior']);
			if($_GET['num_interior'] == ""){
				$direccion_empresa['num_interior'] = 0;
			}else{
				$direccion_empresa['num_interior'] = str_replace("'", "´", $_GET['num_interior']);
			}
			$direccion_empresa['codigo_postal'] = str_replace("'", "´", $_GET['codigo_postal']);

			$this->bases->agregar_direccion($direccion_empresa);
			$id_direccion_empresa_query = $this->bases->obtener_id_direccion($direccion_empresa);

			foreach($id_direccion_empresa_query as $id_direccion_empresa_q)
			{
				$id_direccion = $id_direccion_empresa_q->id_direccion;
			}
			//Obtenemos el id de la empresa y agregamos sucursal
			$id_empresa_query = $this->bases->obtener_id_empresa($datos_empresa['id_propietario']);
			foreach($id_empresa_query as $id_empresa_q)
			{
				$id_empresa = $id_empresa_q->id_empresa;
			}

			$latitud = $_GET['latitud'];
			$longitud = $_GET['longitud'];

			$this->bases->agregar_sucursal_($id_empresa, $id_direccion, $latitud, $longitud);
			
			//Creamos el registro inicial del número de imagenes y videos por empresa
			$this->bases->inicializar_img_videos($id_empresa);

			//Añadimos las secciones dependiendo de su subcategoria

			if($datos_empresa['num_subcategorias'] >= 1) //Subcategoria 1
			{	
				$seccion_1_1 = $_GET['seccion_1_1'];
				if($seccion_1_1 > 0)
				{
					$this->bases->agregar_seccion($seccion_1_1, $id_empresa, 1);
				}
			}
			if($datos_empresa['num_subcategorias'] >= 2)//Subcategoria 2
			{
				$seccion_1_2 = $_GET['seccion_1_2'];
				if($seccion_1_2 > 0)
				{
					$this->bases->agregar_seccion($seccion_1_2, $id_empresa, 2);
				}
			}
			if($datos_empresa['num_subcategorias'] >= 3)//Subcategoria 3
			{
				$seccion_1_3 = $_GET['seccion_1_3'];
				if($seccion_1_3 > 0)
				{
					$this->bases->agregar_seccion($seccion_1_3, $id_empresa, 3);
				}
			}
			if($datos_empresa['num_subcategorias'] >= 4)//Subcategoria 4
			{
				$seccion_1_4 = $_GET['seccion_1_4'];
				if($seccion_1_4 > 0)
				{
					$this->bases->agregar_seccion($seccion_1_4, $id_empresa, 4);
				}
			}
			if($datos_empresa['num_subcategorias'] >= 5)//Subcategoria 5
			{
				$seccion_1_5 = $_GET['seccion_1_5'];
				if($seccion_1_5 > 0)
				{
					$this->bases->agregar_seccion($seccion_1_5, $id_empresa, 5);
				}
			}
			$newdata = array(
				'id_propietario'  => $datos_empresa['id_propietario'],
				'logged_in' => TRUE
			);
			$this->session->set_userdata($newdata);

			redirect('/Welcome/Pago');
	}

	public function cargar_zona()
	{
		$zonas = $this->bases->obtener_zonas_puebla();

		$zonas_lista = "<option value='-1'>-Seleccionar Zona-</option>";
		foreach($zonas as $zona){
			$zonas_lista .= "<option value='".$zona->id_zona."'>".$zona->zona."</option>";
		}
		echo $zonas_lista;
	}

	public function cargar_categorias()
	{
		$categorias = $this->bases->obtener_categorias_todas();
		$categorias_lista = "<option value='-1'>-Seleccionar Categoría-</option>";

		foreach($categorias as $categoria)
		{
			$categorias_lista .= "<option value='".$categoria->id_categorias."'>".$categoria->categoria."</option>";
		}

		echo $categorias_lista;
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

	public function cargar_secciones()
	{
		$id_subcategoria = $this->input->post('id_subcategoria', TRUE);
		$secciones = $this->bases->obtener_secciones($id_subcategoria);
		$secciones_lista = "<option value='-1'>-Selecciona Sección-</option>";

		foreach($secciones as $seccion)
		{
			$secciones_lista .= "<option value='".$seccion->id_secciones."'>".$seccion->secciones."</option>";
		}

		echo $secciones_lista;
	}

	/* Fin Registro de negocio */

	/* Inicio Recuperacion de contraseña */

	public function RecuperarCuenta()
	{
		$this->load->view('administrador/header_registro');
		$this->load->view('recuperar_cuenta');
		$this->load->view('administrador/footer_registro');
	}

	public function EnviarCodigoParaRecuperarCuenta()
	{
		$correo_celular = $this->input->post('correo_celular', TRUE);

		$query = $this->bases->obtener_id_propietario($correo_celular);
		if($query == FALSE)
		{
			redirect('/Welcome/login','location');
		}

		$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
		$subject = 'Ubicalos.mx| Código de recuperación de cuenta';
		$msg = 'Código: '.$codigo;
		$this->bases->modificar_codigo($correo_celular, $codigo);
		/* Campos para poder enviar un correo */
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://mail.ubicalos.mx',
			'smtp_port' => 465,
			'smtp_user' => 'notificaciones@ubicalos.mx',
			'smtp_pass' => 'Ubi$%ca=)los/&123',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);
	
		$CI = & get_instance();
		$CI->load->helper('url');
		$CI->load->library('session');
		$CI->config->item('base_url');
	
		$CI->load->library('email');
	
		$CI->email->initialize($config);
	
		/* Envia el código al correo electronico */
		$CI->email->from('notificaciones@ubicalos.mx')->to($correo_celular)->subject($subject)->message($msg)->send();

		/* Redireccionamos para validar el codigo */
		redirect('Welcome/ConfirmarCodigoRecuperacion?correo_celular='.$correo_celular);
	}

	public function ConfirmarCodigoRecuperacion()
	{
		$data['correo_celular'] = $_GET['correo_celular'];
		$this->load->view('administrador/header_registro');
		$this->load->view('recuperar_cuenta');
		$this->load->view('administrador/footer_registro');
		$this->load->view('confirmar_codigo_recuperacion_cuenta',$data);		
	}

	public function ReenviarCodigoParaRecuperarCuenta()
	{
		$correo_celular = $_GET['correo_celular'];
		$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
		$subject = 'Ubicalos.mx| Código de recuperación de cuenta';
		$msg = 'Código: '.$codigo;
		$this->bases->modificar_codigo($correo_celular, $codigo);
		/* Campos para poder enviar un correo */
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://mail.ubicalos.mx',
			'smtp_port' => 465,
			'smtp_user' => 'notificaciones@ubicalos.mx',
			'smtp_pass' => 'Ubi$%ca=)los/&123',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);
	
		$CI = & get_instance();
		$CI->load->helper('url');
		$CI->load->library('session');
		$CI->config->item('base_url');
	
		$CI->load->library('email');
	
		$CI->email->initialize($config);
		
		/* Envia el código al correo electronico */
		$CI->email->from('notificaciones@ubicalos.mx')->to($correo_celular)->subject($subject)->message($msg)->send();

		/* Redireccionamos para validar el codigo */
		redirect('Welcome/ConfirmarCodigoRecuperacion?correo_celular='.$correo_celular);
	}

	public function VerificacionCodigoCorrectoRecuperacion()
	{
		$correo_celular = $this->input->post('correo_celular', true);
		$codigo_recibido = $this->input->post('codigo',true);
		$codigo = $this->bases->obtener_codigo($correo_celular);
		foreach($codigo as $c)
		{
			$codigo_valido = $c->codigo;
		}
		if($codigo_valido == $codigo_recibido)
		{
			redirect('Welcome/CambiarPassword?correo_celular='.$correo_celular);
		}else{
			redirect('Welcome/ConfirmarCodigoRecuperacion?correo_celular='.$correo_celular);
		}

	}

	public function CambiarPassword()
	{
		$correo_celular = $_GET['correo_celular'];
		$query = $this->bases->obtener_id_propietario($correo_celular);
		if($query == FALSE)
		{
			/* Si el usuario no esta registrado lo manda al index */
			redirect('/Welcome/index','location');
		}else{
			foreach($query as $q)
			{
				$id_propietario = $q->id_propietario;
			}
			$this->bases->confirmar_codigo($id_propietario);
		}
		
		$data['correo_celular']= $correo_celular;
		$data['id_propietario'] = $id_propietario;

		$this->load->view('administrador/header_registro');
		$this->load->view('modificar_contrasenia', $data);
		$this->load->view('administrador/footer_registro');

	}

	public function SobreescribirPassword()
	{
		$id_propietario = $this->input->post('id_propietario', TRUE);
		$contrasenia = $this->input->post('contrasenia', TRUE);

		$contrasenia = md5($contrasenia);
		$this->bases->actualizar_contrasenia($id_propietario, $contrasenia);
		redirect('/Welcome/ConfirmacionPassword');
	}

	public function ConfirmacionPassword()
	{
		$this->load->view('administrador/header_registro');
		$this->load->view('confirmacion_contrasenia');
		$this->load->view('administrador/footer_registro');
	}

	/* */

	/* Inicio de sesion */
	public function Sesion()
	{
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);

			//Verificamos que haya confirmado el codigo
			$codigo_query_confirmacion = $this->bases->codigo_confirmado($_SESSION['id_propietario']);
			if($codigo_query_confirmacion != FALSE)
			{
				foreach($codigo_query_confirmacion as $codigo_query_c)
				{
					$this->session->unset_userdata('id_propietario');
					$this->session->sess_destroy();
					redirect('Welcome/VerificarCodigo?correo='.$codigo_query_c->correo);
				}
			}

			//Si no ha registrado su empresa lo redireccionamos a que lo haga
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			if($informacion_negocio_query == FALSE)
			{
				$id = $_SESSION['id_propietario'];
				$this->session->unset_userdata('id_propietario');
				$this->session->sess_destroy();
				redirect('Welcome/RegistrarNegocio?d='.$id,'location');
			}

			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
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
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_inicio');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sesion_Informacion()
	{
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 1;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			

			/* */
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
				$informacion_negocio['info_general'] = $informacion_negocio_q->info_general;
				$informacion_negocio['servicios_productos'] = $informacion_negocio_q->servicios_productos;
				$informacion_negocio['ademas'] = $informacion_negocio_q->ademas;
				$informacion_negocio['total_subcategorias'] = $informacion_negocio_q->total_subcategorias;
			}
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				$informacion_negocio['id_direccion'] = $informacion_matriz_q->id_direccion;
				$informacion_negocio['latitud'] = $informacion_matriz_q->latitud;
				$informacion_negocio['longitud'] = $informacion_matriz_q->longitud;
				break;
			}
			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_negocio['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_q)
			{
				$informacion_negocio['calle'] = $informacion_direccion_q->calle;
				$informacion_negocio['colonia'] = $informacion_direccion_q->colonia;
				$informacion_negocio['tipo_vialidad'] = $informacion_direccion_q->tipo_vialidad;
				$informacion_negocio['num_ext'] = $informacion_direccion_q->num_ext;
				$informacion_negocio['num_inter'] = $informacion_direccion_q->num_inter;
				$informacion_negocio['cp'] = $informacion_direccion_q->cp;
				$informacion_negocio['municipio'] = $informacion_direccion_q->municipio;
				$informacion_negocio['estado'] = $informacion_direccion_q->estado;
				$informacion_negocio['id_zona'] = $informacion_direccion_q->id_zona;
			}
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
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_informacion');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sesion_Modificar_Informacion()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 1;

			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
				$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
				$informacion_negocio['foto_perfil'] = $informacion_negocio_q->foto_perfil;

				$informacion_negocio['info_general'] = $informacion_negocio_q->info_general;
				$informacion_negocio['servicios_productos'] = $informacion_negocio_q->servicios_productos;
				$informacion_negocio['ademas'] = $informacion_negocio_q->ademas;
				$informacion_negocio['total_subcategorias'] = $informacion_negocio_q->total_subcategorias;
			}
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				$informacion_negocio['id_direccion'] = $informacion_matriz_q->id_direccion;
				$informacion_negocio['actualizacion_dia'] = $informacion_matriz_q->actualizacion;
				$informacion_negocio['latitud'] = $informacion_matriz_q->latitud;
				$informacion_negocio['longitud'] = $informacion_matriz_q->longitud;
				break;
			}
			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_negocio['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_q)
			{
				$informacion_negocio['calle'] = $informacion_direccion_q->calle;
				$informacion_negocio['colonia'] = $informacion_direccion_q->colonia;
				$informacion_negocio['tipo_vialidad'] = $informacion_direccion_q->tipo_vialidad;
				$informacion_negocio['num_ext'] = $informacion_direccion_q->num_ext;
				$informacion_negocio['num_inter'] = $informacion_direccion_q->num_inter;
				$informacion_negocio['cp'] = $informacion_direccion_q->cp;
				$informacion_negocio['municipio'] = $informacion_direccion_q->municipio;
				$informacion_negocio['estado'] = $informacion_direccion_q->estado;
				$informacion_negocio['id_zona'] = $informacion_direccion_q->id_zona;
			}
			$informacion_negocio['zonas'] = $this->bases->obtener_zonas_puebla();
			$informacion_negocio['categorias'] = $this->bases->obtener_categorias_todas();
			
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
				$informacion_negocio['subcategoria'] .= $categoria_subcategoria_seccion_result->subcategoria ."|";
				$informacion_negocio['seccion'] .= $categoria_subcategoria_seccion_result->secciones . "|";
				switch($i){
					case 1:
						$informacion_negocio['seccion_1_1'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_1'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break; 
					case 2:
						$informacion_negocio['seccion_1_2'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_2'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 3:
						$informacion_negocio['seccion_1_3'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_3'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 4:
						$informacion_negocio['seccion_1_4'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_4'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 5:
						$informacion_negocio['seccion_1_5'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_5'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
				}
				$i++;
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

			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_informacion_modificar');
			$this->load->view('footer');
			
			
		}else{
			redirect('/');
		}
	}

	public function cargar_subcategorias_registrada()
	{
		$id_categoria = $this->input->post('categoria_inicial', TRUE);
		$id_subcategoria = $this->input->post('subcategoria_i',TRUE);
		$subcategorias = $this->bases->obtener_subcategorias($id_categoria);
		$subcategorias_lista = "<option value='-1'>-Seleccionar Subcategoría-</option>";

		foreach($subcategorias as $subcategoria)
		{
			if($subcategoria->id_subcategoria == $id_subcategoria)
			{
				$subcategorias_lista .= "<option selected value='".$subcategoria->id_subcategoria."'>".$subcategoria->subcategoria."</option>";
			}else{
				$subcategorias_lista .= "<option value='".$subcategoria->id_subcategoria."'>".$subcategoria->subcategoria."</option>";
			}
		}
		
		echo $subcategorias_lista;
	}

	public function cargar_secciones_registrada()
	{
		$id_subcategoria = $this->input->post('id_subcategoria', TRUE);
		$seccion_i = $this->input->post('seccion_i', TRUE);
		$secciones = $this->bases->obtener_secciones($id_subcategoria);
		$secciones_lista = "<option value='-1'>-Selecciona Sección-</option>";

		foreach($secciones as $seccion)
		{
			if($seccion->id_secciones == $seccion_i)
			{
				$secciones_lista .= "<option selected value='".$seccion->id_secciones."'>".$seccion->secciones."</option>";
			}else{
				$secciones_lista .= "<option value='".$seccion->id_secciones."'>".$seccion->secciones."</option>";
			}
		}

		echo $secciones_lista;
	}

	public function guardar_cambios_informacion_matriz()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				$id_empresa = $sucursales->id_empresa;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}
			/* Actualizamos la informacion de la empresa */
			$datos_negocio['nombre_negocio'] = str_replace("'", "´", $_GET['nombre_negocio'] );
			$datos_negocio['info_general'] = str_replace("'", "´", $_GET['info_general']);
			$datos_negocio['servicios_productos'] = str_replace("'", "´", $_GET['servicios_productos']);
			$datos_negocio['ademas'] = str_replace("'", "´", $_GET['ademas']);
			$datos_negocio['num_subcategorias'] = $_GET['num_subcategorias'];
			$datos_negocio['id_propietario'] = $_SESSION['id_propietario'];
			$this->bases->actualizar_informacion_empresa($datos_negocio);
			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES");
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/*Actualizamos sus coordenadas */

			$latitud = $_GET['latitud'];
			$longitud = $_GET['longitud'];
			$this->bases->actualizar_coordenadad($id_matriz, $latitud, $longitud);

			/* Eliminamos los servicios anteriores */
			$this->bases->eliminar_servicios($id_matriz);
			/* Servicio adicionales */
			$estacionamiento = $_GET['estacionamiento'];
			if($estacionamiento == 'true')
			{
				//añadimos el estacionamiento dependiendo el tipo
				if($_GET['estacionamiento_valor1'] != 0)
				{
					$this->bases->agregar_servicio($id_matriz, $_GET['estacionamiento_valor1']);
				}
				if($_GET['estacionamiento_valor2'] != 0)
				{
					$this->bases->agregar_servicio($id_matriz, $_GET['estacionamiento_valor2']);
				}
				if($_GET['estacionamiento_valor3'] != 0)
				{
					$this->bases->agregar_servicio($id_matriz, $_GET['estacionamiento_valor3']);
				}
			}
			$tarjetas = $_GET['tarjetas'];
			if($tarjetas == 'true')
			{	
				//Añadimos las tarjetas seleccionadas
				if($_GET['tarjeta1'] > 0){
					$this->bases->agregar_servicio($id_matriz, $_GET['tarjeta1']);
				}
				if($_GET['tarjeta2'] > 0)
				{
					$this->bases->agregar_servicio($id_matriz, $_GET['tarjeta2']);
				}
				if($_GET['tarjeta3'] > 0)
				{
					$this->bases->agregar_servicio($id_matriz, $_GET['tarjeta3']);
				}
			}
			$silla_ruedas = $_GET['silla_ruedas'];
			if($silla_ruedas == 'true'){
				$this->bases->agregar_servicio($id_matriz, 1);
			}
			$atm = $_GET['atm'];
			if($atm == 'true' ){
				$this->bases->agregar_servicio($id_matriz, 2);
			}
			$mesas_aire_libre = $_GET['mesas_aire_libre'];
			if($mesas_aire_libre == 'true'){
				$this->bases->agregar_servicio($id_matriz, 6);
			}
			$pantallas = $_GET['pantallas'];
			if($pantallas == 'true'){
				$this->bases->agregar_servicio($id_matriz, 7);
			}
			$reservaciones = $_GET['reservaciones'];
			if($reservaciones == 'true'){
				$this->bases->agregar_servicio($id_matriz, 8);
			}
			$sanitarios = $_GET['sanitarios'];
			if($sanitarios == 'true'){
				$this->bases->agregar_servicio($id_matriz, 9);
			}
			$serv_domicilio = $_GET['serv_domicilio'];
			if($serv_domicilio == 'true'){
				$this->bases->agregar_servicio($id_matriz, 10);
			}
			$wifi = $_GET['wifi'];
			if($wifi == 'true'){
				$this->bases->agregar_servicio($id_matriz, 14);
			}
			$zona_cigarrillo = $_GET['zona_cigarrillo'];
			if($zona_cigarrillo == 'true'){
				$this->bases->agregar_servicio($id_matriz, 15);
			}
			$zona_niños = $_GET['zona_niños'];
			if($zona_niños == 'true'){
				$this->bases->agregar_servicio($id_matriz, 16);
			}
			/* Fin de servicios adicionales */

			/* Agregamos las redes sociales */
			$this->bases->eliminar_redes_sociales($id_matriz);

			$pagina_web = $_GET['pagina_web'];
			if($pagina_web != "")
			{
				$pagina_web = str_replace("'", "´", $pagina_web);
				$this->bases->agregar_red_social($id_matriz,'Pagina_Web',$pagina_web);
			}
			$facebook = $_GET['facebook'];
			if($facebook != "")
			{
				$facebook = str_replace("'", "´", $facebook);
				$this->bases->agregar_red_social($id_matriz,'Facebook',$facebook);
			}
			$instagram = $_GET['instagram'];
			if($instagram != "")
			{
				$instagram = str_replace("'", "´", $instagram);
				$this->bases->agregar_red_social($id_matriz,'Instagram',$instagram);
			}
			$youtube = $_GET['youtube'];
			if($youtube != "")
			{
				$youtube = str_replace("'", "´", $youtube);
				$this->bases->agregar_red_social($id_matriz,'Youtube',$youtube);
			}
			$twitter = $_GET['twitter'];
			if($twitter != "")
			{
				$twitter = str_replace("'", "´", $twitter);
				$this->bases->agregar_red_social($id_matriz,'Twitter',$twitter);
			}
			$snapchat = $_GET['snapchat'];
			if($snapchat != "")
			{
				$snapchat = str_replace("'", "´", $snapchat);
				$this->bases->agregar_red_social($id_matriz,'Snapchat',$snapchat);
			}
			/* Fin de agregar las redes sociales */

			/* Modificamos la direccion */
			$direccion_query = $this->bases->obtener_direccion_sucursal($id_matriz);
			foreach($direccion_query as $direccion)
			{
				$id_direccion = $direccion->id_direccion;
			}

			$estado = str_replace("'", "´", $_GET['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_GET['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}
			//Agregamos la direccion de la empresa
			$direccion_empresa['id_municipio'] = $id_municipio;
			$direccion_empresa['zona'] = $_GET['zona'];
			$direccion_empresa['calle'] = str_replace("'", "´", $_GET['calle']);
			$direccion_empresa['colonia'] = str_replace("'", "´", $_GET['colonia']);
			$direccion_empresa['vialidad'] = $_GET['vialidad'];
			$direccion_empresa['num_exterior'] = str_replace("'", "´", $_GET['num_exterior']);
			if($_GET['num_interior'] == ""){
				$direccion_empresa['num_interior'] = 0;
			}else{
				$direccion_empresa['num_interior'] = str_replace("'", "´", $_GET['num_interior']);
			}
			$direccion_empresa['codigo_postal'] = str_replace("'", "´", $_GET['codigo_postal']);

			$this->bases->actualizar_direccion($id_direccion, $direccion_empresa);

			/* Agregamos el horario de la matriz */
			$this->bases->eliminar_horario($id_matriz);
			$dias_i = array(1=>'l', 2=>'m', 3=>'mi', 4=>'j', 5=>'v', 6=>'s', 7=>'d');
			$dias_nombre_M = array(1 =>"Lunes", 2 =>"Martes", 3 =>"Miércoles", 4 =>"Jueves", 5 =>"Viernes", 6 =>"Sábado", 7 =>"Domingo");
			$dias_nombre_m = array(1 =>"lunes", 2 =>"martes", 3 =>"miercoles", 4 =>"jueves", 5 =>"viernes", 6 =>"sabado", 7 =>"domingo");

			for($i = 1; $i <= 7; $i++)
			{
				$dia_d = "dia_".$dias_i[$i];
				$dia_d_valor = $_GET[$dia_d];
				if($dia_d_valor == 'true')
				{
					$hora_a_1 = $dias_nombre_m[$i]."_1_1";
					$hora_c_1 = $dias_nombre_m[$i]."_1_2";
					$horario['hora_apertura'] = $_GET[$hora_a_1];
					$horario['hora_cierre'] = $_GET[$hora_c_1];
					$horario['dia'] = $dias_nombre_M[$i];
					$horario['id_sucursal'] = $id_matriz;
					$horario['horario_num'] = 1;
					$this->bases->agregar_horario($horario);
					$dia_h2 =  "dia_".$dias_nombre_M[$i]."2";
					if($_GET[$dia_h2] == 'true')
					{
						$hora_a_2 = $dias_nombre_m[$i]."_2_1";
						$hora_c_2 = $dias_nombre_m[$i]."_2_2";
						$horario['hora_apertura'] = $_GET[$hora_a_2];
						$horario['hora_cierre'] = $_GET[$hora_c_2];
						$horario['horario_num'] = 2;
						$this->bases->agregar_horario($horario);
					}
				}
			}

			/* Actualizamos las secciones */

			$seccion_1_1 = $_GET['seccion_1_1'];
			$seccion_1_2 = $_GET['seccion_1_2'];
			$seccion_1_3 = $_GET['seccion_1_3'];
			$seccion_1_4 = $_GET['seccion_1_4'];
			$seccion_1_5 = $_GET['seccion_1_5'];

			$this->bases->eliminar_secciones($id_empresa);

			if($datos_negocio['num_subcategorias'] >= 1)
			{
				if($seccion_1_1 > 0){
					$this->bases->agregar_seccion($seccion_1_1, $id_empresa, 1);
				}
			}
			if($datos_negocio['num_subcategorias'] >= 2)
			{
				if($seccion_1_2 > 0){
					$this->bases->agregar_seccion($seccion_1_2, $id_empresa, 2);
				}
			}
			if($datos_negocio['num_subcategorias'] >= 3)
			{
				if($seccion_1_3 > 0){
					$this->bases->agregar_seccion($seccion_1_3, $id_empresa, 3);
				}
			}
			if($datos_negocio['num_subcategorias'] >= 4)
			{
				if($seccion_1_4 > 0){
					$this->bases->agregar_seccion($seccion_1_4, $id_empresa, 4);
				}
			}
			if($datos_negocio['num_subcategorias'] >= 5)
			{
				if($seccion_1_5 > 0){
					$this->bases->agregar_seccion($seccion_1_5, $id_empresa, 5);
				}
			}

			/* Actualizamos los numeros y correos de la matriz */
			$this->bases->eliminar_contacto_sucursal($id_matriz);

			$telefono1 = $_GET['telefono1'];
			$datos_contacto['id_sucursal'] = $id_matriz;
			$datos_contacto['tipo'] = 'telefono';
			$datos_contacto['valor'] = $telefono1;
			$datos_contacto['indice'] = 1; 
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$tel2 = $_GET['tel2'];
			if($tel2 == 'true')
			{
				$telefono2 = $_GET['telefono2'];
				$datos_contacto['valor'] = $telefono2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			$tel3 = $_GET['tel3'];
			if($tel3 == 'true')
			{
				$telefono3 = $_GET['telefono3'];
				$datos_contacto['valor'] = $telefono3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$num_cel1 = $_GET['num_cel1'];
			$datos_contacto['tipo'] = 'celular';
			$datos_contacto['valor'] = $num_cel1;
			$datos_contacto['indice'] = 1;
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$cel2 = $_GET['cel2'];
			if($cel2 == 'true')
			{
				$num_cel2 = $_GET['num_cel2'];
				$datos_contacto['valor'] = $num_cel2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$cel3 = $_GET['cel3'];
			if($cel3 == 'true'){
				$num_cel3 = $_GET['num_cel3'];
				$datos_contacto['valor'] = $num_cel3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$valor_correo1 = str_replace("'", "´",$_GET['valor_correo1']);
			if($valor_correo1 != "")
			{
				$datos_contacto['tipo'] = 'correo';
				$datos_contacto['valor'] = $valor_correo1;
				$datos_contacto['indice'] = 1;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$correo2 = $_GET['correo2'];
			if($correo2 == 'true')
			{
				$valor_correo2 = str_replace("'", "´",$_GET['valor_correo2']);
				$datos_contacto['valor'] = $valor_correo2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$correo3 = $_GET['correo3'];
			if($correo3 == 'true'){
				$valor_correo3 = str_replace("'", "´",$_GET['valor_correo3']);
				$datos_contacto['valor'] = $valor_correo3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			
			redirect('Welcome/Sesion_Informacion');
		}else{
			redirect('/');
		}
	}

	public function Sesion_Galeria()
	{
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 3;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			
			$informacion_negocio['galeria'] = $this->bases->obtener_galeria($informacion_negocio['id_empresa']);
			$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
			if($galeria_query != FALSE)
			{
				foreach($galeria_query as $galeria)
				{
					$informacion_negocio['total_img'] = $galeria->num_img;
				}

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
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_galeria');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sesion_Agregar_Galeria()
	{
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 3;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

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
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_galeria_agregar_foto');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function subir_fotos(){
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}

			$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
			foreach($galeria_query as $galeria)
			{
				$num_img = $galeria->num_img;
				$maximo_img = $galeria->max_img;
			}
			 
			if($num_img <= $maximo_img) //Verificamos que no exceda el limite de archivos disponibles a subir
			{
				$img_ingresar = 0; //Contador de imagenes a subir
				if(!empty($_FILES["archivo"]['tmp_name']))
				{
					foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
					{
						//Validamos que el archivo exista
						if($_FILES["archivo"]["name"][$key]) {

							$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
							$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
							
							//Cada negocio tendra su propia carpeta de archivos
							$directorio = $this->config->item('url_archivos_ubicalos').'ImagenesEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
							
							$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
							$tamanio_maximo = 6291456; //4MB
							$es_img = getimagesize($_FILES["archivo"]["tmp_name"][$key]);
													
							if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
							{
								//Validamos si la ruta de destino existe, en caso de no existir la creamos
								if(!file_exists($directorio)){
									mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
								}
								$new_name = rand(0,120).''.$filename;
								$dir=opendir($directorio); //Abrimos el directorio de destino
								$target_path = $directorio.'/'.$new_name; //Indicamos la ruta de destino, así como el nombre del archivo
								
								//Movemos y validamos que el archivo se haya cargado correctamente
								//El primer campo es el origen y el segundo el destino
								
								if(move_uploaded_file($source, $target_path)) {
									$img_ingresar ++;
									
									$datos_foto['id_empresa'] = $informacion_negocio['id_empresa'];
									$datos_foto['descripcion'] = NULL;
									$datos_foto['nombre'] = str_replace("'", "´",$new_name);
									$datos_foto['tipo'] = 'imagen';
									$this->bases->agregar_foto_o_video($datos_foto);
									$this->bases->actualizar_total_img($informacion_negocio['id_empresa'], $num_img + $img_ingresar);
								}
								
								closedir($dir); //Cerramos el directorio de destino
								if(($img_ingresar+$num_img) >= $maximo_img)
								{
									break;
								}
							}
						}
					}
				}
			}
			redirect('Welcome/Sesion_Galeria');
		}else{
			redirect('/');
		}
	}

	public function modificar_descripcion_foto()
	{
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}
            $galeria_todos_query = $this->bases->obtener_galeria($informacion_negocio['id_empresa']);
			if($galeria_todos_query != FALSE)
			{
				foreach($galeria_todos_query as $galeria_todos)
				{
					$name_post = "i_".$galeria_todos->id_imagen;
					$valor = str_replace("'", "´", $this->input->post($name_post, TRUE));
					$galeria_todos->id_imagen;
					$this->bases->actualizar_descripcion_galeria($galeria_todos->id_imagen, $valor);
				}
			}
			redirect('Welcome/Sesion_Galeria');
		}else{
			redirect('/');
		}
	}

	public function eliminar_foto(){
		if (isset($_SESSION['id_propietario'])){
			$id_archivo = $this->input->post('id_archivo', TRUE);
			$nombre = str_replace("´", "'", $this->input->post('nombre', TRUE));
			
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}
			$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
			

			foreach($galeria_query as $galeria)
			{
				$num_img = $galeria->num_img;
			}
			unlink($this->config->item('url_archivos_ubicalos')."ImagenesEmpresa/".$informacion_negocio['id_empresa']."/".$nombre);

			$this->bases->elimina_foto_video($id_archivo);
			$num_img--;
			$this->bases->actualizar_total_img($informacion_negocio['id_empresa'], $num_img);

			redirect('Welcome/Sesion_Agregar_Galeria');
		}else{
			redirect('/');
		}
	}

	public function Sesion_Videos(){
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 4;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

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
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_videos');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sesion_Agregar_Videos(){
		if (isset($_SESSION['id_propietario'])){

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 4;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$informacion_negocio['galeria'] = $this->bases->obtener_videos($informacion_negocio['id_empresa']);
			
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal.php');
			$this->load->view('administrador/sesion_videos_agregar');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function subir_videos(){
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}

			$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
			foreach($galeria_query as $galeria)
			{
				$num_videos = $galeria->num_videos;
				$maximo_videos = $galeria->max_videos;
			}
			if($num_videos <= $maximo_videos) //Verificamos que no exceda el limite de archivos disponibles a subir
			{
				$video_ingresar = 0; //Contador de imagenes a subir
				foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
				{

					//Validamos que el archivo exista
					if($_FILES["archivo"]["name"][$key]) {

						$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						$directorio = $this->config->item('url_archivos_ubicalos').'VideosEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
						$tamanio_maximo = 8388608; //8MB
												
						if($tamanio_bytes <= $tamanio_maximo)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}

							$new_name = rand(0,120).''.$filename;
							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$new_name; //Indicamos la ruta de destino, así como el nombre del archivo
							
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								$video_ingresar ++;
								
								$datos_foto['id_empresa'] = $informacion_negocio['id_empresa'];
								$datos_foto['descripcion'] = "";
								$datos_foto['nombre'] = str_replace("'", "´",$new_name);
								$datos_foto['tipo'] = 'video';
								$this->bases->agregar_foto_o_video($datos_foto);
								$this->bases->actualizar_total_videos($informacion_negocio['id_empresa'], $num_videos + $video_ingresar);
							}
							
							closedir($dir); //Cerramos el directorio de destino
							if(($video_ingresar+$num_videos) >= $maximo_videos)
							{
								break;
							}
						}
					}
				}
			}
			
			redirect('Welcome/Sesion_Agregar_Videos'); 
		}else{
			redirect('/');
		}
	}

	public function eliminar_video(){
		if (isset($_SESSION['id_propietario'])){
			$id_archivo = $this->input->post('id_archivo', TRUE);
			$nombre = str_replace("´", "'", $this->input->post('nombre', TRUE));

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}
			$galeria_query = $this->bases->obtener_total_galerias($informacion_negocio['id_empresa']);
			
			foreach($galeria_query as $galeria)
			{
				$num_videos = $galeria->num_videos;
			}
			unlink($this->config->item('url_archivos_ubicalos').'VideosEmpresa/'.$informacion_negocio['id_empresa'].'/'.$nombre);
			$this->bases->elimina_foto_video($id_archivo);
			$num_videos--;
			$this->bases->actualizar_total_videos($informacion_negocio['id_empresa'], $num_videos);
			
			redirect('Welcome/Sesion_Agregar_Videos');
		}else{
			redirect('/');
		}
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

	/* Configracion de sesion de administrador */
	public function Configuracion_Datos_Personales()
	{
		if (isset($_SESSION['id_propietario'])){
			$datos_propietario_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			foreach($datos_propietario_query as $datos_propietario_q)
			{
				$datos_propietario['nombre'] = $datos_propietario_q->nombre;
				$datos_propietario['apellidos'] = $datos_propietario_q->apellidos;
				$datos_propietario['celular'] = $datos_propietario_q->celular;
				$datos_propietario['nacimiento'] = $datos_propietario_q->nacimiento;
				$datos_propietario['sexo'] = $datos_propietario_q->sexo;
			}
			$datos_propietario['position_nav'] = 29;
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/configuracion_datos_personales',$datos_propietario);
			$this->load->view('footer_admin');
		}
		else{
			redirect('/');
		}
	}

	public function actualizar_datos_personales()
	{
		if (isset($_SESSION['id_propietario'])){
			$datos_actualizar['id_propietario'] = $_SESSION['id_propietario'];
			$datos_actualizar['nombre'] = str_replace("'", "´", $this->input->post('nombre',TRUE));
			$datos_actualizar['apellidos'] = str_replace("'", "´", $this->input->post('apellidos',TRUE));
			$datos_actualizar['num_celular'] = str_replace("'", "´", $this->input->post('num_celular',TRUE));
			$datos_actualizar['fecha_nacimiento'] = $this->input->post('fecha_nacimiento',TRUE);
			$datos_actualizar['sexo'] = $this->input->post('sexo',TRUE);
			$this->bases->actualizar_propietario($datos_actualizar);
			redirect('Welcome/Configuracion_Datos_Personales');
		}else{
			redirect('/');
		}	
	}

	public function Configuracion_Cuenta()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$data['o'] = "";
			$data['position_nav'] = 29;

			if(!empty($_GET['confirmado']))
			{
				$data['confirmado'] = $_GET['confirmado'];
			}
			
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/configuracion_correo',$data);
			$this->load->view('footer_admin');
		}else{
			redirect('/');
		}
	}

	public function cambiar_confirmacion_correo()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$this->bases->confirmar_codigo($_SESSION['id_propietario']);

			if(!empty($_GET['confirmado']))
			{
				redirect('Welcome/Configuracion_Cuenta?confirmado=TRUE');
			}

			redirect('Welcome/Configuracion_Cuenta');
		}else{
			redirect('/');
		}

	}

	public function confirmar_cambio_cuenta()
	{
		if (isset($_SESSION['id_propietario'])){
			$correo_celular = str_replace("'", "´", $this->input->post('correo_celular',TRUE));
			$codigo = $this->input->post('codigo',TRUE);

			$correo_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($correo_query as $correo_q)
			{
				$correo_actual = $correo_q->correo;
			}

			$codigo_bd = $this->bases->obtener_codigo($correo_actual);
			foreach($codigo_bd as $codigo_b)
			{
				$codigo_a = $codigo_b->codigo;
			}

			if($codigo_a != $codigo)
			{
				redirect('Welcome/Confirmacion_cambio_correo?correo='.$correo_celular);
			}

			$data['correo'] = $correo_celular;
			$data['id_propietario'] = $_SESSION['id_propietario'];
			$this->bases->cambiar_correo($data);
			
			redirect('Welcome/cambiar_confirmacion_correo?confirmado=TRUE');
		}else{
			redirect('/');
		}

	}

	public function enviar_confirmacion_cambio()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$correo = str_replace("'", "´", $this->input->post('correo_celular',TRUE));
			$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
			$correo = str_replace("'", "´", $this->input->post('correo_celular', TRUE) );
			$correo_outlook = explode("@", $correo);
			if($correo_outlook[1] == "outlook.com")
			{
				$correo = $correo_outlook[0]."@".ucfirst(strtolower($correo_outlook[1]));
			}
			/* Actualizamos el valor del codigo */

			if($this->bases->propietario_existe($correo))
			{
				redirect('Welcome/Confirmacion_cambio_correo?correo=FALSE');
			}

			$correo_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($correo_query as $correo_q)
			{
				$correo_actual = $correo_q->correo;
			}

			$this->bases->modificar_codigo($correo_actual, $codigo);

			/* Campos para poder enviar un correo */
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://mail.ubicalos.mx',
				'smtp_port' => 465,
				'smtp_user' => 'notificaciones@ubicalos.mx',
				'smtp_pass' => 'Ubi$%ca=)los/&123',
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'newline' => "\r\n"
			);
			
			$CI = & get_instance();
			$CI->load->helper('url');
			$CI->load->library('session');
			$CI->config->item('base_url');
		
			$CI->load->library('email');
		
			$CI->email->initialize($config);
		
			$subject = 'Ubicalos.mx| Código de confirmación de cuenta';
			$msg = 'Código: '.$codigo;
		
			/* Envia el código al correo electronico */
			$CI->email->from('notificaciones@ubicalos.mx')->to($correo)->subject($subject)->message($msg)->send();
			
			/* Redireccionamos para validar el codigo */
			redirect('Welcome/Confirmacion_cambio_correo?correo='.$correo);

		}else{
			redirect('/');
		}
	}

	public function reenviar_confirmacion_cambio()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$correo = str_replace("'", "´", $_GET['correo']);
			$codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
			/* Actualizamos el valor del codigo */

			if($this->bases->propietario_existe($correo))
			{
				redirect('Welcome/Confirmacion_cambio_correo?correo=FALSE');
			}

			$correo_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($correo_query as $correo_q)
			{
				$correo_actual = $correo_q->correo;
			}

			$this->bases->modificar_codigo($correo_actual, $codigo);

			/* Campos para poder enviar un correo */
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://mail.ubicalos.mx',
				'smtp_port' => 465,
				'smtp_user' => 'notificaciones@ubicalos.mx',
				'smtp_pass' => 'Ubi$%ca=)los/&123',
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'newline' => "\r\n"
			);
		
			$CI = & get_instance();
			$CI->load->helper('url');
			$CI->load->library('session');
			$CI->config->item('base_url');
		
			$CI->load->library('email');
		
			$CI->email->initialize($config);
		
			$subject = 'Ubicalos.mx| Código de confirmación de cuenta';
			$msg = 'Código: '.$codigo;
		
			/* Envia el código al correo electronico */
			$CI->email->from('notificaciones@ubicalos.mx')->to($correo)->subject($subject)->message($msg)->send();
			
			/* Redireccionamos para validar el codigo */
			redirect('Welcome/Confirmacion_cambio_correo?correo='.$correo);

		}else{
			redirect('/');
		}
	}

	public function Confirmacion_cambio_correo()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$data['correo_celular'] = str_replace("'", "´", $_GET['correo']);
			$data['position_nav'] = 30;
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/configuracion_correo');
			$this->load->view('administrador/confirmacion_cambio_correo',$data);
			$this->load->view('footer_admin');

		}else{
			redirect('/');
		}
	}

	public function Configuracion_Contrasenia()
	{
		if (isset($_SESSION['id_propietario'])){
			$data['position_nav'] = 30;
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/configuracion_cambiar_contrasenia',$data);
			$this->load->view('footer_admin');

		}else{
			redirect('/');
		}
	}

	public function cambiar_contrasenia()
	{
		if (isset($_SESSION['id_propietario'])){

			$contrasenia_actual = md5($this->input->post('contrasenia_actual',TRUE));
			$contrasenia_nueva = md5($this->input->post('contrasenia_nueva',TRUE));
			
			$contrasenia_query = $this->bases->obtener_contrasenia($_SESSION['id_propietario']);
			foreach($contrasenia_query as $contrasenia_q)
			{
				if($contrasenia_q->contraseña != $contrasenia_actual)
				{
					redirect('Welcome/Confirmacion_contrasenia_modificacion?confirmacion=FALSE');
				}else{
					$this->bases->actualizar_contrasenia($_SESSION['id_propietario'], $contrasenia_nueva);
					redirect('Welcome/Confirmacion_contrasenia_modificacion?confirmacion=TRUE');
				}
			}

		}else{
			redirect('/');
		}
	}

	public function Confirmacion_contrasenia_modificacion()
	{
		if (isset($_SESSION['id_propietario'])){
			$data['confirmacion'] = $_GET['confirmacion'];
			$data['position_nav'] = 30;
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			
			$this->load->view('nav-lateral', $informacion_negocio);
			$this->load->view('administrador/configuracion_cambiar_contrasenia',$data);
			$this->load->view('administrador/configuracion_confirmacion_contrasenia',$data);
			$this->load->view('footer_admin');

		}else{
			redirect('/');
		}
	}

	public function Configuracion_Cancelar()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$data['position_nav'] = 29;
			
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/configuracion_cancelar_cuenta');
			$this->load->view('footer_admin');

		}else{
			redirect('/');
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

	public function subir_foto_perfil()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}

			if (($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpeg")|| ($_FILES["file"]["type"] == "image/png")) {

				$filename = $_FILES["file"]["name"];
				$source = $_FILES["file"]["tmp_name"];
				$directorio = $this->config->item('url_archivos_ubicalos').'FotosPerfilEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
				//Validamos si la ruta de destino existe, en caso de no existir la creamos
				if(!file_exists($directorio)){
					mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
				}
				$dir=opendir($directorio); //Abrimos el directorio de destino
				$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
				
				//Movemos y validamos que el archivo se haya cargado correctamente
				//El primer campo es el origen y el segundo el destino
				
				if (move_uploaded_file($source, $target_path)) {
					$foto_query = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
					foreach($foto_query as $foto_q)
					{
						$foto_anterior = $foto_q->foto_perfil;
					}
					unlink($directorio.str_replace("´", "'", $foto_anterior));

					$data['foto_perfil'] = str_replace("'", "´", $filename);
					$data['id_empresa'] = $informacion_negocio['id_empresa'];
					$this->bases->agregar_foto_perfil($data);
					echo $directorio."".$_FILES['file']['name'];
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}

		}else{
			redirect('/');
		}
	}

	public function get_abierto_cerrado_horario()
	{

		$informacion_matriz = $this->bases->obtener_sucursales($_POST['id_empresa']);

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
		$horario_query = $this->bases->obtener_horarios($informacion_matriz[0]->id_sucursal);
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
				<img class="w-1 h-1 ml-0 mt-n1" style="width: 16px;" src="'.base_url().'img/PERFIL_DATOS_VERIFICADOS.svg">
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
		$sucursales = $this->bases->obtener_sucursales($_POST['id_empresa']);
		
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

				echo '
					<p class ="color-blue-ubicalos" style="font-size: 11pt;">
						 '.$direccion_empresa[0]->calle; 
				if(!empty($direccion_empresa[0]->num_inter))
				{
					echo ", Núm. int.".$direccion_empresa[0]->num_inter." ";
				}

				echo	'<a href="'.base_url().'/Welcome/Sesion_Sucursales">
						 (+'.count($sucursales).' Sucursales)
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
	}

	public function get_Boton_Direccion()
	{
		$sucursales = $this->bases->obtener_sucursales($_POST['id_empresa']);
		
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
		$sucursales = $this->bases->obtener_sucursales($_POST['id_empresa']);
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

	/* PAGO */
	public function Pago()
	{
		if (isset($_SESSION['id_propietario']) )
		{
			$data['precio'] = "199.99";
			$propietario_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($propietario_query as $propietario)
			{
				$data['email'] = $propietario->correo;
			}
			$this->load->view('administrador/pago',$data);
	
		}else{
			redirect('/');
		}
		
	}
	public function CobroTarjeta()
	{
		if (isset($_SESSION['id_propietario']) )
		{
			$data['token_id'] = $this->input->post('conektaTokenId');
			$data['nombre']   = $this->input->post('name');
			$data['mail']     = $this->input->post('mail');
			$data['precio'] = "199.99";
			$this->load->view('cobro',$data);
		}else{
			redirect('/');
		}
	}

	public function CobroOxxo()
	{
		if (isset($_SESSION['id_propietario']) )
		{
			$propietario_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($propietario_query as $propietario)
			{
				$data['name']  = $propietario->nombre;
				$data['email'] = $propietario->correo;
				$data['phone'] = $propietario->celular;
			}
			$data['precio'] = "199.99";
			$query = $this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);

			$this->bases->pago_matriz_oxxo($query[0]->id_empresa);
			$this->load->view('oxxo',$data);
		}
		else{
			redirect('/');
		}
	}

	public function Visita(){

		if (isset($_SESSION['id_propietario']) )
		{
		$name   	   = $this->input->post('name',TRUE);
		$phone    	   = $this->input->post('phone', TRUE);
		$date          = $this->input->post('date',TRUE);
		$hour      	   = $this->input->post('hour', TRUE);
		$data = array('nombre' => $name, 'telefono' => $phone,'fecha' => $date, 'hora' => $hour,'id_propietario' => $_SESSION['id_propietario'], 'tipo' => "MATRIZ");
		$this->bases->agregar_Visita($data);
		$inf_negocio = $this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
		$this->bases->actualizar_tipo_pago_visita_matriz($inf_negocio[0]->id_empresa);

		redirect('/');}
		else{
			redirect('/');
		}
	}

	public function VisitaSucursal(){

		if (isset($_SESSION['id_propietario']) )
		{
		$name   	   = $this->input->post('name',TRUE);
		$phone    	   = $this->input->post('phone', TRUE);
		$date          = $this->input->post('date',TRUE);
		$hour      	   = $this->input->post('hour', TRUE);
		$data = array('nombre' => $name, 'telefono' => $phone,'fecha' => $date, 'hora' => $hour,'id_propietario' => $_SESSION['id_propietario'], 'tipo' => "SUCURSAL");
		$this->bases->agregar_Visita($data);
		$inf_negocio = $this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
		$this->bases->actualizar_tipo_pago_visita_sucursal($inf_negocio[0]->id_empresa);

		redirect('/');}
		else{
			redirect('/');
		}
	}

	public function Pago_Matriz_True(){
		if (isset($_SESSION['id_propietario']) )
		{
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				$id_empresa = $sucursales->id_empresa;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}
			date_default_timezone_set('America/Mexico_City');
			$hoy = date("Y-m-d");
			//Actualizamos la fecha del proximo pago
			$fecha = date("Y-m-d",strtotime($hoy."+ 1 year"));
			$this->bases->actualizar_pago_matriz($id_empresa,$fecha);
			redirect('Welcome/Sesion_Informacion');
	
		}else{
			redirect('/');
		}
	}

	public function Pago_Sucursal(){
		if (isset($_SESSION['id_propietario']) )
		{
			$data['precio'] = "49.99";
			$propietario_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
			foreach($propietario_query as $propietario)
			{
				$data['email'] = $propietario->correo;
			}
			$this->load->view('administrador/pago_sucursal',$data);
	
		}else{
			redirect('/');
		}

	}

	public function Pago_Sucursal_True(){
		if (isset($_SESSION['id_propietario']) )
		{
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				$id_empresa = $sucursales->id_empresa;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}
			$this->bases->actualizar_pago_sucursal($id_empresa);

			redirect('/Welcome/Sesion_Agregar_Sucursal');
	
		}else{
			redirect('/');
		}

	}


	public function CobroTarjetaSucursal()
	{
		if (isset($_SESSION['id_propietario']) )
		{
			$data['token_id'] = $this->input->post('conektaTokenId');
			$data['nombre']   = $this->input->post('name');
			$data['mail']     = $this->input->post('mail');
			$data['precio'] = "49.99";
			$this->load->view('cobroSucursal',$data);
		}else{
			redirect('/');
		}
	}

	public function CobroOxxoSucursal()
	{
		if (isset($_SESSION['id_propietario']) )
		{
		$propietario_query = $this->bases->obtener_propietario($_SESSION['id_propietario']);
		foreach($propietario_query as $propietario)
		{
			$data['name']  = $propietario->nombre;
			$data['email'] = $propietario->correo;
			$data['phone'] = $propietario->celular;
		}
		$data['precio'] = "49.99";
		$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
		$this->bases->pago_sucursal_oxxo($informacion_negocio_query[0]->id_empresa);
		$this->load->view('oxxo',$data);
		}
		else{
			redirect('/');
		}
	}
	/*FIN PAGO */

	/*SUCURSALES */
	public function Sesion_Sucursales()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 2;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/* */
			
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				
				break;
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_sucursales');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
		}else{
			redirect('/');
		}
	}

	public function Sesion_Agregar_Sucursal()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 2;

			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
				$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
				$informacion_negocio['foto_perfil'] = $informacion_negocio_q->foto_perfil;

				$informacion_negocio['info_general'] = $informacion_negocio_q->info_general;
				$informacion_negocio['servicios_productos'] = $informacion_negocio_q->servicios_productos;
				$informacion_negocio['ademas'] = $informacion_negocio_q->ademas;
				$informacion_negocio['total_subcategorias'] = $informacion_negocio_q->total_subcategorias;
			}
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				$informacion_negocio['id_direccion'] = $informacion_matriz_q->id_direccion;
				$informacion_negocio['actualizacion_dia'] = $informacion_matriz_q->actualizacion;
				break;
			}
			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_negocio['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_q)
			{
				$informacion_negocio['calle'] = $informacion_direccion_q->calle;
				$informacion_negocio['colonia'] = $informacion_direccion_q->colonia;
				$informacion_negocio['tipo_vialidad'] = $informacion_direccion_q->tipo_vialidad;
				$informacion_negocio['num_ext'] = $informacion_direccion_q->num_ext;
				$informacion_negocio['num_inter'] = $informacion_direccion_q->num_inter;
				$informacion_negocio['cp'] = $informacion_direccion_q->cp;
				$informacion_negocio['municipio'] = $informacion_direccion_q->municipio;
				$informacion_negocio['estado'] = $informacion_direccion_q->estado;
				$informacion_negocio['id_zona'] = $informacion_direccion_q->id_zona;
			}
			$informacion_negocio['zonas'] = $this->bases->obtener_zonas_puebla();
			$informacion_negocio['categorias'] = $this->bases->obtener_categorias_todas();
			
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
				$informacion_negocio['subcategoria'] .= $categoria_subcategoria_seccion_result->subcategoria ."|";
				$informacion_negocio['seccion'] .= $categoria_subcategoria_seccion_result->secciones . "|";
				switch($i){
					case 1:
						$informacion_negocio['seccion_1_1'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_1'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break; 
					case 2:
						$informacion_negocio['seccion_1_2'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_2'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 3:
						$informacion_negocio['seccion_1_3'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_3'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 4:
						$informacion_negocio['seccion_1_4'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_4'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
					case 5:
						$informacion_negocio['seccion_1_5'] = $categoria_subcategoria_seccion_result->id_secciones;
						$informacion_negocio['subcategoria_5'] = $categoria_subcategoria_seccion_result->id_subcategoria;
						break;
				}
				$i++;
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

			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_agregar_sucursal');
			$this->load->view('footer');			
		}else{
			redirect('/');
		}
	}

	public function Anadir_Sucursal(){
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_empresa = $sucursales->id_empresa;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

			/*Agregar dirección*/
			$estado = str_replace("'", "´", $_GET['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_GET['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}

			/*Agregamos la direccion de la sucursal*/
			$direccion_sucursal['id_municipio'] = $id_municipio;
			$direccion_sucursal['zona'] = $_GET['zona'];
			$direccion_sucursal['calle'] = str_replace("'", "´", $_GET['calle']);
			$direccion_sucursal['colonia'] = str_replace("'", "´", $_GET['colonia']);
			$direccion_sucursal['vialidad'] = $_GET['vialidad'];
			$direccion_sucursal['num_exterior'] = str_replace("'", "´", $_GET['num_exterior']);
			if($_GET['num_interior'] == ""){
				$direccion_sucursal['num_interior'] = 0;
			}else{
				$direccion_sucursal['num_interior'] = str_replace("'", "´", $_GET['num_interior']);
			}
			$direccion_sucursal['codigo_postal'] = str_replace("'", "´", $_GET['codigo_postal']);

			$this->bases->agregar_direccion($direccion_sucursal);
			
			$id_direccion_sucursal_query = $this->bases->obtener_id_direccion($direccion_sucursal);
			foreach($id_direccion_sucursal_query as $id_direccion_sucursal_q)
			{
				$id_direccion_sucursal = $id_direccion_sucursal_q->id_direccion;
			}

			/*Agregamos la sucursal */

			$latitud = $_GET['latitud'];
			$longitud = $_GET['longitud'];

			$this->bases->agregar_sucursal_($id_empresa, $id_direccion_sucursal, $latitud, $longitud);

			//$this->bases->agregar_sucursal($id_empresa, $id_direccion_sucursal);
			$id_sucursal_query = $this->bases->obtener_id_sucursal($id_empresa,$id_direccion_sucursal);
			foreach($id_sucursal_query as $id_sucursal_query_q)
			{
				$id_sucursal = $id_sucursal_query_q->id_sucursal;
				break;
			}
			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_sucursal, strftime("%d-%h-%y"));
			/* Servicio adicionales */
			$estacionamiento = $_GET['estacionamiento'];
			if($estacionamiento == 'true')
			{
				//añadimos el estacionamiento dependiendo el tipo
				if($_GET['estacionamiento_valor1'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor1']);
				}
				if($_GET['estacionamiento_valor2'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor2']);
				}
				if($_GET['estacionamiento_valor3'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor3']);
				}
			}
			
			$tarjetas = $_GET['tarjetas'];
			if($tarjetas == 'true')
			{	
				//Añadimos las tarjetas seleccionadas
				if($_GET['tarjeta1'] > 0){
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta1']);
				}
				if($_GET['tarjeta2'] > 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta2']);
				}
				if($_GET['tarjeta3'] > 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta3']);
				}
			}
			$silla_ruedas = $_GET['silla_ruedas'];
			if($silla_ruedas == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 1);
			}
			$atm = $_GET['atm'];
			if($atm == 'true' ){
				$this->bases->agregar_servicio($id_sucursal, 2);
			}
			$mesas_aire_libre = $_GET['mesas_aire_libre'];
			if($mesas_aire_libre == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 6);
			}
			$pantallas = $_GET['pantallas'];
			if($pantallas == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 7);
			}
			$reservaciones = $_GET['reservaciones'];
			if($reservaciones == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 8);
			}
			$sanitarios = $_GET['sanitarios'];
			if($sanitarios == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 9);
			}
			$serv_domicilio = $_GET['serv_domicilio'];
			if($serv_domicilio == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 10);
			}
			$wifi = $_GET['wifi'];
			if($wifi == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 14);
			}
			$zona_cigarrillo = $_GET['zona_cigarrillo'];
			if($zona_cigarrillo == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 15);
			}
			$zona_niños = $_GET['zona_niños'];
			if($zona_niños == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 16);
			}
			/* Fin de servicios adicionales */

			/* Agregamos las redes sociales */
			$pagina_web = $_GET['pagina_web'];
			if($pagina_web != "")
			{
				$pagina_web = str_replace("'", "´", $pagina_web);
				$this->bases->agregar_red_social($id_sucursal,'Pagina_Web',$pagina_web);
			}
			$facebook = $_GET['facebook'];
			if($facebook != "")
			{
				$facebook = str_replace("'", "´", $facebook);
				$this->bases->agregar_red_social($id_sucursal,'Facebook',$facebook);
			}
			$instagram = $_GET['instagram'];
			if($instagram != "")
			{
				$instagram = str_replace("'", "´", $instagram);
				$this->bases->agregar_red_social($id_sucursal,'Instagram',$instagram);
			}
			$youtube = $_GET['youtube'];
			if($youtube != "")
			{
				$youtube = str_replace("'", "´", $youtube);
				$this->bases->agregar_red_social($id_sucursal,'Youtube',$youtube);
			}
			$twitter = $_GET['twitter'];
			if($twitter != "")
			{
				$twitter = str_replace("'", "´", $twitter);
				$this->bases->agregar_red_social($id_sucursal,'Twitter',$twitter);
			}
			$snapchat = $_GET['snapchat'];
			if($snapchat != "")
			{
				$snapchat = str_replace("'", "´", $snapchat);
				$this->bases->agregar_red_social($id_sucursal,'Snapchat',$snapchat);
			}
			/* Fin de agregar las redes sociales */

			
			$dias_i = array(1=>'l', 2=>'m', 3=>'mi', 4=>'j', 5=>'v', 6=>'s', 7=>'d');
			$dias_nombre_M = array(1 =>"Lunes", 2 =>"Martes", 3 =>"Miércoles", 4 =>"Jueves", 5 =>"Viernes", 6 =>"Sábado", 7 =>"Domingo");
			$dias_nombre_m = array(1 =>"lunes", 2 =>"martes", 3 =>"miercoles", 4 =>"jueves", 5 =>"viernes", 6 =>"sabado", 7 =>"domingo");

			for($i = 1; $i <= 7; $i++)
			{
				$dia_d = "dia_".$dias_i[$i];
				$dia_d_valor = $_GET[$dia_d];
				if($dia_d_valor == 'true')
				{
					$hora_a_1 = $dias_nombre_m[$i]."_1_1";
					$hora_c_1 = $dias_nombre_m[$i]."_1_2";
					$horario['hora_apertura'] = $_GET[$hora_a_1];
					$horario['hora_cierre'] = $_GET[$hora_c_1];
					$horario['dia'] = $dias_nombre_M[$i];
					$horario['id_sucursal'] = $id_sucursal;
					$horario['horario_num'] = 1;
					$this->bases->agregar_horario($horario);
					$dia_h2 =  "dia_".$dias_nombre_M[$i]."2";
					if($_GET[$dia_h2] == 'true')
					{
						$hora_a_2 = $dias_nombre_m[$i]."_2_1";
						$hora_c_2 = $dias_nombre_m[$i]."_2_2";
						$horario['hora_apertura'] = $_GET[$hora_a_2];
						$horario['hora_cierre'] = $_GET[$hora_c_2];
						$horario['horario_num'] = 2;
						$this->bases->agregar_horario($horario);
					}
				}
			}

			
			/* Actualizamos los numeros y correos de la matriz */

			$telefono1 = $_GET['telefono1'];
			$datos_contacto['id_sucursal'] = $id_sucursal;
			$datos_contacto['tipo'] = 'telefono';
			$datos_contacto['valor'] = $telefono1;
			$datos_contacto['indice'] = 1; 
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$tel2 = $_GET['tel2'];
			if($tel2 == 'true')
			{
				$telefono2 = $_GET['telefono2'];
				$datos_contacto['valor'] = $telefono2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			$tel3 = $_GET['tel3'];
			if($tel3 == 'true')
			{
				$telefono3 = $_GET['telefono3'];
				$datos_contacto['valor'] = $telefono3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$num_cel1 = $_GET['num_cel1'];
			$datos_contacto['tipo'] = 'celular';
			$datos_contacto['valor'] = $num_cel1;
			$datos_contacto['indice'] = 1;
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$cel2 = $_GET['cel2'];
			if($cel2 == 'true')
			{
				$num_cel2 = $_GET['num_cel2'];
				$datos_contacto['valor'] = $num_cel2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$cel3 = $_GET['cel3'];
			if($cel3 == 'true'){
				$num_cel3 = $_GET['num_cel3'];
				$datos_contacto['valor'] = $num_cel3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$valor_correo1 = str_replace("'", "´",$_GET['valor_correo1']);
			if($valor_correo1 != "")
			{
				$datos_contacto['tipo'] = 'correo';
				$datos_contacto['valor'] = $valor_correo1;
				$datos_contacto['indice'] = 1;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$correo2 = $_GET['correo2'];
			if($correo2 == 'true')
			{
				$valor_correo2 = str_replace("'", "´",$_GET['valor_correo2']);
				$datos_contacto['valor'] = $valor_correo2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$correo3 = $_GET['correo3'];
			if($correo3 == 'true'){
				$valor_correo3 = str_replace("'", "´",$_GET['valor_correo3']);
				$datos_contacto['valor'] = $valor_correo3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			
			redirect('Welcome/Sesion_Sucursales');
		}else{
			redirect('/');
		}
	}

	public function Sesion_Editar_Sucursal()
	{
		if (isset($_SESSION['id_propietario']))
		{

			$id_sucursal =  $_GET['id_sucursal'];
			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 2.1;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/* */
			
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				
				break;
			}

			$latLong = $this->bases->obtener_latlong_sucursal($id_sucursal);

			if($latLong != FALSE)
			{
				$informacion_negocio['latitud'] = $latLong[0]->latitud;
				$informacion_negocio['longitud'] = $latLong[0]->longitud;

			}else{
				redirect('/');
			}
			
			
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			/* Obtenemos el horario */
			$horarios_query = $this->bases->obtener_horarios($id_sucursal);
			if($horarios_query != FALSE)
			{
				foreach($horarios_query as $horarios)
				{
					$dia = $horarios->dia.$horarios->horario_num;
					$informacion_negocio[$dia] = $horarios;
				}
			}
			/* Obtenemos los contactos de la matriz */
			$contactos_query = $this->bases->obtener_contactos_sucursal($id_sucursal);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_negocio[$tipo_contacto] = $contactos_q->valor;
				}
			}
			/* Obtenemos las redes sociales */
			$redes_sociales_query = $this->bases->obtener_redes_sociales($id_sucursal);
			if($redes_sociales_query != FALSE)
			{
				foreach($redes_sociales_query as $redes_sociales)
				{
					$tipo_red_social = $redes_sociales->red_social;
					$informacion_negocio[$tipo_red_social] = $redes_sociales->usuario;
				} 
			}
			/* Obtenemos los servicios */
			$servicios_query = $this->bases->obtener_servicios_adicionales($id_sucursal);
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
			/*** Información para la sucursal ***/
			$informacion_sucursal['nombre_negocio'] = $informacion_negocio['nombre_negocio'] ;
			$informacion_sucursal['id_sucursal'] = $id_sucursal;

			$informacion_direccion_query = $this->bases->obtener_id_direccion_sucursal($id_sucursal);

			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['id_direccion'] =  $informacion_direccion_sucursal_q ->id_direccion;
			}

			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_sucursal['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['calle'] = $informacion_direccion_sucursal_q->calle;
				$informacion_sucursal['colonia'] = $informacion_direccion_sucursal_q->colonia;
				$informacion_sucursal['tipo_vialidad'] = $informacion_direccion_sucursal_q->tipo_vialidad;
				$informacion_sucursal['num_ext'] = $informacion_direccion_sucursal_q->num_ext;
				$informacion_sucursal['num_inter'] = $informacion_direccion_sucursal_q->num_inter;
				$informacion_sucursal['cp'] = $informacion_direccion_sucursal_q->cp;
				$informacion_sucursal['municipio'] = $informacion_direccion_sucursal_q->municipio;
				$informacion_sucursal['estado'] = $informacion_direccion_sucursal_q->estado;
				$informacion_sucursal['id_zona'] = $informacion_direccion_sucursal_q->id_zona;
			}
			$informacion_sucursal['zonas'] = $this->bases->obtener_zonas_puebla();
					
			/* Obtenemos las redes sociales de sucursal */
			$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_sucursal['id_sucursal']);
			if($redes_sociales_query != FALSE)
			{
				foreach($redes_sociales_query as $redes_sociales)
				{
					$tipo_red_social = $redes_sociales->red_social;
					$informacion_sucursal[$tipo_red_social] = $redes_sociales->usuario;
				} 
			}
			/* Obtenemos los servicios de sucursal*/
			$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_sucursal['id_sucursal']);
			if($servicios_query != FALSE)
			{
				foreach($servicios_query as $servicios)
				{
					$tipo_servicio = $servicios->servicio;
					$informacion_sucursal[$tipo_servicio] = $servicios->servicio;
				}
			}

			/* Obtenemos el horario de sucursal */
			$horarios_query = $this->bases->obtener_horarios($informacion_sucursal['id_sucursal']);
			if($horarios_query != FALSE)
			{
				foreach($horarios_query as $horarios)
				{
					$dia = $horarios->dia.$horarios->horario_num;
					$informacion_sucursal[$dia] = $horarios;
				}
			}

			/* Obtenemos los contactos de la sucursal */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_sucursal['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_sucursal[$tipo_contacto] = $contactos_q->valor;
				}
			}

			/* Función para abierto ahora o cerrado */
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
	            $horario_query = $this->bases->obtener_horarios($id_sucursal);
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
			/*Fin abierto ahora*/
			
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_sucursal_modificar',$informacion_sucursal);
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sucursal_Modificar()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$id_sucursal = $_GET['id_sucursal'];

			$latitud = $_GET['latitud'];
			$longitud = $_GET['longitud'];

			$this->bases->actualizar_coordenadad($id_sucursal, $latitud, $longitud);

			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_sucursal, strftime("%d-%h-%y"));
			/* Eliminamos los servicios anteriores */
			$this->bases->eliminar_servicios($id_sucursal);
			/* Servicio adicionales */
			$estacionamiento = $_GET['estacionamiento'];
			if($estacionamiento == 'true')
			{
				//añadimos el estacionamiento dependiendo el tipo
				if($_GET['estacionamiento_valor1'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor1']);
				}
				if($_GET['estacionamiento_valor2'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor2']);
				}
				if($_GET['estacionamiento_valor3'] != 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['estacionamiento_valor3']);
				}
			}
			
			$tarjetas = $_GET['tarjetas'];
			if($tarjetas == 'true')
			{	
				//Añadimos las tarjetas seleccionadas
				if($_GET['tarjeta1'] > 0){
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta1']);
				}
				if($_GET['tarjeta2'] > 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta2']);
				}
				if($_GET['tarjeta3'] > 0)
				{
					$this->bases->agregar_servicio($id_sucursal, $_GET['tarjeta3']);
				}
			}
			$silla_ruedas = $_GET['silla_ruedas'];
			if($silla_ruedas == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 1);
			}
			$atm = $_GET['atm'];
			if($atm == 'true' ){
				$this->bases->agregar_servicio($id_sucursal, 2);
			}
			$mesas_aire_libre = $_GET['mesas_aire_libre'];
			if($mesas_aire_libre == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 6);
			}
			$pantallas = $_GET['pantallas'];
			if($pantallas == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 7);
			}
			$reservaciones = $_GET['reservaciones'];
			if($reservaciones == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 8);
			}
			$sanitarios = $_GET['sanitarios'];
			if($sanitarios == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 9);
			}
			$serv_domicilio = $_GET['serv_domicilio'];
			if($serv_domicilio == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 10);
			}
			$wifi = $_GET['wifi'];
			if($wifi == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 14);
			}
			$zona_cigarrillo = $_GET['zona_cigarrillo'];
			if($zona_cigarrillo == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 15);
			}
			$zona_niños = $_GET['zona_niños'];
			if($zona_niños == 'true'){
				$this->bases->agregar_servicio($id_sucursal, 16);
			}
			/* Fin de servicios adicionales */

			/* Agregamos las redes sociales */
			$this->bases->eliminar_redes_sociales($id_sucursal);

			$pagina_web = $_GET['pagina_web'];
			if($pagina_web != "")
			{
				$pagina_web = str_replace("'", "´", $pagina_web);
				$this->bases->agregar_red_social($id_sucursal,'Pagina_Web',$pagina_web);
			}
			$facebook = $_GET['facebook'];
			if($facebook != "")
			{
				$facebook = str_replace("'", "´", $facebook);
				$this->bases->agregar_red_social($id_sucursal,'Facebook',$facebook);
			}
			$instagram = $_GET['instagram'];
			if($instagram != "")
			{
				$instagram = str_replace("'", "´", $instagram);
				$this->bases->agregar_red_social($id_sucursal,'Instagram',$instagram);
			}
			$youtube = $_GET['youtube'];
			if($youtube != "")
			{
				$youtube = str_replace("'", "´", $youtube);
				$this->bases->agregar_red_social($id_sucursal,'Youtube',$youtube);
			}
			$twitter = $_GET['twitter'];
			if($twitter != "")
			{
				$twitter = str_replace("'", "´", $twitter);
				$this->bases->agregar_red_social($id_sucursal,'Twitter',$twitter);
			}
			$snapchat = $_GET['snapchat'];
			if($snapchat != "")
			{
				$snapchat = str_replace("'", "´", $snapchat);
				$this->bases->agregar_red_social($id_sucursal,'Snapchat',$snapchat);
			}
			/* Fin de agregar las redes sociales */

			/* Modificamos la direccion */
			$direccion_query = $this->bases->obtener_direccion_sucursal($id_sucursal);
			foreach($direccion_query as $direccion)
			{
				$id_direccion = $direccion->id_direccion;
			}

			$estado = str_replace("'", "´", $_GET['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_GET['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}
			//Agregamos la direccion de la empresa
			$direccion_empresa['id_municipio'] = $id_municipio;
			$direccion_empresa['zona'] = $_GET['zona'];
			$direccion_empresa['calle'] = str_replace("'", "´", $_GET['calle']);
			$direccion_empresa['colonia'] = str_replace("'", "´", $_GET['colonia']);
			$direccion_empresa['vialidad'] = $_GET['vialidad'];
			$direccion_empresa['num_exterior'] = str_replace("'", "´", $_GET['num_exterior']);
			if($_GET['num_interior'] == ""){
				$direccion_empresa['num_interior'] = 0;
			}else{
				$direccion_empresa['num_interior'] = str_replace("'", "´", $_GET['num_interior']);
			}
			$direccion_empresa['codigo_postal'] = str_replace("'", "´", $_GET['codigo_postal']);

			$this->bases->actualizar_direccion($id_direccion, $direccion_empresa);

			/* Agregamos el horario de la matriz */
			$this->bases->eliminar_horario($id_sucursal);
			$dias_i = array(1=>'l', 2=>'m', 3=>'mi', 4=>'j', 5=>'v', 6=>'s', 7=>'d');
			$dias_nombre_M = array(1 =>"Lunes", 2 =>"Martes", 3 =>"Miércoles", 4 =>"Jueves", 5 =>"Viernes", 6 =>"Sábado", 7 =>"Domingo");
			$dias_nombre_m = array(1 =>"lunes", 2 =>"martes", 3 =>"miercoles", 4 =>"jueves", 5 =>"viernes", 6 =>"sabado", 7 =>"domingo");

			for($i = 1; $i <= 7; $i++)
			{
				$dia_d = "dia_".$dias_i[$i];
				$dia_d_valor = $_GET[$dia_d];
				if($dia_d_valor == 'true')
				{
					$hora_a_1 = $dias_nombre_m[$i]."_1_1";
					$hora_c_1 = $dias_nombre_m[$i]."_1_2";
					$horario['hora_apertura'] = $_GET[$hora_a_1];
					$horario['hora_cierre'] = $_GET[$hora_c_1];
					$horario['dia'] = $dias_nombre_M[$i];
					$horario['id_sucursal'] = $id_sucursal;
					$horario['horario_num'] = 1;
					$this->bases->agregar_horario($horario);
					$dia_h2 =  "dia_".$dias_nombre_M[$i]."2";
					if($_GET[$dia_h2] == 'true')
					{
						$hora_a_2 = $dias_nombre_m[$i]."_2_1";
						$hora_c_2 = $dias_nombre_m[$i]."_2_2";
						$horario['hora_apertura'] = $_GET[$hora_a_2];
						$horario['hora_cierre'] = $_GET[$hora_c_2];
						$horario['horario_num'] = 2;
						$this->bases->agregar_horario($horario);
					}
				}
			}

			
			/* Actualizamos los numeros y correos de la matriz */
			$this->bases->eliminar_contacto_sucursal($id_sucursal);

			$telefono1 = $_GET['telefono1'];
			$datos_contacto['id_sucursal'] = $id_sucursal;
			$datos_contacto['tipo'] = 'telefono';
			$datos_contacto['valor'] = $telefono1;
			$datos_contacto['indice'] = 1; 
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$tel2 = $_GET['tel2'];
			if($tel2 == 'true')
			{
				$telefono2 = $_GET['telefono2'];
				$datos_contacto['valor'] = $telefono2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			$tel3 = $_GET['tel3'];
			if($tel3 == 'true')
			{
				$telefono3 = $_GET['telefono3'];
				$datos_contacto['valor'] = $telefono3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$num_cel1 = $_GET['num_cel1'];
			$datos_contacto['tipo'] = 'celular';
			$datos_contacto['valor'] = $num_cel1;
			$datos_contacto['indice'] = 1;
			$this->bases->agregar_contacto_sucursal($datos_contacto);

			$cel2 = $_GET['cel2'];
			if($cel2 == 'true')
			{
				$num_cel2 = $_GET['num_cel2'];
				$datos_contacto['valor'] = $num_cel2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$cel3 = $_GET['cel3'];
			if($cel3 == 'true'){
				$num_cel3 = $_GET['num_cel3'];
				$datos_contacto['valor'] = $num_cel3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$valor_correo1 = str_replace("'", "´",$_GET['valor_correo1']);
			if($valor_correo1 != "")
			{
				$datos_contacto['tipo'] = 'correo';
				$datos_contacto['valor'] = $valor_correo1;
				$datos_contacto['indice'] = 1;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			$correo2 = $_GET['correo2'];
			if($correo2 == 'true')
			{
				$valor_correo2 = str_replace("'", "´",$_GET['valor_correo2']);
				$datos_contacto['valor'] = $valor_correo2;
				$datos_contacto['indice'] = 2;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}
			
			$correo3 = $_GET['correo3'];
			if($correo3 == 'true'){
				$valor_correo3 = str_replace("'", "´",$_GET['valor_correo3']);
				$datos_contacto['valor'] = $valor_correo3;
				$datos_contacto['indice'] = 3;
				$this->bases->agregar_contacto_sucursal($datos_contacto);
			}

			
			redirect('Welcome/Sesion_Sucursales');
		}else{
			redirect('/');
		}
	}	

	public function Sesion_Eliminar_Sucursal(){
		$id_sucursal =  $this->input->post('id_sucursal_modal');
		$eliminar_sucursal_query =$this->bases-> eliminar_sucursal($id_sucursal);
		redirect('Welcome/Sesion_Sucursales');
		
	}

	public function Sesion_VerMas_Sucursal(){
	
		if (isset($_SESSION['id_propietario']))
		{
			$id_sucursal 		=  $_GET['id_sucursal'];
			$abierto_sucursal	=  $_GET['abierto_sucursal'];
			$horario_sucursal	=  $_GET['horario_sucursal'];
			$informacion_sucursal['cont'] = $_GET['cont'];
			$informacion_sucursal['abierto_sucursal'] = $abierto_sucursal;
			$informacion_sucursal['horario_sucursal'] = $horario_sucursal;


			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 2;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			/* */
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
				$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
				$informacion_negocio['foto_perfil'] = $informacion_negocio_q->foto_perfil;

				$informacion_negocio['info_general'] = $informacion_negocio_q->info_general;
				$informacion_negocio['servicios_productos'] = $informacion_negocio_q->servicios_productos;
				$informacion_negocio['ademas'] = $informacion_negocio_q->ademas;
				$informacion_negocio['total_subcategorias'] = $informacion_negocio_q->total_subcategorias;
			}

			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				$informacion_negocio['id_direccion'] = $informacion_matriz_q->id_direccion;
				$informacion_negocio['actualizacion_dia'] = $informacion_matriz_q->actualizacion;
				break;
			}
			
			/*** Información para la sucursal ***/
			$informacion_sucursal['nombre_negocio'] = $informacion_negocio['nombre_negocio'] ;
			$informacion_sucursal['id_sucursal'] = $id_sucursal;
			$informacion_sucursal['sucursal'] = $this->bases->obtener_sucursal($id_sucursal);

			$informacion_direccion_query = $this->bases->obtener_id_direccion_sucursal($id_sucursal);

			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['id_direccion'] =  $informacion_direccion_sucursal_q ->id_direccion;
			}

			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_sucursal['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['calle'] = $informacion_direccion_sucursal_q->calle;
				$informacion_sucursal['colonia'] = $informacion_direccion_sucursal_q->colonia;
				$informacion_sucursal['tipo_vialidad'] = $informacion_direccion_sucursal_q->tipo_vialidad;
				$informacion_sucursal['num_ext'] = $informacion_direccion_sucursal_q->num_ext;
				$informacion_sucursal['num_inter'] = $informacion_direccion_sucursal_q->num_inter;
				$informacion_sucursal['cp'] = $informacion_direccion_sucursal_q->cp;
				$informacion_sucursal['municipio'] = $informacion_direccion_sucursal_q->municipio;
				$informacion_sucursal['estado'] = $informacion_direccion_sucursal_q->estado;
				$informacion_sucursal['id_zona'] = $informacion_direccion_sucursal_q->id_zona;
			}

			$zonas_query = $this->bases->obtener_zona($informacion_sucursal['id_zona']);
			foreach ($zonas_query as $zonas) {
				$informacion_sucursal['zona'] = $zonas->zona;
			}
			
					
			/* Obtenemos las redes sociales de sucursal */
			$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_sucursal['id_sucursal']);
			if($redes_sociales_query != FALSE)
			{
				foreach($redes_sociales_query as $redes_sociales)
				{
					$tipo_red_social = $redes_sociales->red_social;
					$informacion_sucursal[$tipo_red_social] = $redes_sociales->usuario;
				} 
			}
			/* Obtenemos los servicios de sucursal*/
			$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_sucursal['id_sucursal']);
			if($servicios_query != FALSE)
			{
				foreach($servicios_query as $servicios)
				{
					$tipo_servicio = $servicios->servicio;
					$informacion_sucursal[$tipo_servicio] = $servicios->servicio;
				}
			}

			/* Obtenemos el horario de sucursal */
			$horarios_query = $this->bases->obtener_horarios($informacion_sucursal['id_sucursal']);
			if($horarios_query != FALSE)
			{
				foreach($horarios_query as $horarios)
				{
					$dia = $horarios->dia.$horarios->horario_num;
					$informacion_sucursal[$dia] = $horarios;
				}
			}

			/* Obtenemos los contactos de la sucursal */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_sucursal['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_sucursal[$tipo_contacto] = $contactos_q->valor;
				}
			}

			/* Función para abierto ahora o cerrado */
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
			
			/*Fin abierto ahora*/
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
						
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_sucursal_ver_mas',$informacion_sucursal);
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');
		}
	}

	public function Sesion_VerMapa_Sucursal(){
		/*Información para la matriz*/
		$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
		//Si no pago la matriz lo redireccionamos al pago
		$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
		if($pago_realizado_query == FALSE)
		{
			redirect('/Welcome/Pago');
		}

		$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
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

		$this->load->view('nav-lateral',$informacion_negocio);
		$this->load->view('administrador/informacion_negocio_principal');
		$this->load->view('administrador/sesion_sucursal_ver_mapa',$informacion_negocio);
		$this->load->view('administrador/publicidad');
		$this->load->view('footer');

	}
	/*FIN SUCURSALES */

	/*PROMOCIONES*/
	public function Sesion_Promocion()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 5;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/* */
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				
				break;
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_promocion');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}

	public function Sesion_Agregar_Promocion()
	{
		if (isset($_SESSION['id_propietario'])){
			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 5;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/***/
			$informacion_matriz_query = $this->bases->obtener_sucursales($informacion_negocio['id_empresa']);
			foreach($informacion_matriz_query as $informacion_matriz_q)
			{
				$informacion_negocio['id_sucursal'] = $informacion_matriz_q->id_sucursal;
				
				break;
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
			/* Fin abierto ahora*/

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
													$abierto_sucursal[$ID]  = "TRUE";;
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_agregar_promocion');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}

	public function Anadir_Promocion()
	{
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			//Obtenemos el id de la empresa y agregamos promocion
			$id_empresa_query = $this->bases->obtener_id_empresa($_SESSION['id_propietario']);
			foreach($id_empresa_query as $id_empresa_q)
			{
				$id_empresa = $id_empresa_q->id_empresa;
			}
			
			$data['titulo_promocion'] = str_replace("'", "´", $this->input->post('titulo_promocion',TRUE));
			$data['descripcion'] = str_replace("'", "´", $this->input->post('descripcion', TRUE));
			$data['inicia'] = $this->input->post('inicia',TRUE);
			$data['finaliza'] = $this->input->post('finaliza', TRUE);
			$data['id_empresa'] = $id_empresa;
			$data['foto'] = NULL;
			$data['porcentaje'] = NULL;
			
			$array_sucursales = array();

			$sucursales_query = $this->bases->obtener_sucursales($id_empresa);
			foreach($sucursales_query as $sucursales_q){
				$name = "t_".$sucursales_q->id_sucursal;
				if(!empty($this->input->post($name,TRUE)))
				{
					//Guardamos las sucursales que fueron seleccionadas
					array_push($array_sucursales,$sucursales_q->id_sucursal);
				}

			}

			if(count($array_sucursales) >= 1)
			{
				$porcentaje_imagen = $this->input->post('porcentaje_imagen',TRUE);
				//Selecciono una imagen
				if($porcentaje_imagen == 'imagen')
				{

					if(!empty($_FILES))
					{
						$filename = $_FILES["archivo"]["name"]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'PromocionesEmpresa/'.$id_empresa.'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'];
						$tamanio_maximo = 6291456; //4MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}

							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
							
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								//El archivo se guardo correctamente, ahora podemos guardar en la bd
								$data['foto'] = str_replace("'", "´", $filename);
								$this->bases->agregar_promocion($data);
								//Obtenemos el id de la promocion
								$id_promocion_query = $this->bases->obtener_ultima_promo($id_empresa);
								foreach($id_promocion_query as $id_promocion_q)
								{
									$id_promocion = $id_promocion_q->id_promociones;
									break;
								}
								$data['id_promocion'] = $id_promocion;

								for( $i=0; $i < count($array_sucursales); $i++)
								{ 
									$data['id_sucursal'] = $array_sucursales[$i];
									$this->bases->agregar_promocion_sucursal($data);
								}
							}
							
							closedir($dir); //Cerramos el directorio de destino
							
						}
					}
				}else{
					$data['porcentaje'] = $this->input->post('rango',TRUE);
					$this->bases->agregar_promocion($data);
					//Obtenemos el id de la promocion
					$id_promocion_query = $this->bases->obtener_ultima_promo($id_empresa);
					foreach($id_promocion_query as $id_promocion_q)
					{
						$id_promocion = $id_promocion_q->id_promociones;
						break;
					}
					$data['id_promocion'] = $id_promocion;

					for( $i=0; $i < count($array_sucursales); $i++)
					{
						$data['id_sucursal'] = $array_sucursales[$i];
						$this->bases->agregar_promocion_sucursal($data);
					}
				}
			}
			redirect('/Welcome/Sesion_Promocion');
		}
	}

	public function Eliminar_Promocion(){
		if(isset($_SESSION['id_propietario'])){
			$id_promocion = $this->input->post('id_promocion', TRUE);
			$promocion_query = $this->bases->obtener_promocion_id($id_promocion);
			foreach ($promocion_query as $promocion_q){
				$promocion = $promocion_q;
			}
			if(!empty($promocion->foto)){
				if(unlink("PromocionesEmpresa/".$promocion->id_empresa."/".$promocion->foto)){
					$this->bases->eliminar_promocion($id_promocion);
				}
			}else{
				$this->bases->eliminar_promocion($id_promocion);
			}			
			redirect('Welcome/Sesion_Promocion');			
		}else{
			redirect('/');
		}

	}

	public function Promocion_Sucursales(){
		if (isset($_SESSION['id_propietario'])){
			
			$id_promociones	= $_GET['id_promociones'];
			$sucursales 	= $_GET['sucursales'];

			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 5.1;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
			$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
			/***/
	
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_promocion_ver_mas');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');		
		}
		else{
			redirect('/');
		}	
	}

	public function Sesion_Promocion_Editar(){
		if (isset($_SESSION['id_propietario'])){
			$id_promociones	= $_GET['id_promociones'];

			$informacion_promo = $this->bases->obtener_promocion_id($id_promociones);
			foreach($informacion_promo as $informacion){
				$informacion_negocio['promocion']=$informacion;
			}
			$promocion_query  = $this->bases->obtener_promocion_sucursal($id_promociones);	
			if($promocion_query != FALSE)
			{
				$promocion_su= array();
				foreach($promocion_query as $promocion_q)
				{	
					array_push($promocion_su, $promocion_q->id_sucursal);					
				}	
				$informacion_negocio['promocion_id_sucursal'] = $promocion_su;
			}


			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 5.1;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
			$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
			/***/
	
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			$informacion_negocio['sucursales'] 			= $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
			/** */			         
			$informacion_negocio['sucursales'] = $this->bases->todas_sucursales($informacion_negocio['id_empresa']);
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_promocion_editar');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}

	public function Editar_Promocion(){
		if (isset($_SESSION['id_propietario']))
		{
			//Obtenemos el id de la empresa y agregamos promocion
			$id_empresa_query = $this->bases->obtener_id_empresa($_SESSION['id_propietario']);
			foreach($id_empresa_query as $id_empresa_q)
			{
				$id_empresa = $id_empresa_q->id_empresa;
			}
			
			$data['id_promocion'] = $this->input->post('id_promociones',TRUE);
			$data['titulo_promocion'] = str_replace("'", "´", $this->input->post('titulo_promocion',TRUE));
			$data['descripcion'] = str_replace("'", "´", $this->input->post('descripcion', TRUE));
			$data['inicia'] = $this->input->post('inicia',TRUE);
			$data['finaliza'] = $this->input->post('finaliza', TRUE);
			$data['id_empresa'] = $id_empresa;
			$data['foto'] = NULL;
			$data['porcentaje'] = NULL;
			
			$array_sucursales = array();

			$sucursales_query = $this->bases->obtener_sucursales($id_empresa);
			foreach($sucursales_query as $sucursales_q){
				$name = "t_".$sucursales_q->id_sucursal;
				if(!empty($this->input->post($name,TRUE)))
				{
					//Guardamos las sucursales que fueron seleccionadas
					array_push($array_sucursales,$sucursales_q->id_sucursal);
				}
			}

			if(count($array_sucursales) >= 1)
			{
				$porcentaje_imagen = $this->input->post('porcentaje_imagen',TRUE);
				//Selecciono una imagen
				if($porcentaje_imagen == 'imagen')
				{

					if(!empty($_FILES))
					{
						$filename = $_FILES["archivo"]["name"]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'PromocionesEmpresa/'.$id_empresa.'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'];
						$tamanio_maximo = 6291456; //4MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}

							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
							
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								//El archivo se guardo correctamente, ahora podemos guardar en la bd
								$data['foto'] = str_replace("'", "´", $filename);
								
								//actualizamos la promoción
								$this->bases->modificar_promocion($data);
								 //eliminar sucursales de las promocion		
					            $this->bases->eliminar_promocion_sucursal($data['id_promocion']);

								for( $i=0; $i < count($array_sucursales); $i++)
								{ 
									$data['id_sucursal'] = $array_sucursales[$i];
									$this->bases->agregar_promocion_sucursal($data);
								}
							}
							
							closedir($dir); //Cerramos el directorio de destino
							
						}
					}
				}else{
					$data['porcentaje'] = $this->input->post('rango',TRUE);
					//actualizamos la promoción
					$this->bases->modificar_promocion($data);
					//eliminar sucursales de las promocion		
				   	$this->bases->eliminar_promocion_sucursal($data['id_promocion']);

					for( $i=0; $i < count($array_sucursales); $i++)
					{
						$data['id_sucursal'] = $array_sucursales[$i];
						$this->bases->agregar_promocion_sucursal($data);
					}
				}
			}
			redirect('/Welcome/Sesion_Promocion');
		}

	}

	public function Sesion_VerMas_Promocion_Sucursal(){
	
		if (isset($_SESSION['id_propietario']))
		{
			$id_sucursal 		=  $_GET['id_sucursal'];
			$abierto_sucursal	=  $_GET['abierto_sucursal'];
			$horario_sucursal	=  $_GET['horario_sucursal'];
			$informacion_sucursal['abierto_sucursal'] = $abierto_sucursal;
			$informacion_sucursal['horario_sucursal'] = $horario_sucursal;

			/***/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 5.1;	
			$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

		
			$informacion_negocio['subcategorias'] 	= $secciones;

			/*** Información para la sucursal ***/
			$informacion_sucursal['nombre_negocio'] = $informacion_negocio['nombre_negocio'] ;
			$informacion_sucursal['id_sucursal'] = $id_sucursal;

			$informacion_direccion_query = $this->bases->obtener_id_direccion_sucursal($id_sucursal);

			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['id_direccion'] =  $informacion_direccion_sucursal_q ->id_direccion;
			}

			$informacion_direccion_query = $this->bases->obtener_direccion($informacion_sucursal['id_direccion']);
			foreach($informacion_direccion_query as $informacion_direccion_sucursal_q)
			{
				$informacion_sucursal['calle'] = $informacion_direccion_sucursal_q->calle;
				$informacion_sucursal['colonia'] = $informacion_direccion_sucursal_q->colonia;
				$informacion_sucursal['tipo_vialidad'] = $informacion_direccion_sucursal_q->tipo_vialidad;
				$informacion_sucursal['num_ext'] = $informacion_direccion_sucursal_q->num_ext;
				$informacion_sucursal['num_inter'] = $informacion_direccion_sucursal_q->num_inter;
				$informacion_sucursal['cp'] = $informacion_direccion_sucursal_q->cp;
				$informacion_sucursal['municipio'] = $informacion_direccion_sucursal_q->municipio;
				$informacion_sucursal['estado'] = $informacion_direccion_sucursal_q->estado;
				$informacion_sucursal['id_zona'] = $informacion_direccion_sucursal_q->id_zona;
			}

			$zonas_query = $this->bases->obtener_zona($informacion_sucursal['id_zona']);
			foreach ($zonas_query as $zonas) {
				$informacion_sucursal['zona'] = $zonas->zona;
			}
			
					
			/* Obtenemos las redes sociales de sucursal */
			$redes_sociales_query = $this->bases->obtener_redes_sociales($informacion_sucursal['id_sucursal']);
			if($redes_sociales_query != FALSE)
			{
				foreach($redes_sociales_query as $redes_sociales)
				{
					$tipo_red_social = $redes_sociales->red_social;
					$informacion_sucursal[$tipo_red_social] = $redes_sociales->usuario;
				} 
			}
			/* Obtenemos los servicios de sucursal*/
			$servicios_query = $this->bases->obtener_servicios_adicionales($informacion_sucursal['id_sucursal']);
			if($servicios_query != FALSE)
			{
				foreach($servicios_query as $servicios)
				{
					$tipo_servicio = $servicios->servicio;
					$informacion_sucursal[$tipo_servicio] = $servicios->servicio;
				}
			}

			/* Obtenemos el horario de sucursal */
			$horarios_query = $this->bases->obtener_horarios($informacion_sucursal['id_sucursal']);
			if($horarios_query != FALSE)
			{
				foreach($horarios_query as $horarios)
				{
					$dia = $horarios->dia.$horarios->horario_num;
					$informacion_sucursal[$dia] = $horarios;
				}
			}

			/* Obtenemos los contactos de la sucursal */
			$contactos_query = $this->bases->obtener_contactos_sucursal($informacion_sucursal['id_sucursal']);
			if($contactos_query != FALSE)
			{
				foreach($contactos_query as $contactos_q)
				{
					$tipo_contacto = $contactos_q->tipo.$contactos_q->indice;
					$informacion_sucursal[$tipo_contacto] = $contactos_q->valor;
				}
			}

			/* Función para abierto ahora o cerrado */
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
			
			/*Fin abierto ahora*/
			$informacion_negocio['num_sucursales'] = $this->bases->num_sucursales($informacion_negocio['id_empresa']);
			
			$this->load->view('administrador/header_principal');
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_promocion_ver_mas_sucursal',$informacion_sucursal);
			$this->load->view('administrador/publicidad');
			$this->load->view('administrador/footer');
			
		}else{
			redirect('/');
		}
	}
	/*FIN PROMOCIONES*/


	/*EVENTOS */
	public function Sesion_Eventos()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 6;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

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
			
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_eventos');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');	
		}
	}

	public function Sesion_Agregar_Evento()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 6;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_agregar_evento');
			$this->load->view('footer');

		}else{
			redirect('/');	
		}	
	}

	public function Anadir_Evento()
	{
		if (isset($_SESSION['id_propietario'])){

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */

			//Obtenemos el id de la empresa y agregamos evento
			$id_empresa_query = $this->bases->obtener_id_empresa($_SESSION['id_propietario']);
			foreach($id_empresa_query as $id_empresa_q)
			{
				$id_empresa = $id_empresa_q->id_empresa;
			}
			
			$porcentaje_imagen = $_POST['porcentaje_imagen'];
			$data['imagen'] = "";
			//No subio imagen
			if($porcentaje_imagen == "sin_imagen")
			{
				$data['imagen'] = "sin_imagen";
			}else{
				//Subio una imagen (La subimos al servidor)
				if(!empty($_FILES["archivo_foto"]['tmp_name']))
				{
					$filename = $_FILES["archivo_foto"]["name"]; //Obtenemos el nombre original del archivo
					$source = $_FILES["archivo_foto"]["tmp_name"]; //Obtenemos un nombre temporal del archivo

					//Cada negocio tendra su propia carpeta de archivos
					$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$id_empresa.'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
					
					$tamanio_bytes =  $_FILES["archivo_foto"]['size'];
					$tamanio_maximo = 6291456; //6MB
					$es_img = getimagesize($_FILES["archivo_foto"]["tmp_name"]);

					if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
					{
						//Validamos si la ruta de destino existe, en caso de no existir la creamos
						if(!file_exists($directorio)){
							mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
						}

						$dir=opendir($directorio); //Abrimos el directorio de destino
						$new_name = rand(0,120).''.$filename;
						$target_path = $directorio.'/P'.$new_name; //Indicamos la ruta de destino, así como el nombre del archivo
						
						//Movemos y validamos que el archivo se haya cargado correctamente
						//El primer campo es el origen y el segundo el destino
						
						if(move_uploaded_file($source, $target_path)) {
							//Obtenemos el nombre con el que se guardo
							$data['imagen'] = str_replace("'", "´", 'P'.$new_name);
						}else{
							$data['imagen'] = "sin_imagen";	
						}
						
						closedir($dir); //Cerramos el directorio de destino
						
					}

				}else{
					$data['imagen'] = "sin_imagen";
				}
			}

			/* Agregamos la direccion */
			//Obtenemos el id del estado
			$estado = str_replace("'", "´", $_POST['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_POST['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}
			
			//Agregamos la direccion de la empresa
			$direccion_evento['id_municipio'] = $id_municipio;
			$direccion_evento['zona'] = $_POST['zona'];
			$direccion_evento['calle'] = str_replace("'", "´", $_POST['calle']);
			$direccion_evento['colonia'] = str_replace("'", "´", $_POST['colonia']);
			$direccion_evento['vialidad'] = $_POST['vialidad'];
			$direccion_evento['num_exterior'] = str_replace("'", "´", $_POST['num_ext']);
			if($_POST['num_inter'] == ""){
				$direccion_evento['num_interior'] = 0;
			}else{
				$direccion_evento['num_interior'] = str_replace("'", "´", $_POST['num_inter']);
			}
			$direccion_evento['codigo_postal'] = str_replace("'", "´", $_POST['postal_code']);
			$this->bases->agregar_direccion($direccion_evento);

			$id_direccion_ = $this->bases->obtener_id_direccion($direccion_evento);

			foreach($id_direccion_ as $id_direccion_q)
			{
				$id_direccion = $id_direccion_q->id_direccion;
			}
			$data['id_empresa'] = $id_empresa;
			$data['nombre'] = str_replace("'", "´", $_POST['nombre_evento']);
			$data['sinopsis'] = str_replace("'", "´", $_POST['sinopsis']);
			$data['id_direccion'] = $id_direccion;
			$data['latitud'] = $_POST['latitud'];
			$data['longitud'] = $_POST['longitud'];
			//Agregamos el evento a la bd
			$this->bases->insertar_evento($data);
			//Obtenemos su id
			$id_evento_query = $this->bases->obtener_evento($data);
			foreach($id_evento_query as $id_evento_q)
			{
				$id_evento = $id_evento_q->id_evento;
			}

			$datos_fecha['id_evento'] = $id_evento;
			$datos_fecha['fecha'] = $_POST['fecha'];
			$datos_fecha['hora'] = $_POST['hora'];
			$this->bases->insertar_fecha_evento($datos_fecha);

			$fecha = 2;
			while(true)
			{
				$var_fecha = "fecha_".$fecha;
				$var_hora = "hora_".$fecha;

				if(!empty($_POST[$var_fecha]))
				{
					$datos_fecha['fecha'] = $_POST[$var_fecha];
					$datos_fecha['hora'] = $_POST[$var_hora];

					/* Aqui los agregamos a la bd */
					$this->bases->insertar_fecha_evento($datos_fecha);
				}else{
					break;
				}
				$fecha++;
			}

			$datos_concepto['id_evento'] = str_replace("'", "´",  $id_evento);
			$datos_concepto['concepto'] = str_replace("'", "´", $_POST['concepto']);
			$datos_concepto['precio'] = $_POST['precio'];
			$this->bases->insertar_concepto_evento($datos_concepto);
			$concepto = 2;
			while(true)
			{
				$var_concepto = "concepto_".$concepto;
				$var_precio = "precio_".$concepto;

				if(!empty($_POST[$var_concepto]))
				{
					$datos_concepto['concepto'] = str_replace("'", "´", $_POST[$var_concepto]);
					$datos_concepto['precio'] = str_replace("'", "´", $_POST[$var_precio]);

					/* Aqui los agregamos a la bd */
					$this->bases->insertar_concepto_evento($datos_concepto);

				}else{
					break;
				}
				$concepto++;
			}

			/* Agregamos el video */
			if(!empty($_FILES["video"]['tmp_name']))
			{
				$filename = $_FILES["video"]["name"]; //Obtenemos el nombre original del archivo
				$source = $_FILES["video"]["tmp_name"]; //Obtenemos un nombre temporal del archivo

				//Cada negocio tendra su propia carpeta de archivos
				$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$id_empresa; //Declaramos un  variable con la ruta donde guardaremos los archivos
				
				$tamanio_bytes =  $_FILES["video"]['size'];
				$tamanio_maximo = 8388608; //8MB

				if($tamanio_bytes <= $tamanio_maximo)
				{
					//Validamos si la ruta de destino existe, en caso de no existir la creamos
					if(!file_exists($directorio)){
						mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
					}

					$dir=opendir($directorio); //Abrimos el directorio de destino
					$prefijo = rand(0,120);
					$nuevo_nombre = str_replace("'", "´", $prefijo.$filename);
					$target_path = $directorio.'/'.$nuevo_nombre; //Indicamos la ruta de destino, así como el nombre del archivo
					
					//Movemos y validamos que el archivo se haya cargado correctamente
					//El primer campo es el origen y el segundo el destino
					
					if(move_uploaded_file($source, $target_path)) {
						//Obtenemos el nombre con el que se guardo 
						$datos_video['id_evento'] = $id_evento;
						$datos_video['foto'] = $nuevo_nombre;
						$datos_video['descripcion'] = NULL;
						$datos_video['tipo'] = "video";
						$this->bases->insertar_foto_video_evento($datos_video);
					}
					
					closedir($dir); //Cerramos el directorio de destino
					
				}

			}

			if(!empty($_FILES["archivo"]['tmp_name']))
			{
				$img_ingresar = 0;
				foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
				{
					//Validamos que el archivo exista
					if($_FILES["archivo"]["name"][$key] && $img_ingresar < 8) {

						$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$id_empresa; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
						$tamanio_maximo = 6291456; //6MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"][$key]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}

							$dir=opendir($directorio); //Abrimos el directorio de destino
							$prefijo = rand(0,120);
							$nuevo_nombre = str_replace("'", "´", $prefijo.$filename);
							$target_path = $directorio.'/'.$nuevo_nombre;
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								$img_ingresar ++;
								$datos_foto['id_evento'] = $id_evento;
								$datos_foto['foto'] = $nuevo_nombre;
								$datos_foto['descripcion'] = NULL;
								$datos_foto['tipo'] = "imagen";
								$this->bases->insertar_foto_video_evento($datos_foto);
							}
							closedir($dir); //Cerramos el directorio de destino							
						}
					}
				}
			}
			
			redirect('/Welcome/Sesion_Eventos');

		}else{
			redirect('/');
		}

	}

	public function Sesion_Mostrar_Evento(){

		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 6;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
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
				redirect('Welcome/Sesion_Eventos');
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

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_mostrar_evento');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
			
		}else{
			redirect('/');	
		}
	}

	public function eliminar_evento()
	{
		if (isset($_SESSION['id_propietario'])){
			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$id_empresa = $informacion_negocio_query[0]->id_empresa;
			 
			$evento = $_POST['id_evento'];
			
			$this->bases->eliminar_fechas_evento($evento);
			$this->bases->eliminar_concepto_evento($evento);
			
			$fotos = $this->bases->obtener_galeria_evento($evento);
			if($fotos != FALSE)
			{
				foreach($fotos as $foto)
				{
					$archivo = $foto->foto;
					$id_archivo = $foto->id_foto;
					unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$id_empresa."/".$archivo);
					$this->bases->eliminar_archivo_evento($id_archivo);
				}
			}

			$video = $this->bases->obtener_video_evento($evento);
			if($video != FALSE)
			{
				foreach($video as $v)
				{
					$archivo = $v->foto;
					$id_archivo = $v->id_foto;
					unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$id_empresa."/".$archivo);
					$this->bases->eliminar_archivo_evento($id_archivo);
				}
			}
			
			$evento_ = $this->bases->obtener_evento_id($evento, $id_empresa);
			/* Eliminamos la imagen principal */
			foreach($evento_ as $event)
			{
				if($event->imagen != "sin_imagen")
				{
					unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$id_empresa."/".$event->imagen);
				}
			}
			
			$this->bases->eliminar_evento($evento);
			redirect('/Welcome/Sesion_Eventos');			

		}else{
			redirect('/');	
		}
	}

	public function Sesion_Modificar_Evento()
	{
		if (isset($_SESSION['id_propietario'])){
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 6.5;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;

			/* Informacion para modificra el evento */
			$id_evento = $_GET['evento'];
			$evento_query = $this->bases->obtener_evento_id($id_evento, $informacion_negocio['id_empresa']);


			if($evento_query == FALSE)
			{
				redirect('/');
			}

			$informacion_negocio['zonas'] = $this->bases->obtener_zonas_puebla();
			$informacion_negocio['evento'] = $evento_query[0];
			$informacion_negocio['fechas'] = $this->bases-> obtener_fechas_evento($id_evento);
			$informacion_negocio['conceptos'] = $this->bases->obtener_conceptos_evento($id_evento);
			$informacion_negocio['fotos_evento'] = $this->bases->obtener_galeria_evento($id_evento);
			$informacion_negocio['video_evento'] = $this->bases->obtener_video_evento($id_evento);

			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal');
			$this->load->view('administrador/sesion_modificar_evento');
			$this->load->view('footer');
			
		}else{
			redirect('/');	
		}
	}

	public function guardar_cambios_eventos()
	{
		if (isset($_SESSION['id_propietario'])){
			
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}
			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */
			$evento = $_POST['evento'];

			/* Modificamos la direccion */
			//Obtenemos el id del estado
			$estado = str_replace("'", "´", $_POST['estado']);
			$id_estado_query = $this->bases->obtener_id_estado($estado);
			if($id_estado_query != FALSE)
			{
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}else{
				//Si no existe el estado lo agregamos y obtenemos el id
				$this->bases->agregar_estado($estado);
				$id_estado_query = $this->bases->obtener_id_estado($estado);
				foreach($id_estado_query as $id_estado_q)
				{
					$id_estado = $id_estado_q->id_estado;
				}
			}
			//Verificamos si existe el municipio
			//Si existe obtenemos su id, sino lo añadimos con el id del estado
			$municipio = str_replace("'", "´", $_POST['municipio']);
			$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
			if($id_municipio_query != FALSE)
			{
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}

			}else{
				$this->bases->agregar_municipio($id_estado, $municipio);
				$id_municipio_query = $this->bases->obtener_id_municipio($id_estado, $municipio);
				foreach($id_municipio_query as $id_municipio_q){
					$id_municipio = $id_municipio_q->id_municipio;
				}
			}
			$direccion_evento['id_municipio'] = $id_municipio;
			$direccion_evento['zona'] = $_POST['zona'];
			$direccion_evento['calle'] = str_replace("'", "´", $_POST['calle']);
			$direccion_evento['colonia'] = str_replace("'", "´", $_POST['colonia']);
			$direccion_evento['vialidad'] = $_POST['vialidad'];
			$direccion_evento['num_exterior'] = str_replace("'", "´", $_POST['num_ext']);
			if($_POST['num_inter'] == ""){
				$direccion_evento['num_interior'] = 0;
			}else{
				$direccion_evento['num_interior'] = str_replace("'", "´", $_POST['num_inter']);
			}
			$direccion_evento['codigo_postal'] = str_replace("'", "´", $_POST['postal_code']);

			$dir_query = $this->bases->obtener_id_dir_evento($evento);
			$id_direccion = $dir_query[0]->id_direccion;
			/* Actualizamos la direccion */
			$this->bases->actualizar_direccion($id_direccion, $direccion_evento);

			/* Actualizamos la informacion principal del evento */
			$porcentaje_imagen = $_POST['porcentaje_imagen'];
			$data['imagen'] = $_POST['nombre_imagen'];
			//No subio imagen
			if($porcentaje_imagen == "sin_imagen")
			{
				if($data['imagen'] != "sin_imagen")
				{
					unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$informacion_negocio['id_empresa']."/".$data['imagen']);
				}
				$data['imagen'] = "sin_imagen";
			}else{
				//Subio una imagen (La subimos al servidor)
				if(!empty($_FILES["archivo_foto"]['tmp_name']))
				{
					$filename = $_FILES["archivo_foto"]["name"]; //Obtenemos el nombre original del archivo
					$source = $_FILES["archivo_foto"]["tmp_name"]; //Obtenemos un nombre temporal del archivo

					//Cada negocio tendra su propia carpeta de archivos
					$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
					
					$tamanio_bytes =  $_FILES["archivo_foto"]['size'];
					$tamanio_maximo = 6291456; //6MB
					$es_img = getimagesize($_FILES["archivo_foto"]["tmp_name"]);

					if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
					{
						if($data['imagen'] != "sin_imagen")
						{
							unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$informacion_negocio['id_empresa']."/".$data['imagen']);
						}
						//Validamos si la ruta de destino existe, en caso de no existir la creamos
						if(!file_exists($directorio)){
							mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
						}

						$dir=opendir($directorio); //Abrimos el directorio de destino
						$target_path = $directorio.'/P'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
						
						//Movemos y validamos que el archivo se haya cargado correctamente
						//El primer campo es el origen y el segundo el destino
						
						if(move_uploaded_file($source, $target_path)) {
							//Obtenemos el nombre con el que se guardo
							$data['imagen'] = str_replace("'", "´", 'P'.$filename);
						}else{
							$data['imagen'] = "sin_imagen";	
						}
						closedir($dir); //Cerramos el directorio de destino
					}

				}
			}

			$data['id_evento'] = $evento;
			$data['nombre'] = str_replace("'", "´", $_POST['nombre_evento']);
			$data['sinopsis'] = str_replace("'", "´",  $_POST['sinopsis']);
			$data['latitud'] = $_POST['latitud'];
			$data['longitud'] = $_POST['longitud'];
			$this->bases->actualizar_evento_datos($data);

			/* Eliminamos y actualizamos las fechas */
			$this->bases->eliminar_fechas_evento($evento);
			$datos_fecha['id_evento'] = $evento;
			$datos_fecha['fecha'] = $_POST['fecha'];
			$datos_fecha['hora'] = $_POST['hora'];
			$this->bases->insertar_fecha_evento($datos_fecha);

			$fecha = 1;
			while(true)
			{
				$var_fecha = "fecha_".$fecha;
				$var_hora = "hora_".$fecha;

				if(!empty($_POST[$var_fecha]))
				{
					$datos_fecha['fecha'] = $_POST[$var_fecha];
					$datos_fecha['hora'] = $_POST[$var_hora];

					/* Aqui los agregamos a la bd */
					$this->bases->insertar_fecha_evento($datos_fecha);
				}else{
					break;
				}
				$fecha++;
			}

			$this->bases->eliminar_concepto_evento($evento);
			$datos_concepto['id_evento'] = str_replace("'", "´",  $evento);
			$datos_concepto['concepto'] = $_POST['concepto'];
			$datos_concepto['precio'] = $_POST['precio'];
			$this->bases->insertar_concepto_evento($datos_concepto);
			$concepto = 1;
			while(true)
			{
				$var_concepto = "concepto_".$concepto;
				$var_precio = "precio_".$concepto;

				if(!empty($_POST[$var_concepto]))
				{
					$datos_concepto['concepto'] = str_replace("'", "´", $_POST[$var_concepto]);
					$datos_concepto['precio'] = str_replace("'", "´", $_POST[$var_precio]);

					/* Aqui los agregamos a la bd */
					$this->bases->insertar_concepto_evento($datos_concepto);

				}else{
					break;
				}
				$concepto++;
			}

			$query_fotos = $this->bases->obtener_galeria_evento($evento);
			/* Actualizamos descripcion de foto */
			if($query_fotos != FALSE)
			{
				for($i=0; $i<sizeof($query_fotos); $i++)
				{
					$name_post = "i_".$query_fotos[$i]->id_foto;
					$valor = str_replace("'", "´", $this->input->post($name_post, TRUE));
					$this->bases->actualizar_descripcion_foto_evento($query_fotos[$i]->id_foto, $valor);
				}
			}

			/* Agregamos las nuevas fotos */
			if(!empty($_FILES["archivo"]['tmp_name']))
			{
				if($query_fotos != FALSE)
				{
					$img_ingresar = count($query_fotos);
				}else{
					$img_ingresar = 0;
				}
				foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
				{
					//Validamos que el archivo exista
					if($_FILES["archivo"]["name"][$key] && $img_ingresar < 8) {

						$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$informacion_negocio['id_empresa']; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
						$tamanio_maximo = 6291456; //6MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"][$key]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}

							$dir=opendir($directorio); //Abrimos el directorio de destino
							$prefijo = md5(time());
							$nuevo_nombre = str_replace("'", "´", $prefijo.$filename);
							$target_path = $directorio.'/'.$nuevo_nombre;
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								$img_ingresar ++;
								$datos_foto['id_evento'] = $evento;
								$datos_foto['foto'] = $nuevo_nombre;
								$datos_foto['descripcion'] = NULL;
								$datos_foto['tipo'] = "imagen";
								$this->bases->insertar_foto_video_evento($datos_foto);
							}
							closedir($dir); //Cerramos el directorio de destino							
						}
					}
				}
			}

			/* Agregamos el nuevo video */
			if(!empty($_FILES["video"]['tmp_name']))
			{
				$filename = $_FILES["video"]["name"]; //Obtenemos el nombre original del archivo
				$source = $_FILES["video"]["tmp_name"]; //Obtenemos un nombre temporal del archivo

				//Cada negocio tendra su propia carpeta de archivos
				$directorio = $this->config->item('url_archivos_ubicalos').'EventosEmpresa/'.$informacion_negocio['id_empresa']; //Declaramos un  variable con la ruta donde guardaremos los archivos
				
				$tamanio_bytes =  $_FILES["video"]['size'];
				$tamanio_maximo = 8388608; //8MB

				if($tamanio_bytes <= $tamanio_maximo)
				{
					//Validamos si la ruta de destino existe, en caso de no existir la creamos
					if(!file_exists($directorio)){
						mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
					}

					$dir=opendir($directorio); //Abrimos el directorio de destino
					$prefijo = md5(time());
					$nuevo_nombre = str_replace("'", "´", $prefijo.$filename);
					$target_path = $directorio.'/'.$nuevo_nombre; //Indicamos la ruta de destino, así como el nombre del archivo
					
					//Movemos y validamos que el archivo se haya cargado correctamente
					//El primer campo es el origen y el segundo el destino
					
					if(move_uploaded_file($source, $target_path)) {
						//Obtenemos el nombre con el que se guardo 
						$datos_video['id_evento'] = $evento;
						$datos_video['foto'] = $nuevo_nombre;
						$datos_video['descripcion'] = NULL;
						$datos_video['tipo'] = "video";
						$this->bases->insertar_foto_video_evento($datos_video);
					}
					
					closedir($dir); //Cerramos el directorio de destino
					
				}

			}
			redirect('/Welcome/Sesion_Eventos');

		}else{
			redirect('/');	
		}
	}

	public function eliminar_archivo_evento()
	{
		if (isset($_SESSION['id_propietario'])){
			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

			date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			$id_empresa = $informacion_negocio_query[0]->id_empresa;
			/* Eliminamos de la carpeta */
			unlink($this->config->item('url_archivos_ubicalos')."EventosEmpresa/".$id_empresa."/".$_POST['nombre']);
			$this->bases->eliminar_archivo_evento($_POST['id_archivo']);
			header('Location:' . getenv('HTTP_REFERER'));
		}else{
			redirect('/');	
		}
	}
	/*FIN EVENTOS */
	/*BLOGS*/
	public function Sesion_blogs(){
		if (isset($_SESSION['id_propietario'])){
			
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
			
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 7;
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			
			$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
			$informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);
			
			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['nombre_negocio'] = $informacion_negocio_q->nombre;
				$informacion_negocio['foto_perfil'] = $informacion_negocio_q->foto_perfil;
			}
			/*Obtiene la información de blogs  */
			$informacion_negocio['blogs']  = $this->bases->obtener_blogs($informacion_negocio['id_empresa']);

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_blogs');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}

	public function Sesion_Agregar_Blog(){
		if (isset($_SESSION['id_propietario'])){

			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 7;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/***/ 
			
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;			

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_agregar_blog');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}

	}

	public function Anadir_Blog(){
		if (isset($_SESSION['id_propietario'])){

			/*Obtenemos valores */
			$data['titulo'] = str_replace("'", "´", $this->input->post('titulo',TRUE));
			$data['subtitulo'] = str_replace("'", "´", $this->input->post('subtitulo',TRUE));
			$data['blog'] = str_replace("'", "´", $this->input->post('blog',TRUE));

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */
			$data['fecha'] = strftime("%Y-%m-%d %H:%M:%S");

			$informacion_negocio_query = $this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$data['id_empresa'] = $informacion_negocio_q->id_empresa;
			}


			$imagen = $this->input->post('imagen',TRUE);
			if($imagen == "imagen"){	
				if(!empty($_FILES))
				{
					$filename = $_FILES["foto"]["name"]; //Obtenemos el nombre original del archivo
					$source = $_FILES["foto"]["tmp_name"]; //Obtenemos un nombre temporal del archivo
					
					//Cada negocio tendra su propia carpeta de archivos
					$directorio = $this->config->item('url_archivos_ubicalos').'FotosBlogEmpresa/'.$data['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
					
					$tamanio_bytes =  $_FILES["foto"]['size'];
					$tamanio_maximo = 6291456; //4MB
					$es_img = getimagesize($_FILES["foto"]["tmp_name"]);
											
					if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
					{
						//Validamos si la ruta de destino existe, en caso de no existir la creamos
						if(!file_exists($directorio)){
							mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
						}
						$dir=opendir($directorio); //Abrimos el directorio de destino
						$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
						
						//Movemos y validamos que el archivo se haya cargado correctamente
						//El primer campo es el origen y el segundo el destino
						
						if(move_uploaded_file($source, $target_path)) {
							//El archivo se guardo correctamente, ahora podemos guardar en la bd
							$data['imagen'] = str_replace("'", "´", $filename);								
						closedir($dir); //Cerramos el directorio de destino
						}
					}
				}
			}else{
				$data['imagen'] = NULL;
			}
			//agregamos blog a la BD
			$this->bases->agregar_blog($data);
			
			//Obtenemos id del blog 
			$id_blog_query = $this->bases->obtener_ultimo_blog($data['id_empresa']);
			foreach($id_blog_query as $id_blog_q)
			{
				$id_blog = $id_blog_q->id_blog;
				break;
			}
			if(!empty($_FILES["archivo"]['tmp_name']))
			{
				foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
				{
					//Validamos que el archivo exista
					if($_FILES["archivo"]["name"][$key]) {
						$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'FotosBlogEmpresa/'.$data['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
						$tamanio_maximo = 6291456; //4MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"][$key]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}
							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
							
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								$datos_foto['id_blog'] = $id_blog;
								$datos_foto['foto'] = str_replace("'", "´",$filename);
								$datos_foto['descripcion'] = NULL;
								$this->bases->agregar_foto_blog($datos_foto);
							}
							
							closedir($dir); //Cerramos el directorio de destino
						}
					}
				}
			}
			redirect('Welcome/Sesion_blogs');
		}else{
			redirect('/');
		}
	}	
	
	public function Eliminar_Blog(){
		if(isset($_SESSION['id_propietario'])){
			$id_blog = $this->input->post('id_blog', TRUE);
			$blog_query = $this->bases->obtener_blog($id_blog);
			foreach ($blog_query as $blog_q){
				$blog = $blog_q;
			}

			//obtenemos las fotos de galeria blog
			$galeria_blog_query = $this->bases->obtener_galeria_blog($id_blog);
			foreach ($galeria_blog_query as $galeria_blog_q) { 
				unlink($this->config->item('url_archivos_ubicalos')."FotosBlogEmpresa/".$blog->id_empresa."/".$galeria_blog_q->foto);
			}

			if(!empty($blog->foto)){
				if(unlink($this->config->item('url_archivos_ubicalos')."FotosBlogEmpresa/".$blog->id_empresa."/".$galeria_blog_q->imagen)){
					$this->bases->eliminar_blog($id_blog);
				}
			}else{
				$this->bases->eliminar_blog($id_blog);
			}			
			redirect('Welcome/Sesion_blogs');			
		}else{
			redirect('/');
		}
	}

	public function Sesion_Blog_Editar(){
		if (isset($_SESSION['id_propietario'])){
			/*Obtiene la información de blogs  */
			$id_blog = $_GET['id_blog'];
			$blog_query  = $this->bases->obtener_blog($id_blog);
			foreach ($blog_query as $blog_q){
			  $informacion_negocio['blog'] = $blog_q;
			  break;
			}
			
			$informacion_negocio['galeria_blog'] = $this->bases->obtener_galeria_blog($id_blog);
			$informacion_negocio['total_img']	 = $this->bases->num_fotos_blog($id_blog);
			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}
	
			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 7;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/***/ 
			
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;			
			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_blog_modificar');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}	

	public function modifica_blog(){
		if (isset($_SESSION['id_propietario'])){

			$id_blog = $this->input->post('id_blog', TRUE);
			$data['titulo'] = $this->input->post('titulo', TRUE);
			$data['subtitulo'] = $this->input->post('subtitulo', TRUE);
			$data['blog'] = $this->input->post('blog', TRUE);
			$data['id_blog'] = $id_blog;

			/* Guardamos su ultima actualizacion */
			$sucursales_query = $this->bases->obtener_sucursal_matriz($_SESSION['id_propietario']);
			foreach($sucursales_query as $sucursales)
			{
				$id_matriz = $sucursales->id_sucursal;
				break; /* Ponemos el break para que solo obtenga el id de la matriz */
			}

            date_default_timezone_set('America/Mexico_City');
			setlocale(LC_TIME, "ES")."<br>";
			$this->bases->update_actualizacion_sucursal($id_matriz, strftime("%d-%h-%y"));
			/* **************************************************************************** */
			$data['fecha'] = strftime("%Y-%m-%d %H:%M:%S");

			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}

			/* Actualiza descripción de fotos */
            $galeria_todos_query = $this->bases->obtener_galeria_blog($id_blog);
			if($galeria_todos_query != FALSE)
			{
				foreach($galeria_todos_query as $galeria_todos)
				{
					$name_post = "i_".$galeria_todos->id_foto;
					$valor = str_replace("'", "´", $this->input->post($name_post, TRUE));
					$galeria_todos->id_foto;
					$this->bases->actualizar_descripcion_galeria_blog($galeria_todos->id_foto, $valor);
				}
			}
			/* FIN Actualiza descripción de fotos */
		

			$imagen = $this->input->post('imagen',TRUE);
			$foto_principal = $this->bases->obtener_blog($id_blog);
			
			if($imagen == "imagen"){				
				if(!empty($source = $_FILES["foto"]["tmp_name"]))
				{
					$filename = $_FILES["foto"]["name"]; //Obtenemos el nombre original del archivo
					$source = $_FILES["foto"]["tmp_name"]; //Obtenemos un nombre temporal del archivo
					
					//Cada negocio tendra su propia carpeta de archivos
					$directorio = $this->config->item('url_archivos_ubicalos').'FotosBlogEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
					
					$tamanio_bytes =  $_FILES["foto"]['size'];
					$tamanio_maximo = 6291456; //4MB
					$es_img = getimagesize($_FILES["foto"]["tmp_name"]);
											
					if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
					{
						//Validamos si la ruta de destino existe, en caso de no existir la creamos
						if(!file_exists($directorio)){
							mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
						}
						$dir=opendir($directorio); //Abrimos el directorio de destino
						$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
						
						//Movemos y validamos que el archivo se haya cargado correctamente
						//El primer campo es el origen y el segundo el destino
						
						if(move_uploaded_file($source, $target_path)) {
							//El archivo se guardo correctamente, ahora podemos guardar en la bd
							$data['imagen'] = str_replace("'", "´", $filename);
						}
						
						closedir($dir); //Cerramos el directorio de destino
						
					}
				}else{
					$data['imagen'] = $foto_principal[0]->imagen;
				}
			}else{
				$data['imagen'] = NULL;
			}
			//Actualiza blog a la BD
			$this->bases->update_blog($data);
			
			if(!empty($_FILES["archivo"]['tmp_name']))
			{
				foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
				{
					//Validamos que el archivo exista
					if($_FILES["archivo"]["name"][$key]) {
						$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
						$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
						
						//Cada negocio tendra su propia carpeta de archivos
						$directorio = $this->config->item('url_archivos_ubicalos').'FotosBlogEmpresa/'.$informacion_negocio['id_empresa'].'/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
						
						$tamanio_bytes =  $_FILES["archivo"]['size'][$key];
						$tamanio_maximo = 6291456; //4MB
						$es_img = getimagesize($_FILES["archivo"]["tmp_name"][$key]);
												
						if($tamanio_bytes <= $tamanio_maximo && $es_img == TRUE)
						{
							//Validamos si la ruta de destino existe, en caso de no existir la creamos
							if(!file_exists($directorio)){
								mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
							}
							$dir=opendir($directorio); //Abrimos el directorio de destino
							$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
							
							//Movemos y validamos que el archivo se haya cargado correctamente
							//El primer campo es el origen y el segundo el destino
							
							if(move_uploaded_file($source, $target_path)) {
								$datos_foto['id_blog'] = $id_blog;
								$datos_foto['foto'] = str_replace("'", "´",$filename);
								$datos_foto['descripcion'] = NULL;
								$this->bases->agregar_foto_blog($datos_foto);
							}
							
							closedir($dir); //Cerramos el directorio de destino
						}
					}
				}
			}
			redirect('Welcome/Sesion_blogs');
		}else{
			redirect('/');
		}
	
	}

	public function eliminar_foto_blog(){
		if (isset($_SESSION['id_propietario'])){
			$id_foto = $this->input->post('id_archivo', TRUE);
			$foto = str_replace("´", "'", $this->input->post('nombre', TRUE));
			
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			foreach ($informacion_negocio_query as $informacion_negocio_q)
			{
				$informacion_negocio['id_empresa'] = $informacion_negocio_q->id_empresa;
			}
		
			unlink($this->config->item('url_archivos_ubicalos')."FotosBlogEmpresa/".$informacion_negocio['id_empresa']."/".$foto);
			$this->bases->elimina_foto_blog($id_foto);
			
			redirect('Welcome/Sesion_blogs');
		}else{
			redirect('/');
		}
	}

	public function Sesion_VerMas_Blog(){
		if (isset($_SESSION['id_propietario'])){

			/*Obtiene la información de blogs  */
			$id_blog = $_GET['id_blog'];
			$blog_query  = $this->bases->obtener_blog($id_blog);
			foreach ($blog_query as $blog_q){
			  $informacion_negocio['blog'] = $blog_q;
			  break;
			}
			
			$informacion_negocio['galeria_blog'] = $this->bases->obtener_galeria_blog($id_blog);
			$informacion_negocio['total_img']	 = $this->bases->num_fotos_blog($id_blog);
			
			/*Información para la matriz*/
			$informacion_negocio_query =$this->bases->obtener_informacion_negocio($_SESSION['id_propietario']);
			//Si no pago la matriz lo redireccionamos al pago
			$pago_realizado_query = $this->bases->pago_realizado($informacion_negocio_query[0]->id_empresa);
			if($pago_realizado_query == FALSE)
			{
				redirect('/Welcome/Pago');
			}

			$informacion_negocio['id_empresa'] = $informacion_negocio_query[0]->id_empresa;
			$informacion_negocio['position_nav'] = 7.1;	
			$informacion_negocio['nombre_negocio'] = $informacion_negocio_query[0]->nombre;
			/***/ 
			
			$informacion_negocio['foto_perfil'] = $this->bases->obtener_foto_perfil($informacion_negocio['id_empresa']);
            $informacion_negocio['nombre_empresa'] = $this->bases->obtener_nombre_empresa($informacion_negocio['id_empresa']);

			/* */
			$categorias_query = $this->bases->obtener_categorias_todas();
			$secciones = array();
			foreach ($categorias_query as $categorias_q){
				$secciones[$categorias_q->id_categorias] =  $this->bases->obtener_subcategorias($categorias_q->id_categorias);
			} 
			$informacion_negocio['subcategorias'] 	= $secciones;

			$this->load->view('nav-lateral',$informacion_negocio);
			$this->load->view('administrador/informacion_negocio_principal',$informacion_negocio);
			$this->load->view('administrador/sesion_blog_ver_mas');
			$this->load->view('administrador/publicidad');
			$this->load->view('footer');
		}else{
			redirect('/');	
		}
	}
	/*FIN BLOGS */
	public function Login()
	{
		if (isset($_SESSION['id_propietario']) )
		{
			redirect('/Welcome/Sesion');
		}else{
			if(!empty($_GET['error']))
			{
				$datos['error'] = $_GET['error'];
				$this->load->view('login',$datos);
			}else{
				$this->load->view('login');
			}
		}
	}

	public function iniciar_sesion()
	{
		$cuenta = str_replace("'", "´", $this->input->post('cuenta', TRUE) );
		$password = str_replace("'", "´", $this->input->post('password', TRUE) );
		$password = md5($password);
		$id_sesion_query = $this->bases->iniciar_sesion($cuenta, $password);
		
		if($id_sesion_query != FALSE)
		{
			foreach($id_sesion_query as $id_sesion_q)
			{
				$id_propietario = $id_sesion_q->id_propietario;
			}
			$newdata = array(
				'id_propietario'  => $id_propietario,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($newdata);
			redirect('/Welcome/Sesion');
		}else{
			redirect('/Welcome/Login?error=1');
		}
		
	}

	public function cerrar_session(){
		$this->session->unset_userdata('id_propietario');
		$this->session->sess_destroy();
		redirect('/','location');
	}

	public function Privacidad()
	{
		$this->load->view('administrador/header_registro');
		$this->load->view('Privacidad');
		$this->load->view('administrador/footer_registro');
	}

	public function Condiciones()
	{
		$this->load->view('administrador/header_registro');
		$this->load->view('Condiciones');
		$this->load->view('administrador/footer_registro');
	}

}
