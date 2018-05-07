<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ubicacionmanager elalmacenweb fugaz Controller Class index de ubicaciones de posiciones
 *
 * @package     mcierreventa
 * @author      Lenz McKAY PICCORO
 */
class Ubicacionmanager extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** 
	 * entrada index si no se especifica destiino del controlador
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function index()
	{
		$data['menusub'] = $this->genmenu('malmacen');
		$this->render('malmacen/ubicacionindex',$data);
	}

	/**
	 * realiza uan busqueda de productos por descripcion larga y/o pro codigo de referencia
	 * toma lso dos parametros del GET/POST 'referencia' y 'descripcion' y renderiza en la vista
	 *
	 * @access	public
	 * @return	void
	 */
	public function ubicacioneslistar($cod_almacen = NULL)
	{
		$renderdata = TRUE;

		$des_posicion = $this->input->get_post('des_posicion');
		$des_producto =  $this->input->get_post('descripcion');

		if($des_posicion == null AND $des_producto == null)
			$renderdata = FALSE;

		$des_producto == str_replace(' ', '', $des_producto);
		$des_posicion == str_replace(' ', '', $des_posicion);

		if($des_posicion == '' AND $des_producto == '')
			$renderdata = FALSE;

		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('malmacen');
			$this->render('malmacen/ubicacionindex',$data);
			return;
		}

		$parametros=array();
		if ($des_posicion != '')
			$parametros['des_posicion']=$des_posicion;
		if ($des_producto != '')
			$parametros['des_producto']=$des_producto;

		$this->load->model('malmacen/almacenproductomodel'); // este contiene abstraccion de existencia, ubicaciones, y conteos
		$productos_query=$this->almacenproductomodel->get_ubicaciones_simple(null,$parametros,FALSE);

		$data = $parametros; // con esto estoy enviando el mismo array a la vista como variables
		$data['productos_query']=$productos_query;

		$data['menusub'] = $this->genmenu('mproductos');
		$render['1']='malmacen/ubicacionindex';
		$render['2']='malmacen/ubicacionlistar';

		$this->render($render,$data); // abajo se muestra los resultados
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
