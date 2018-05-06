<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Indexlogin elyanero Controller Class de inicio, es el index controler de login y info user
 *
 * @author		PICCORO Lenz McKAY
 */
class Indexlogin extends YA_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/**	entrada index si no se especifica destiino del controlador */
	public function index()
	{
		$redirurl = $this->currenturl;
		$permited = $this->permite;

		if( $permited == 0 OR $permited == FALSE)
			redirect('indexlogin/entrarlogin?userurl='.$redirurl,'location');
		else
			$this->infologin();
	}

	/**
	 * cierra la sesion y renderiza la pantalla de inicio para otra sesion
	 *
	 * @access	public
	 * @param	string  data
	 * @return	void
	 */
	public function salirlogin($data=NULL)
	{
		$data = array();
		$this->login->userlogout();
		$this->render('indexlogin',$data);
	}

    /**
	 * renderiza informacion no sensible del usuario
	 *
	 * @access	public
	 * @param	string  data
	 * @return	void
	 */
	public function infologin($data=NULL)
	{
		$data = array();
        $this->checku();
        $data['presentar'] = $this->login->userinfo();
        $this->render('infologin',$data);
	}

    /**
	 * renderiza un inicio de sesion, para usuario y clave nuevas, si exitoso, redirecciona a patalla info
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
    public function entrarlogin()
    {
        $username = $this->input->post('username1');
        $userclave = $this->input->post('userclave1');
        $userurl = $this->input->get_post('userurl');
        if(!isset($userurl))
	    $userurl = 'indexlogin/infologin';
	$access = $this->login->userlogin($username, $userclave);
        if($access == 0 OR $access < 1)
            redirect('indexlogin/salirlogin?userurl='.$userurl,'location');
        else
            redirect($userurl,'location');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
