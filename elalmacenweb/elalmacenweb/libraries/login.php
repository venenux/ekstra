<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('PHPASS_STRENGTH', 8);

/**
 * Login Class para elyanero, Makes authentication simple
 *
 * @package   Login
 * @author    PICCORO lenz McKAY
 * @copyright Copyright (c) 2016, PICCORO
 */
class Login
{
	protected $CI; // CodeIgniter object

	public function __construct()
	{
		$this->CI =& get_instance();
	}


	/**
	 * validation user credentials, instancia el modelo esk_usuario y verifica las credenciales en db
	 *
	 * @access	public
	 * @param	string    usuaername o ficha
	 * @param	string    userclave
	 * @return	integer   TRUE/1 si credenciales validas
	 */
	function userlogin($user_email = '', $user_pass = '') 
	{

		if($user_email == NULL OR $user_pass == NULL)
			return 0;//false es cero universalmente

		$user_email == str_replace(' ', '', $user_email);
		$user_pass == str_replace(' ', '', $user_pass);

		if($user_email == '' OR $user_pass == '')
			return 0;//false es cero universalmente

		$usuario = array('cuantos' => 0);
		log_message('info', 'Usuario access ' . $user_email . ' from '.$this->CI->input->ip_address());
		$this->CI->load->model('esk_usuario');
		$usuarios = $this->CI->esk_usuario->getusuario($user_email, $user_pass);

		if( is_array($usuarios) )
			$usuario = $usuarios[0];
		else
			return 0;//false e universal 0, aqui error en db
		if ($usuario['cuantos'] < 1 OR $usuario['cuantos'] > 1) //user_email already exists
			return 0;//false es cero universalmente

		$this->CI->load->library('encrypt');
		$this->CI->load->library('session');
		// si hubo cambio de ip/navegador destruye y reinicia, seguridad!
		if($this->CI->session->userdata('username') != $usuario['username'])
		{
			$this->CI->session->sess_destroy();
			$this->CI->session->sess_create();
			$usuario['ses_ip'] = $this->CI->input->ip_address();
			$usuario['ses_cookie'] = $this->CI->session->userdata('session_id');;
			$usuario['userlogin'] = 1;
		}
		$this->CI->session->set_userdata($usuario);

		$filter = "ficha = '".$usuario['ficha']."' AND username = '".$usuario['username']."'";
		$datauser['ses_ip'] = $this->CI->input->ip_address();
		$datauser['ses_cookie'] = $this->CI->session->userdata('session_id');
		$datauser['sessionflag'] = date('YmdHis').'.'.$this->CI->input->ip_address();
		$permited = $this->CI->esk_usuario->updusuario($datauser, $filter);
		if($permited != TRUE OR $permited == 0)
			return 0;//false es cero universalmente
		log_message('info', 'Usuario session ' . $user_email . '/'.$usuario['username'].' - '.$this->CI->input->ip_address());
		return 1;
	}

	/**
	 * Logout user, if user are null logout current user, mata la sesion en CI y sale de la app
	 *
	 * @access	public
	 * @param	string    usuaername o ficha si no se define detecta el actual
	 * @return	void
	 */
	function userlogout($user_email= null) 
	{
		$permited = FALSE;
		if($user_email == null) 
			$user_email = $this->CI->session->userdata('username');
		$user_email == str_replace(' ', '', $user_email);

		$usuario['userlogin'] = 0;	// 0 es false universal
		$this->CI->load->library('encrypt');
		$this->CI->load->library('session');
		$this->CI->session->sess_destroy();
		$this->CI->session->set_userdata($usuario);

		if($user_email != null and $user_email != '')
		{
			$filter = " username = '".$user_email."'";
			$datauser['ses_ip'] = '';
			$datauser['ses_cookie'] = '';
			$datauser['sessionlast'] = date('YmdHis').'.'.$this->CI->input->ip_address();
			$this->CI->load->model('esk_usuario');
			$permited = $this->CI->esk_usuario->updusuario($datauser, $filter);
		}
		log_message('info', 'Usuario logout ' . $user_email . '='.$permited.' from '.$this->CI->input->ip_address());
	}


	/**
	 * check session user, if user are null check if there a currentl loged in user in true
	 *
	 * @access	public
	 * @param	string    usuaername o ficha, si no se provee detecta el actual
	 * @return	integer   0/FALSE si el usuario no es ya valido
	 */
	function usercheck($user_email= null) 
	{

		$this->CI->load->library('encrypt');
		$this->CI->load->library('session');
		$userlogin = $this->CI->session->userdata('userlogin');
		if($userlogin == FALSE)
			return 0;
		if($userlogin < 1 OR $userlogin > 1)
			return 0;

		$username = $this->CI->session->userdata('username');
		$userclave = $this->CI->session->userdata('userclave');

		if($user_email == null) $user_email = $username;
			$user_email == str_replace(' ', '', $user_email);
		if($userclave == FALSE OR $username == FALSE)
			return 0;
		$user_pass = $userclave;

		$this->CI->load->model('esk_usuario');
		$usuarios = $this->CI->esk_usuario->getusuario($user_email, $user_pass);

		if( is_array($usuarios) )
			$usuario = $usuarios[0];
		else
			return 0;
		if ($usuario['cuantos'] < 1 OR $usuario['cuantos'] > 1) //usuario no valido, si mas de uno es seguridad invalida
			return 0;//false es cero universalmente

            $filter = "userclave = '".$userclave."' AND username = '".$username."'";
            $datauser['ses_ip'] = $this->CI->input->ip_address();
            $datauser['ses_cookie'] = $this->CI->session->userdata('session_id');
            $datauser['sessionflag'] = date('YmdHis').'.'.$this->CI->input->ip_address();
            $permited = $this->CI->esk_usuario->updusuario($datauser, $filter);
            log_message('info', 'Usuario session ' . $user_email . '/'.$username.' from '.$this->CI->input->ip_address());
            return 1;
    }



	/**
	* Edit a user password ONLY BY INTRANET!
	* @author    PICCORO Lenz McKAY <mckaygerhard[at]gmail.com>
	*
	* @access  public
	* @param  string Username
	* @param  string older passowrd
	* @param  string newer password
	* @return integer 0 si no se pudo o usuario invalido
	*/
	function userpass($user = '', $old_pass = '', $new_pass = '')
	{
		if($user == null OR $old_pass == null OR $new_pass == null)
			return 0; // 0 es universalmente falso

		$user == str_replace(' ', '', $user);
		$new_pass == str_replace(' ', '', $new_pass);
		$user_pass == str_replace(' ', '', $old_pass);

		//Make sure account info was sent
		if($user == '' OR $user_pass == '' OR $new_pass == '')
			return 0; // 0 es universalmente falso

		//Check against user table
		$sqluser = "SELECT * FROM ".$this->user_table." WHERE ficha = ? OR username = ?";
		$query = $this->CI->db->query($sqluser, array($user, $user));
		
		if ($query->num_rows() < 1 OR $query->num_rows() > 1) //user_email does not exist, not updating
			return 0; // 0 es universalmente falso

		$rowuser = $query->row();
		$sessionlast = $rowuser->sessionlast;
		$username = $rowuser->username;
		$userficha = $rowuser->userficha;
		//Hash user_pass using phpass when i property integrated phppass
		//$hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		//$user_pass_hashed = $hasher->HashPassword($user_pass);

		//Update account into the database
		$filter = "ficha = ".$userficha." AND username = ".$username." AND userclave = ".$old_pass;
		$data = array();
		$data['sessionflag'] = date('YmdHis');
		$data['userclave'] = $new_pass;

		$sqluser = $this->db->update_string($this->user_table, $data, $filter);
		return $this->CI->db->query($sqluser);

	}

	/**
	 * information user non sensible
	 *
	 * @access	public
	 * @param	string username
	 * @return	string tabla HTML con la info formateada
	 */
	function userinfo($user_email = null) 
	{

		if($user_email == null)
			$user_email = $this->CI->session->userdata('username');
		if($user_email != null)
			$user_email = str_replace(' ', '', $user_email);

		$this->CI->load->model('esk_usuario');

        log_message('info', 'Usuario info ' . $user_email . ' from '.$this->CI->input->ip_address());
        $usuarios = $this->CI->esk_usuario->getuserinfo($user_email);
        if( !is_array($usuarios) )
            return 'sin informacion';

        $this->CI->load->library('table');
        $this->CI->table->clear();
		$tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
        $this->CI->table->set_template($tablapl);
        $this->CI->table->set_heading(array_keys($usuarios[0]));
        $this->CI->table->add_row(array_values($usuarios[0]));
        $infodata = $this->CI->table->generate();

		log_message('info', 'Usuario session ' . $user_email .' from '.$this->CI->input->ip_address());

		return $infodata;
	}


}
?>
