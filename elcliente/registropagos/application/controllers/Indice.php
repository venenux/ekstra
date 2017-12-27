<?php //controlador index para elcliente

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Indice extends CI_Controller
{

	public function __construct()
	{
			parent::__construct();
			$this->load->library('session');
			$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT',TRUE);
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0', TRUE);
			$this->output->set_header('Pragma: no-cache', TRUE);
			$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
			$this->output->enable_profiler(TRUE);
	}


	public function index()
	{
			if( $this->session->userdata('logueado') !== TRUE)
			{ 
				$this->load->helper('url');
				redirect('login_usuario/iniciarsesion/?errl=Debe iniciar sesion con usuario y clave valida');
			}
			else
			{  
				$sDatausuario['controlador']	='RegistroPagos ';
				$this->load->helper(array('form', 'url','html'));
				$this->load->view('view_header');
				$this->session->set_userdata($sDatausuario);
				$this->load->helper('url');
				redirect('Registropagos/index', 'refresh');
			} 
	}

}
?>
