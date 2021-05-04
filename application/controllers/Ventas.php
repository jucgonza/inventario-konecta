<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Productos');
      $this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->library('session'); // Para poder usar flashdata
	}

	public function guardar_venta(){
		// Validaciones de campos formulario producto
		$this->form_validation->set_rules('producto','Código producto','required|integer',[
         'required' => 'Por favor ingrese un código de producto',
			'integer' => 'El código debe ser un número entero'
		]);
		$this->form_validation->set_rules('cantidad','Cantidad','required|integer',[
         'required' => 'Por favor ingrese la cantidad',
			'integer' => 'Ingrese un número entero'
		]);
      if($this->form_validation->run() == FALSE){
         $this->load->view('gestion/productos');
      }else{
			$mensaje = [
				'exito' => 0,
				'desc' => 'Algo falló al guardar la venta.'
			];
			// Sanitizar algunos campos del formulario
			$producto = $this->input->post('producto',TRUE);
			$cantidad = $this->input->post('cantidad',TRUE);

         // Obtengo info del producto
         $producto_info = $this->M_Productos->obtener_producto($producto,['stock']);
         if(empty($producto_info)){
            $mensaje['desc'] = 'El producto no existe!';
            $this->session->set_flashdata('mensaje',$mensaje);
            redirect('Gestion');
         }

         $balance = $producto_info['stock'] - $cantidad;
         if($balance < 0){
            $mensaje['desc'] = 'Venta rechazada: No hay stock disponible.';
            $this->session->set_flashdata('mensaje',$mensaje);
            redirect('Gestion');
         }

         $this->M_Productos->actualizar_producto_stock($producto,$cantidad,'-');
         $mensaje['exito'] = 1;
			$mensaje['desc'] = 'Venta exitosa: Stock actualizado';
         $this->session->set_flashdata('mensaje',$mensaje);
			redirect('Gestion');
      }
	}
}
