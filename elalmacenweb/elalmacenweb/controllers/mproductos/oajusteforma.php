<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oajusteforma elyanero Controller Class de busqueda de productos
 * @name		Oajusteforma
 * @author		PICCORO Lenz McKAY
 */
class Oajusteforma extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$this->ajuste0crear();
	}

	/**
	 * ofrece formulario para paso 1 crear la forma 23 desde cero
	 * @name	ajuste0crear
	 * @access	public
	 * @return	void
	 */
	public function ajuste0crear($data=NULL)
	{
		if($data==NULL)
			$data = array();
		$this->load->library('sys');
		$this->load->model('mproductos/oajustemodel');
		// arreglo de causas para enviar el codigo de causa
		$arreglocausas = $this->oajustemodel->get_causas();
		$data['arreglocausas']=$arreglocausas;
		// arreglo de tiendas para enviar el msc escoger
		$arreglotiendas = $this->oajustemodel->get_sucursales_oasis();
		$data['arreglotiendas']=$arreglotiendas;
		// ultimo correlativo de las formas 23
		$ultimocorrelativo = $this->oajustemodel->get_correlativo();
		$data['ultimocorrelativo']=$ultimocorrelativo;
		// datos especificos del forma 23 ajuste
		$cod_correlativo = $this->sys->completar_codigo($ultimocorrelativo + 1,6);
		$data['cod_correlativo']=$cod_correlativo;
		$data['cod_depa_list'] = $this->oajustemodel->cod_depa_list;
		$data['cod_tipo_list'] = $this->oajustemodel->cod_tipo_list;
		$data['cod_signo_list'] = $this->oajustemodel->cod_signo_list;
		// pinto el formulario apra crear el 23 de ajuste
		$this->render('mproductos/ajuste0crear',$data);
	}

	/**
	 * prepara los valores para crear la forma 23 desde cero a partir de un par producto,cantidad
	 * @name	ajuste1crear
	 * @access	public
	 * @param	void (POST/GET cod_depa si es el resto o si es hogar)
	 * @param	void (POST/GET cod_tipo si es valor/precio o si es cantidad/diponible)
	 * @param	void (POST/GET cod_causa una lista de letras que significan la causa
	 * @param	void (POST/GET cod_signo si positivo sube, si negativo rebaja)
	 * @param	void (POST/GET cod msc tienda a donde se aplicara)
	 * @param	void (POST/GET list codigos separados por comma)
	 * @param	void (POST/GET list ajustar separados por comma, cada numero)
	 * @return	void
	 */
	public function ajuste1crear()
	{
		$this->checku();
		$renderdata = TRUE; // todo normal hasta encontrar faltante parametros

		// ******** INI captura de valores, ojo igual que abajo ajuste2creado **********
		$aprocesar = $this->input->get_post('but_proceso1');
		$aprocesar == str_replace(' ', '', $aprocesar);
		if($aprocesar == '')
			$aprocesar = $this->input->get_post('but_proceso2');
		$aprocesar == str_replace(' ', '', $aprocesar);
		if($aprocesar == '')
			$aprocesar = 'A_Procesar_2';

		$cod_depa = $this->input->get_post('cod_depa');
		$cod_tipo = $this->input->get_post('cod_tipo');
		$cod_signo = $this->input->get_post('cod_signo');
		$cod_causa = $this->input->get_post('cod_causa');
		$cod_tienda = $this->input->get_post('cod_tienda'); // ejem: T o msc 001034 en donde aplica, T todas
		$cod_correlativo = $this->input->get_post('cod_correlativo');
		$list_codigos = $this->input->get_post('list_codigos'); // ejem: 0000101010\n0000101110 misma veces que ajustar
		$list_ajustar = $this->input->get_post('list_ajustar'); // ejem: 238,4\n56 misma veces que codigos

		$cod_depa == str_replace(' ', '', $cod_depa);
		$cod_tipo == str_replace(' ', '', $cod_tipo);
		$cod_signo == str_replace(' ', '', $cod_signo);
		$cod_causa == str_replace(' ', '', $cod_causa);
		$cod_tienda == str_replace(' ', '', $cod_tienda);
		$cod_correlativo == str_replace(' ', '', $cod_correlativo);
		$codigos_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_codigos)));
		$codigos_format = str_replace(' ', '', $codigos_format);
		$cod_codigos = explode(PHP_EOL,$codigos_format);
		$ajustar_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_ajustar)));
		$ajustar_format = str_replace(' ', '', $ajustar_format);
		$can_ajustes = explode(PHP_EOL,$ajustar_format);

		if($cod_depa == '' OR $cod_tipo == '' OR $cod_signo == '' OR $cod_causa == '' OR $cod_tienda == '')
			$renderdata = FALSE;
		if(count($cod_codigos) != count($can_ajustes))
			$renderdata = FALSE;
		if($codigos_format == '' OR $ajustar_format == '')
			$renderdata = FALSE;

		$data['cod_depa']=$cod_depa;
		$data['cod_tipo']=$cod_tipo;
		$data['cod_signo']=$cod_signo;
		$data['cod_causa']=$cod_causa;
		$data['cod_tienda']=$cod_tienda;
		$data['cod_correlativo']=$cod_correlativo;
		$data['list_codigos']=$list_codigos;
		$data['list_ajustar']=$list_ajustar;

		if( $renderdata!==TRUE OR $aprocesar == 'A_Procesar_0')
		{
			$this->ajuste0crear($data);
			return;
		}
		// ******** FIN captura de valores, ojo igual que abajo ajuste2creado **********

		// ******** INI arreglo de productos vs ajustes vs valores viejos **********
		$this->load->model('mproductos/oajustemodel');
		$list_codigosajustes=$this->oajustemodel->get_producto_existencia_msc(null,$cod_codigos,$cod_tipo,$cod_tienda,FALSE);
		$renderdata = is_array($list_codigosajustes);
		if($renderdata)
		{
			$cuantoslist = count($cod_codigos);
			$cuantosteng = count($list_codigosajustes);
			if( $cuantosteng < 1 OR $cuantoslist < $cuantosteng) // imposible, mas de los pasados?
				$renderdata = FALSE;
		}
		if($renderdata != TRUE)
		{
			$this->ajuste0crear($data);
			return;
		}
		$arrayajustar = array();
		$arrayproducto = array();
		$this->load->library('sys');
		foreach($cod_codigos as $indx => $codigoproducto) // emparejo codigo con ajustar, con codigo como idice
		{
			$codigoproducto = $this->sys->completar_codigo($codigoproducto); // codigo vienen completados desde db ajustar
			$arrayajustar[$codigoproducto] = $can_ajustes[$indx]; // se compararan los codigos abajo pero completados
		}
		foreach($list_codigosajustes as $indx => $arrayproducto) // ahora uso el emparejo y saco por codigo
		{
			if( array_key_exists($arrayproducto['cod_producto'],$arrayajustar) )
				$arrayproducto['can_valor_nue'] = $arrayajustar[$arrayproducto['cod_producto']];
			else
				$arrayproducto['can_valor_nue'] = '0';
			$list_codigosajustes[$indx] = $arrayproducto;
		}
		$data['list_codigosajustes'] = $list_codigosajustes;
		// ******** FIN arreglo de productos vs ajustes vs valores viejos **********

		$this->load->model('mproductos/oajustemodel');
		// arreglo de causas para enviar el codigo de causa
		$arreglocausas = $this->oajustemodel->get_causas();
		$data['arreglocausas']=$arreglocausas;
		// arreglo de tiendas para enviar el msc escoger
		$arreglotiendas = $this->oajustemodel->get_sucursales_oasis();
		$data['arreglotiendas']=$arreglotiendas;
		// ultimo correlativo de las formas 23
		$ultimocorrelativo = $this->oajustemodel->get_correlativo();
		$data['ultimocorrelativo']=$ultimocorrelativo;
		// datos especificos del forma 23 ajuste
		$data['cod_depa_list'] = $this->oajustemodel->cod_depa_list;
		$data['cod_tipo_list'] = $this->oajustemodel->cod_tipo_list;
		$data['cod_signo_list'] = $this->oajustemodel->cod_signo_list;
		// pinto el formulario apra crear el 23 de ajuste
		$render['1']='mproductos/ajuste1crear';
		//	$render['2']='mproductos/oaproductosmostrar';
		if( $renderdata!==TRUE OR $aprocesar == 'A_Procesar_0')
		{
			$this->ajuste0crear($data);
			return;
		}
		$this->render($render,$data); // abajo se muestra los resultados
	}

	/**
	 * crea la forma 23 desde la preparacion "ajuste1crear" a partir de un par producto,cantidad
	 * @name	ajuste1crear
	 * @access	public
	 * @param	void (POST/GET cod_depa si es el resto o si es hogar)
	 * @param	void (POST/GET cod_tipo si es valor/precio o si es cantidad/diponible)
	 * @param	void (POST/GET cod_signo si positivo sube, si negativo rebaja)
	 * @param	void (POST/GET cod_causa una lista de letras que significan la causa
	 * @param	void (POST/GET cod msc tienda a donde se aplicara)
	 * @param	void (POST/GET list codigos separados por comma)
	 * @param	void (POST/GET list ajustar separados por comma, cada numero)
	 * @return	void
	 */
	public function ajuste2creado($cod_forma = '')
	{
		$this->checku();
		$renderdata = TRUE; // todo normal hasta encontrar faltante parametros

		// ******** INI captura de valores, ojo igual que abajo ajuste2creado **********
		$aprocesar = $this->input->get_post('but_proceso1');
		$aprocesar == str_replace(' ', '', $aprocesar);
		if($aprocesar == '')
			$aprocesar = $this->input->get_post('but_proceso2');
		$aprocesar == str_replace(' ', '', $aprocesar);
		if($aprocesar == '')
			$aprocesar = 'A_Procesar_2';

		$cod_depa = $this->input->get_post('cod_depa');
		$cod_tipo = $this->input->get_post('cod_tipo');
		$cod_signo = $this->input->get_post('cod_signo');
		$cod_causa = $this->input->get_post('cod_causa');
		$cod_tienda = $this->input->get_post('cod_tienda'); // ejem: T o msc 001034 en donde aplica, T todas
		$cod_correlativo = $this->input->get_post('cod_correlativo');
		$list_codigos = $this->input->get_post('list_codigos'); // ejem: 0000101010\n0000101110 misma veces que ajustar
		$list_ajustar = $this->input->get_post('list_ajustar'); // ejem: 238,4\n56 misma veces que codigos

		$cod_depa == str_replace(' ', '', $cod_depa);
		$cod_tipo == str_replace(' ', '', $cod_tipo);
		$cod_signo == str_replace(' ', '', $cod_signo);
		$cod_causa == str_replace(' ', '', $cod_causa);
		$cod_tienda == str_replace(' ', '', $cod_tienda);
		$cod_correlativo == str_replace(' ', '', $cod_correlativo);
		$codigos_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_codigos)));
		$codigos_format = str_replace(' ', '', $codigos_format);
		$cod_codigos = explode(PHP_EOL,$codigos_format);
		$ajustar_format = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$list_ajustar)));
		$ajustar_format = str_replace(' ', '', $ajustar_format);
		$can_ajustes = explode(PHP_EOL,$ajustar_format);

		if($cod_depa == '' OR $cod_tipo == '' OR $cod_signo == '' OR $cod_causa == '' OR $cod_tienda == '')
			$renderdata = FALSE;
		if(count($cod_codigos) != count($can_ajustes))
			$renderdata = FALSE;
		if($codigos_format == '' OR $ajustar_format == '')
			$renderdata = FALSE;

		$data['cod_depa']=$cod_depa;
		$data['cod_tipo']=$cod_tipo;
		$data['cod_signo']=$cod_signo;
		$data['cod_causa']=$cod_causa;
		$data['cod_tienda']=$cod_tienda;
		$data['cod_correlativo']=$cod_correlativo;
		$data['list_codigos']=$list_codigos;
		$data['list_ajustar']=$list_ajustar;

		if( $renderdata!==TRUE OR $aprocesar == 'A_Procesar_0')
		{
			$this->ajuste0crear($data);
			return;
		}
		// ******** FIN captura de valores, ojo igual que abajo ajuste2creado **********

		// ******** INI arreglo de productos vs ajustes vs valores viejos **********
		$this->load->model('mproductos/oajustemodel');
		$list_codigosajustes=$this->oajustemodel->get_producto_existencia_msc(null,$cod_codigos,$cod_tipo,$cod_tienda,FALSE);
		$renderdata = is_array($list_codigosajustes);
		if($renderdata)
		{
			$cuantoslist = count($cod_codigos);
			$cuantosteng = count($list_codigosajustes);
			if( $cuantosteng < 1 OR $cuantoslist < $cuantosteng) // imposible, mas de los pasados?
				$renderdata = FALSE;
		}
		if($renderdata != TRUE)
		{
			$this->ajuste0crear($data);
			return;
		}
		$arrayajustar = array();
		$arrayproducto = array();
		$this->load->library('sys');
		foreach($cod_codigos as $indx => $codigoproducto) // emparejo codigo con ajustar, con codigo como idice
		{
			$codigoproducto = $this->sys->completar_codigo($codigoproducto); // codigo vienen completados desde db ajustar
			$arrayajustar[$codigoproducto] = $can_ajustes[$indx]; // se compararan los codigos abajo pero completados
		}
		foreach($list_codigosajustes as $indx => $arrayproducto) // ahora uso el emparejo y saco por codigo
		{
			$cod_sucursal = $arrayproducto['cod_sucursal'];
			if( array_key_exists($arrayproducto['cod_producto'],$arrayajustar) )
				$arrayproducto['can_valor_nue'] = $arrayajustar[$arrayproducto['cod_producto']];
			else
				$arrayproducto['can_valor_nue'] = '0';
			$list_codigosajustes[$indx] = $arrayproducto;
		}
		$data['list_codigosajustes'] = $list_codigosajustes;
		// ******** FIN arreglo de productos vs ajustes vs valores viejos **********

		$parametros = $data;
		$parametros['cod_msc'] = $cod_tienda;
		$parametros['cod_sucursal'] = $cod_sucursal;
		unset($parametros['list_codigos']);
		unset($parametros['list_ajustar']);
		unset($parametros['cod_tienda']);
		$parametrosdetalle = array();
		foreach($list_codigosajustes as $indx => $arrayproducto) // ahora uso el emparejo y saco por codigo
		{
			unset($arrayproducto['cod_msc']);
			unset($arrayproducto['nom_sucursal']);
			unset($arrayproducto['cod_sucursal']);
			unset($arrayproducto['des_producto']);
			unset($arrayproducto['saldo_producto']);
			unset($arrayproducto['precio_producto']);
			$parametrosdetalle[$indx] = $arrayproducto;
		}
		$parametros['list_codigosajustes'] = $parametrosdetalle;
		$this->load->model('mproductos/oajustemodel');
		$cod_oajuste = $this->oajustemodel->set_forma_preforma(NULL,$parametros,FALSE);
		$data['cod_oajuste']=$cod_oajuste;
		if($cod_oajuste != TRUE)
		{
			$this->ajuste0crear($data);
			return;
		}
		$renderdata = $this->oajustemodel->get_ajuste_codigo(NULL,$cod_oajuste,FALSE);

		$data['cod_oajuste'] = $cod_oajuste;
		$data['cod_sucursal'] = $cod_sucursal;
		$data['renderdata'] = $renderdata;
		// pinto el formulario apra crear el 23 de ajuste
		$render['1']='mproductos/ajuste2crear';
		$this->render($render,$data); // abajo se muestra los resultados
	}


}

/* End of file oaproductos.php */
/* Location: ./application/controllers/oaproductos.php */
