<?php //controlador para login

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Indexlogin extends CI_Controller
{

	public function __construct()
	{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('curl');
			$this->load->model('Modelodatos');
			$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT',TRUE);
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0', TRUE);
			$this->output->set_header('Pragma: no-cache', TRUE);
			$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
			$this->output->enable_profiler(TRUE);
	}


	public function index($errl=null)
	{
			$this->_desloguear();
			$this->load->helper(array('form', 'url','html'));
			if( $errl === null )
				$errl = $this->input->get_post('errl');
			$data['errl'] = $errl;
			$this->load->view('iniciosesion', $data);
	}

	public function salir($errl=null)
	{
			$this->_desloguear();
			if( $errl === null )
				$errl = $this->input->get_post('errl');
			$this->index($errl);
	}

	private function _desloguear()
	{
			$sDatausuario = null;
			$sDatausuario = array('logueado' => FALSE);
			$this->session->set_userdata($sDatausuario);
				$this->session->sess_destroy();
			$this->session->set_userdata($sDatausuario);
	}

	public function iniciarsesion($errl=null)
	{ 
			$this->load->library('curl');
			$sLogin= str_ireplace(' ','',$this->input->post('username'));
			$sPass = str_ireplace(' ','',$this->input->post('userclave'));
			$sMurl=str_ireplace(' ','',$this->input->post('modulourl'));
			if( $errl === null )
				$errl = $this->input->get_post('errl');
			if ($sLogin!='' AND $sPass!='' )
			{
						$transfsLogin=md5($sLogin);
						$transfsPass=md5($sPass);
						$arreglopost=array ('username'=> $transfsLogin, 'userclave'=> $transfsPass, 'modulourl'=>$sMurl) ;
						$response= $this->Modelodatos->check_user($arreglopost);
						$dataresponse=json_decode($response,TRUE);
						$errl = 'No se recibio respuesta, decodificacion erronea. ';
						if ( !is_array($dataresponse) )
							return $this->index($errl);
						if ( array_key_exists('username',$dataresponse) ) 
							$sDatausuario['username'] = $transfsLogin ;		// recibe en md5 tambien
						if ( array_key_exists('userclave',$dataresponse) ) 
							$sDatausuario['userclave'] = $transfsPass ;		// recive en md5 tambien
						if ( array_key_exists('userclave',$dataresponse) ) 
							$sDatausuario['userclave'] = $sPass ;
							if (is_array($dataresponse) AND array_key_exists('status',$dataresponse) )
							{
								$sDatausuario['username']	= $sLogin ;	
								$sDatausuario['logueado'] = TRUE;
								$sDatausuario['accionpagina']='logueado';
								$sDatausuario['controlador']	='RegistroPagos ';
								$sDatausuario['modulo']	=$smodulo;
								$this->load->helper(array('form', 'url','html'));
								$this->load->view('view_header');
								$this->session->set_userdata($sDatausuario);
								redirect('Registropagos/index', 'refresh');
							} 
						$errl = 'Existe un error  en el Servidor: la respuesta recibida no era la esperada. ';
						$this->index($errl);
			} 
			else
			{
				$errl = $this->input->get_post('errl'); 
				if( $errl == '' )
					$errl = 'usuario y/o clave invalidos';
				$this->index($errl);
			}
	}


	// funcion para determinar http response code  porque esta version de php no la tiene
	function http_response_code($code = NULL)
	{
			if ($code !== NULL)
			{
					switch ($code) 
					{
							case 100: $text = 'Continue'; break;
							case 101: $text = 'Switching Protocols'; break;
							case 200: $text = 'OK'; break;
							case 201: $text = 'Created'; break;
							case 202: $text = 'Accepted'; break;
							case 203: $text = 'Non-Authoritative Information'; break;
							case 204: $text = 'No Content'; break;
							case 205: $text = 'Reset Content'; break;
							case 206: $text = 'Partial Content'; break;
							case 300: $text = 'Multiple Choices'; break;
							case 301: $text = 'Moved Permanently'; break;
							case 302: $text = 'Moved Temporarily'; break;
							case 303: $text = 'See Other'; break;
							case 304: $text = 'Not Modified'; break;
							case 305: $text = 'Use Proxy'; break;
							case 400: $text = 'Bad Request'; break;
							case 401: $text = 'Unauthorized'; break;
							case 402: $text = 'Payment Required'; break;
							case 403: $text = 'Forbidden'; break;
							case 404: $text = 'Not Found'; break;
							case 405: $text = 'Method Not Allowed'; break;
							case 406: $text = 'Not Acceptable'; break;
							case 407: $text = 'Proxy Authentication Required'; break;
							case 408: $text = 'Request Time-out'; break;
							case 409: $text = 'Conflict'; break;
							case 410: $text = 'Gone'; break;
							case 411: $text = 'Length Required'; break;
							case 412: $text = 'Precondition Failed'; break;
							case 413: $text = 'Request Entity Too Large'; break;
							case 414: $text = 'Request-URI Too Large'; break;
							case 415: $text = 'Unsupported Media Type'; break;
							case 500: $text = 'Internal Server Error'; break;
							case 501: $text = 'Not Implemented'; break;
							case 502: $text = 'Bad Gateway'; break;
							case 503: $text = 'Service Unavailable'; break;
							case 504: $text = 'Gateway Time-out'; break;
							case 505: $text = 'HTTP Version not supported'; break;
							default:
									exit('Unknown http status code "' . htmlentities($code) . '"');
							break;
							}
					$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
					header($protocol . ' ' . $code . ' ' . $text);
					$GLOBALS['http_response_code'] = $code;
			} 
			else
			{
					$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
			}
			return $code;
	}

}
	?>












