<?php


/**
 * Async Class para almacen, Makes process continue until ends
 * @package   Async
 * @author    PICCORO lenz McKAY
 * @copyright Copyright (c) 2016, PICCORO
 */
class Async
{

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/*
	 * llamada http iondependiente por medio de socket, emula un formulario
	 * name: tobackground 
	 * @param array $parametros cada key es el nombre de cada valor que se enviara por POST
	 * @param string $moduleurlandmethod url a partir de index.php/ del modulo y methodo a invocar en el sistema
	 * @return void
	 */
	function tobackground($parametros,$moduleurlandmethod)
	{
		if ($parametros == NULL)
			return FALSE;
		if (!is_array($parametros))
			return FALSE;
		$post_string = http_build_query($parametros);
		$parts = parse_url( site_url($moduleurlandmethod));
		$errnom = 0;
		$errstr = "";
		//For secure server
		//$fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errnom, $errstr, 30);
		//For localhost and un-secure server
		//$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errnom, $errstr, 30);
		$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errnom, $errstr, 30);
		if(!$fp)
			return FALSE;
		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		if (isset($post_string))
			$out.= $post_string;
		fwrite($fp, $out);
		fclose($fp);
		return TRUE;
	}
}
?>
