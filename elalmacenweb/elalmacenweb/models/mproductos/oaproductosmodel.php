<?php
/* @Autor:Tyrone Lucero
 * @Descripción: Modelo para acceder a base de datos Oasis y hacer 
 *               consultas de busquedas sobre productos
 * @Fecha: 22 febrero 2018
 */ 
class oaproductosmodel extends CI_Model {

	public $dbmy;
	public $dboa;

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
	 * @param       array/string  $parametros    txt_descripcion_larga del producto, o arreglo con txt_descripcion_larga y txt_referencia
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, txt_descripcion_larga, txt_referencia, precio )
	 */
	public function get_producto_simple($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$queryfiltros= "";
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			$queryfiltros=	" and txt_descripcion_larga like '%".$parametros."%' ";
			else
			{	
				if( array_key_exists('txt_referencia',$parametros))
				{
					$txt_referencia = $parametros['txt_referencia'];
					$txt_referencia = $this->dboa->escape_srt($txt_referencia,TRUE);
					$queryfiltros = " and txt_referencia like '%".$txt_referencia."%' ";
				}
				if( array_key_exists('txt_descripcion_larga',$parametros))
				{
					$txt_descripcion_larga = $parametros['txt_descripcion_larga'];
					$txt_descripcion_larga = $this->dboa->escape_str($txt_descripcion_larga,TRUE);
					$queryfiltros = " and txt_descripcion_larga like '%".$txt_descripcion_larga."%' ";
				}
		  }		
		}

		$sqlprimerocuenta = "
		SELECT 
			count(*) as cuantos 
		FROM
			tv_existencia a, tv_producto b, ta_proveedor_producto c 
		WHERE
			a.cod_interno = b.cod_interno 
			AND a.cod_interno = c.cod_interno 
			AND cod_proveedor='000000000000' 
			and cod_sucursal='79'  
			and almacen='0'  
			".$queryfiltros." 
		";

		$querydbusuarios1c = $this->dboa->query($sqlprimerocuenta);
		$resultchequeo = $querydbusuarios1c->result();
		foreach ($resultchequeo  as $row)
			$cuantos = $row->cuantos;
		
		$sqlsegundotraer = "
		SELECT 
			".$cuantos." as resultado,
			a.cod_interno as cod_interno, 
			txt_descripcion_larga, 
			txt_referencia, 
			(select mto_precio as precio FROM ta_precio_producto c WHERE cod_interno = a.cod_interno AND cod_precio = 0  ORDER BY fec_desde DESC LIMIT 1 OFFSET 0) as precio 
		FROM
			tv_existencia a, tv_producto b, ta_proveedor_producto c 
		WHERE
			a.cod_interno = b.cod_interno 
			AND a.cod_interno = c.cod_interno 
			AND cod_proveedor='000000000000'  
			and cod_sucursal='79' 
			and almacen='0'  
			".$queryfiltros." 
		ORDER BY a.cod_interno 
		LIMIT 40000 OFFSET 0
		";
		
		$querydbusuarios2u = $this->dboa->query($sqlsegundotraer);	// sino devuelve el count pero en arreglo
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
	 * Devuyelve la existencia de un producto consultado  por codigo
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   $cod_producto como arreglo
	 * @return      array   array( )
	 */

	public function get_producto_existencia($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;

		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			{
				$cod_producto =$parametros;
			}
			else
			{	
				if( array_key_exists('cod_producto',$parametros))
					$cod_producto = $parametros['cod_producto'];
				else
					return $arreglo_reporte = FALSE;
			}
		}
		else
		{
			return $arreglo_reporte = FALSE;
		}

		$proveedores = '';
		$proveedorarray = array();
		$proveedorarray = $this->get_producto_proveedores($usuario, $cod_producto, FALSE);
		foreach($proveedorarray as $key => $values)
			$proveedores .= $values['cod_proveedor'] . ',';

		// enviar lso detalles como texto enel query, todo junto mijo
		// consulta de las exisstencia, a este le adoso los detalles tantas veces exista filas, todo en uno
		$sqlexist = "
			SELECT 
				nom_sucursal, cod_sucursal, saldo_producto, cod_interno, txt_descripcion_larga, cod_msc, txt_referencia, 
				dpto || ' ' || (select txt_descrip_dep from EXTDEPAT where cod_departamento=dpto LIMIT 1 OFFSET 0) as dpto, 
				familia || ' ' || (select txt_familia FROM EXTFAMILIA where cod_departamento=dpto and cod_familia=familia LIMIT 1 OFFSET 0) as familia, 
				clase || ' ' || (select descripcion FROM td_clase where cod_clase=clase LIMIT 1 OFFSET 0) as clase, 
				'".$proveedores."' as proveedores, 
				(select mto_precio as precio FROM ta_precio_producto c WHERE cod_interno = '".$cod_producto."' AND cod_precio = 0  ORDER BY fec_desde DESC LIMIT 1 OFFSET 0) as precio 
			FROM
				DBA.EXT2
			WHERE
				cod_interno = '".$cod_producto."'
				AND cod_msc IN (
					SELECT 
						ta_usuario_sucursal.cod_msc AS cod_msc1
					FROM
						tm_usuario
					JOIN
						ta_usuario_sucursal ON tm_usuario.cod_usuario = ta_usuario_sucursal.cod_usuario
					GROUP BY cod_msc1)
			ORDER BY saldo_producto DESC  
		 ";
		$query = $this->dboa->query($sqlexist);	// sino devuelve el count pero en arreglo
		$arreglo_rep = $query->result_array();
		if(count($arreglo_rep) < 1)
			return $arreglo_rep = FALSE;
		else
			return $arreglo_rep;

	}

	/**
	 * Devuyelve la existencia de un producto consultado  por codigo
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   $cod_producto como arreglo
	 * @return      array   array( )
	 */

	public function get_producto_proveedores($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
		{
			$this->dboa = $this->load->database('oasisdb', TRUE);
			if($driverconected != TRUE)
				return FALSE;
		}

		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			{
				$cod_producto =$parametros;
			}
			else
			{	
				if( array_key_exists('cod_producto',$parametros))
					$cod_producto = $parametros['cod_producto'];
				else
					return $arreglo_reporte = array('0'=>array('cuantos'=>0));
			}
		}
		else
		{
			return $arreglo_reporte = array('0'=>array('cuantos'=>0));
		}

		$sqltest = "
			SELECT * FROM ProveedorEmpaque where cod_interno='".$cod_producto."';
		 ";
		$queryprovprod = $this->dboa->query($sqltest);
		$arreglo_reporte = $queryprovprod->result_array();
		if(count($arreglo_reporte) < 1)
			return $arreglo_reporte = array('0'=>'No hay asociados');
		else
			return $arreglo_reporte;

	}

}

?>
