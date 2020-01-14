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

}
?>
