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
}


