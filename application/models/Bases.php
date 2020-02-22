<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class bases extends CI_Model {

  function __construct()
    {parent::__construct();}
  
    /* Inicio consultas categorias, subcategorias y secciones */

    public function obtener_categorias_todas()
    {
      $sql = "SELECT * FROM categorias WHERE 1 ";
      $query = $this->db->query($sql);

      return $query->result();
    }

	public function get_categoria($id_categoria)
	{
		$sql = "SELECT `categoria` FROM `categorias` WHERE `id_categorias` LIKE '".$id_categoria."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

    public function obtener_subcategorias($id_categoria)
    {
      $sql = "SELECT id_subcategoria , subcategoria FROM subcategoria WHERE id_categoria LIKE '".$id_categoria."'";
      $query = $this->db->query($sql);
      return $query->result();
	}
	
	public function obtener_nombre_subcategoria($id_subcategoria){
		$sql = "SELECT subcategoria FROM subcategoria WHERE id_subcategoria LIKE '".$id_subcategoria."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

    public function obtener_secciones($id_subcategoria)
    {
      $sql = "SELECT id_secciones,  secciones FROM secciones WHERE id_subcategoria LIKE '".$id_subcategoria."'";
      $query = $this->db->query($sql);

      return $query->result();
    }

	public function obtener_nombre_seccion($id_secciones)
    {
		$sql = "SELECT secciones FROM secciones WHERE id_secciones LIKE '".$id_secciones."'";
		$query = $this->db->query($sql);
			
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
    }

	/* Fin consultas categorias, subcategorias y secciones */
	
	/* Funciones de vista principal */

	public function obtener_categorias_rand()
	{
		$sql = "SELECT `id_categorias`, `categoria` FROM `categorias` WHERE 1 ORDER BY RAND()";
		$query = $this->db->query($sql);

		return $query->result();
	}

	public function get_sucursales_categorias_lat_long($id_categoria,$latUser,$longUser)
	{
		$sql = "SELECT e.id_empresa, e.nombre, e.foto_perfil, suc.id_sucursal, z.zona, sub.subcategoria, s.secciones, (6371 * ACOS( 
			SIN(RADIANS(suc.latitud)) * SIN(RADIANS(".$latUser.")) 
			+ COS(RADIANS(suc.longitud - (".$longUser."))) * COS(RADIANS(suc.latitud)) 
			* COS(RADIANS(".$latUser."))
			)
			) AS distance FROM empresa as e inner join categoria as c inner join subcategoria as sub inner join secciones as s inner join sucursal as suc inner join direccion as dir inner join zona as z on c.id_secciones = s.id_secciones and s.id_subcategoria = sub.id_subcategoria and suc.id_empresa = e.id_empresa and suc.id_direccion = dir.id_direccion and dir.id_zona = z.id_zona and c.id_empresa = e.id_empresa WHERE sub.id_categoria LIKE '".$id_categoria."' AND c.num_subcategoria LIKE '1' AND e.verificacion LIKE 'TRUE' ORDER BY distance LIMIT 8";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_sucursales_categorias($id_categoria)
	{
		$sql = "SELECT e.id_empresa, e.nombre, e.foto_perfil, suc.id_sucursal, z.zona, sub.subcategoria, s.secciones FROM empresa as e inner join categoria as c inner join subcategoria as sub inner join secciones as s inner join sucursal as suc inner join direccion as dir inner join zona as z on c.id_secciones = s.id_secciones and s.id_subcategoria = sub.id_subcategoria and suc.id_empresa = e.id_empresa and suc.id_direccion = dir.id_direccion and dir.id_zona = z.id_zona and c.id_empresa = e.id_empresa WHERE sub.id_categoria LIKE '".$id_categoria."' AND c.num_subcategoria LIKE '1' AND e.verificacion LIKE 'TRUE' ORDER BY RAND() LIMIT 8";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_Imagen_Empresa($id_sucursal)
	{
		$sql = "SELECT g.nombre FROM galeria as g inner join sucursal as s on g.id_empresa = s.id_empresa WHERE s.id_sucursal LIKE '".$id_sucursal."' AND g.tipo LIKE 'imagen' ORDER BY RAND() LIMIT 1";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_Publicidad_Categoria_Home($id_categoria)
	{
		$sql = "SELECT e.id_empresa, e.nombre, e.foto_perfil, suc.id_sucursal, z.zona, sub.subcategoria, s.secciones, p.id_publicidad FROM empresa as e inner join categoria as c inner join subcategoria as sub inner join secciones as s inner join publicidad_categoria as p inner join sucursal as suc inner join direccion as dir inner join zona as z  on c.id_secciones = s.id_secciones and s.id_subcategoria = sub.id_subcategoria and suc.id_empresa = e.id_empresa and suc.id_direccion = dir.id_direccion and dir.id_zona = z.id_zona and c.id_empresa = e.id_empresa and p.id_empresa = e.id_empresa WHERE sub.id_categoria LIKE '".$id_categoria."' AND c.num_subcategoria LIKE '1' AND e.verificacion LIKE 'TRUE' ORDER BY RAND() LIMIT 1";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}

	}

	public function get_Img_Publicidad_Categoria($id_publicidad)
	{
		$sql = "SELECT g.id_empresa, g.nombre FROM publicidad_categoria_imagenes as p inner join galeria as g ON p.id_imagen = g.id_imagen WHERE p.id_publicidad LIKE '".$id_publicidad."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_publicidad_banner()
	{
		$sql = "SELECT p.id_empresa, p.tipo_banner, p.logo, p.foto, p.color_div, p.color_boton FROM publicidad_banner as p inner join empresa as e on p.id_empresa = e.id_empresa WHERE p.status LIKE 'TRUE' AND e.verificacion LIKE 'TRUE' ORDER BY RAND() LIMIT 1";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_publicidad_tarjeta_ch()
	{
		$sql = "SELECT e.foto_perfil, e.nombre, p.id_empresa , z.zona FROM publicidad_tarjeta_ch as p inner join empresa as e inner join zona as z on p.id_empresa = e.id_empresa and p.id_zona = z.id_zona  WHERE p.status = 'TRUE' ORDER BY RAND()";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	/* Fin consultas vista principal */

	public function obtener_zonas_puebla()
    {
      $sql = "SELECT * FROM zona WHERE estado LIKE 1";
      $query = $this->db->query($sql);

      return $query->result();
	}
	
	public function obtener_zona($id_zona){
		$sql = "SELECT zona FROM `zona` WHERE `id_zona` = '".$id_zona."'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function obtener_horarios($id_sucursal)
    {
      $sql = "SELECT * FROM horario WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

	/* */
	public function get_sucursales_subcategorias_lat_long($id_subcategoria,$latUser,$longUser,$page)
	{
		$sql = "SELECT DISTINCT e.id_empresa, e.nombre, e.foto_perfil, suc.id_sucursal, suc.actualizacion, z.zona, sub.subcategoria, s.secciones, dir.calle, dir.tipo_vialidad, dir.num_ext, dir.num_inter, dir.cp, dir.colonia, (6371 * ACOS( SIN(RADIANS(suc.latitud)) * SIN(RADIANS(".$latUser.")) + COS(RADIANS(suc.longitud - (".$longUser."))) * COS(RADIANS(suc.latitud)) * COS(RADIANS(".$latUser.")) ) ) AS distance FROM empresa as e inner join categoria as c inner join subcategoria as sub inner join secciones as s inner join sucursal as suc inner join direccion as dir inner join zona as z on c.id_secciones = s.id_secciones and s.id_subcategoria = sub.id_subcategoria and suc.id_empresa = e.id_empresa and suc.id_direccion = dir.id_direccion and dir.id_zona = z.id_zona and c.id_empresa = e.id_empresa WHERE sub.id_subcategoria LIKE '".$id_subcategoria."' AND e.verificacion LIKE 'TRUE' ORDER BY distance LIMIT 10 offset ".$page."";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

	public function get_sucursales_categorias_inicio_lat_long($id_categoria,$latUser,$longUser)
	{
		$sql = "SELECT e.id_empresa, e.nombre, e.foto_perfil, suc.id_sucursal, suc.actualizacion, z.zona, sub.subcategoria, s.secciones, dir.calle, dir.tipo_vialidad, dir.num_ext, dir.num_inter, dir.cp, dir.colonia, (6371 * ACOS( 
			SIN(RADIANS(suc.latitud)) * SIN(RADIANS(".$latUser.")) 
			+ COS(RADIANS(suc.longitud - (".$longUser."))) * COS(RADIANS(suc.latitud)) 
			* COS(RADIANS(".$latUser."))
			)
			) AS distance FROM empresa as e inner join categoria as c inner join subcategoria as sub inner join secciones as s inner join sucursal as suc inner join direccion as dir inner join zona as z on c.id_secciones = s.id_secciones and s.id_subcategoria = sub.id_subcategoria and suc.id_empresa = e.id_empresa and suc.id_direccion = dir.id_direccion and dir.id_zona = z.id_zona and c.id_empresa = e.id_empresa WHERE sub.id_categoria LIKE '".$id_categoria."' AND c.num_subcategoria LIKE '1' AND e.verificacion LIKE 'TRUE' ORDER BY distance";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return FALSE;
		}
	}

}
?>
