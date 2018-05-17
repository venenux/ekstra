<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Unidadmanager elalmacenweb fugaz Controller Class index de unidades de unidades
 *
 * @package     malmacen
 * @author      Lenz McKAY PICCORO
 */
class Unidad extends YA_Controller {

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
		$this->unidadeslistar();
	}

	/**
	 * realiza uan busqueda de unidad por descripcion larga y/o pro codigo de referencia
	 * toma lso dos parametros del GET/POST 'referencia' y 'descripcion' y renderiza en la vista
	 *
	 * @access	public
	 * @return	void
	 */
	public function unidadeslistar($cod_almacen = NULL)
	{
		$renderdata = TRUE;

		$des_unidad = $this->input->get_post('des_unidad');
		$des_unidad = str_replace(' ', '', $des_unidad);

		$cod_unidad = $this->input->get_post('cod_unidad');
		$cod_unidad = str_replace(' ', '', $cod_unidad);

		if(empty($des_unidad) AND empty($cod_unidad))
			$renderdata = FALSE;

		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('malmacen');
			$this->render('malmacen/unidadformfilter',$data);
			return;
		}

		$parametros=array();
		if ($des_unidad != '')
			$parametros['des_unidad']=$des_unidad;
		if ($cod_unidad != '')
			$parametros['cod_unidad']=$cod_unidad;

		$this->load->model('malmacen/unidadmodel'); // este contiene abstraccion de tabla unidad unidcamente
		$unidads_query=$this->unidadmodel->get_unidades_list($this->username,$parametros,FALSE);

		$data = $parametros; // con esto estoy enviando el mismo array a la vista como variables
		$data['unidads_query']=$unidads_query;

		$data['menusub'] = $this->genmenu('malmacen');
		$render['1']='malmacen/unidadformfilter';
		$render['2']='malmacen/unidadlistar';

		$this->render($render,$data); // abajo se muestra los resultados
	}
}

/* End of file php */
/* Location: ./application/controllers/welcome.php */
