<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
/**
* This  code emulate a database queries.
*
* @package CodeIgniter
* @subpackage Rest Server
* @category Controller
* @author Tyrone Lucero AKA Mc Gyver
* @license  FSR-> FeniX Software Research  
* @link -not available yet!-
*/
class Emudb extends   REST_Controller {
function __construct()
{
// Construct the parent class
parent::__construct();
// Configure limits on our controller methods 
// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
$this->methods['patxcode_get']['limit']=500; // este valor hay que investigar
$this->methods['login_post']['limit']=100;
$this->methods['registrar_post']['limit']=100;
}
/*  Esta función devuelve toda la data de los usuarios      */
public function  users_get()
{ 
		/*	$user1=array();
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
			 

        $fullusers=array('1'=> $user1 ,'2'=>$user2,'3'=>$user3); */
        
            $this->load->model('Modelodatos');
	        $fullusers =$this->Modelodatos->getFullusers_data();

					if ( $fullusers)
						{
							// Set the response and exit
							$this->response( $fullusers, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
						 
							$data['json']=$this->response( $fullusers, REST_Controller::HTTP_OK); //save here this json... 
							 
						}
							else
						{
								// Set the response and exit
								$this->response(array(
								'status' => FALSE,
								'message' => 'No fueron hallados registros de Usuarios.'
								), REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
								}
}

/* Esta función  devuelve  patrimonio(s) [inmueble(s)]  especifico(s) de un usuario */

public function patxcode_get()
{ /* Generar los datos ficticios en un arreglo, el indice es el codigo arrendatario */

			
				 //datos ficticios primer arrendatario 
			/*$pati1 = array('debe'=>22300, 'desde'=>'20170530', 'des_patrimonio'=>'Local 7 Oficina 4 Ruices','codpatrimonio'=>'PAT20170101010101');
				$pati2 = array('debe'=>55690, 'desde'=>'20170810', 'des_patrimonio'=>'Galpon Valencia','codpatrimonio'=>'PAT20170101010102');
				$pati3 = array('debe'=>232342, 'desde'=>'20170630', 'des_patrimonio'=>'Local  2 oficina  10 Lido','codpatrimonio'=>'PAT20170101010103');
				// guardar los patrimonios
				$patiuser1 = array('PAT20170101010101'=>$pati1, 'PAT20170101010102'=>$pati2, 'PAT20170101010103'=>$pati3);
				//  asociar ese arrendario con sus inmuebles en un mega array
				$arraypatrimonioxarrendatario['ARR100521']=$patiuser1 ;
				//datos ficticios segundo arrendatario 
				$pati21 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento  77 Sabal','codpatrimonio'=>'PAT2107882111');
				// guardar los patrimonios
				$patiuser2=array('PAT2107882111' =>$pati21 );
				//  asociar ese arrendario con sus inmuebles en un mega array
				$arraypatrimonioxarrendatario['ARR300722']=$patiuser2 ;
				//datos ficticios tercer  arrendatario 
				$pati1 = array('debe'=>22500, 'desde'=>'20170415', 'des_patrimonio'=>'Apartamento 25 Sabana Grande','codpatrimonio'=>'PAT21078821778');
				$pati3 = array('debe'=>21342, 'desde'=>'20170215', 'des_patrimonio'=>'Local  3 oficina  20 Lido','codpatrimonio'=>'PAT201701454503');
				// guardar los patrimonios
				$patiuser3=array('PAT21078821778'=>$pati1,'PAT201701454503'=>$pati3);
				//  asociar ese arrendario con sus inmuebles en un mega array
				$arraypatrimonioxarrendatario['ARR800823']=$patiuser3;*/
				
 				    $this->load->model('Modelodatos');
	              $arraypatrimonioxarrendatario =$this->Modelodatos->getRegistros();
 				
 				// preguntar por el parametro, si no viene devolver null 
					$cod_arr = $this->get('beta');
					 if ($cod_arr === NULL) 
						{
							 // establecer  la respuesta AL NULL y cerrar/salir
								$this->response(array(
								'status' => FALSE,
								'message' => ' Parámetro no fue especificado.  No hay información.'
								), REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
						  }
						else
						{
									//el parámetro será una cadena de texto, no hay que transformar ni nada, se toma tal cual 
									// tomar ese parametro y hacer la búsqueda 
									$arrayinfo= NULL; //  no tiene nada aún
										  if (array_key_exists( $cod_arr, $arraypatrimonioxarrendatario) )
											   {
													// cargar el array para exportarlo
												
								  
													$arrayinfo= $arraypatrimonioxarrendatario[$cod_arr];
													// se manda todo ese chorizo (que no, que no  es para ti Charlie Mata Lozano)
													$this->set_response( $arrayinfo, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
													 }
										  else
												 {
													$this->set_response(array(
													 'status' => FALSE,
													 'message' => 'No existen registros asociados con el parámetro de búsqueda.'
													 ), REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
												 }
					}
	   }


public function login_post()
{  // recibe el post desde el sistema de registro pagos, especificamente desde el controlador de login 
			
						// Se asume alegremente que  el post es enviado por cientificos, aliens o unicornios inteligentes, asi 	 que esta variable $ok es TRUE	
						$ok = TRUE;
						//  determinar si el método   Request utilizado es POST, los demás no interesa
						if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0)
						{
						   //throw new Exception('Request method must be POST!');
					        // nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
					          $this->set_response(array(
											 'status' => FALSE,
											 'message' => 'El método Request debe ser POST.',
											  'httpstatus'=> 'HTTP_METHOD_NOT_ALLOWED',
											 'code'=> '405'
											 ), REST_Controller::HTTP_METHOD_NOT_ALLOWED); // HTTP_METHOD_NOT_ALLOWED ( 405) being the HTTP response code
					         	$ok = FALSE; 
					      } // = 405;
						 
					//     Asegurarse que el contenido recibido desde el   request POST 	ha sido establecido/configurado como application/json
						$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
						if(strcasecmp($contentType, 'application/json') != 0)
						{
						   //	throw new Exception('Content type must be: application/json');
						        // nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
						             $this->set_response(array(
											 'status' => FALSE,
											 'message' => 'Tipo contenido  debe ser: application/json.',
											 'httpstatus'=> 'HTTP_UNSUPPORTED_MEDIA_TYPE',
											 'code'=> '415'
											 ), REST_Controller::HTTP_UNSUPPORTED_MEDIA_TYPE); // HTTP_UNSUPPORTED_MEDIA_TYPE (415) being the HTTP response code
					            	$ok = FALSE;
							}
					 // preguntar si todo ha salido de maravillas, rociado con orina arcoiris de unicornio 
					 //  SI $ok = TRUE : ---> se sigue con este método 
				     // SI 	$ok = FALSE; --->	 no  tiene sentido continuar
				     
						  	if($ok==TRUE) 
							  {		
								  	//aqui se usa la sentencia para recibir la data del post (RAW)
											$content = trim(file_get_contents("php://input"));
									
											//se decodifica la data  RAW del post a partir del JSON recibido
											// y transformarlo a un array normal y  NO un array de objetos (libreria std)
											$datalogin = json_decode($content, true);
											/*
																	       
									       /*							       		      */
											// determinar si JSON is invalido
											if(!is_array($datalogin))
												 {
														//throw new Exception('Received content contained invalid JSON!');
														   // nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
															 $this->set_response(array(
																 'status' => FALSE,
																 'message' => ' El contenido JSON recibido es invalido.',
																'httpstatus'=> ' HTTP_UNPROCESSABLE_ENTITY',
																 'code'=> '422'
																 ), REST_Controller:: HTTP_UNPROCESSABLE_ENTITY); //  HTTP_UNPROCESSABLE_ENTITY (422) being the HTTP response code
												}
												else
												//  se hace la busqueda  par hallar el usuario  y la contraseña
												{    
														  // preparar le ciclo de búsqueda
																 $salir=FALSE;
														        
																  $i= 1;
																  // obtener los usuarios desde el modelo emulatronio de la bd (faakeee)
																  $this->load->model('Modelodatos');
																   $datausers =$this->Modelodatos->getFullusers_data();
																   $max= 	count($datausers );	
												                   //preparar estas cadenas con las que contiene el post para chequear 
																 // $arreglopost=array ('fx'=> $sLogin, 'dy'=> $sPass, 'z0'=>$smodulo) );
													
																   $login=$datalogin['fx'];
															       $pass =$datalogin['dy'];
												    			   $datauser=array();
																
																			while (($salir==FALSE)&& ($i <=$max ))
																			  { 	   // acceder al elemento en la posicion i
																						   $datauser	=$datausers[$i];
																						   //comparar si la  contraseña y el login coinciden con lo que esta en el arreglo pero en MD5
																					       // luego será usado SHA2 o lo que sea																						
																							$md5log=md5($datauser['login']);
																							$md5pas=md5($datauser['clave']);
																							  if (( $login == $md5log ) && ($pass==$md5pas))
																								   {																								//  hay match:
																											$salir=TRUE;
																											 // Invocar el modelo  y cargar   los datos de los usuarios
																										  
																											$elresponse=$this->Modelodatos->getRegistrosbycode($datauser['codarrendatario']);
																											// por fin, dar la respuesta en json usando REST SERVER
																										
																											$this->response( $elresponse, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code				
																									   } // fin si
																									else								    
																										 { 	// incremento la variable de posicion 
																											$i= $i+1 ;
																										 }
																					  
																								} // mientras
																
																			if		($salir==FALSE)	
																			
																			{ //ese usuario no existe y si existe la clave no coincide.. ni pistas se dan,
																						 $this->set_response(array(
																								 'status' => FALSE,
																								 'message' => 'Datos proporcionados son incoherentes.' ,
																								 'httpstatus'=> 'HTTP_UNAUTHORIZED',
																								 'code'=> '401'
																								 ), REST_Controller::HTTP_UNAUTHORIZED); //HTTP_UNAUTHORIZED (401) being the HTTP response code
																				//si el usuario si existe  tendrá que buscar adivinos para que se entere que fué lo que pasó
																				}	
																									
													
												}
							} // fin del si (y de este código)
		
}// end login_post
public function  registrar_post()
{  // recibe los datos del formulario de pago via post
// Se asume alegremente que  el post es enviado por cientificos, aliens o unicornios inteligentes, asi 	 que esta variable $ok es TRUE	
						$ok = TRUE;
						//  determinar si el método   Request utilizado es POST, los demás no interesa
						if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0)
							{
							   //throw new Exception('Request method must be POST!');
								// nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
								  $this->set_response(array(
												 'status' => FALSE,
												 'message' => 'El método Request debe ser POST.',
												  'httpstatus'=> 'HTTP_METHOD_NOT_ALLOWED',
												 'code'=> '405'
												 ), REST_Controller::HTTP_METHOD_NOT_ALLOWED); // HTTP_METHOD_NOT_ALLOWED ( 405) being the HTTP response code
									$ok = FALSE; 
							  } // = 405;
							 
					//     Asegurarse que el contenido recibido desde el   request POST 	ha sido establecido/configurado como application/json
						$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
						if(strcasecmp($contentType, 'application/json') != 0)
							{
							   //	throw new Exception('Content type must be: application/json');
									// nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
										 $this->set_response(array(
												 'status' => FALSE,
												 'message' => 'Tipo contenido  debe ser: application/json.',
												 'httpstatus'=> 'HTTP_UNSUPPORTED_MEDIA_TYPE',
												 'code'=> '415'
												 ), REST_Controller::HTTP_UNSUPPORTED_MEDIA_TYPE); // HTTP_UNSUPPORTED_MEDIA_TYPE (415) being the HTTP response code
										$ok = FALSE;
								}
					 // preguntar si todo ha salido de maravillas, rociado con orina arcoiris de unicornio 
					 //  SI $ok = TRUE : ---> se sigue con este método 
				     // SI 	$ok = FALSE; --->	 no  tiene sentido continuar
				     
						  	if($ok==TRUE) 
							  {		
								  	//aqui se usa la sentencia para recibir la data del post (RAW)
											$content = trim(file_get_contents("php://input"));
									
											//se decodifica la data  RAW del post a partir del JSON recibido
											// y transformarlo a un array normal y NO  un array de objetos (libreria std)
											$dataregistro = json_decode($content, true);
											/*
																	       
									       /*							       		      */
											// determinar si JSON is invalido
											if(!is_array($dataregistro))
											 {
												 	//throw new Exception('Received content contained invalid JSON!');
													   // nanay aqui nada de excepciones, con un mensaje usando  REST SERVER  ya se conoce el error del lado del cliente
														 $this->set_response(array(
															 'status' => FALSE,
															 'message' => ' El contenido JSON recibido es invalido.',
															'httpstatus'=> ' HTTP_UNPROCESSABLE_ENTITY',
															 'code'=> '422'
															 ), REST_Controller:: HTTP_UNPROCESSABLE_ENTITY); //  HTTP_UNPROCESSABLE_ENTITY (422) being the HTTP response code
												}
												else
												//  normalmente si viniera este registro  de una fuente externa revisaria su contenido
												// pero ya fue diseñado el lado del cliente y perfectamente se conoce  como vendrá ese json
												{    
														 /*
															$elregistro [ 'intranet']=$data['username'] <--- key--< 
														   $elregistro[ 'can_depositado']=$this->input->post('cant');
															$elregistro[ 'num_referencia']=$this->input->post('numrefcheq');
															$elregistro [ 'cod_patrimonio']=$this->input->post('patrimonio');
														
															$elregistro [ 'intranet']=$data['username'];*/
															
															 /* se carga el modelo megafake y se "escribe en bd" al invocar el metodo*/ 
															   $this->load->model('Modelodatos');
															   $resultado =$this->Modelodatos->registrarpago($dataregistro);
												   		//  dar la respuesta en json usando REST SERVER
																									
													  	$this->response( $resultado, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code				
														
												}
	
             }
	
} // function recibirdatos_post
 



}/// end class Emudb

