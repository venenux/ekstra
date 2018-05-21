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

	private function _procesa_listado($pedido_digital_archivo_data)
	{
		$list_codigos = $pedido_digital_archivo_data['list_codigos'];
		$list_cantida = $pedido_digital_archivo_data['list_cantida'];
		$codigos_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_codigos)));
		$codigos_format = str_replace(' ', '', $codigos_format);
		//$cod_codigos = explode(PHP_EOL,$codigos_format);
		$ajustar_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_cantida)));
		$ajustar_format = str_replace(' ', '', $ajustar_format);
		//$can_ajustes = explode(PHP_EOL,$ajustar_format);
		$pedido_digital_archivo_data['list_codigos'] = $codigos_format;
		$pedido_digital_archivo_data['list_cantida'] = $ajustar_format;
		return $pedido_digital_archivo_data;
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
		// inicializa el array de datos que se envia a la vista
		$data['entidad_origen'] = $entidad_origen;
		$data['entidad_destino'] = $entidad_destino;
		$data['list_codigos'] = $list_codigos;
		$data['list_cantida'] = $list_cantida;
		// detecta archivo si hubo o no
		$pedido_digital_archivo = '';//$pedido_digital_archivo = $this->input->get_post('pedido_digital_archivo');
		$pedido_digital_archivo_data = array();
		if(array_key_exists('pedido_digital_archivo',$_FILES))
			$pedido_digital_archivo = $_FILES['pedido_digital_archivo']['name'];
		$data['pedido_digital_archivo'] = $pedido_digital_archivo;

		$this->load->library('form_validation');
		$tienecampos = FALSE;
		$tienearchivo = FALSE;
		$tieneentidades = FALSE;
		$this->form_validation->set_rules('entidad_origen', 'Origen', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('entidad_destino[]', 'Destino', 'trim|required|alpha_numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$tieneentidades = FALSE;
			$data['form_error_mensaje'] = 'Debe escoger destino y origen';
			$this->pedido0digital($data);
			return;
		}
		$tieneentidades = TRUE;
		$this->form_validation->set_rules('list_codigos', 'Codigos', 'trim|required');
		$this->form_validation->set_rules('list_cantida', 'Cantidad', 'trim|required');
		if ($this->form_validation->run() == FALSE)
		{
			$tienecampos = FALSE;
			if($pedido_digital_archivo=='')
			{
				$data['form_error_mensaje'] = 'Si no coloca cantidad y codigos debe subir archivo';
				$this->pedido0digital($data);
				return;
			}
		}
		// procesamiento: quita duplicados y detecta si procesa archivo o no
		$tienecampos = TRUE;
		$pedido_digital_archivo_data['list_codigos'] = $list_codigos;
		$pedido_digital_archivo_data['list_cantida'] = $list_cantida;
		if($pedido_digital_archivo!='')
		{
			$tienearchivo = TRUE;
			$this->load->library('sys');
			$pedido_digital_archivo_data = $this->sys->procesar_archivo_pedido_csv('pedido_digital_archivo');
		}
		$data['list_codigos'] = $pedido_digital_archivo_data['list_codigos'];
		$data['list_cantida'] = $pedido_digital_archivo_data['list_cantida'];
		$pedido_digital_archivo_data = $this->_procesa_listado($pedido_digital_archivo_data);
		$data['pedido_digital_archivo'] = $pedido_digital_archivo;
		$data['pedido_digital_archivo_data'] = $pedido_digital_archivo_data;
		// procesado de datos listo, se presenta la informacion en pantalla de confirmacion
		$data['menusub'] = $this->genmenu('malmacen');
		$vistas[0]='malmacen/pedido0digital';
		$vistas[1]='malmacen/pedido1digital';
		$this->render($vistas,$data);
	}

}

/* End of file php */
/* Location: ./application/controllers/welcome.php */
