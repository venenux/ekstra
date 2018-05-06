<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oaproductos elyanero Controller Class de busqueda de productos
 * @author		PICCORO Lenz McKAY
 */
class Oaproductosrechazados extends YA_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index($data = array())
	{
		$this->checku(); // revisa si es usuario valildo

		// los producto rechazados son solo en tiendas: REUSE DE MODELO DATOS TIENDAS CERRADAS SIRVE, porque trae msc
		$this->load->model('mcierreventa/oacierreventamodel');
		$arreglocierretiendas = $this->oacierreventamodel->get_cierre_tiendas();
		$data['arreglocierretiendas']=$arreglocierretiendas;
		// envia el arreglo de tiendas cierre, esta tiene el msc y sello incluidos
		$this->render('mproductos/oaproductosrechazadosindex',$data);

	}

	/**
	 * renderiza un inicio de sesion, para usuario y clave nuevas, si exitoso, redirecciona a patalla info
	 * realiza uan busqueda de productos por descripcion larga y/o pro codigo de referencia
	 * toma lso dos parametros del GET/POST 'referencia' y 'descripcion' y renderiza en la vista **/

	public function consultaproductosrechazados()
	{
		$this->checku();

		$renderdata =TRUE;
		$cod_msc = $this->input->get_post('cod_msc');
		$fec_ini = $this->input->get_post('fec_ini');
		$fec_fin = $this->input->get_post('fec_fin');

		$parametros=array();
		$parametros['cod_msc']=$cod_msc;
		$parametros['fec_ini']=date_format(date_create($fec_ini),'Y-m-d');
		$parametros['fec_fin']=date_format(date_create($fec_fin),'Y-m-d');
		$this->load->model('mproductos/oaproductosrechazadosmodel');
		$productos_rechazados=$this->oaproductosrechazadosmodel->get_productos_rechazados_por_tienda(null,$parametros,FALSE);
		
		if( !is_array($productos_rechazados) OR count($productos_rechazados) < 1)
			$renderdata = FALSE;

		if( $renderdata == FALSE )
		{
			$data['avisoresultado']='No se obtuvieron resultados o error en los datos';
			$this->index($data);
			return;
		}


		$data['productos_rechazados'] = $productos_rechazados;
		
		$this->render('mproductos/oaproductosrechazadosmostrar',$data);

		
	}

}

/* End of file oaproductos.php */
/* Location: ./application/controllers/oaproductos.php */
