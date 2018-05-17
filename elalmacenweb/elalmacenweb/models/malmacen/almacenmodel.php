<?php
/* @Autor: Lenz McKAY Gerhard
 * @Descripción: Modelo para consultas de busquedas sobre almacens
 * @Fecha: 22 febrero 2018
 */ 
class Almacenmodel extends CI_Model {

	public $dbmy; // cargar el conex aqui la conex principal.. 
	public $dboa; // cargar otra conex a otra db aqui

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de las almacenes y sus nombres 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   arreglo con ( des_almacen, cod_almacen) o cod_almacen unicamente
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(cod_entidad, cod_almacen - des_almacen)
	 */
	public function get_almacenes_list($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$queryfiltros= "";
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			{
				$queryfiltros=	" and c.des_almacen like '%".$this->dbmy->escape_like_str($parametros)."%' ";
			}
			else
			{
				if( array_key_exists('cod_almacen',$parametros))
				{
					$cod_almacen = $parametros['cod_almacen'];
					if(stripos($cod_almacen,',') !== FALSE)
					{
						$cod_almacenes = explode(',',$cod_almacen);
						foreach($cod_almacenes as $alm=>$cod_almacen)
						{
							$cod_almacen = $this->dbmy->escape($cod_almacen,TRUE);
							$queryfiltros .= " and c.cod_almacen = ".$cod_almacen." ";
						}
					}
					else
					{
						$cod_almacen = $this->dbmy->escape($cod_almacen,TRUE);
						$queryfiltros = " and c.cod_almacen = ".$cod_almacen." ";
					}
				}
				if( array_key_exists('des_almacen',$parametros))
				{
					$des_almacen = $parametros['des_almacen'];
					$des_almacen = $this->dbmy->escape_str($des_almacen,TRUE);
					$queryfiltros = " and c.des_almacen like '%".$des_almacen."%' ";
				}
			}
		}

		$sqlsegundotraer = "
		SELECT 
			c.cod_entidad,
			c.cod_almacen,
			c.des_almacen
		FROM
			esk_almacen_mapa c 
		WHERE
			c.cod_almacen <> ''
			".$queryfiltros." 
		ORDER BY c.cod_almacen 
		LIMIT 40000 OFFSET 0
		";
		
		$querydbusuarios2u = $this->dbmy->query($sqlsegundotraer);	// sino devuelve el count pero en arreglo
		$arreglo_reporte = $querydbusuarios2u->result_array();

		if( !is_array($arreglo_reporte) )
			return FALSE;
		if( count($arreglo_reporte) < 1 )
			return FALSE;
		if($exportarodt !== TRUE)
			return $arreglo_reporte;	// devuelve un arreglo y el primer elemento del elemento '0' es 'resultado'
		else
			{
			 	$arreglo_csv=$this-> _hacerflujocsv($arreglo_reporte);
		        return $arreglo_csv;
		     }  
	}

	/**
	 * obtiene una lista de las almacenes y sus nombres 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 * en este el indice es el codigo almacen, usese en combox selects
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   arreglo con ( des_almacen, cod_almacen) o cod_almacen unicamente
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(cod_entidad, cod_almacen - des_almacen)
	 */
	public function get_almacenes_box($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$almacenesselectbox = array();
		$arreglo = $this->get_almacenes_list($usuario, $parametros, $exportarodt);
		if($arreglo != FALSE)
		{
			foreach($arreglo as $indice => $almacen)
			{
				$almacenesselectbox[$almacen['cod_entidad']] = $almacen['cod_almacen'] .' - '. $almacen['des_almacen'];
			}
			return $almacenesselectbox;
		}
		return FALSE;
	}

}

?>
