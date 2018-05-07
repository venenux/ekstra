<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Indexmalmacen elalmacenweb fugaz Controller Class index de almacen
 *
 * @package     mcierreventa
 * @author      Lenz McKAY PICCORO
 */
class Indexmalmacen extends YA_Controller {

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
		$data['menusub'] = $this->genmenu('malmacen');
		$this->render(null,$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
