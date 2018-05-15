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

	/* 
	 * la logica de menu esta descrita en docs/desarrollo-gencontroler-y-menu.md
	 * name: genmenu genera un menu de enlaces plano usando `getcontrollers` segun los nombres de controladores del directorio
	 * @param string $moduledir nombre del directorio de controllers especifico sino directorios de modulos
	 * @return string html table con los nombres de archivos de controladores o los directorios si no se especifica modulo dir
	 */
    public function genmenu($modulename = NULL)
    {
        $permited = $this->permite;
        if( $permited == 0 OR $permited == FALSE)
            return '';
		$arraylinkscontrollers = $this->getcontrollers($modulename);
		if($modulename == NULL OR $modulename == '')
		{
			foreach($arraylinkscontrollers as $menuidex=>$menulink)
			{
				if(strpos($menulink,'m') >0 OR strpos($menulink,'index')>0)
				{
					//$menuname = $menulink;
					$findname = '/'.$modulename.'/';
					$menuname = preg_replace($findname, '', $menulink, 1);
					$menuname = stristr($menuname,'index');
					$menuname = str_replace('indexm','',$menuname);
					$menuname = ucfirst($menuname);
					//$menuname = strpos($menuname,'m');
					if(stripos($menulink,'ndexm') >0 AND stripos($menulink,'ndexm')<9)
						$menuarraymain[$menuidex] = anchor($menulink,ucfirst($menulink.'('.$menuname ),'class=" btn btn-10 form" ');
					if(stripos($menuname,'ndex') >0 AND stripos($menuname,'ndex')<7)
						$menuarraymain[$menuidex] = anchor($menulink,ucfirst($menuname),'class=" btn btn-10 form" ');
				}
			}
			$menuarraymain[] = anchor('indexlogin/salirlogin','Salir','class=" btn btn-10 form" ');
		}
		else
		{
			// TODO hacer logica para submenu
			foreach($arraylinkscontrollers as $menuidex=>$menulink)
			{
				$menuname = stristr($menulink,'m');
				$findname = '/'.$modulename.'/';
				$menuname = preg_replace($findname, '', $menulink, 1);
				$menuname = str_replace('/','',$menuname);
				$menuname = ucfirst($menuname);
				$menuarraymain[$menuidex] = anchor($menulink,$menuname,'class=" btn btn-10 form" ');
			}
		}
		$this->table->clear();
		$this->table->add_row($menuarraymain);
		return $this->table->generate();
		echo $menuarraymain;
    }

	/* 
	 * esta logica esta descrita en docs/desarrollo-gencontroler-y-menu.md
	 * name: getcontrollers obtiene nombre de controladores o nombre de directorios de controladores
	 * @param string $moduledir nombre del directorio de controllers especifico sino directorios de modulos
	 * @return array con los nombres de archivos de controladores o los directorios si no se especifica modulo dir
	 */
	public function getcontrollers($moduledir = NULL)
	{
		if($moduledir == NULL)
			$moduledir = '';
		$controllers = array();
		$moduledir = str_replace(' ','',$moduledir);
		// Scan files in the /application/controllers{moduledir} directory
		$this->load->helper('file');
			$files = get_dir_file_info(APPPATH.'controllers/'.$moduledir, TRUE);
		if(!is_array($files) OR count($files)<1)
			$files = get_dir_file_info(APPPATH.'controllers/', TRUE);
		foreach(array_keys($files) as $file)
		{
			if( strpos($file,'htm') !== FALSE)
				continue;
			if( strpos($file,'php') === FALSE AND $moduledir == '')
				$name = str_replace(EXT, '', $file).'/index'.str_replace(EXT, '', $file);
			else
				$name = $moduledir.'/'.str_replace(EXT, '', $file);
			$controllers[] = $name;
		}
		return $controllers;
	}

    /** permite repintar sin llamar tanto o escribir tanto, automaticamente carga header y footer */
    public function render($view, $data = NULL) 
    {
        if( !isset($data['currenturl']) )
            $data['currenturl'] = $this->currenturl;
        if( !isset($data['userurl']) )
            $data['userurl'] = $this->userurl;
		$data['menu'] = $this->genmenu();
		$this->load->view('header',$data);
		if(!is_array($view) )
		{
			if($view != '' OR $view != NULL)
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

