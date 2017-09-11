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
		    		/*  obtener el json remoto con los datos de  los usuarios no se implementará ese método
		               $datausers = $this->Modelodatos->get_Datos_Users(); //p 
			         */
			         
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
				    
				    	// invocar el modelo para que haga el trabjo de enviar el post
				    	$elrequest= $this->Modelodatos->request_for_login($arreglopost);
				    	$sDatausuario['flecha']	=	$elrequest;
				    	// revisar la respuesta  segun sea la respuesta permitir sesion
				    	// EL RESPONSE QUE ES JSON SE DEBE DE TRANSFORMAR, SEGUNDA VARIABLE
				    	$response=json_decode($elrequest,TRUE);
				    	 $sDatausuario['patrimonios']=$response;// esto se mostrará en formulario
								if (array_key_exists('status', $response) )
								{ // no hay sesion, no may match, respuesta recibida : error
 									    $this->index(); // mostrar la misma pantalla, no dar señales de nada
							  	echo 'error : ';
							             	var_dump($response);
							  	}	
								else
								{//  significa que el servidor mandó la respuesta, el arreglo con los patrimonios
									// establecer sesión	
									              //	echo 'Legolas was here!';
									              //	var_dump($response);
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
														// cargar los datos del usuario ey  la sesion
													  	$this->session->set_userdata($sDatausuario);
												    redirect('Registropagos/index', 'refresh');
								
											}	// if 
			  
							   	
				 
				} // if login ni pass vacios 
			
			else $this->index(); //aquí no vale ni login ni password Vacío... 
		
}// funcion iniciarsesion/ 




	}// fin class Login_usuario	
	?>












