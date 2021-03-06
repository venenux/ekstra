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
		$enlace1crear = 'malmacen/pedido/pedido0digital';
		$enlace2listar = 'malmacen/pedido/pedidomanejar/';
		$this->pedidoslistar();
	}

	/** 
	 * listado de pedidospara aprobar o procesar con botones
	 * @name	pedidoslistar
	 * @access	public
	 * @param	string $parametros codigos separados por coma
	 * @return	void
	 */
	public function pedidoslistar($parametros = NULL)
	{
		$cod_pedidos = $this->input->get_post('cod_pedidos');
		$cod_pedidos = str_replace(' ', '', $cod_pedidos);
		if($cod_pedidos == NULL  OR $cod_pedidos == '')
			$cod_pedidos = $parametros;
		$this->load->model('malmacen/pedidomodel'); // este contiene abstraccion de tabla unidad unidcamente
		$pedido_listado_array=$this->pedidomodel->get_pedido_codigo($this->username,NULL);
		$data['pedido_digital'] = $pedido_listado_array;
		$indx = 0;
		if(!is_array($pedido_listado_array))
		{
			$data['presentarpedidos'] = 'No se encontraron datos coincidente o el codigo ha sido bloqueado/removido';
			$data['menusub'] = $this->genmenu('malmacen');
			$this->render('malmacen/pedidolistado',$data);
			return;
		}
		foreach($pedido_listado_array as $arraypedido)
		{
			$codigoacciones = '';
			$pedido_listado_array_detalles = $arraypedido['codigospedidos'];
			if(is_array($pedido_listado_array_detalles) )
			{
				$indxd = 0;
				foreach($pedido_listado_array_detalles as $aca)
				{
					unset($pedido_listado_array_detalles[$indxd]['cod_pedido']);
					unset($pedido_listado_array_detalles[$indxd]['sessionflag']);
					unset($pedido_listado_array_detalles[$indxd]['sessionficha']);
					$pedido_listado_array[$indx]['codigospedidos']= $pedido_listado_array_detalles;
					$indxd += 1;
				}
			}	// esto requiere permitted_uri_chars permita el simbolo "+"
			$esaprobado1 = $arraypedido['cod_aprobado1'];
			$esaprobado1 = str_replace(' ', '', $esaprobado1);
			$esaprobado2 = $arraypedido['cod_aprobado2'];
			$esaprobado2 = str_replace(' ', '', $esaprobado2);
			if( $esaprobado1 == '' OR $esaprobado2 == '' )
			{
				$codigoacciones .= ' '.anchor('malmacen/pedido/aprobar/'.$arraypedido['cod_pedido'],'V');
				$codigoacciones .= ' '.anchor('malmacen/pedido/denegar/'.$arraypedido['cod_pedido'],'X');
				$pedido_listado_array[$indx] = array_merge(array('acciones'=>$codigoacciones),$pedido_listado_array[$indx]);
			}
			else
			{
				$pedido_listado_array[$indx] = array_merge(array('acciones'=>'APROBADO'),$pedido_listado_array[$indx]);
			}
			$indx += 1;
		}
		// pinto el listado de los pedidos realizados y pendientes
		$this->load->library('table', NULL, 'renderpedidos');
		$this->load->library('table', NULL, 'renderdetalles');
		$tmmopen = '<table with=100% border="1" cellpadding="2" cellspacing="2" style="border=0px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->renderpedidos->clear();
		$this->renderpedidos->set_template($tablapl);
		$this->renderpedidos->set_heading(array_keys($pedido_listado_array[0]));
		$this->renderpedidos->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "fixedColumns" => "false" ) );
		$indx = 0;
		foreach($pedido_listado_array as $arraypedido)
		{
			$list_pedidos_detalles = $arraypedido['codigospedidos'];
			$detalle = '';
			if(is_array($list_pedidos_detalles) AND count($list_pedidos_detalles) >1 )
			{
				$this->renderdetalles->clear();
				$tmmopen = '<table with=100% border="1" cellpadding="2" cellspacing="2" style="border=1px;"  >';
				$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
				$this->renderdetalles->set_template($tablapl);
				$this->renderdetalles->set_heading(array_keys($list_pedidos_detalles[0]));
				$this->renderdetalles->set_datatables(FALSE);
				foreach($list_pedidos_detalles as $aca)
					$this->renderdetalles->add_row(array_values($aca));
				$detalle = $this->renderdetalles->generate();
			}
			$arraypedido['codigospedidos'] = $detalle;
			$this->renderpedidos->add_row(array_values($arraypedido));
		}
		$data['presentarpedidos'] = $this->renderpedidos->generate();
		$data['menusub'] = $this->genmenu('malmacen');
		$this->render('malmacen/pedidolistado',$data);
	}

	private function _procesa_listado($pedido_digital_archivo_data)
	{
		$list_codigos = $pedido_digital_archivo_data['list_codigos'];
		$list_cantida = $pedido_digital_archivo_data['list_cantida'];
		$codigos_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_codigos)));
		$codigos_format = str_replace(' ', '', $codigos_format);
		$arraycodigos = explode(PHP_EOL,$codigos_format);
		$ajustar_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_cantida)));
		$ajustar_format = str_replace(' ', '', $ajustar_format);
		$arraycantida = explode(PHP_EOL,$ajustar_format);
		$pedido_digital_archivo_data['list_codigos'] = $codigos_format;
		$pedido_digital_archivo_data['list_cantida'] = $ajustar_format;
		$arraylistado = array();
		if( count($arraycodigos) == count($arraycantida) )
		{
			foreach($arraycodigos as $indic => $codigop)
			{
				$arraylistado[$indic]=array('cod_producto'=>$codigop,'can_producto'=>$arraycantida[$indic]);
			}
		}
		$pedido_digital_archivo_data['arraylistado'] = $arraylistado;
		return $pedido_digital_archivo_data;
	}

	/** 
	 * ofrece formulario para paso 1 crear el pepdido
	 * @name	pedido0digital
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
	public function pedido1digital($data=NULL)
	{
		$but_proceso1 = $this->input->get_post('but_proceso1');
		$but_proceso2 = $this->input->get_post('but_proceso2');
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
		if($but_proceso1 != '')
		{
			$vistas[0]='malmacen/pedido0digital';
			$vistas[1]='malmacen/pedido1digital';
			$this->render($vistas,$data);
		}
		else
			$this->pedido2digital($data);
	}

	/** 
	 * procesa el pedido y sigue
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function pedido2digital($data = NULL)
	{
		$renderdata = TRUE;
		if(!is_array($data))
			$renderdata = FALSE;
		if(!array_key_exists('pedido_digital_archivo_data',$data))
			$renderdata = FALSE;
		if(!array_key_exists('entidad_origen',$data))
			$renderdata = FALSE;
		if(!array_key_exists('entidad_destino',$data))
			$renderdata = FALSE;
		$data['semaforo_procesar'] = TRUE;
		if($renderdata == FALSE)
		{
			$data['form_error_mensaje'] = 'Conexcion perdida o se intento llamar el formulario sin datos, debe repetirse el proceso';
			$this->pedido0digital($data);
			return;
		}
		$parametros = array();
		$parametros['cod_pedido'] = date('YmdHis');
		$parametros['cod_ejecuta'] = $data['entidad_origen'];// TODO mientras
		$parametros['cod_origen'] = $data['entidad_origen'];
		$parametros['cod_destino'] = implode(',',$data['entidad_destino']);
		$parametros['list_codigos'] = $data['pedido_digital_archivo_data']['arraylistado'];
		$this->load->model('malmacen/pedidomodel'); // este contiene abstraccion de tabla unidad unidcamente
		$cod_pedido = $this->pedidomodel->set_pedido($this->username,$parametros,FALSE);
		$pedido_listado_array=$this->pedidomodel->get_pedido_codigo($this->username,$cod_pedido);
		$data['pedido_digital'] = $pedido_listado_array;
		if($almaceneslist !== FALSE)
			$this->pedido0digital($data);
		else
			$this->render('malmacen/pedido2procesar',$data);
	}

}

/* End of file php */
/* Location: ./application/controllers/welcome.php */
