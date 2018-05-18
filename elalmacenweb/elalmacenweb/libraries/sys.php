<?php
defined("BASEPATH") or die("El acceso al script no está permitido");

/** esta clase depende del CSS bootstrap y el CSS default*/
class sys
{
	private $sessiondatauser = null;

	// We'll use a constructor, as you can't directly call a function from a property definition.
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('encrypt'); // TODO buscar como setiear desde aqui key encrypt
		$this->CI->load->library('session');
		$this->CI->load->library('table');
	}

	function completarceros($codigoocadena)
	{
		// llenar el codigo aqui
		return $codigoocanena;
	}


	/** asociacion 1_n sin tabla extra:
	 * asocia un registro a muchos registros, escribiendo los valores
	 * asociados en el registro asociar, seguidos de comas, de esta manera
	 * se presentan directos en la vista html y por el crud sin esfuerzo de codificar
	 * @return string que toma la lista y la une toda con comas
	 */
	function _asoc_unomuchos($value, $row, $arrayasoc)
	{
		$arrayofenty = array();
		$rendercel = '';
		if ($value == '' )	// this must be before search, or a exception will raise
			return '';
		if ( strpos($value, ',') )
			$arrayofenti = explode(',', $value);
		else
			$arrayofenti = array($value);
		foreach( $arrayofenti as $keyen => $valuen )
		{
			//if ( array_key_exists($keyen, $arrayasoc) )
			{
				$keyexist = array_search($valuen, $arrayasoc);
				if ( $keyexist !== FALSE OR $keyexist !== NULL )
				{
					if ( isset($arrayasoc[$valuen]) )
						$rendercel .= $arrayasoc[$valuen] . ', ';
					else
						$rendercel .= ' ';
				}
			}
		}
		return $rendercel;
	}

	/** devuelve diferencia entre dos fechas, en formato YYYYMMDD , puede ser en meses (m), dias (d) o años (y) */
	public function hace_cuanto($in1, $in2, $formatodate = "d")
	{
		if ( intval($in1) < 3 OR intval($in2) < 3 )	// solo strings o null, arreglos o vacio no
			return 0;
		$dateobj1 = DateTime::createFromFormat('Ymd', $in1);
		$dateobj2 = DateTime::createFromFormat('Ymd', $in2);
		$interval3 = $dateobj1->diff($dateobj2); // php >> 5.3 exclusivo
		$cuantodias = $interval3->format('%a');
		if($formatodate == 'm')
			return ( $interval3->y * 12 ) + $interval3->m;
		if($formatodate == 'y')
			return $interval3->y;
		else
			return $cuantodias;
	}

	/** separa y formatea el session flag o session ficha YYYYMMDDhhmmss + entidad + . + iduse */
	public function cuando_y_quien($value, $row = null)
	{
		$userficha = array('','Sistema');
		$fechadate = '18120000';
		$fechatime = '00:00:00';

		if ( strlen($value) > 7 )
			$fechadate = substr($value, 0, 8);
		if ( strlen($value) > 13 )
			$fechatime = substr($value, 8, 6);
		if ( stripos($value,'.') !== FALSE )
			$userficha = explode('.',$value);

		return $fechadate . '@' . $fechatime . ': ' . $userficha[1];
	}

	/** genera elflag para saber quien altero o session flag YYYYMMDDhhmmss.entidad.usuario */
	public function cuando_altera($post_array = array(), $primary_key = array() )
	{
		$sessiondata = $this->CI->session->all_userdata();
		$sessf = date("YmdHis") . 'PELIGROREVISAR.sinsesion';
		if( isset($sessiondata[0]) )
		{
			$sessf = date("YmdHis").'.'.$sessiondata[0]['cuantos'].'.'.$sessiondata[0]['intranet'];
		}
		$post_array['sessionflag'] = $sessf;
		return $post_array;
	}

	/** genera elflag para saber quien creo o session ficha YYYYMMDDhhmmss.entidad.usuario*/
	public function cuando_crea($post_array = array() , $primary_key = array() )
	{
		$sessiondata = $this->CI->session->all_userdata();
		$sessf = date("YmdHis") . 'PELIGROREVISAR.sinsesion';
		if( isset($sessiondata[0]) )
		{
			$sessf = date("YmdHis").'.'.$sessiondata[0]['cuantos'].'.'.$sessiondata[0]['intranet'];
		}
		$post_array['sessionficha'] = $sessf;
		return $post_array;
	}

	/** genera un codigo prfYYYYMMMDDhhmmss donde PRF es prefijo, y el resto es anio, mes, dia, hora,minuto, segundo*/
	public function generar_codigo($post_array, $namecod, $prefix = '')
	{
		if ( array_key_exists('cod_' . $namecod, $post_array) )
			$post_array['cod_' . $namecod] = $prefix . date("YmdHis");
		if ( array_key_exists($namecod, $post_array) )
			$post_array[$namecod] = $prefix . date("YmdHis");
		if ( !is_array($post_array) )
			$post_array = array($namecod => $prefix . date("YmdHis") );
		log_message('info', 'pre insert new register '. $namecod . ' with data: ' . var_dump($post_array) );
		return $post_array;
	}

	public function titulo($indicetitulo)
	{
		$indicetitulo = str_replace('num', 'Numero', $indicetitulo);
		$indicetitulo = str_replace('cod', 'Codigo', $indicetitulo);
		$indicetitulo = str_replace('des', 'Descripcion', $indicetitulo);
		$indicetitulo = str_replace('can_', '', $indicetitulo);
		$indicetitulo = ucwords(str_replace(array('_', '-'), ' ', $indicetitulo));
		$indicetitulo = nbs() .$indicetitulo .nbs(3);
		return $indicetitulo;
	}


	/** lista par combo box de estado */
	public function estado_array($init = false)
	{
		if ( $init ) 	$estado[''] ='';
		$estado = array(); // ACTIVA|INACTIVA|CERRADA|SUSPENDIDA
		$estado['INACTIVO'] = 'INACTIVO';
		$estado['ACTIVO'] = 'ACTIVO';
		$estado['CERRADO'] = 'CERRADO';
		$estado['SUSPENDIDO'] = 'SUSPENDIDO';
		return $estado;
	}

	/** lista par combo box de clase */
	public function clase_array($init = false)
	{
		$clase = array(); // INTERNO|NORMAL|EXTERNO
		if ( $init ) 
			$clase[''] ='';
		$clase['INTERNO'] = 'INTERNO';
		$clase['EXTERNO'] = 'EXTERNO';
		return $clase;
	}

	/*
	 * recibe un codigo y rellena con ceros al inicio de la cadena 
	 * name: completar_codigo
	 * @param string $incodigo
	 * @return string
	 */
	public function completar_codigo($incodigo ='', $digitos = 10)
	{
		if($digitos>10)
			$digitos=10;
		$excodigo=$incodigo;
		$tam=strlen($incodigo);
		if(strlen($incodigo)<$digitos)
		{
			$fin= $digitos -$tam ;
			for ($i= 1; $i<= $fin; $i++ )
				$excodigo= '0'.$excodigo;
		}
		if(strlen($incodigo)>$digitos)
			$excodigo=substr($incodigo,-1,$digitos);
		return $excodigo;
	}

	/*
	 * procesa un archivo de un input segun los parametros
	 * name: sys::procesar_archivo
	 * @param
	 * @return
	 */
	public function procesar_archivo($campoinput, $modulo = '', $nombre = '')
	{
		// CARGA DEL ARCHIVO ****************************************************** /
		$cargaconfig['upload_path'] = 'assets/elalmacenwebarchivos/';
		$cargaconfig['allowed_types'] = 'txt|.|csv';
		$cargaconfig['max_size']  = 0; //'100'; // en kilobytes
		$cargaconfig['max_width'] = 0;
		$cargaconfig['max_height'] = 0;
		//$cargaconfig['remove_spaces'] = true;
		$cargaconfig['encrypt_name'] = TRUE;
		$this->CI->load->helper('inflector');
		$this->CI->load->library('upload', $cargaconfig);
		$this->CI->upload->initialize($cargaconfig);
		$this->CI->upload->do_upload($campoinput); // nombre del campo alla en el formulario
		log_message('info', 'cargando archivo....');
		$file_data = $this->CI->upload->data();
		$filenamen = $modulo . date('Ymdhis') . $nombre .'.txt';
        $filenameorig =  $file_data['file_path'] . $file_data['file_name'];
        $filenamenewe =  $file_data['file_path'] . $filenamen;
        log_message('info', 'renombrando archivo....');
		copy( $filenameorig, $filenamenewe); // TODO: rename
        unlink($filenameorig);
		$resultado = $this->CI->upload->data();
		log_message('info', $filenameorig . '  y  ' . $filenamenewe .'trabajados');
		$resultado['filenameorig'] = $filenameorig;
		$resultado['filenamenewe'] = $filenamenewe;
		return $resultado;
	}
}
