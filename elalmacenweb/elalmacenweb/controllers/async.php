<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Async elyanero Controller Class de "desasociado"  de procesos
 * @name		Async
 * @author		PICCORO Lenz McKAY
 */
class Async extends YA_Controller {

	function __construct()
	{
		parent::__construct();
		$this->checku();
	}

	/** entrada index si no se especifica destiino del controlador */
	public function index()
	{
		
	}

	/*
	 * envio de correos en un proceso aparte, 
	 * asume la llamada "aparte" de este proceso por la libreria async
	 * name: mailto_backend 
	 * @param void (POST) $parametros (correode,correoa,correoas,correome,rutaarchivo) datos del envio del correo incluido el adjunto
	 * @return void
	 */
	public function mailto_backend()
	{
		$correoa = $this->input->get_post('correoa');
		$correode = $this->input->get_post('correode');
		$correoas = $this->input->get_post('correoas');
		$correome = $this->input->get_post('correome');
		$rutaarchivo = $this->input->get_post('rutaarchivo'); // previamente guardado pro el proceso
		$correotime = date('YmdHis');
		//$filenameneweordendespachoadjuntar = 'ordendespachogenerada' . $this->numeroordendespacho . '.txt';
		//if ( ! write_file($filenameneweordendespachoadjuntar, $correocontenido))
		//{
		//	 echo 'Unable to write the file';
		//}
		$configm['protocol'] = 'mail';// en sysdevel y sysnet envia pero syscenter no
		$configm['wordwrap'] = FALSE;
		$configm['starttls'] = TRUE; // requiere sendmail o localmail use courierd START_TLS_REQUIRED=1 sendmail no envia
		$configm['smtp_crypto'] = 'tls';
		$this->load->library('email');
		$this->email->initialize($configm);
		$this->email->from($correode, $correode);
		$this->email->to($correoa);
		$this->email->cc('soporte-vnz@intranet1.net.ve');
		$this->email->reply_to('soporte@intranet1.net.ve', 'soporte');
		$this->email->subject($correoas);
		$this->email->message($correome); 
		$this->email->send();
		//$this->email->attach($filenameneweordendespachoadjuntar);
		$this->email->send();
	}

}
