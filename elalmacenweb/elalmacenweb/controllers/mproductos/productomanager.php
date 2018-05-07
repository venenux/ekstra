<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Productomanager elyanero Controller Class de busqueda de productos
 * 
 * @author		PICCORO Lenz McKAY
 */
class Productomanager extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$data = array();
		$data['menusub'] = $this->genmenu('mproductos');
		$this->render('mproductos/productomanagerindex',$data);
	}

	/**
	 * realiza uan busqueda de productos por descripcion larga y/o pro codigo de referencia
	 * toma lso dos parametros del GET/POST 'referencia' y 'descripcion' y renderiza en la vista
	 *
	 * @access	public
	 * @return	void
	 */
	public function mostrarproductos()
	{
		$renderdata =TRUE;
		$txt_referencia = $this->input->get_post('referencia');
		$des_producto =  $this->input->get_post('descripcion');

		if($txt_referencia == null AND $des_producto == null)
			$renderdata = FALSE;

		$des_producto == str_replace(' ', '', $des_producto);
		$txt_referencia == str_replace(' ', '', $txt_referencia);

		if($txt_referencia == '' AND $des_producto == '')
			$renderdata = FALSE;

		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('mproductos');
			$this->render('mproductos/productomanagerindex',$data); // invalid se vuelve al formulario
			return;
		}

		$parametros=array();
		if ($txt_referencia != '')
			$parametros['txt_referencia']=$txt_referencia;
		if ($des_producto != '')
			$parametros['des_producto']=$des_producto;

		$this->load->model('mproductos/productomodel');
		$productos_query=$this->productomodel->get_producto_simple(null,$parametros,FALSE);

		$data['des_producto']=$des_producto;
		$data['txt_referencia']=$txt_referencia;
		$data['productos_query']=$productos_query;

		$data['menusub'] = $this->genmenu('mproductos');
		$render['1']='mproductos/productomanagerindex';
		$render['2']='mproductos/productosmostrar';

		$this->render($render,$data); // abajo se muestra los resultados
	}

	/**
	 * renderiza un inicio de sesion, para usuario y clave nuevas, si exitoso, redirecciona a patalla info
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function existenciaproductos($codpro = '')
	{

		$this->checku();
		$parametros = array();
		$renderdata = TRUE;

		$cod_producto = $this->input->post('cod_producto');

		$cod_producto = str_replace(' ', '', $cod_producto);
		if($cod_producto == '' OR $cod_producto == NULL)
			$cod_producto = $codpro;

		$cod_producto = str_replace(' ', '', $cod_producto);
		if($cod_producto == '' OR $cod_producto == NULL)
			$renderdata = FALSE;

		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('mproductos');
			$this->render('mproductos/productomanagerindex',$data); // invalid se vuelve al formulario
			return;
		}

		$this->load->library('sys'); // aqui en esta lib llenar el codigo de compeltar ceros y llamarlo
		$cod_producto = $this->sys->completar_codigo($cod_producto);

		$this->load->model('mproductos/productomodel'); // yo llenare el modelo, $parametros debe tener el codigo y debe ser arreglo
		$parametros['cod_producto']=$cod_producto;

		$productos_detalle=$this->productomodel->get_producto_existencia(null,$parametros,FALSE);
		if( $productos_detalle === FALSE OR $productos_detalle == 0)
			$infodata = "No se pudo consultar OP, problemas con la conexcion intentar mas tarde";
		else if( $productos_detalle  === NULL )
			$infodata = "No se pudo obtener resultados con el código especificado.";
		else if( !is_array($productos_detalle ) )
			$infodata = "No hay resultados con esos parametros, intente otros!";
		else if( count($productos_detalle ) < 1 )
			$infodata = "No hay resultados con esos parametros, o estos parametros pueden que prpicien error de busqueda, intente otros!";
		else
			$infodata = $productos_detalle;
		$data['modeloresultados1'] = 0;
		if(is_array($infodata))
			$data['modeloresultados1'] = count($infodata);

		$data['cod_producto']=$cod_producto;
		$data['productosarreglo']=$infodata;

		$data['menusub'] = $this->genmenu('mproductos');
		$render['1']='mproductos/productomanagerindex';
		$render['2']='mproductos/productosmostrar';
		$this->render($render,$data); // abajo se muestra los resultados
	}


}

/* End of file Productomanager.php */
/* Location: ./application/controllers/Productomanager.php */
