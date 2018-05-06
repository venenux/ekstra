<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indexcontroler extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','html'));
		$this->load->library('table');
		$this->output->enable_profiler(TRUE);
	}

	/**	http://127.0.0.1/codeigniterpower/index.php/indexcontroler/index */
	public function index()
	{
		$data = array();
		$this->load->view('header.php',$data);
		$this->load->view('inicion.php',$data);
		$this->load->view('footer.php',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
