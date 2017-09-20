<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registropagos extends CI_Controller
 {

public function __construct()
{
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('grocery_CRUD');
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT',TRUE);
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0', TRUE);
		$this->output->set_header('Pragma: no-cache', TRUE);
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
		$this->load->model('Modelodatos');
		$this->output->enable_profiler(TRUE);
}

public function index()
{
		$this->load->library('session');
			if( $this->session->userdata('logueado') == TRUE AND  $this->session->userdata('username') != '')
			{
					$this->load->helper(array('form', 'url','html'));
					$username = $this->session->userdata('username');
					$datauser=$this->session->userdata('patrimonios');
					$data['datos'] = $datauser;
					$data['username'] =$username ;
					$data['accionpagina']='logueado';
					$data['a']= $this->session->userdata('flecha');
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
					$this->table->clear();
					$this->table->add_row('Imuebles asociados:');
					$titulos = array('Cod Patrimonio'.nbs(2),nbs().'Descripción'.nbs(2),nbs().'Monto'.nbs(2),nbs().'Fecha Contrato');
					$this->table->add_row($titulos);
					$patrimonios= array();
						 foreach($datauser as $item)
						{
								 $this->table->add_row($item['codpatrimonio'],$item['des_patrimonio'], $item['debe'],$item['desde']) ;
								 $patrimonios[$item['codpatrimonio']]=$item['codpatrimonio'];
					   }
					$tablapatrim=$this->table->generate();
					$data['infopatrim']=	$tablapatrim;    
					$this->load->view('view_datos', $data);    
					$this->form_validation->set_rules('cant', 'Cantidad Depositada', 'trim|required|numeric');
					$this->form_validation->set_rules('numrefcheq', 'Número Referencia Cheque o Serial Bauche', 'trim|required');
					$this->form_validation->set_rules('patrimonio', 'Código Inmueble a Pagar', 'trim|required');
					$this->form_validation->set_message('required', 'El campo %s es obligatorio');			
					$this->form_validation->set_message('numeric', 'El campo %s debe contener sólo números (el punto (.) es separador decimal)');		
					$data['listapatrimonios']=$patrimonios;
						 if($this->form_validation->run() === true)
						{
								$elregistro=array(
									'intranet'=>$data['username'], // el intranet será el indice de este arreglo
									 'cant_pago'=>$this->input->post('cant'),
									  'numref'=>$this->input->post('numrefcheq'),
									  'cod_pat'=>$this->input->post('patrimonio')
													);
								 $elrequest =$this->Modelodatos->registrarpago($elregistro);
								 $data['request']=  $elrequest; // esta es la respuesta, si no se recibe una cadena de texto  especifica es error
										if (!is_array( $elrequest))
										{  
												if ($elrequest=='"Pago registrado"')
												 {  
														echo "<script>alert('¡ Pago registrado exitosamente !');</script>";
												}
												else
												{
														echo "<script>alert(' El Pago no ha sido registrado por un error en el Servidor.');</script>";
												}
										}	
										 else
										{//
												echo "<script>alert(' El Pago no ha sido registrado por un error en el Servidor.');</script>";
										 }
								redirect('Registropagos', 'refresh');
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
						{ 
								redirect('login_usuario/iniciarsesion');
						}
			}
}

}



 ?>

