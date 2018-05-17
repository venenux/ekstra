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
		$this->load->model('malmacen/almacenmodel'); // este contiene abstraccion de tabla unidad unidcamente
		$almaceneslist=$this->almacenmodel->get_almacenes_box($this->username,NULL,FALSE);
		$data['list_almacenes_origen']=$almaceneslist;
		$data['list_almacenes_destino']=$almaceneslist;
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

		$archivo_digital = $this->input->get_post('archivo_digital');
		$archivo_digital = str_replace(' ', '', $archivo_digital);

		$archivo_codigo = $this->input->get_post('archivo_codigo');
		$archivo_codigo = str_replace(' ', '', $archivo_codigo);

		if(empty($archivo_codigo) AND empty($archivo_digital))
			$renderdata = FALSE;

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
