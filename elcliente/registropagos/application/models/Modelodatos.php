<?php
/* 
This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License v3 or any other.
* 
* Este código es un modelo donde  se devuelve un conjunto de valores  en un array
*  emulando el resultado de un query en una bd 
*/
class Modelodatos extends CI_Model 
{ 

public function __construct() 
{
parent::__construct();
// aqui  se carga la libreria Curl hecha para codeigniter 2.algo 
	$this->load->library('curl');
}
// envia las cadenas en un json al emudb
	 
			 //que permite enviar las cadenas capturadas a emudb 
				
			
			    // aja ya termino lo facil ahora empieza el arte de lanzar fechas,
			    // este... digo... programar web
//~ /***** 

/******************************** **********************************  ******************************* ************
* ***** **         funciones     del modelo  en uso y en edición                                                                                  * ***** ** 
******************************** **********************************  ******************************* *************/
public function get_Datos_Users()
{// solicita los datos usando curl  a la ruta establecida
    // usando get en emudb
 
           // establecer la ruta configurada en /config/config.php  se usa actualmente  emudb (Aqui Hay Copy Rights, nada de coPy lEftz )        
           // actual ='http://127.0.0.1/~systemas/CodeEmudb/index.php/api/emudb/users';

          $laurl =$this->config->item('json_get_users'); 
       
         
			
    		 // habilitar el curl()
				$scurl= curl_init();
				// Disable SSL verification
				curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($scurl, CURLOPT_URL,$laurl);
				// Execute
				$ladata=curl_exec($scurl);
				// Closing
				curl_close($scurl);
	             $result = json_decode($ladata,true);
         ;
       ///
               return $result; 

}
public function get_patrimonios_by_code($xcode)
{// solicita los datos usando curl 
    // usando get en emudb
         // establecer la ruta configurada en /config/config.php  se usa actualmente  emudb (Aqui Hay Copy Rights, nada de coPy lEftz ) 
         // por ahora es:  http://127.0.0.1/~systemas/CodeEmudb/index.php/api/emudb/patxcode/beta/';
          $url =$this->config->item('json_get_patrim'); 
         

             /// hacer la manipulacion  a la url para agrgarle el codigo:
			   $laurl=$url.$xcode;
    		 // habilitar el curl()
				$scurl= curl_init();
				// Disable SSL verification
				curl_setopt($scurl, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($scurl, CURLOPT_URL,$laurl);
				// Execute
				$ladata=curl_exec($scurl);
				// Closing
				curl_close($scurl);
	          $result = json_decode($ladata,true);
         ;
       ///
               return $result; 

} // fin

public function  request_for_login($losdatos)
{ // se toma el login el password y el modulo a acceder para determinar si hay sesion o no
			 
			 // establecer la ruta configurada en /config/config.php  se usa actualmente  emudb (Aqui Hay Copy Rights, nada de coPy lEftz ) 
         // por ahora es:  http://127.0.0.1/~systemas/CodeEmudb/index.php/api/emudb/patxcode/beta/';
               $laurl =$this->config->item('json_post_login');
               
				// crear el json
				$eljson =json_encode($losdatos);
				$scurl = curl_init();
			   curl_setopt($scurl, CURLOPT_URL, $laurl);
				curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($scurl , CURLOPT_POST, 1);
				curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
				curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
				$response  = curl_exec($scurl );
				curl_close($scurl ); 
				
		
			/*		curl_setopt($scurl, CURLOPT_URL, $laurl);
					curl_setopt($scurl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($eljson)));
					curl_setopt($scurl, CURLOPT_CUSTOMREQUEST, 'PUT');
					curl_setopt($scurl, CURLOPT_POSTFIELDS,$eljson);
					curl_setopt($scurl,CURLOPT_RETURNTRANSFER, true);
					$response  = curl_exec($scurl);
					curl_close($scurl);*/
				
				
               return $response; // esto no se sabe si funciona
}//fin

public function registrarpago( $parametros = array() )
{ // mandar esto usando json al emulador  (función no probada)
				/*  la url donde se va a mandar el json... esto puede cambiar porque se está usando la de prueba (emudb)
				 por ahora esta es una prueba, pero estando la data en json puede ir hasta
				 la base de datos real..*/
				$laurl ='http://127.0.0.1/~systemas/CodeEmudb/index.php/welcome/recibir_datos_sesion'; 
				
				//codificar el array:a json:
				$eljson=json_encode($parametros);
				//  crear la sesion cURL 
				$this->curl->create($laurl);
				// preparar el envio del json y determinar cual es el contenido del post:
				$this->curl->option(CURLOPT_HTTPHEADER, array('Content-type: application/json; Charset=UTF-8'));
				// preparar el post
				$this->curl->post($eljson);
				// ejecutar -enviar - el post
				echo $result=$this->curl->execute();   
				// luego el emulador recibe el json, lo decodifica y puede enviar la data a donde deba mandarlo
}// fin registrarpagos


/******************************** **********************************  ******************************* ************
* funciones experimentales de este modelo, Se mantienen como ejemplos pero pueden ser eliminadas en el futuro 
******************************** **********************************  ******************************* *************/

public function getDatos()
{
					$user1=array();
					$user2=array();
					$user3=array();

					 $user1 ['login'] = 'user1'; 
					 $user1 ['clave']=  '321';
					 $user1 ['codarrendatario']='ARR100521';
					   
					 
					 $user2 ['login'] = 'user2';
					 $user2 [ 'clave']=  '123';
					 $user2 ['codarrendatario']='ARR300722' ;
					   
					 $user3 ['login'] = 'user3'; 
					 $user3['clave']=  '213';
					 $user3['codarrendatario']= 'ARR800823';
					 

					 $fullusers=array('1'=> $user1 ,'2'=>$user2,'3'=>$user3);
						
					return  $fullusers;
}// fin

public function getRegistros($username)
{
					$arreglopatrimonios['cod_arrendatario'] = '700125522';
					$arreglopatrimonios['username'] = $username;
					$arreglopatrimonios['userclave'] = '321';
					$pati1 = array('debe'=>22300, 'desde'=>'20170530', 'des_patrimonio'=>'Local 7 Oficina 4 Ruices','codpatrimonio'=>'PAT20170101010101');
					$pati2 = array('debe'=>55690, 'desde'=>'20170810', 'des_patrimonio'=>'Galpon Valencia','codpatrimonio'=>'PAT20170101010102');
					$pati3 = array('debe'=>232342, 'desde'=>'20170630', 'des_patrimonio'=>'Local  2 oficina  10 Lido','codpatrimonio'=>'PAT20170101010103');
					$arreglopatrimonios = array('PAT20170101010101'=>$pati1, 'PAT20170101010102'=>$pati2, 'PAT20170101010103'=>$pati3);

					return $arreglopatrimonios;
	}

public function setRegistros($username, $parametros = array() )
{
				$arreglopatrimonios['cod_arrendatario'] = '700125522';
				$arreglopatrimonios['username'] = $username;
				$arreglopatrimonios['userclave'] = '321';
				//	$arreglopatrimonios['patrimonios'] = array('PAT20170101010102'=>'local oficina', 'PAT20170101010101'=>'local galpon');

				if (!is_array($parametros) )
				return FALSE;
				return $arreglopatrimonios;
}






}// fin del modelol
