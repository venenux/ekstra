<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ingresodigital elalmacenweb fugaz Controller Class index de ingreso por archivo digital
 *
 * @package     malmacen
 * @author      Lenz McKAY PICCORO
 */
class Ingreso extends YA_Controller {

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
		$this->ingreso0digital();
	}

	/** 
	 * entrada index si no se especifica destiino del controlador
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function ingreso0digital()
	{
		$this->load->model('malmacen/almacenmodel'); // este contiene abstraccion de tabla unidad unidcamente
		$almaceneslist=$this->almacenmodel->get_almacenes_box($this->username,NULL,FALSE);
		$data['almaceneslist']=$almaceneslist;

		$data['menusub'] = $this->genmenu('malmacen');

		$this->render('malmacen/ingreso0digital',$data); // abajo se muestra los resultados
	}

	/**
	 * realiza el proceso de ingreso pero usndo un archivo preparado
	 * @access	public
	 * @return	void
	 */
	public function ingreso1digital($cod_almacen = NULL)
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
