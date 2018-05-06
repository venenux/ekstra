<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Oaproductos elyanero Controller Class de busqueda de productos
 * 
 * @author		PICCORO Lenz McKAY
 */
class Oaproductosexistencias extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$data = array();
		$this->render('mproductos/oaproductosexistenciasformulario',$data);
	}

	/** metodo que invoca la llamada asincrona y despues ofrece el boton de ver resultados */
	public function mostrarexistencias1formulario()
	{
		// aqui muestro el formulario con los datos, que es la fecha por ahora
		$data = array();

		// cuando en el formulario da submit, invoca la llamada remota, y segun esta ofrece resultados
		$fec_ini = $this->input->get_post('fec_ini');
		$fec_fin = $this->input->get_post('fec_fin');
		$data['fec_ini']=$fec_ini;
		$data['fec_fin']=$fec_fin;

		$this->invocarhacerexistecia(NULL,$fec_ini,$fec_fin,FALSE);
		// if formulario invocado then vuelve pintar pero con otro boton que duice "ver resultados"
		$this->render('mproductos/oaproductosexistenciasformulario',$data);
		// NOTA usa directo el objeto resulset no uses array
	}


	/**
	 * ver lso resultados de la existencia despues de invocar la llamada remota
	 * @access	public
	 * @return	void
	 */
	public function mostrarexistencias2resultados($fec_ini = '',$fec_fin = '',$hacecsv = FALSE)
	{
		$renderdata =TRUE;
		$fec_ini = $this->input->get_post('fec_ini');
		$fec_fin = $this->input->get_post('fec_fin');

		$data['fec_ini']=$fec_ini;
		$data['fec_fin']=$fec_fin;

		$render['1']='mproductos/oaproductosexistenciasformulario';
		$render['2']='mproductos/oaproductosexistenciasresultados';

		$this->render($render,$data); // abajo se muestra los resultados
	}

	/**
	 * invoca en el servidor un proceso para ser de manera sincrona, sin esperar respuesta usando socket
	 * @name		invocarhacerexistecia
	 * @access	public
	 * @param	string $fec_ini
	 * @param	string $fec_fin
	 * @param	boolean $hacercsv
	 * @return	void
	 */
	public function invocarhacerexistecia($fec_ini = '',$fec_fin = '',$hacecsv = FALSE)
	{

		$this->checku();
		
	}


}

/* End of file oaproductos.php */
/* Location: ./application/controllers/oaproductos.php */
