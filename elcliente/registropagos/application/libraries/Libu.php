<?php
defined("BASEPATH") or die("El acceso al script no está permitido");

/** esta clase depende del CSS bootstrap y el CSS default*/
class Libu
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
		$this->sessiondatauser = $this->CI->session->all_userdata();
	}

	/** utilidad para saber la ip server/cliente y sabrr a donde dirigir la llamada json/login/data */
	function getipnet($side='client', $large=false)
	{
		$addresssrv = $_SERVER['REMOTE_ADDR'];
		if($side == 'server')
		$addresssrv = $_SERVER['SERVER_ADDR'];
		$wherecut = strripos($addresssrv,'.');
		if($wherecut < 4)
		$addresssrv = '127.0.0.1';
		if($large)
		$addresssrv = substr($addresssrv, 0, $wherecut);
		return $addresssrv;
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
	public function hace_cuanto($in1 = null, $in2 = null, $formatodate = "m")
	{
		if ( $in1 == null OR $in2 == null)
			return 0;
		if ( intval($in1) < 3 OR intval($in2) < 3 )	// solo strings o null, arreglos o vacio no
			return 0;
		$dateobj1 = DateTime::createFromFormat('Ymd', $in1);
		$dateobj2 = DateTime::createFromFormat('Ymd', $in2);
		$interval3 = $dateobj1->diff($dateobj2);
		return $interval3->format('%R%a');
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
		$sessiondata = $this->sessiondatauser;
		$sessf = date("YmdHis") . 'PELIGROREVISAR.sinsesion';
		if( isset($sessiondata[0]) )
			$sessf = date("YmdHis").$sessiondata[0]['cuantos'].'.'.$sessiondata[0]['intranet'];
		$post_array['sessionflag'] = $sessf;
		return $post_array;
	}

	/** genera elflag para saber quien creo o session ficha YYYYMMDDhhmmss.entidad.usuario*/
	public function cuando_crea($post_array = array() , $primary_key = array() )
	{
		$sessiondata = $this->sessiondatauser;
		$sessf = date("YmdHis") . 'PELIGROREVISAR.sinsesion';
		if( isset($sessiondata[0]) )
			$sessf = date("YmdHis").$sessiondata[0]['cuantos'].'.'.$sessiondata[0]['intranet'];
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
		if ( $post_array == '' or $post_array == null )
			$post_array[$namecod] = $prefix . date("YmdHis");
		log_message('info', 'pre insert new register '. $namecod . ' with data: ' . var_dump($post_array) );
		return $post_array;
	}

}
