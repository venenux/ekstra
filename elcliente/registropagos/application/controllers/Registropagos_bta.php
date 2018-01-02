<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registropagos_bta extends CI_Controller
 {

   /**
	el pago de los inmuebles
  */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('curl');
		// asegura que no se use sino un nuevo request/pagina y no cache del navegador windoser
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT',TRUE);
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0', TRUE);
		$this->output->set_header('Pragma: no-cache', TRUE);
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
		// cargar el modelo mas fake  que billete de tres
		$this->load->model('Modelodatos');
		$this->output->enable_profiler(TRUE);
	}



	public function index()
	{
		$this->load->library('session');
		if( $this->session->userdata('logueado') == TRUE AND  $this->session->userdata('username') != '')
		{
			//cargar helper para crear los formularios, las rutas y los html
			$this->load->helper(array('form', 'url','html'));
			// cargar los datos de la sessión
			$username = $this->session->userdata('username');
			$userclave = $this->session->userdata('userclave');
			$codarrendatario=$this->session->userdata('codarrendatario');
			//$datauser = $this->Modelodatos->getRegistros($username);
			$datauser=$this->Modelodatos->get_patrimonios_by_code($codarrendatario);
			$url =$this->config->item('json_get_patrim'); 
			$laruta=    $url .$codarrendatario;
			$data['aa']= $codarrendatario;
			$data['aruta'] =$laruta;
			$data['datos'] = $datauser;
 			$data['username'] =$username ;
    		$data['accionpagina']='logueado';
    		$this->load->library('table');
	    	$this->load->helper(array('form', 'url','html'));
		    $tmplnewtable = array ( 'table_open'  => '<table border="0" cellpadding="1" cellspacing="1" class="table">' );
		    $this->table->set_caption(NULL);
		    $this->table->clear();
		    $this->table->set_template($tmplnewtable);
		    $this->table->add_row('Nombre Usuario: ', $data['username'], '');
            $tabladatos=$this->table->generate();
            $data['htmluser']=$tabladatos;
            $this->load->view('view_header', $data);
				   $datausers = $this->Modelodatos->get_Datos_Users(); //primero veo si en data hay algo		
              $this->table->clear();
      		  $this->table->add_row('Imuebles asociados:');
				// creat ,los titulos de la tabla:
               $titulos = array('Cod Patrimonio'.nbs(2),nbs().'Descripción'.nbs(2),nbs().'Monto'.nbs(2),nbs().'Fecha Contrato');
               $this->table->add_row($titulos);
                 //cargar los pseudo datos del arreglo a la tabla
                 foreach($datauser as $item)
                 { $this->table->add_row($item['codpatrimonio'],$item['des_patrimonio'], $item['debe'],$item['desde']) ;}
					$tablapatrim=$this->table->generate();
					 $data['infopatrim']=	$tablapatrim;    
				   // mostrar la vista de la data usuario   
					 $this->load->view('view_datos', $data);    
                   // validar los campos, necesarios para que el usuario no  deje ninguno en blanco y escriba bien       
      		    	$this->form_validation->set_rules('cant', 'Cantidad Depositada', 'trim|required|numeric');
		    		$this->form_validation->set_rules('numrefcheq', 'Número Referencia Cheque', 'trim|required');
					$this->form_validation->set_rules('patrimonio', 'Código Inmueble a Pagar', 'trim|required');
					// configurar los mensajes de error en español
					$this->form_validation->set_message('required', 'El campo %s es obligatorio');			
					$this->form_validation->set_message('numeric', 'El campo %s debe contener sólo números (el punto (.) es separador decimal)');		
			           // configurar un html  con los códigos nada mas para generar tantos radio buttons como patrimonios tenga el usuario
			          $this->table->clear();
   	                  $this->table->add_row(' <form action="">');
   	                   $prefix= '<input type="radio" name="patrimonio" value="';
   	                      
   	                     foreach($datauser as $item)
   	                     // recorrer el arreglo con la data para generar el  html
                           { 
								 $name= $item['codpatrimonio'];
								  $cadena=$prefix.''.$name.'" >'.$name.'<br>';
								 $this->table->add_row($cadena);
                               }
                            // agregar ultima línea par acerrar el html:
                            $this->table->add_row('</form>');
                            //generar y mandad a data:
                            $tablaradios=$this->table->generate();
                            $data['htmlradio']= $tablaradios;
   	                  
   	                    
   	                      if($this->form_validation->run() === true){
		 
											$elregistro[ 'can_depositado']=$this->input->post('cant');
											$elregistro[ 'num_referencia']=$this->input->post('numrefcheq');
											$elregistro [ 'cod_patrimonio']=$this->input->post('patrimonio');
										   // estos datos se envian al modelo trifake:
											 $this->Modelodatos->registrarpago($elregistro);
											$data['elpost']=  $elregistro; // esto es para ver sie en data captura el post
												// mensaje al usuario avisando el exito 
										
										   
										   echo "<script>alert('¡Pago Registrado!');</script>";
                                            
									  	redirect('Registropagos_bta', 'refresh');
                              
										
									
				   }	
            else
            {
				      $this->load->view('view_pagoform',$data);
				}

   	   
   	            $this->load->view('view_footer', $data);
	   	   }
			else
			 {
				if( $this->session->userdata('logueado') == FALSE)
				 {   // mandarlo a... iniciarsesion
					redirect('Indexlogin/iniciarsesion');
				 }
		}
	}// function index


}// fin clase registro pagos



 ?>

