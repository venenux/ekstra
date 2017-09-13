<?php //controlador para login
	if (!defined('BASEPATH'))
		exit('No direct script access allowed');

	class Login_usuario extends CI_Controller
	{
	  private $usuariologin, $sessionflag, $acc_lectura, $acc_escribe, $acc_modifi,$tmplnewtable,$sDatausuario;

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
		     // cargar liberia curl
			$this->load->library('curl');
			$this->load->model('Modelodatos');
			$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT',TRUE);
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0', TRUE);
			$this->output->set_header('Pragma: no-cache', TRUE);
			$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
			$this->output->enable_profiler(TRUE);
		    
		    }


		public function index()
		{
			$sDatausuario = null;
		  	$sDatausuario = array('logueado' => FALSE);
			$sDatausuario['codarrendatario']='';
			$sDatausuario['cod_controlador']	='';
			$sDatausuario['cod_aplicacion']	='';
			$sDatausuario['username']	= '';	
			$sDatausuario['accionpagina']='';
		   $sDatausuario['flecha']= null;	
			$this->session->set_userdata($sDatausuario);
			$this->load->helper(array('form', 'url','html'));
			$this->load->view('iniciosesion', $sDatausuario);
		}

		public function salir()
		{	// destruir la session e invalidarla
			$sDatausuario = array('logueado' => FALSE);
			$sDatausuario['codarrendatario']='';
			$sDatausuario['cod_controlador']	='';
			$sDatausuario['cod_aplicacion']	='';
			$sDatausuario['username']	= '';	
			$sDatausuario['accionpagina']='';
			$this->session->set_userdata($sDatausuario);
			$this->session->sess_destroy();
			$this->index();
		}

public function iniciarsesion()
{	    // la libreria curl
           $this->load->library('curl');
       
		  //obtener el post con  login contraseña 
		    $sLogin= $this->input->post('username');
			$sPass = $this->input->post('contrasena');
	        $smodulo=$this->input->post('modulo');
			if ($sLogin!='' &&     $sPass!='' )
			{   	
				   ///   login y la contraseña (desde formulario) no son vacios; 
		    		       
				      	/*  tomar   los valores que vienen desde formulario y mandarlos al controlador EmuDB
				    	 *   luego cuando se conozca el servidor que usará el sistema seŕa configurada la ruta 
				    	 *   primero deben transformarse...
				    	 * */
				    	 $transfsLogin=md5(  $sLogin);
				    	  $transfsPass=md5( $sPass);
				    	
				    	   //{"userintranet":{"intranetclave":"szsd890fbh6s0d89f7g0sdf76g0sd896g08sdf6","modulo":"1"}}
				    	                                    //  fx= userintranet                   dy=intranetclave           z0= modulo                                      
				   
				   	 // cargar al arreglo 
				    	// $arreglopost=array ('fx'=> $sLogin,  'dy'=> $sPass, 'z0'=>$smodulo) ;
				       $arreglopost=array ('fx'=> $transfsLogin, 'dy'=> $transfsPass, 'z0'=>$smodulo) ;        
				    
				    	// invocar el modelo para que haga el trabajo de enviar el post
				    	$elrequest= $this->Modelodatos->request_for_login($arreglopost);
				    	$sDatausuario['flecha']	=	$elrequest;
				    	// revisar la respuesta  segun sea la respuesta permitir sesion
				    	// EL RESPONSE QUE ES JSON SE DEBE TRANSFORMAR A ARREGLO NORMAL,(SEGUNDA VARIABLE EN TRUE)
				    	$response=json_decode($elrequest,TRUE);
				    
				        $sDatausuario['patrimonios']=$response;// esto se mostrará en formulario
						
							// aceptar que la fuerza existe y se  mando este login celestialmente correcto como unicornio-pony-encantado  
		
								
												if (!is_array($response))
												{ // no hay sesion poque  se recibó otra cosa distinta a un arreglo 
													   // mostrar la misma pantalla, no dar señales de nada
														echo 'Existe un error  en el Servidor: la respuesta recibida no era la esperada. ';
													   // se siente una perturbación en la fuerza... es como si un servidor remoto manda hmtl  con errores
													   //		$code=$this->http_response_code( NULL); 
														//	 var_dump($code);
														  $this->index();
												}	
												else
												{		//  significa que el servidor mandó la respuesta en forma de array (json)
														//  ahora a determinar  si es el arreglo con los patrimonios (respuesta correcta esperada) -->hay sesion
														//  o algun error  hallado en el EmuDB ( respuesta correcta pero no la esperada)  --> no  hay sesion
																if (array_key_exists('status',$response))
																{ 
																	 // se siente una perturbación en la fuerza... es como si un servidor remoto  
																	 //mando array con status de error
																	 	    // aqui se puede agregar segmentos de código para determinar cual fué el error
																	 	   // pero por ahora aplicar esta caimanada
																	 
																		//	echo 'Se han detectado Errores:  ';
																		//	var_dump($response);
																		//		$code=$this->http_response_code( NULL); 
																		
																		//		 var_dump($code);
																				$this->index();
															
																}
															  else
															  {  // establecer sesión	
																				$sDatausuario['username']	= $sLogin ;	
																				$sDatausuario['logueado'] = TRUE;
																				$sDatausuario['accionpagina']='logueado';
																				// por ahora el modulo es 1 y se configuara estos datos asi, luego 
																				//cuando se desarrollen/diseñen mas controladores y cosas en el sistema
																				// se adaptara esata lineas a los cambios
																				$sDatausuario['controlador']	='RegistroPagos ';
																				$sDatausuario['modulo']	=$smodulo;
																				
																				 // se carga estas librerias para preparar las vistas de tablas y lo demás
																				$this->load->helper(array('form', 'url','html'));
																				$this->load->view('view_header');
																				
																			/* cuando se tengan que cargar  un controlador según sea el modulo se configura aqui que 
																			/   controlador se cargará */	
																				//if  ($smodulo==1)
																					// cargar los datos del usuario en  la sesion
																					$this->session->set_userdata($sDatausuario);
																				 redirect('Registropagos/index', 'refresh');
																  																  
																  } // if array_key..		
												} 	// if !is_Array..	
											
			  	} // if login ni pass vacios 
			
			else $this->index(); //aquí no vale ni login ni password Vacío... 
		
}// funcion iniciarsesion/ 


// funcion para determinar http response code  porque esta version de php no la tiene
  function http_response_code($code = NULL) {

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




	}// fin class Login_usuario	
	?>












