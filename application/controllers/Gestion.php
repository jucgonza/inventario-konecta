<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gestion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Productos'); // Cargar el modelo globalmente
      $this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->library('session'); // Para poder usar flashdata
	}

	public function index()
	{
		$this->load->view('gestion/productos',[
			'categorias' => $this->M_Productos->obtener_categorias(), // Obtener todas las categorias activas
			'productos' => $this->M_Productos->obtener_productos(), // Obtener todos los productos activos
			'resultado' => $this->session->flashdata('mensaje'),
			'producto_info' => $this->session->flashdata('producto_info')
		]);
	}

	public function producto($accion,$id)
	{
		switch($accion){
			case 'ver':
				$producto_info = $this->M_Productos->obtener_producto($id);
				if(empty($producto_info)){
					$this->session->set_flashdata('mensaje',[
						'exito' => 0,
						'desc' => 'No se encontró información del producto.'
					]);
					break;
				}

				$this->session->set_flashdata('producto_info',$producto_info);
				break;
			case 'eliminar':
				$mensaje = [
					'exito' => 0,
					'desc' => 'Algo falló al intentar eliminar el producto'
				];
				$eliminado = $this->M_Productos->cambiar_producto_estado($id,2);
				if($eliminado){
					$mensaje['exito'] = 1;
					$mensaje['desc'] = 'Producto eliminado exitosamente';
				}
				$this->session->set_flashdata('mensaje',$mensaje);
				break;
			default:
				$this->session->set_flashdata('mensaje',[
					'exito' => 0,
					'desc' => 'Esta acción no esta permitida!'
				]);
		}
		redirect('Gestion');
	}

	public function guardar_producto(){
		// Validaciones de campos formulario producto
		$this->form_validation->set_rules('nombre','Nombre','required',[
         'required' => 'Por favor ingrese el nombre del producto'
		]);
      $this->form_validation->set_rules('referencia','Referencia','required',[
         'required' => 'Por favor ingrese la referencia'
		]);
		$this->form_validation->set_rules('precio','Precio','required|integer',[
         'required' => 'Por favor ingrese un precio',
			'integer' => 'El precio debe ser un número entero'
		]);
		$this->form_validation->set_rules('peso','Peso','required|integer',[
         'required' => 'Por favor ingrese un peso',
			'integer' => 'El peso debe ser un número entero'
		]);
		$this->form_validation->set_rules('categoria','Categoria','required|integer',[
         'required' => 'Por favor seleccione una categoria',
			'integer' => 'El código de la categoria es incorrecto'
		]);
		$this->form_validation->set_rules('stock','Stock','required|integer',[
         'required' => 'Por favor ingrese el stock del producto',
			'integer' => 'El stock debe ser un número entero'
		]);
		$this->form_validation->set_rules('id_producto','Código del producto','integer',[
			'integer' => 'El código del producto debe ser un número entero.'
		]);
      if($this->form_validation->run() == FALSE){
         $this->load->view('gestion/productos');
      }else{
			$mensaje = [
				'exito' => 0,
				'desc' => 'Algo falló al guardar el registro.'
			];
			// Sanitizar algunos campos del formulario
			$nombre = filter_var($this->input->post('nombre',TRUE),FILTER_SANITIZE_STRING);
			$referencia = filter_var($this->input->post('referencia',TRUE),FILTER_SANITIZE_STRING);
			$precio = $this->input->post('precio',TRUE);
			$peso = $this->input->post('peso',TRUE);
			$categoria_id = $this->input->post('categoria',TRUE);
			$stock = $this->input->post('stock',TRUE);

			if($id_producto = $this->input->post('id_producto',TRUE)){
				// Actualizar el producto en bd
				$guardado = $this->M_Productos->actualizar_producto($id_producto,$nombre,$referencia,$precio,$peso,$categoria_id,$stock);
			}else{
				// Guadar el nuevo producto en bd
				$guardado = $this->M_Productos->guardar_nuevo_producto($nombre,$referencia,$precio,$peso,$categoria_id,$stock);
			}
			if($guardado){
				$mensaje['exito'] = 1;
				$mensaje['desc'] = 'Item '.(isset($id_producto) ? 'actualizado':'guardado').' exitosamente!';
			}
			$this->session->set_flashdata('mensaje',$mensaje);
			redirect('Gestion');
      }
	}
}
