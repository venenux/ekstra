<?php
/* @Autor:Tyrone Lucero
 * @Descripción: Modelo para acceder a base de datos Oasis y hacer 
 *               consultas de busquedas sobre productos
 * @Fecha: 22 febrero 2018
 */ 
class oaproductosrechazadosmodel extends CI_Model {

	public $dbmy; // objeto conexcion mysql
	public $dboa; // objeto conexcion odbc oasys

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de productos rechazados en una tienda idetificada por msc, 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL. 
	 *
	 * @param       string  $usuario    nombre del usuario intranet de la sesion que ejecuta la consulta
	 * @param       array con el codigo msc de la tienda a consultar
	 * @return      array   array(un yanero de campos )
	 */
	public function get_productos_rechazados_por_tienda($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{

		if( $parametros == NULL)
			return FALSE; // no intento conexcion si no hay parametros

		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if( $driverconected !== TRUE )
			return FALSE; // no conecto a DB no se puede seguir

		if( !is_array($parametros) )
		{
			return FALSE; // tienen que ser minimo 3 parametros
		}

		if( array_key_exists('cod_msc',$parametros))
			$cod_msc= $parametros['cod_msc'];
		else
			return FALSE; // tiene que venir el msc a juro

		if( array_key_exists('fec_ini',$parametros))
			$idfechaini=$parametros['fec_ini'];
		else
			$idfechaini = date('Y-m-d'); // sino se asume

		if( array_key_exists('fec_fin',$parametros))
			$idfechafin=$parametros['fec_fin'];
		else
			$idfechafin = date('Y-m-d'); // si olvido se asume

		$sqlrchz = "select b.cod_pos,b.dianeg,b.cod_msc,
					 (select nom_sucursal 
					  from DBA.tc_codmsc 
					  where cod_msc=b.cod_msc) 
					as sucursal,b.cod_interno,a.txt_descripcion_larga 
					from td_detvtaprod_rechazados b 
					join DBA.tv_producto a 
					on b.cod_interno=a.cod_interno 
					where b.cod_msc='" .$cod_msc."'  
					and b.dianeg >='".$idfechaini."' 
					and b.dianeg <= '".$idfechafin."'  
					group by b.cod_pos,b.dianeg,b.cod_msc, 
					sucursal,b.cod_interno,a.txt_descripcion_larga
					 ";
					
					$query = $this->dboa->query($sqlrchz);	// sino devuelve el count pero en arreglo
					$arreglo_rechz = $query->result_array();
		return    $arreglo_rechz;
	}



}

?>
