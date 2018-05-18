<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pedidodigital elalmacenweb fugaz Controller Class index de pedido por archivo digital
 *
 * @package     malmacen
 * @author      Lenz McKAY PICCORO
 */
class Pedido extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('table');
		$this->checku(); // OJO para todo el controller este saca o deja seguir si no hay o hay sesion
	}

	/** 
	 * entrada index si no se especifica destiino del controlador
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function index()
	{
		$this->pedido0digital();
	}

	/** 
	 * ofrece formulario para paso 1 crear el pepdido
	 * @name	ajuste0crear
	 * @access	public
	 * @return	void
	 */
	public function pedido0digital($data=NULL)
	{
		if($data==NULL)
			$data = array();
		$this->load->library('sys');
		//$this->load->model('mproductos/oajustemodel');
		// arreglo de sucursales para enviar el msc escoger
		//$list_sucursales = $this->oajustemodel->get_sucursales_galpones();
		//$data['list_sucursales']=$list_sucursales;
		// ultimo correlativo de las formas 23

		$pedido_digital_archivo = '';
		if(array_key_exists('pedido_digital_archivo',$_FILES))
			$pedido_digital_archivo = $_FILES['pedido_digital_archivo']['name'];
		$this->load->library('sys');
		if($pedido_digital_archivo != '')
			$pedido_digital_archivo_data = $this->sys->procesar_archivo('pedido_digital_archivo');
		else
			$pedido_digital_archivo_data = array();
		$pedido_digital_archivo = 'pepe';
		$data['pedido_digital_archivo'] = $pedido_digital_archivo;
		$data['pedido_digital_archivo_data'] = $pedido_digital_archivo_data;

		$this->load->model('malmacen/almacenmodel'); // este contiene abstraccion de tabla unidad unidcamente
		$almaceneslist=$this->almacenmodel->get_almacenes_box($this->username,NULL,FALSE);
		$data['list_entidades_origen']=$almaceneslist;
		$data['list_entidades_destino']=$almaceneslist;

		$data['menusub'] = $this->genmenu('malmacen');
		$this->render('malmacen/pedido0digital',$data); // abajo se muestra los resultados
	}

	/**
	 * realiza el proceso de pedido pero usndo un archivo preparado
	 * @access	public
	 * @return	void
	 */
	public function pedido1digital($cod_almacen = NULL)
	{
		$renderdata = TRUE;
		$entidad_origen = $this->input->get_post('entidad_origen');
		$entidad_destino = $this->input->get_post('entidad_destino');
		$list_codigos = $this->input->get_post('list_codigos');
		$list_cantida = $this->input->get_post('list_cantida');
		$pedido_digital_archivo = $this->input->get_post('pedido_digital_archivo');
		$data['entidad_origen'] = $entidad_origen;
		$data['entidad_destino'] = $entidad_destino;
		$data['list_codigos'] = $list_codigos;
		$data['list_cantida'] = $list_cantida;
		$data['pedido_digital_archivo'] = $pedido_digital_archivo;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('entidad_origen', 'Origen', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('entidad_destino', 'Destino', 'trim|required|alpha_numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$this->pedido0digital($data);
			return;
		}
		
		$this->form_validation->set_rules('list_codigos', 'Codigos', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('list_cantida', 'Cantidad', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('pedido_digital_archivo', 'Archvo digital', 'required');

		if(empty($pedido_digital_archivo) AND empty($pedido_digital_archivo))
			$renderdata = FALSE;

		$this->load->library('sys');
		$pedido_digital_archivo_data = $this->sys->procesar_archivo('pedido_digital_archivo');

		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('malmacen');
			$this->render('malmacen/unidadformfilter',$data);
			return;
		}
	}
}

/* End of file php */
/* Location: ./application/controllers/welcome.php */
