<?php
/* @Autor: Lenz McKAY Gerhard
 * @Descripción: Modelo para consultas de busquedas sobre unidads
 * @Fecha: 22 febrero 2018
 */ 
class Unidadmodel extends CI_Model {

	public $dbmy; // cargar el conex aqui la conex principal.. 
	public $dboa; // cargar otra conex a otra db aqui

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de las unidades de manejo de unidad de cantidades 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 * ESTA AUN DEARROLLANDOSE EL SQL!!!
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   arreglo con ( des_unidad, cod_unidad) o cod_unidad unicamente
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(resultado, cod_interno, des_unidad)
	 */
	public function get_unidades_list($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$queryfiltros= "";
		if( $parametros != NULL)
		{
			if( !is_array($parametros) )
			$queryfiltros=	" and c.des_unidad like '%".$this->dbmy->escape_srt($parametros)."%' ";
			else
			{	
				if( array_key_exists('cod_unidad',$parametros))
				{
					$cod_unidad = $parametros['cod_unidad'];
					$cod_unidad = $this->dbmy->escape_srt($cod_unidad,TRUE);
					$queryfiltros = " and c.cod_unidad = '".$txt_referencia."' ";
				}
				if( array_key_exists('des_unidad',$parametros))
				{
					$des_unidad = $parametros['des_unidad'];
					$des_unidad = $this->dbmy->escape_str($des_unidad,TRUE);
					$queryfiltros = " and c.des_unidad like '%".$des_unidad."%' ";
				}
		  }		
		}

		$sqlprimerocuenta = "
		SELECT 
			count(*) as resultado 
		FROM
			esk_unidad_producto c 
		WHERE
			c.cod_unidad <> ''
			".$queryfiltros." 
		";

		$querydbusuarios1c = $this->dbmy->query($sqlprimerocuenta);
		$resultchequeo = $querydbusuarios1c->result();
		foreach ($resultchequeo  as $row)
			$resultado = $row->resultado;
		
		$sqlsegundotraer = "
		SELECT 
			".$resultado." as resultado,
			c.cod_unidad,
			c.des_unidad
		FROM
			esk_unidad_producto c 
		WHERE
			c.cod_unidad <> ''
			".$queryfiltros." 
		ORDER BY c.cod_unidad 
		LIMIT 40000 OFFSET 0
		";
		
		$querydbusuarios2u = $this->dbmy->query($sqlsegundotraer);	// sino devuelve el count pero en arreglo
		$arreglo_reporte = $querydbusuarios2u->result_array();

		if($resultado < 0)
			$arreglo_reporte = array('0'=>array('resultado'=>0));
		if($exportarodt !== TRUE)
			return $arreglo_reporte;	// devuelve un arreglo y el primer elemento del elemento '0' es 'resultado'
		else
			{
			 	$arreglo_csv=$this-> _hacerflujocsv($arreglo_reporte);
		        return $arreglo_csv;
		     }  
	}

}

?>
