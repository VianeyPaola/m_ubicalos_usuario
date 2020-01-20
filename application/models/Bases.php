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

    public function obtener_subcategorias($id_categoria)
    {
      $sql = "SELECT id_subcategoria , subcategoria FROM subcategoria WHERE id_categoria LIKE '".$id_categoria."'";
      $query = $this->db->query($sql);

      return $query->result();
    }

    public function obtener_secciones($id_subcategoria)
    {
      $sql = "SELECT id_secciones,  secciones FROM secciones WHERE id_subcategoria LIKE '".$id_subcategoria."'";
      $query = $this->db->query($sql);

      return $query->result();
    }

		/* Fin consultas categorias, subcategorias y secciones */
		
		/* Funciones de vista principal */

		public function obtener_categorias_rand()
		{
			$sql = "SELECT `id_categorias`, `categoria` FROM `categorias` WHERE 1 ORDER BY RAND()";
			$query = $this->db->query($sql);

			return $query->result();
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


		/* Fin consultas vista principal */

}
?>
