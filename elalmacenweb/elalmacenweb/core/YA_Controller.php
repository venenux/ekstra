<?php

/**
 * elyanero Application Controller Class, super clase que todo controlador hereda para funciones comunes
 *
 * @author		Lenz McKAY PICCORO
 */
class YA_Controller extends CI_Controller
{
	/** inicia y en checku se asigna a la url pedida por get/post userurl */
	public $userurl = NULL;
	/** inicia y en checku se asigna a la url pedida por controller/metodo */
	public $currenturl = NULL;
	/**  permiso cargado en el controler en cda request */
	public $permite = FALSE;
	/**  nombre de usuario tomado de la session activa */
	public $username = FALSE;

	public function __construct($module = NULL)
	{
		parent::__construct();

		$this->load->helper(array('form', 'url','html'));
		$this->load->library('table');
		$this->load->library('encrypt'); // TODO buscar como setiear desde aqui key encrypt
		$this->load->library('session');
		$this->load->library('login');

		$this->permite = $this->login->usercheck();

		$this->output->set_header("HTTP/1.1 200 OK");
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");

		$this->output->enable_profiler(TRUE);
		if( $module != NULL)
			$module = str_replace(' ', '', $module);
	}

    /** revision de session, si invalidad redirige a login */
    public function checku()
    {
        $this->userurl = $this->input->get_post('userurl');
        $this->currenturl = $this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4);

        $redirurl = $this->currenturl;
        $permited = $this->permite;

        if( $permited == 0 OR $permited == FALSE)
            redirect('indexlogin/entrarlogin?userurl='.$redirurl,'location');

		$usernamechecked = $this->session->userdata('username');
		$this->username = $usernamechecked;

        if( trim($this->userurl) != '' and trim($this->currenturl) != '')
            $redirurl = $this->userurl;
    }

    /** generacion temporal d emenu para que se avanze en desarrollo */
    public function menu()
    {
        $permited = $this->permite;
//        $permited = $this->login->usercheck();
        if( $permited == 0 OR $permited == FALSE)
            return '';
        $menuarraymain['0'] = anchor('indexlogin/salirlogin','Salir','class=" btn btn-10 form" ');
        $menuarraymain['1'] = anchor('mproductos/oaproductos/index','Buscar productos','class=" btn btn-10 form" ');
        $menuarraymain['2'] = anchor('mcierreventa/mcierreventa/index','Cierre/replicacion','class=" btn btn-10 form" ');
        $this->table->clear();
        $this->table->add_row($menuarraymain);
        return $menu1 = $this->table->generate();
        
    }

    /** permite repintar sin llamar tanto o escribir tanto, automaticamente carga header y footer */
    public function render($view, $data = NULL) 
    {
        if( !isset($data['currenturl']) )
            $data['currenturl'] = $this->currenturl;
        if( !isset($data['userurl']) )
            $data['userurl'] = $this->userurl;
        if( !isset($data['menu']) )
            $data['menu'] = $this->menu();
        $this->load->view('header',$data);
        if(!is_array($view) )
		{
			$this->load->view($view, $data);
        }
        else
		{
			foreach($view as $vistas=>$vistacargar)
				$this->load->view($vistacargar, $data);
		}
		$this->load->view('footer',$data);
    }

}

