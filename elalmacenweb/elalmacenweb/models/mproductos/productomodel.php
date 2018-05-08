<?php
/* @Autor:Tyrone Lucero
 * @Descripción: Modelo para consultas de busquedas sobre productos
 * @Fecha: 22 febrero 2018
 */ 
class Productomodel extends CI_Model {

	public $dbmy; // cargar el conex aqui la conex principal.. 
	public $dboa; // cargar otra conex a otra db aqui

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de productos segun descripcion o referencia, 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 * si no hay productos devuelve un arreglo con el primera columna un numero 0, 
	 * la primera columna siempre trae el total
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros    des_producto del producto, o arreglo con des_producto y txt_referencia
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, des_producto, txt_referencia, precio )
	 */
	public function get_producto_simple($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
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


	/**
	 * Devuelve la existencia y en que posicion de todos los productos en cada una de las posiciones
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   $cod_producto como arreglo
	 * @return      array   array( )
	 */

	public function get_producto_existencia_posicion($usuario = NULL, $parametros = NULL, $exportarodt = FALSE, $porposicion = TRUE)
	{
		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		if(!$porposicion)
			$canprodcolum = "'en todos' as cod_posicion, a.can_producto";
		else
			$canprodcolum = "e.des_almacen , SUM(a.can_producto) as can_producto";

		$sqlexist = "
			SELECT 
				p.cod_codigo, 
				p.des_producto,
				b.cod_posicion,
				".$canprodcolum."
			FROM
				esk_almacen_producto AS a
				LEFT JOIN
					esk_almacen_ubicacion AS b
				ON 
					a.cod_ubicacion = b.cod_ubicacion
				LEFT JOIN
					esk_productos AS p 
				ON 
					a.cod_producto = p.cod_producto
				LEFT JOIN
					esk_almacen_mapa AS e
				ON
					e.cod_entidad = b.cod_entidad
			ORDER BY
				p.cod_codigo ASC, b.cod_ubicacion ASC
		 ";
		$query = $this->dbmy->query($sqlexist);	// sino devuelve el count pero en arreglo
		$arreglo_rep = $query->result_array();
		if(count($arreglo_rep) < 1)
			return $arreglo_rep = FALSE;
		else
			return $arreglo_rep;

	}

	/**
	 * Devuelve la existencia y en que almacen de todos los productos en todas las posiciones
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   $cod_producto como arreglo
	 * @return      array   array( )
	 */

	public function get_producto_existencia_almacen($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$arreglo_rep = $this->get_producto_existencia_posicion($usuario,$parametros,$exportarodt,FALSE)
		return $arreglo_rep;
	}

}

?>
