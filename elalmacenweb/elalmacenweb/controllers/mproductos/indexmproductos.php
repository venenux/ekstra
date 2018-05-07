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
		$this->checku();
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

		$data['menusub'] = $this->genmenu('mproductos');
		$this->render(null,$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
