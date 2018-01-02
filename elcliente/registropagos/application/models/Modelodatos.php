<?php
/* 
* Este código es un modelo donde  se devuelve un conjunto de valores  en un array
*  emulando el resultado de un query en una bd 
*/
class Modelodatos extends CI_Model 
{
	public $laurl = array();

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->laurl['0'] = base_url('../elsistemaweb/index.php/indexcontrol/indexverificar');
		$this->laurl['1'] = anchor('http://intranet1.net.ve/elsistemaweb/index.php/indexcontrol/indexverificar');
		$this->load->library('curl');
	}
	/******************************** **********************************  ******************************* ************
	* ***** **         funciones     del modelo  en uso y en edición                                                                                  * ***** ** 
	******************************** **********************************  ******************************* *************/
	public function get_Datos_Users($modulo = '0')
	{
		$scurl= curl_init();
		curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($scurl, CURLOPT_URL, $this->laur[$modulo]);
		$ladata=curl_exec($scurl);
		curl_close($scurl);
		$result = json_decode($ladata,true);
		return $result; 
	}

	public function get_patrimonios_by_code($xcode, $modulo = '0')
	{
		$laurl=$this->laurl[$modulo].$xcode;
		$scurl= curl_init();
		curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($scurl, CURLOPT_URL, $laurl);
		$ladata=curl_exec($scurl);
		curl_close($scurl);
		$result = json_decode($ladata,true);
		return $result; 
	}

	public function get_patrimonios_by_user($xcode, $modulo = '0')
	{
		$laurl=$url.$xcode;
		$scurl= curl_init();
		curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($scurl, CURLOPT_URL, $this->laur[$modulo]);
		$ladata=curl_exec($scurl);
		curl_close($scurl);
		$result = json_decode($ladata,true);
		return $result; 
	}

	public function  request_for_login($losdatos,$modulo='0')
	{
		$eljson =json_encode($losdatos);
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $this->laur[$modulo]);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );
		curl_close($scurl ); 
		return $response; // devolver respuesta del servidor
	}

	public function registrarpago($parametros,$modulo = '0')
	{
		if ( ! $this->check_user($parametros) )
			return FALSE;
		$eljson =json_encode($parametros);
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $this->laur[$modulo]);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );
		curl_close($scurl ); 
		return $response;
	}

	public function check_user($userandclaveandmodulo)
	{
		if ( !is_array($userandclaveandmodulo) )
			return FALSE;
		if ( ! array_key_exists ( 'modulourl' , $userandclaveandmodulo ))
			return FALSE;
		if ( ! array_key_exists ( 'userclave' , $userandclaveandmodulo ))
			return FALSE;
		if ( ! array_key_exists ( 'username' , $userandclaveandmodulo ))
			return FALSE;
		
		$this->load->library('libu');
		$ipnet = $this->libu->getipnet('server', TRUE);
		$this->config->load('cli_modulourl');
		$urlnet = $this->config->item('modulourl'.$userandclaveandmodulo['modulourl']);
		if( strripos($ipnet,'10.10.') !== FALSE OR strripos($ipnet,'37.10.') !== FALSE)
			if ( strripos($ipnet,'37.10.') == 0)
				$urldelsistemaweb = 'http://intranet1.net.ve/'.$urlnet;
			else
				$urldelsistemaweb = 'http://10.10.34.23/'.$urlnet;
		if( strripos($ipnet,'127.0.0') !== FALSE OR strripos($ipnet,'localhost') !== FALSE)
			if ( strripos($ipnet,'127.0.0') == 0)
				$urldelsistemaweb = 'http://127.0.0.1/'.$urlnet;
			else
				$urldelsistemaweb = 'http://10.10.34.23/'.$urlnet;
		else
			$urldelsistemaweb = 'http://sabalnomina.no-ip.org/'.$urlnet;
		log_message('info', 'Intentanto validar request hacia '.$urldelsistemaweb);
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $urldelsistemaweb);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,http_build_query($userandclaveandmodulo));
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );
		curl_close($scurl ); 
		return $response; // devolver respuesta del servidor
	}



}


