<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ajustemanager elyanero Controller Class de manejo de ajustes
 * @name		ajustemanager
 * @author		PICCORO Lenz McKAY
 */
class ajustemanager extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$this->ajustelistado();
	}

	/**
	 * ofrece listado de ajustes con sus detalles
	 * @name	ajustelistado
	 * @access	public
	 * @return	void
	 */
	public function ajustelistado($data=NULL)
	{
		if($data==NULL)
			$data = array();
		$this->load->library('sys');
		$this->load->model('mproductos/oajustemodel');
		$list_ajustes = $this->oajustemodel->get_ajuste_codigo();

		$indx = 0;
		foreach($list_ajustes as $arrayajuste)
		{
			$codigoacciones = '';
			$list_ajustes_detalles = $arrayajuste['codigosajustes'];
			if(is_array($list_ajustes_detalles) )
			{
				$indxd = 0;
				foreach($list_ajustes_detalles as $aca)
				{
					unset($list_ajustes_detalles[$indxd]['cod_oajuste']);
					unset($list_ajustes_detalles[$indxd]['sessionflag']);
					unset($list_ajustes_detalles[$indxd]['sessionficha']);
					$list_ajustes[$indx]['codigosajustes']= $list_ajustes_detalles;
					$indxd += 1;
				}
			}	// esto requiere permitted_uri_chars permita el simbolo "+"
			$codigoacciones .= ' '.anchor('mproductos/ajustemanager/ajusteaprobar/'.$arrayajuste['cod_oajuste'],'V');
			$codigoacciones .= ' '.anchor('mproductos/ajustemanager/ajusteeliminar/'.$arrayajuste['cod_oajuste'],'X');
			$list_ajustes[$indx] = array_merge(array('acciones'=>$codigoacciones),$list_ajustes[$indx]);
			$indx += 1;
		}
		$data['list_ajustes'] = $list_ajustes;

		$data['presentar_ajustes'] = $this->_renderizatablalistados($list_ajustes);
		// pinto el listado de los ajustes realizados y pendientes
		$this->render('mproductos/ajustelistado',$data);
	}

	private function _renderizatablalistados($ajuste_listado_array)
	{
		$this->load->library('table', NULL, 'renderajustes');
		$this->load->library('table', NULL, 'renderdetalles');
		$tmmopen = '<table with=100% border="1" cellpadding="0" cellspacing="0" style="border=0px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->renderajustes->clear();
		$this->renderajustes->set_template($tablapl);
		$this->renderajustes->set_heading(array_keys($ajuste_listado_array[0]));
		$this->renderajustes->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "fixedColumns" => "false" ) );
		$indx = 0;
		foreach($ajuste_listado_array as $arrayajuste)
		{
			$list_ajustes_detalles = $arrayajuste['codigosajustes'];
			$detalle = '';
				$this->renderdetalles->clear();
				$tmmopen = '<table with=100% border="1" cellpadding="1" cellspacing="1" style="border=1px;"  >';
				$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
				$this->renderdetalles->set_template($tablapl);
				$this->renderdetalles->set_heading(array_keys($list_ajustes_detalles[0]));
				$this->renderdetalles->set_datatables(FALSE);
				if(is_array($list_ajustes_detalles) )
				{
					foreach($list_ajustes_detalles as $aca)
						$this->renderdetalles->add_row(array_values($aca));
				}
				$detalle = $this->renderdetalles->generate();
			$arrayajuste['codigosajustes'] = $detalle;
			$this->renderajustes->add_row(array_values($arrayajuste));
		}
		return $this->renderajustes->generate();
	}

	/**
	 * ofrece detalle de un ajuste para acciones
	 * @name	ajustelistado
	 * @access	public
	 * @return	void
	 */
	public function ajustedetalle($parametro=NULL,$accionpara=NULL)
	{
		if($data==NULL)
			$data = array();
		$this->load->library('sys');
		$this->load->model('mproductos/oajustemodel');

		$cod_oajuste = $this->input->get_post('cod_oajuste');
		$cod_oajuste == str_replace(' ', '', $cod_oajuste);
		if($cod_oajuste == '' OR $cod_oajuste == NULL)
			$cod_oajuste = $parametro;
		$cod_oajuste == str_replace(' ', '', $cod_oajuste);
		if($cod_oajuste == '' OR $cod_oajuste == NULL)
			$renderdata = NULL;

		$ajuste_detalle_array = $this->oajustemodel->get_ajuste_codigo($this->username,$cod_oajuste);
		

		$data['ajuste_detalle_array'] = $ajuste_detalle_array;
		$data['presentar_ajustes'] = $this->_renderizatablalistados($ajuste_detalle_array);

		// pinto el listado de los ajustes realizados y pendientes
		$this->render('mproductos/ajustedetalle',$data);
	}

	/**
	 * ofrece listado de ajustes con sus detalles
	 * @name	ajustelistado
	 * @access	public
	 * @return	void
	 */
	public function ajusteaprobar($parametro=NULL)
	{
		$this->load->library('sys');
		$this->load->model('mproductos/oajustemodel');

		$cod_oajuste = $this->input->get_post('cod_oajuste');
		$cod_oajuste == str_replace(' ', '', $cod_oajuste);
		if($cod_oajuste == '' OR $cod_oajuste == NULL)
			$cod_oajuste = $parametro;
		$cod_oajuste == str_replace(' ', '', $cod_oajuste);
		if($cod_oajuste == '' OR $cod_oajuste == NULL)
			$renderdata = NULL;

		$ajuste_detalle_array = $this->oajustemodel->get_ajuste_codigo($this->username,$cod_oajuste);
		$data['ajuste_detalle_array'] = $ajuste_detalle_array;
		$data['presentar_ajustes'] = $this->_renderizatablalistados($ajuste_detalle_array);

		// pinto el listado de los ajustes realizados y pendientes
		$render['1']='mproductos/ajustedetalle';
		$render['2']='mproductos/ajusteaprobar';
		$this->render($render,$data); // abajo se muestra los resultados
	}

}
