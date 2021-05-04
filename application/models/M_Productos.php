<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Productos extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function obtener_categorias()
	{
      $this->db->where('estado_id',1);
      $query = $this->db->get('categorias');
      if($query->num_rows() > 0){
         return $query->result_array();
      }
      return array();
   }

   public function guardar_nuevo_producto($nombre,$referencia,$precio,$peso,$categoria_id,$stock)
   {
      $data = [
         'nombre' => $nombre,
         'referencia' => $referencia,
         'precio' => $precio,
         'peso' => $peso,
         'categoria_id' => $categoria_id,
         'stock' => $stock
      ];
      return $this->db->insert('productos',$data) ? $this->db->insert_id() : FALSE;
   }

   public function obtener_productos()
   {
      $this->db->select('id_producto,nombre,stock,fecha_creacion');
      $this->db->where('estado_id',1);
      $query = $this->db->get('productos');
      if($query->num_rows() > 0){
         return $query->result_array();
      }
      return array();
   }

   public function cambiar_producto_estado($id,$nuevo_estado)
   {
      $this->db->set('estado_id',$nuevo_estado);
      $this->db->where('id_producto',$id);
      $this->db->update('productos');
      return $this->db->affected_rows() > 0;
   }

   public function obtener_producto($id)
   {
      $this->db->where('id_producto',$id);
      $query = $this->db->get('productos');
      if($query->num_rows() > 0){
         return $query->row_array();
      }

      return FALSE;
   }
}