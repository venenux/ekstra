<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Indexmproductos elyanero Controller Class index de mproductos
 *
 * @package     mproductos
 * @author      Lenz McKAY PICCORO
 */
class Indexmproductos extends YA_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * index con menu
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function index()
	{
		$this->checku();
		$data['menusub'] = $this->genmenu('mproductos');
		$this->render(null,$data);
	}

	/**
	 * ejemplo llamada con campos inputs enviados con menu
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function ejemploforminputs()
	{
		$renderdata =TRUE;
		// pido los inputs si vienen o no de un formulario no importa los pide a cualqueir request
		$username1 = $this->input->get_post('username1');
		$userdescripcion1 =  $this->input->get_post('userdescripcion1');
		// esta parte se encarga de ver si el request vino de el formulario o fue uan llamada a manopla
		if($username1 == null AND $userdescripcion1 == null)
			$renderdata = FALSE;
		// despeus limpio de espacios y veo que queda si no trajo nada, es decri si solo puso espacios y ningun alfanumerico
		$username1 == str_replace(' ', '', $username1);
		$userdescripcion1 == str_replace(' ', '', $userdescripcion1);
		//aqui verifico si vino vacio sino fino
		if($username1 == '' /*OR $userdescripcion1 == ''*/)
			$renderdata = FALSE;
		// despues segun las verificaciones renderizo o no
		if( $renderdata!==TRUE )
		{
			$data = array();
			$data['menusub'] = $this->genmenu('mproductos'); // menu sub siempre debe colocarse con el nombre del subdir del controler LEER DOCU
			$this->render('mproductos/productonuevoinput',$data); // invalid se vuelve al formulario
			return;
		}
		// ahora que  tengo los valores del formulario lo puedo enviar a un modelo (directorio models) o volverlo colocar
		$data['semaforo'] = 1;
		$data['respuesta1'] = $username1;
		$data['respuesta2'] = $userdescripcion1;
		$data['menusub'] = $this->genmenu('mproductos'); // ojo leer documentacion DESARROLLO
		$this->render('mproductos/productonuevoinput',$data); // invalid se vuelve al formulario
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
