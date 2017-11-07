<?php
/* 
* Este código es un modelo donde  se devuelve un conjunto de valores  en un array
*  emulando el resultado de un query en una bd 
*/
class Modelodatos extends CI_Model 
{ 

	public function __construct() 
	{
		parent::__construct();
		$this->load->library('curl');
	}
	/******************************** **********************************  ******************************* ************
	* ***** **         funciones     del modelo  en uso y en edición                                                                                  * ***** ** 
	******************************** **********************************  ******************************* *************/
	public function get_Datos_Users()
	{
		$laurl =$this->config->item('json_get_users'); 
		$scurl= curl_init();
		curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($scurl, CURLOPT_URL,$laurl);
		$ladata=curl_exec($scurl);
		curl_close($scurl);
		$result = json_decode($ladata,true);
		return $result; 
}

public function get_patrimonios_by_code($xcode)
{//
		$url =$this->config->item('json_get_patrim'); 
		$laurl=$url.$xcode;
		$scurl= curl_init();
		curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($scurl, CURLOPT_URL,$laurl);
		$ladata=curl_exec($scurl);
		curl_close($scurl);
		$result = json_decode($ladata,true);
		return $result; 
}

	public function  request_for_login($losdatos,$modulo)
	{
		$this->load->helper('url');
		$laurl['0'] = base_url('../elsistemaweb/index.php/indexcontrol/indexverificar');
		$laurl['1'] = anchor('http://intranet1.net.ve/elsistemaweb/index.php/indexcontrol/indexverificar');
		$eljson =json_encode($losdatos);
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $laurl[$modulo]);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );
		curl_close($scurl ); 
		return $response; // devolver respuesta del servidor
	}

public function registrarpago( $parametros  )
{ 
		$laurl =$this->config->item('json_post_registropagos');
		$eljson =json_encode($parametros);
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $laurl);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );
		curl_close($scurl ); 
		return $response;
}
}


