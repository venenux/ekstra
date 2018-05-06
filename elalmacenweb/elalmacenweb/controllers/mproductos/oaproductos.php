<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oaproductos elyanero Controller Class de busqueda de productos
 * 
 * @author		PICCORO Lenz McKAY
 */
class Oaproductos extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$data = array();
		        //path to directory to scan
$directory = APPPATH.'/controllers/';
 
//get all image files with a .jpg extension.
$images = glob($directory . "*");
$arraymodulesdetected = array();
//print each file name
foreach($images as $image)
{
	if(is_dir($image))
array_push($arraymodulesdetected, str_replace($directory, '', $image));
}
echo print_r($arraymodulesdetected,TRUE);


		$this->render('mproductos/oaproductosindex',$data);
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
		$txt_descripcion_larga =  $this->input->get_post('descripcion');

		if($txt_referencia == null AND $txt_descripcion_larga == null)
			$renderdata = FALSE;

		$txt_descripcion_larga == str_replace(' ', '', $txt_descripcion_larga);
		$txt_referencia == str_replace(' ', '', $txt_referencia);

		if($txt_referencia == '' AND $txt_descripcion_larga == '')
			$renderdata = FALSE;

		if( $renderdata!==TRUE )
		{
			$data = array();
			$this->render('mproductos/oaproductosindex',$data); // invalid se vuelve al formulario
			return;
		}

		$parametros=array();
		if ($txt_referencia != '')
			$parametros['txt_referencia']=$txt_referencia;
		if ($txt_descripcion_larga != '')
			$parametros['txt_descripcion_larga']=$txt_descripcion_larga;

		$this->load->model('mproductos/oaproductosmodel');
		$productos_query=$this->oaproductosmodel->get_producto_simple(null,$parametros,FALSE);

		$data['txt_descripcion_larga']=$txt_descripcion_larga;
		$data['txt_referencia']=$txt_referencia;
		$data['productos_query']=$productos_query;

		$render['1']='mproductos/oaproductosindex';
		$render['2']='mproductos/oaproductosmostrar';

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
			$this->render('mproductos/oaproductosindex',$data); // invalid se vuelve al formulario
			return;
		}

		$this->load->library('sys'); // aqui en esta lib llenar el codigo de compeltar ceros y llamarlo
		$cod_producto = $this->sys->completar_codigo($cod_producto);

		$this->load->model('mproductos/oaproductosmodel'); // yo llenare el modelo, $parametros debe tener el codigo y debe ser arreglo
		$parametros['cod_producto']=$cod_producto;

		$productos_detalle=$this->oaproductosmodel->get_producto_existencia(null,$parametros,FALSE);
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

		$render['1']='mproductos/oaproductosindex';
		$render['2']='mproductos/oaproductosexistencia';
		$this->render($render,$data); // abajo se muestra los resultados
	}


}

/* End of file oaproductos.php */
/* Location: ./application/controllers/oaproductos.php */
