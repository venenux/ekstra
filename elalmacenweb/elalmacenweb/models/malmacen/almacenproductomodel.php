<?php
/* @Autor:Tyrone Lucero
 * @Descripción: Modelo para consultas de busquedas sobre productos
 * @Fecha: 22 febrero 2018
 */ 
class Almacenproductomodel extends CI_Model {

	public $dbmy; // cargar el conex aqui la conex principal.. 
	public $dboa; // cargar otra conex a otra db aqui

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de las ubiaciones del galpon con sus coordenadas en el mapa
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 * ESTA AUN DEARROLLANDOSE EL SQL!!!
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros    des_producto del producto, o arreglo con des_producto y txt_referencia
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, des_producto, txt_referencia, precio )
	 */
	public function get_ubicaciones_simple($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$queryfiltros= "";
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			$queryfiltros=	" and des_producto like '%".$parametros."%' ";
			else
			{	
				if( array_key_exists('txt_referencia',$parametros))
				{
					$txt_referencia = $parametros['txt_referencia'];
					//$txt_referencia = $this->dbmy->escape_srt($txt_referencia,TRUE);
					$queryfiltros = " and txt_referencia like '%".$txt_referencia."%' ";
				}
				if( array_key_exists('des_producto',$parametros))
				{
					$des_producto = $parametros['des_producto'];
					$des_producto = $this->dbmy->escape_str($des_producto,TRUE);
					$queryfiltros = " and des_producto like '%".$des_producto."%' ";
				}
		  }		
		}

		$sqlprimerocuenta = "
		SELECT 
			count(*) as cuantos 
		FROM
			esk_producto_almacen c 
		WHERE
			c.cod_producto <> ''
			".$queryfiltros." 
		";

		$querydbusuarios1c = $this->dbmy->query($sqlprimerocuenta);
		$resultchequeo = $querydbusuarios1c->result();
		foreach ($resultchequeo  as $row)
			$cuantos = $row->cuantos;
		
		$sqlsegundotraer = "
		SELECT 
			".$cuantos." as resultado,
			c.cod_producto,
			c.des_producto
		FROM
			esk_producto_almacen c 
		WHERE
			c.cod_producto <> ''
			".$queryfiltros." 
		ORDER BY c.cod_producto 
		LIMIT 40000 OFFSET 0
		";
		
		$querydbusuarios2u = $this->dbmy->query($sqlsegundotraer);	// sino devuelve el count pero en arreglo
		$arreglo_reporte = $querydbusuarios2u->result_array();

		if($cuantos < 0)
			$arreglo_reporte = array('0'=>array('cuantos'=>0));
		if($exportarodt !== TRUE)
			return $arreglo_reporte;	// devuelve un arreglo y el primer elemento del elemento '0' es 'cuantos'
		else
			{
			 	$arreglo_csv=$this-> _hacerflujocsv($arreglo_reporte);
		        return $arreglo_csv;
		     }  
	}

}

?>
