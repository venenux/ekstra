<?php


/**
 * Async Class para elyanero, Makes process continue until ends
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
	 * 
	 * name: tobackground
	 * @param string $url llamada a el metodo modelo que invoca el sp
	 * @return void
	 * 
	 */
	function tobackground($url, $params)
	{
		$post_string = http_build_query($params);
		$parts = parse_url($url);
			$errno = 0;
		$errstr = "";

	   //Use SSL & port 443 for secure servers
	   //Use otherwise for localhost and non-secure servers
	   //For secure server
		//$fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);

		//For localhost and un-secure server
	   $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
		if(!$fp)
		{
			echo "Something Went Wrong";
		}
		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		if (isset($post_string)) $out.= $post_string;
		fwrite($fp, $out);
		fclose($fp);
  }
}
?>
