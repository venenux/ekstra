<?php
/* @Autor:Tyrone Lucero
 * @Descripción: Modelo para acceder a base de datos Oasis y hacer 
 *               consultas de busquedas sobre productos
 * @Fecha: 22 febrero 2018
 */ 
class oaproductosexistenciasmodel extends CI_Model {

	public $dbmy;
	public $dboa;

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * invoca el sp via CLI, no devuelve resultado alguno, invocar aqui asincrono
	 * @name        invoca_sp_existencias
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros    txt_descripcion_larga del producto, o arreglo con txt_descripcion_larga y txt_referencia
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, txt_descripcion_larga, txt_referencia, precio )
	 */
	public function invoca_sp_existencias($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;
		if( $parametros == NULL)
			return FALSE;
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
				return FALSE
			if( !array_key_exists('fec_ini',$parametros))
				return FALSE;
			if( array_key_exists('txt_descripcion_larga',$parametros))
				return FALSE;
			$fec_ini = $parametros['fec_ini'];
			$fec_ini = $this->dboa->escape_srt($fec_ini,TRUE);
			$fec_fin = $parametros['fec_fin'];
			$fec_fin = $this->dboa->escape_str($fec_fin,TRUE);
		}

		$sqlprimerocuenta = "
		SELECT 
			*
		FROM
			sp
		WHERE
			".$queryfiltros." 
		";

		$querydb1c = $this->dboa->query($sqlprimerocuenta);
		$resultchequeo = $querydb1c->result();

	}


	/**
	 * invoca el sp via CLI, no devuelve resultado alguno, invocar aqui asincrono
	 * @name        invoca_sp_existencias
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros    txt_descripcion_larga del producto, o arreglo con txt_descripcion_larga y txt_referencia
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, txt_descripcion_larga, txt_referencia, precio )
	 */
	public function invoca_tabl_existencias($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;
		if( $parametros == NULL)
			return FALSE;
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
				return FALSE
			if( !array_key_exists('fec_ini',$parametros))
				return FALSE;
			if( array_key_exists('txt_descripcion_larga',$parametros))
				return FALSE;
			$fec_ini = $parametros['fec_ini'];
			$fec_ini = $this->dboa->escape_srt($fec_ini,TRUE);
			$fec_fin = $parametros['fec_fin'];
			$fec_fin = $this->dboa->escape_str($fec_fin,TRUE);
		}

	}

}

?>
