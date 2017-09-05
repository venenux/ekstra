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
	        $smodulo=$this->input->post('moduloindexarray');
			if ($sLogin!='' &&     $sPass!='' )
			{   	
				   ///   login y la contraseña () no son vacios; 
				   // obtener el json remoto con los datos de  los usuarios
		               $datausers = $this->Modelodatos->get_Datos_Users(); //p 
			       //    	$datausers =$this->Modelodatos->getDatos();
				    	$hay_match=0;			
				      	$i= 1;
				      	$max= 	count($datausers );	
				      
				    	 $datauser=array();
					        while (($hay_match < 1 )&& ($i <=$max ))
					          { 	   // acceder al elemento en la posicion i
								           $datauser	=$datausers[$i];
								  		     if (($sLogin== $datauser['login'] ) && ($datauser['clave']== $sPass))
												{
													//  hay match:
													$hay_match=1;
													$sDatausuario['codarrendatario']=$datauser['codarrendatario'];
													$sDatausuario['cod_controlador']	='controlpago';
													$sDatausuario['cod_aplicacion']	='007';
													$sDatausuario['username']	= $datauser['login'] ;	
													$sDatausuario['logueado'] = TRUE;
													$sDatausuario['accionpagina']='logueado';
													$this->load->helper(array('form', 'url','html'));
													$this->load->view('view_header');
													//esta linea impedia la sesion
										        	$this->session->set_userdata($sDatausuario);
												   redirect('Registropagos/index', 'refresh');
												   } // fin si
								         	else								    
						                     { 	// incremento la variable de posicion 
											    $i= $i+1 ;
											 }
							  
							  } // mientras
				
				   	if		($hay_match<1)	
				   	
				   	{  $this->index();
					   echo 'Error : no Hay respuesta del Emulador DB';
					}  
				
				} // if login ni pass vacios 
			
			else $this->index(); //aquí no vale ni login ni password Vacío... 
			
}// funcion iniciarsesion/ 




	}// fin class Login_usuario	
	?>
