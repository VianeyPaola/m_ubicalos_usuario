<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class bases extends CI_Model {

  function __construct()
    {parent::__construct();}

    /* Inicio consultas para el propietario */
    public function propietario_existe($correo_celular)
    {
      $sql = "SELECT id_propietario FROM propietario WHERE correo LIKE '".$correo_celular."'";
      $query = $this->db->query($sql);

      if($query->num_rows() > 0)
        return TRUE;
      else
        return FALSE;
    }

    public function obtener_propietario($id_propietario)
    {
      $sql = "SELECT * FROM propietario WHERE id_propietario LIKE '".$id_propietario."'";
      $query = $this->db->query($sql);

      if($query->num_rows() > 0)
        return $query->result();
      else
        return FALSE;
    }
    public function agregar_propietario($data)
    {
      $sql = "INSERT INTO `propietario`(`id_propietario`, `nombre`, `apellidos`, `celular`, `nacimiento`, `sexo`, `correo`, `contraseña`, `codigo`, `codigo_confirmado`) 
      VALUES (NULL, '".$data['nombre']."','".$data['apellidos']."','".$data['num_celular']."','".$data['fecha_nacimiento']."','".$data['sexo']."','".$data['correo']."','".$data['contrasenia']."','".$data['codigo']."','FALSE')";

      $this->db->query($sql);
      
    }

    public function codigo_confirmado($id_propietario)
    {
      $sql = "SELECT * FROM `propietario` WHERE `codigo_confirmado` LIKE 'FALSE' AND  `id_propietario` LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return null;
      }
    }

    public function obtener_codigo($correo)
    {
      $sql = "SELECT codigo FROM propietario WHERE correo LIKE '".$correo."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return null;
      }
    }

    public function modificar_codigo($correo_celular, $codigo)
    {
      $sql = "UPDATE `propietario` SET codigo = '".$codigo."',  codigo_confirmado = 'FALSE'  WHERE correo LIKE '".$correo_celular."'";
      $this->db->query($sql);
    }

    public function confirmar_codigo($id_propietario)
    {
      $sql = "UPDATE propietario SET codigo_confirmado = 'TRUE' WHERE id_propietario LIKE '".$id_propietario."' ";
      $this->db->query($sql);
    }

    public function obtener_id_propietario($correo_celular)
    {
      $sql = "SELECT id_propietario FROM propietario WHERE correo LIKE '".$correo_celular."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function actualizar_contrasenia($id_propietario, $contrasenia)
    { 
      $sql = "UPDATE propietario SET contraseña = '".$contrasenia."' WHERE id_propietario LIKE '".$id_propietario."' ";
      $this->db->query($sql);
    }

    public function obtener_telefono($id_propietario)
    {
      $sql = "SELECT celular FROM propietario WHERE id_propietario LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_nombre_propietario($id_propietario)
    {
      $sql = "SELECT nombre FROM propietario WHERE id_propietario LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }


    public function borrar_propietario($id_propietario)
    {
      $sql = "DELETE FROM propietario WHERE id_propietario LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_propietario($data)
    {
      $sql = "UPDATE `propietario` SET `nombre`='".$data['nombre']."', `apellidos`='".$data['apellidos']."',`celular`= '".$data['num_celular']."',`nacimiento`='".$data['fecha_nacimiento']."',`sexo`='".$data['sexo']."' WHERE `id_propietario` LIKE '".$data['id_propietario']."' ";
      $query = $this->db->query($sql);
    }

    public function obtener_contrasenia($id_propietario)
    {
      $sql = "SELECT `contraseña` FROM `propietario` WHERE `id_propietario` LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function cambiar_correo($data)
    {
      $sql = "UPDATE `propietario` SET  `correo`='".$data['correo']."' WHERE `id_propietario` LIKE '".$data['id_propietario']."' ";
      $query = $this->db->query($sql);
    }


    /* Fin consultas para el propietario */

/* Consultas para promociones */
    public function agregar_promocion($data){
      $sql = "INSERT INTO `promociones` (`id_promociones`, `id_empresa`, `foto`, `porcentaje`, `titulopromo`, `descripcion`, `fecha_ini`, `fecha_fin`) VALUES (NULL,'".$data['id_empresa']."','".$data['foto']."', '".$data['porcentaje']."','".$data['titulo_promocion']."' , '".$data['descripcion']."', '".$data['inicia']."', '".$data['finaliza']."')";
      $query = $this->db->query($sql);
    }

    public function agregar_promocion_sucursal($data){
      $sql = "INSERT INTO `sucursal_promo` (`id_sucursal`, `id_promociones`) VALUES ('".$data['id_sucursal']."', '".$data['id_promocion']."')";
      $query = $this->db->query($sql);
    }

    public function obtener_ultima_promo($id_empresa){
      $sql =" SELECT id_promociones FROM promociones WHERE id_empresa LIKE '".$id_empresa."' ORDER BY `id_promociones` DESC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function modificar_promocion($data){
      $sql = "UPDATE `promociones` SET `titulopromo` = '".$data['titulo_promocion']."',`descripcion` = '".$data['descripcion']."', `fecha_ini` = '".$data['inicia']."',`fecha_fin` = '".$data['finaliza']."', `foto` = '".$data['foto']."',`porcentaje` = '".$data['porcentaje']."' WHERE `promociones`.`id_promociones` LIKE '".$data['id_promocion']."'";
      $query = $this->db->query($sql);
    }

    public function eliminar_promocion_sucursal($id_promocion){
      $sql = "DELETE FROM `sucursal_promo` WHERE `id_promociones`= '".$id_promocion."'";
      $query = $this->db->query($sql);
    }
    public function eliminar_promocion($id_promocion){
      $sql = "DELETE FROM `promociones` WHERE `id_promociones` = '".$id_promocion."'";
      $query = $this->db->query($sql);
    }


/* Fin consultas promociones */

    /* Inicio de consulta para obtener las zonas */
    public function obtener_zonas_puebla()
    {
      $sql = "SELECT * FROM zona WHERE estado LIKE 1";
      $query = $this->db->query($sql);

      return $query->result();
    }
    /*  */

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

    /* Consultas para el registro de negocio */
    public function insertar_empresa($data)
    {
      $sql = "INSERT INTO `empresa`(`id_empresa`, `nombre`, `info_general`, `servicios_productos`, `ademas`, `foto_perfil`, `id_propietario`, `total_subcategorias`,`pago_matriz`, `pago_sucursal`, `tipo_pago_matriz`, `fecha_pago_matriz`, `tipo_pago_sucursal`, `fecha_pago_sucursal`, `fecha_inicio`, `verificacion`, `calificacion` ) VALUES (NULL,'".$data['nombre_negocio']."',NULL,NULL,NULL,NULL,'".$data['id_propietario']."','".$data['num_subcategorias']."','FALSE','FALSE','oxxo',CURDATE(),'sin_suc',CURDATE(), CURDATE(),'FALSE', 0)";
      $this->db->query($sql);
    }

    public function obtener_id_empresa($id_propietario)
    {
      $sql = "SELECT id_empresa FROM empresa WHERE id_propietario LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      return $query->result();
    }

    public function obtener_id_estado($estado)
    {
      $sql = "SELECT id_estado FROM estado WHERE estado LIKE '".$estado."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function agregar_estado($estado)
    {
      $sql = "INSERT INTO `estado`(`id_estado`, `estado`) VALUES (NULL,'".$estado."')";
      $this->db->query($sql);
    }

    public function obtener_id_municipio($id_estado, $municipio)
    {
      $sql = "SELECT id_municipio FROM municipio WHERE  id_estado LIKE '".$id_estado."' AND municipio LIKE '".$municipio."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function agregar_municipio($id_estado, $municipio)
    {
      $sql = "INSERT INTO `municipio`(`id_municipio`, `id_estado`, `municipio`) VALUES (NULL,'".$id_estado."','".$municipio."')";
      $this->db->query($sql);
    }

    public function agregar_direccion($data)
    {
      $sql = "INSERT INTO `direccion`(`id_direccion`, `id_municipio`, `id_zona`, `calle`, `colonia`, `tipo_vialidad`, `num_ext`, `num_inter`, `cp`) VALUES (NULL,'".$data['id_municipio']."','".$data['zona']."','".$data['calle']."','".$data['colonia']."','".$data['vialidad']."','".$data['num_exterior']."','".$data['num_interior']."','".$data['codigo_postal']."')";
      $this->db->query($sql);
    }

    public function obtener_id_direccion($data)
    {
      $sql = "SELECT id_direccion FROM direccion WHERE id_municipio LIKE '".$data['id_municipio']."' AND id_zona LIKE '".$data['zona']."' AND calle LIKE '".$data['calle']."' AND colonia LIKE '".$data['colonia']."' AND tipo_vialidad LIKE '".$data['vialidad']."' AND num_ext LIKE '".$data['num_exterior']."' AND num_inter LIKE '".$data['num_interior']."' AND cp LIKE '".$data['codigo_postal']."' ";
      $query = $this->db->query($sql);
      return $query->result();
		}
		
		public function agregar_sucursal($id_empresa, $id_direccion){
			$sql = "INSERT INTO `sucursal`(`id_sucursal`, `id_empresa`, `id_direccion`,`actualizacion`, `latitud`, `longitud`) VALUES (NULL,'".$id_empresa."','".$id_direccion."', CURDATE(), NULL, NULL)";
      $this->db->query($sql);
		}

    public function agregar_sucursal_($id_empresa, $id_direccion, $latitud, $longitud)
    {
      $sql = "INSERT INTO `sucursal`(`id_sucursal`, `id_empresa`, `id_direccion`,`actualizacion`, `latitud`, `longitud`) VALUES (NULL,'".$id_empresa."','".$id_direccion."', CURDATE(), '".$latitud."', '".$longitud."')";
      $this->db->query($sql);
    }

    public function agregar_seccion($id_seccion, $id_empresa, $num_categoria)
    {
      $sql = "INSERT INTO `categoria`(`id_secciones`, `id_empresa`, `num_subcategoria`) VALUES ('".$id_seccion."','".$id_empresa."','".$num_categoria."')";
      $this->db->query($sql);
    }

    
    public function agregar_foto_o_video($data)
    {
      $sql = "INSERT INTO `galeria`(`id_imagen`, `id_empresa`, `descripcion`, `nombre`, `tipo`) VALUES (NULL,'".$data['id_empresa']."','".$data['descripcion']."','".$data['nombre']."','".$data['tipo']."')";
      $query = $this->db->query($sql);
    }

    public function inicializar_img_videos($id_empresa){
      $sql = "INSERT INTO `total_img_videos`(`id_empresa`, `num_img`, `num_videos`, `max_img`, `max_videos`) VALUES ('".$id_empresa."','0','0','50','5')";
      $query = $this->db->query($sql);
    }
    /* Fin de  consultas para el registro de negocio */

    /* Iniciar Sesion */
      public function iniciar_sesion($cuenta, $password)
      {
        $sql = "SELECT id_propietario  FROM propietario WHERE correo LIKE '".$cuenta."' AND contraseña LIKE '".$password."' ";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
          return $query->result();
        }else{
          return FALSE;
        }
      }

      public function tiene_empresa($id_propietario)
      {
        $sql = "SELECT id_empresa FROM empresa WHERE id_propietario LIKE '".$id_propietario."' ";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
          return $query->result();
        }else{
          return FALSE;
        }
      }

      public function pago_realizado($id_empresa)
      {
        $sql = "SELECT * FROM empresa WHERE id_empresa LIKE '".$id_empresa."' AND pago_matriz LIKE 'TRUE'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
          return $query->result();
        }else{
          return FALSE;
        }
      }


    /* */
    /* Inicio consultas para Blog */
    public function agregar_blog($data){
      $sql = "INSERT INTO `blog` (`id_blog`, `id_empresa`, `titulo`, `subtitulo`, `blog`, `fecha`, `imagen`) VALUES (NULL, '".$data['id_empresa']."', '".$data['titulo']."', '".$data['subtitulo']."', '".$data['blog']."', '".$data['fecha']."', '".$data['imagen']."')";
      $query = $this->db->query($sql);
    }

    public function agregar_foto_blog($data)
    {
      $sql = "INSERT INTO `foto_blog`(`id_foto` , `id_blog` , `foto` ,`descripcion`) VALUES (NULL,'".$data['id_blog']."','".$data['foto']."','".$data['descripcion']."')";
      $query = $this->db->query($sql);
    }

    public function eliminar_blog($id_blog){
      $sql = "DELETE FROM `blog` WHERE `id_blog`= '".$id_blog."'";
      $query = $this->db->query($sql);
    }
    
    public function obtener_ultimo_blog($id_empresa){
      $sql =" SELECT id_blog FROM blog WHERE id_empresa LIKE '".$id_empresa."' ORDER BY `id_blog` DESC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_blogs($id_empresa)
    {
      $sql = "SELECT * FROM blog  WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_blog($id_blog)
    {
      $sql = "SELECT * FROM blog  WHERE id_blog LIKE '".$id_blog."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_galeria_blog($id_blog)
    {
      $sql = "SELECT * FROM foto_blog  WHERE id_blog LIKE '".$id_blog."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function num_fotos_blog($id_blog)
    {
      $sql = "SELECT * FROM foto_blog  WHERE id_blog LIKE '".$id_blog."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->num_rows();
      }else{
        return FALSE;
      }
    }

    public function elimina_foto_blog($id_archivo){
      $sql = "DELETE FROM foto_blog WHERE id_foto LIKE '".$id_archivo."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_descripcion_galeria_blog($id_foto, $descripcion)
    {
      $sql = "UPDATE foto_blog SET descripcion='".$descripcion."' WHERE id_foto LIKE '".$id_foto."' ";
      $query = $this->db->query($sql);
    }

    public function update_blog($data){
      $sql = "UPDATE `blog` SET `titulo` = '".$data['titulo']."' , `subtitulo` = '".$data['subtitulo']."', `blog` = '".$data['blog']."', `fecha` = '".$data['fecha']."', `imagen` = '".$data['imagen']."' WHERE `blog`.`id_blog` = '".$data['id_blog']."'";
      $query = $this->db->query($sql);
    }
    /* Fin consultas para Blog */

    /* Inicio de consultas para obtener informacion del negocio */
    public function obtener_informacion_negocio($id_propietario)
    {
      $sql = "SELECT * FROM empresa WHERE id_propietario LIKE '".$id_propietario."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_nombre_empresa($id_empresa)
    {
      $sql = "SELECT `nombre` FROM `empresa` WHERE `id_empresa` LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_sucursales($id_empresa)
    {
      $sql = "SELECT * FROM sucursal WHERE id_empresa LIKE '".$id_empresa."' ORDER BY id_sucursal  ASC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_sucursal($id_sucursal)
    {
      $sql = "SELECT * FROM sucursal WHERE id_sucursal LIKE '".$id_sucursal."' ORDER BY id_sucursal  ASC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
		}
		
		public function obtener_latlong_sucursal($id_sucursal)
		{
			$sql = "SELECT `latitud`, `longitud` FROM `sucursal` WHERE `id_sucursal` LIKE '".$id_sucursal."'";
			$query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
		}

    public function obtener_sucursal_matriz($id_propietario)
    {
      $sql = "SELECT s.id_sucursal, s.id_empresa FROM empresa as e inner join sucursal as s on s.id_empresa = e.id_empresa WHERE e.id_propietario LIKE '".$id_propietario."' ORDER BY id_sucursal  ASC ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_direccion($id_direccion)
    {
      $sql = "SELECT d.id_zona, d.calle, d.colonia, d.tipo_vialidad, d.num_ext, d.num_inter, d.cp, m.municipio, e.estado FROM direccion as d inner join municipio as m inner join estado as e on d.id_municipio = m.id_municipio AND m.id_estado = e.id_estado WHERE d.id_direccion LIKE '".$id_direccion."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_categoria_subcategoria_secciones_empresa($id_empresa)
    {
      $sql = "SELECT c.num_subcategoria, c.id_secciones, s.id_subcategoria, sb.id_categoria, s.secciones, sb.subcategoria, ct.categoria  FROM categoria as c inner join secciones as s inner join subcategoria as sb inner join categorias as ct on c.id_secciones = s.id_secciones AND s.id_subcategoria = sb.id_subcategoria AND sb.id_categoria = ct.id_categorias WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_redes_sociales($id_sucursal)
    {
      $sql = "SELECT * FROM red_social WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_servicios_adicionales($id_sucursal)
    {
      $sql = "SELECT servicio FROM servicio as s inner join servicios as ss on s.id_servicios = ss.id_servicios  WHERE s.id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_direccion_sucursal($id_sucursal)
    {
      $sql = "SELECT id_direccion FROM sucursal WHERE id_sucursal LIKE '".$id_sucursal."' ";
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

    public function obtener_contactos_sucursal($id_sucursal)
    {
      $sql = "SELECT * FROM contacto_sucursal WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_galeria($id_empresa)
    {
      $sql = "SELECT * FROM galeria WHERE id_empresa LIKE '".$id_empresa."' AND `tipo` LIKE 'imagen' ORDER BY id_imagen  DESC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_videos($id_empresa)
    {
      $sql = "SELECT * FROM galeria WHERE id_empresa LIKE '".$id_empresa."' AND `tipo` LIKE 'video' ORDER BY id_imagen  DESC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_video($id_video){
      $sql = "SELECT * FROM galeria WHERE id_imagen LIKE '".$id_video."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function todas_sucursales($id_empresa)
    {
      $sql = "SELECT sucursal.id_sucursal, sucursal.latitud, sucursal.longitud, sucursal.actualizacion, sucursal.id_empresa, sucursal.id_direccion, direccion.id_zona, direccion.calle, direccion.colonia, direccion.num_ext, direccion.num_inter, direccion.cp, zona.zona FROM sucursal LEFT JOIN direccion ON sucursal.id_direccion = direccion.id_direccion LEFT JOIN zona ON zona.id_zona = direccion.id_zona WHERE sucursal.id_empresa = '".$id_empresa."' ORDER BY id_sucursal ASC";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_id_sucursal($id_empresa,$id_direccion){
      $sql = "SELECT id_sucursal FROM `sucursal` WHERE `id_empresa` = '".$id_empresa."' AND `id_direccion` = '".$id_direccion."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function num_sucursales($id_empresa)
    {
      $sql = "SELECT * FROM `sucursal` WHERE `id_empresa` = '".$id_empresa."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->num_rows();
      }else{
        return FALSE;
      }
    }
    
    public function obtener_id_direccion_sucursal($id_sucursal){
      $sql = "SELECT id_direccion FROM `sucursal` WHERE `id_sucursal` = '".$id_sucursal."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function eliminar_sucursal($id_sucursal){
     $sql = "DELETE FROM `sucursal` WHERE `id_sucursal` = '".$id_sucursal."'";
     $query = $this->db->query($sql);
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

    public function obtener_total_galerias($id_empresa)
    {
      $sql = "SELECT * FROM total_img_videos WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_promociones($id_empresa)
    {
      $sql = "SELECT * FROM promociones  WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_promocion_id($id_promocion)
    {
      $sql = "SELECT * FROM promociones  WHERE id_promociones LIKE '".$id_promocion."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_promocion_sucursal($id_promocion)
    {
      $sql = "SELECT * FROM sucursal_promo  WHERE id_promociones LIKE '".$id_promocion."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    /* Sobreescribir en la base de datos */

    public function agregar_foto_perfil($data)
    {
      $sql = "UPDATE `empresa` SET `foto_perfil`='".$data['foto_perfil']."' WHERE `id_empresa` LIKE '".$data['id_empresa']."'";
      $query = $this->db->query($sql);
    }

    public function obtener_foto_perfil($id_empresa)
    {
      $sql = "SELECT `foto_perfil` FROM `empresa` WHERE `id_empresa` LIKE '".$id_empresa."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function actualizar_pago_sucursal($id_empresa){
      $sql = "UPDATE `empresa` SET `pago_sucursal` = 'TRUE', `tipo_pago_sucursal` = 'tarjeta', `fecha_pago_sucursal` = CURDATE() WHERE `id_empresa` LIKE '".$id_empresa."'";
      $query = $this->db->query($sql);
    }

    public function pago_sucursal_oxxo($id_empresa)
    {
      $sql = "UPDATE `empresa` SET `tipo_pago_sucursal`='oxxo',`fecha_pago_sucursal`= CURDATE() WHERE `id_empresa` LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_pago_matriz($id_empresa, $fecha){
      $sql = "UPDATE `empresa` SET `pago_matriz` = 'TRUE', `tipo_pago_matriz` = 'tarjeta', `fecha_pago_matriz`= '".$fecha."' WHERE `id_empresa` LIKE '".$id_empresa."'";
      $query = $this->db->query($sql);
    }

    public function pago_matriz_oxxo($id_empresa)
    {
      $sql = "UPDATE `empresa` SET `tipo_pago_matriz`='oxxo',`fecha_pago_matriz`= CURDATE() WHERE `id_empresa` LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_total_img($id_empresa, $num_img){
      $sql = "UPDATE total_img_videos SET num_img = $num_img  WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_total_videos($id_empresa, $num_videos){
      $sql = "UPDATE total_img_videos SET num_videos = $num_videos  WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_informacion_empresa($data)
    {
      $sql = "UPDATE `empresa` SET `nombre`='".$data['nombre_negocio']."',`info_general`='".$data['info_general']."',`servicios_productos`='".$data['servicios_productos']."',`ademas`='".$data['ademas']."',`total_subcategorias`='".$data['num_subcategorias']."' WHERE id_propietario LIKE '".$data['id_propietario']."' ";
      $query = $this->db->query($sql);
    }

    public function update_actualizacion_sucursal($id_sucursal, $fecha)
    {
      $sql = "UPDATE `sucursal` SET `actualizacion`='".$fecha."' WHERE `id_sucursal` LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
    }

		public function actualizar_coordenadad($id_sucursal, $latitud, $longitud)
		{
			$sql = "UPDATE `sucursal` SET `latitud`= '".$latitud."',`longitud`= '".$longitud."' WHERE `id_sucursal` LIKE '".$id_sucursal."' ";
			$query = $this->db->query($sql);
		}

    public function actualizar_direccion($id_direccion, $data)
    {
      $sql = "UPDATE `direccion` SET `id_municipio`='".$data['id_municipio']."',`id_zona`='".$data['zona']."',`calle`='".$data['calle']."',`colonia`='".$data['colonia']."',`tipo_vialidad`='".$data['vialidad']."',`num_ext`='".$data['num_exterior']."',`num_inter`='".$data['num_interior']."',`cp`='".$data['codigo_postal']."' WHERE `id_direccion` LIKE '".$id_direccion."' ";
      $query = $this->db->query($sql);
    }

    public function agregar_servicio($id_surcursal, $id_servicio)
    {
      $sql = "INSERT INTO `servicio`(`id_sucursal`, `id_servicios`) VALUES ('".$id_surcursal."','".$id_servicio."')";
      $query = $this->db->query($sql);
    }

    public function eliminar_servicios($id_surcursal)
    {
      $sql = "DELETE FROM servicio WHERE id_sucursal LIKE '".$id_surcursal."' ";
      $query = $this->db->query($sql);
    }

    public function agregar_red_social($id_sucursal,$red_social,$usuario)
    {
      $sql = "INSERT INTO `red_social`(`id_sucursal`, `red_social`, `usuario`) VALUES ('".$id_sucursal."','".$red_social."','".$usuario."')";
      $query = $this->db->query($sql);
    }

    public function eliminar_redes_sociales($id_sucursal)
    {
      $sql = "DELETE FROM red_social WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
    }

    public function eliminar_secciones($id_empresa)
    {
      $sql = "DELETE FROM categoria WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function eliminar_horario($id_sucursal)
    {
      $sql = "DELETE FROM horario WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
    }

    public function agregar_horario($data)
    {
      $sql = "INSERT INTO `horario`(`id_horario`, `id_sucursal`, `dia`, `hora_apertura`, `hora_cierre`, `horario_num`) VALUES (NULL,'".$data['id_sucursal']."','".$data['dia']."','".$data['hora_apertura']."','".$data['hora_cierre']."','".$data['horario_num']."')";
      $query = $this->db->query($sql);
    }

    public function agregar_contacto_sucursal($data)
    {
      $sql = "INSERT INTO `contacto_sucursal`(`id_contacto`, `id_sucursal`, `tipo`, `valor`,`indice`) VALUES (NULL,'".$data['id_sucursal']."','".$data['tipo']."','".$data['valor']."','".$data['indice']."')";
      $query = $this->db->query($sql);
    }

    public function eliminar_contacto_sucursal($id_sucursal)
    {
      $sql = "DELETE FROM contacto_sucursal WHERE id_sucursal LIKE '".$id_sucursal."' ";
      $query = $this->db->query($sql);
    }

    public function elimina_foto_video($id_archivo){
      $sql = "DELETE FROM galeria WHERE id_imagen LIKE '".$id_archivo."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_descripcion_galeria($id_imagen, $descripcion)
    {
      $sql = "UPDATE galeria SET descripcion='".$descripcion."' WHERE id_imagen LIKE '".$id_imagen."' ";
      $query = $this->db->query($sql);
    }
    
    /* Función para visita  */

    public function agregar_Visita($data)
    {
    $sql = "INSERT INTO visita(id_visita, nombre, telefono, fecha, hora, id_propietario, status,tipo) VALUES(NULL,'".$data['nombre']."' , '".$data['telefono']."' , '".$data['fecha']."' , '".$data['hora']."' , '".$data['id_propietario']."' , 'FALSE', '".$data['tipo']."')";
    $query = $this->db->query($sql);

    }

    public function actualizar_tipo_pago_visita_matriz($id_empresa)
    {
      $sql = "UPDATE empresa SET tipo_pago_matriz = 'visita' WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_tipo_pago_visita_sucursal($id_empresa)
    {
      $sql = "UPDATE empresa SET tipo_pago_sucursal = 'visita' WHERE id_empresa LIKE '".$id_empresa."' ";
      $query = $this->db->query($sql);
    }

    /***********************/

    /* Inicia consultas para eventos */

    public function insertar_evento($data)
    {
      $sql = "INSERT INTO `evento`(`id_evento`, `id_empresa`, `imagen`, `nombre`, `sinopsis`, `latitud`, `longitud`, `id_direccion`) VALUES (NULL,'".$data['id_empresa']."','".$data['imagen']."','".$data['nombre']."','".$data['sinopsis']."', '".$data['latitud']."' , '".$data['longitud']."' ,'".$data['id_direccion']."' )";
      $query = $this->db->query($sql); 
    }

    public function obtener_evento($data)
    {
      $sql = "SELECT id_evento FROM evento WHERE id_empresa LIKE '".$data['id_empresa']."' AND nombre LIKE '".$data['nombre']."' AND id_direccion LIKE '".$data['id_direccion']."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_evento_id($id_evento, $id_empresa)
    {
      $sql = "SELECT ev.id_evento, ev.imagen, ev.nombre, ev.sinopsis, ev.latitud, ev.longitud, z.id_zona, z.zona, dir.calle, dir.num_inter, dir.tipo_vialidad, dir.colonia, dir.num_ext, dir.cp, muni.municipio, e.estado FROM zona as z inner join evento as ev inner join direccion as dir inner join municipio as muni inner join estado as e on ev.id_direccion = dir.id_direccion and z.id_zona = dir.id_zona and dir.id_municipio = muni.id_municipio and muni.id_estado = e.id_estado WHERE ev.id_empresa LIKE '".$id_empresa."' AND ev.id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_id_dir_evento($id_evento)
    {
      $sql = "SELECT id_direccion FROM evento WHERE id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_galeria_evento($id_evento)
    {
      $sql = "SELECT * FROM foto_evento WHERE id_evento LIKE '".$id_evento."' AND tipo LIKE 'imagen' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_video_evento($id_evento)
    {
      $sql = "SELECT * FROM foto_evento WHERE id_evento LIKE '".$id_evento."' AND tipo LIKE 'video' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_fechas_evento($id_evento)
    {
      $sql = "SELECT fecha, hora FROM fecha_evento WHERE id_evento LIKE '".$id_evento."' ORDER BY fecha ASC ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_conceptos_evento($id_evento)
    {
      $sql = "SELECT concepto, precio FROM concepto WHERE id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_eventos_todos($id_empresa)
    {
      $sql = "SELECT ev.id_evento, ev.imagen, ev.nombre, ev.sinopsis, z.zona FROM zona as z inner join evento as ev inner join direccion as dir on ev.id_direccion = dir.id_direccion and z.id_zona = dir.id_zona WHERE ev.id_empresa LIKE '".$id_empresa."'";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_fecha_evento($id_evento)
    {
      $sql = "SELECT fecha, hora FROM fecha_evento WHERE id_evento LIKE '".$id_evento."' AND ( fecha > CURDATE() OR fecha LIKE CURDATE() ) ORDER BY fecha ASC LIMIT 1";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_concepto_evento($id_evento)
    {
      $sql = "SELECT concepto, precio FROM concepto WHERE id_evento LIKE '".$id_evento."' LIMIT 1";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function insertar_fecha_evento($data)
    {
      $sql = "INSERT INTO `fecha_evento`(`id_fecha`, `id_evento`, `fecha`, `hora`) VALUES (NULL,'".$data['id_evento']."','".$data['fecha']."','".$data['hora']."')";
      $query = $this->db->query($sql);
    }

    public function insertar_concepto_evento($data)
    {
      $sql = "INSERT INTO `concepto`(`id_concepto`, `id_evento`, `concepto`, `precio`) VALUES (NULL,'".$data['id_evento']."','".$data['concepto']."','".$data['precio']."')";
      $query = $this->db->query($sql);
    }

    public function insertar_foto_video_evento($data)
    {
      $sql = "INSERT INTO `foto_evento`(`id_foto`, `id_evento`, `foto`, `descripcion`, `tipo`) VALUES (NULL,'".$data['id_evento']."','".$data['foto']."','".$data['descripcion']."','".$data['tipo']."')";
      $query = $this->db->query($sql);
    }

    public function actualizar_evento_datos($data)
    {
      $sql = "UPDATE `evento` SET `imagen`='".$data['imagen']."',`nombre`='".$data['nombre']."',`sinopsis`='".$data['sinopsis']."', `latitud`='".$data['latitud']."', `longitud`='".$data['longitud']."'  WHERE id_evento LIKE '".$data['id_evento']."'";
      $query = $this->db->query($sql);
    }

    public function eliminar_fechas_evento($id_evento)
    {
      $sql = "DELETE FROM fecha_evento WHERE id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
    }

    public function eliminar_concepto_evento($id_evento)
    {
      $sql = "DELETE FROM concepto WHERE id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
    }

    public function actualizar_descripcion_foto_evento($id_foto, $descripcion)
    {
      $sql = "UPDATE foto_evento SET descripcion='".$descripcion."' WHERE id_foto LIKE '".$id_foto."'";
      $query = $this->db->query($sql);
    }

    public function eliminar_archivo_evento($id_archivo)
    {
      $sql = "DELETE FROM foto_evento WHERE id_foto LIKE '".$id_archivo."' ";
      $query = $this->db->query($sql);
    }

    public function eliminar_evento($id_evento)
    {
      $sql = "DELETE FROM evento WHERE id_evento LIKE '".$id_evento."' ";
      $query = $this->db->query($sql);
    }

    /* Finaliza consultas para eventos */

    /* Comienza consultas para publicidad */

    public function obtener_cascada()
    {
      $sql = "SELECT e.foto_perfil, e.nombre, p.id_empresa , z.zona FROM publicidad_cascada as p inner join empresa as e inner join zona as z on p.id_empresa = e.id_empresa and p.id_zona = z.id_zona WHERE p.status = 'TRUE' ORDER BY RAND() LIMIT 1";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
    }

    public function obtener_publicidad_perfil()
    {
      $sql = "SELECT p.id_tarjeta, e.foto_perfil, e.nombre, p.id_empresa, p.nombre as publicidad, p.tipo, z.zona FROM publicidad_tarjeta_principal as p inner join empresa as e inner join sucursal as s inner join direccion as d inner join zona as z on s.id_empresa = p.id_empresa and s.id_direccion = d.id_direccion and z.id_zona = d.id_zona and p.id_empresa = e.id_empresa WHERE p.status = 'TRUE' ORDER BY RAND() LIMIT 1";
      $query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
		}
		
		public function obtener_publicidad_perfil_id($id_tarjeta)
		{
			$sql = "SELECT p.id_empresa, p.nombre, p.tipo, e.nombre as nombre_empresa, e.foto_perfil FROM publicidad_tarjeta_principal as p inner join empresa as e on p.id_empresa = e.id_empresa WHERE p.id_tarjeta LIKE '".$id_tarjeta."'";
			$query = $this->db->query($sql);
      if($query->num_rows() > 0)
      {
        return $query->result();
      }else{
        return FALSE;
      }
		}

    /* Finaliza consultas para publicidad */

}
?>
