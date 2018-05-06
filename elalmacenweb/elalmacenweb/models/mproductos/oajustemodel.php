<?php
/**
 * Oajustemodel 
 * @autor: Lenz Gerardo mckaygerhard@gmail.com
 * @description: manejo de data para forma 23 ajustes en valores o cantidades
 */ 
class Oajustemodel extends CI_Model {

	public $cod_depa_list = array('D'=>'DEMAS','H'=>'HOGAR');
	public $cod_tipo_list = array('C'=>'CANTIDAD','V'=>'VALOR');
	public $cod_signo_list = array('+'=>'POSITIVO','-'=>'NEGATIVO');

	public $dbmy;
	public $dboa;

	// el correlativo es la verdadera PK de este modelo de datos, hace que haya unicidad

	public function __construct() 
	{
		parent::__construct();
		$this->dboa = $this->load->database('oasisdb', TRUE);
		$this->dbmy = $this->load->database('elyanerodb', TRUE);
	}

	/*
	 * obtiene ultimo correlativo o verifica si existe el correlativo argumentado
	 * @name: get_correlativo
	 * @param string $usuario si viene desde api
	 * @param string $correlativo numero correlativo o sin parametros siqueire el ultimo
	 * @return array
	 */
	public function get_correlativo($usuario = NULL, $correlativo = NULL)
	{
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;
		$sqlfiltro = '';
		if( !is_array($correlativo) AND $correlativo != NULL AND $correlativo != '')
			$sqlfiltro = " WHERE cod_correlativo='".$correlativo."'";
		$sqlcorrelativo = "SELECT SUBSTR(CONCAT('000000', cod_correlativo), -6) AS cod_correlativo FROM yan_oajuste_forma ".$sqlfiltro." ORDER BY cod_correlativo DESC LIMIT 1 OFFSET 0";
		$querycorrelativo = $this->dbmy->query($sqlcorrelativo);
		$arreglo_correlativo = $querycorrelativo->result_array();
		if(count($arreglo_correlativo) < 1)
			return '000000';
		if($sqlfiltro != '')
			$arreglo_correlativo;
		return $arreglo_correlativo[0]['cod_correlativo'];
	}

	/*
	 * obtiene ultimo ajuste o si pasa el codigo verifica si existe el ajuste argumentado
	 * @name: get_ajuste_codigo
	 * @param string $usuario si viene desde api
	 * @param string $codigo numero correlativo o sin parametros siqueire el ultimo
	 * @return array $arreglo_oajuste(array(codigo, tipo, signo, $arreglo_detalle))
	 */
	public function get_ajuste_codigo($usuario = NULL, $codigo = NULL)
	{
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$sqlfiltro = "";
		if( !is_array($codigo) AND $codigo != NULL AND $codigo != '')
			$sqlfiltro = " WHERE cod_oajuste='".$codigo."' ";
		$sqlcabeza = "SELECT * FROM yan_oajuste_forma ".$sqlfiltro." ORDER BY cod_correlativo DESC LIMIT 1000 OFFSET 0";
		$querycabeza = $this->dbmy->query($sqlcabeza);
		$arreglo_oajuste = $querycabeza->result_array();
		if(count($arreglo_oajuste) < 1)
			return FALSE;
		if(!array_key_exists('cod_oajuste', $arreglo_oajuste[0]))
			return FALSE;
		foreach($arreglo_oajuste as $index => $oajuste)
		{
			$this->load->library('sys');
			$cod_correlativo = $arreglo_oajuste[$index]['cod_correlativo'];
			$cod_correlativo = $this->sys->completar_codigo($cod_correlativo,6);
			$arreglo_oajuste[$index]['cod_correlativo'] = $cod_correlativo;
			$cod_oajuste = $arreglo_oajuste[$index]['cod_oajuste'];
			$sqlfiltro = " WHERE cod_oajuste='".$cod_oajuste."' ";
			$sqldetalle = "SELECT * FROM yan_oajuste_detalle ".$sqlfiltro." ";
			$querydetalle = $this->dbmy->query($sqldetalle);
			$arreglo_detalle = $querydetalle->result_array();
			if(count($arreglo_detalle) < 1)
				$arreglo_detalle = array();
			$arreglo_oajuste[$index]['codigosajustes'] = $arreglo_detalle;
		}

		return $arreglo_oajuste; // el correlativo permite al cod_oajuste ser unico
	}

	/*
	 * obtiene listado de tiendas vs sellos, ejemplo: get_sucursales(array('cod_sucursal'=>'43,34')); get_sucursales(array('cod_sucursal'=>'43,34','cod_msc'=>'001034'));
	 * @name: get_sucursales_oasis
	 * @param string $usuario si viene desde api
	 * @param array/string $parametros, o '001032,001232' o sino array(cod_msc=>'001034,001043', cod_sucursal=>'34,2,23')
	 * @param string $exportar si se exporta a CSV
	 * @return array
	 */
	public function get_sucursales_oasis($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$cod_msc = NULL;
		$cod_sucursal = NULL;
		if( !is_array($parametros) )
			$cod_msc = $parametros;
		if( is_array($parametros) )
		{
			if(array_key_exists('cod_msc',$parametros))
				$cod_msc = $parametros['cod_msc'];
			if(array_key_exists('cod_sucursal',$parametros))
				$cod_sucursal = $parametros['cod_sucursal'];
		}
		$sqlfiltros = $sqlfiltro1 = $sqlfiltro2 = "";
		if($cod_msc != NULL AND $cod_msc != '')
			$sqlfiltro1 = "cod_msc IN (".$cod_msc.")";
		if($cod_sucursal != NULL AND $cod_sucursal != '')
			$sqlfiltro2 = " cod_sec_arc IN (".$cod_sucursal.")";
		if($sqlfiltro1 != '')
			$sqlfiltros = " WHERE ( ".$sqlfiltro1." )";
		if($sqlfiltro2 != '')
			$sqlfiltros = " WHERE ( ".$sqlfiltro2." )";
		if($sqlfiltro1 != '' AND $sqlfiltro2 != '')
			$sqlfiltros = " WHERE ( ".$sqlfiltro1." OR ".$sqlfiltro2." )";

		$sqlsello = "SELECT cod_msc,cod_sec_arc,nom_sucursal,cod_alt_msc FROM tc_codmsc ".$sqlfiltros;
		$querysello = $this->dboa->query($sqlsello);	// sino devuelve el count pero en arreglo
		$resultsetsello = $querysello->result();
		// no se puede usar directo array, problemas de encoding, se parsea a mano:
		$index = 0;
		$arreglo_sello = array();
		if(count($resultsetsello) > 0)
		foreach($resultsetsello as $row)
		{
			$arrayrow = array();
			$arrayrow['cod_msc'] = $row->cod_msc;
			$arrayrow['cod_sucursal'] = $row->cod_sec_arc;
			$arrayrow['nom_sucursal'] = iconv(mb_detect_encoding($row->cod_alt_msc, mb_detect_order(), true), "UTF-8//IGNORE", $row->nom_sucursal);
			$arrayrow['cod_alt_msc'] = iconv(mb_detect_encoding($row->cod_alt_msc, mb_detect_order(), true), "UTF-8//IGNORE", $row->cod_alt_msc);
			$arreglo_sello[$index] = $arrayrow;
			$index +=1;
		}
		if(count($arreglo_sello)<1)
			return FALSE;
		return $arreglo_sello;
	}


	/*
	 * obtiene la existencia o precio en el cod_msc para los codigo de productos pasados
	 * @name: get_producto_existencia_msc
	 * @param string $usuario si viene desde api
	 * @param array $codigos, cada valor del index es un codigo de producto unicamente eso
	 * @param string $cod_tipo V/C si es valor o cantidad
	 * @param string $cod_msc  el valor del codigo msc de la tienda del ajustar
	 * @param boolean $exportar si se exporta a CSV
	 * @return array
	 */
	public function get_producto_existencia_msc($usuario = NULL, $codigos = NULL, $cod_tipo = NULL, $cod_msc = NULL, $exportarodt = FALSE)
	{
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;

		if($codigos == NULL)
			return FALSE;
		if( !is_array($codigos) )
			return FALSE;
		if($cod_msc == '' OR $cod_msc == NULL)
			return FALSE;

		$arreglo_sello = $this->get_sucursales_oasis($usuario, $cod_msc);	// sino devuelve el count pero en arreglo
		if(count($arreglo_sello) < 1 OR count($arreglo_sello) > 1)
			return FALSE;
		if(!array_key_exists('cod_sucursal',$arreglo_sello['0'])) // sin sello es sin existencia, optimo
			return FALSE;
		$sello_msc = $arreglo_sello['0']['cod_sucursal'];

		// no se puede implode, con la lib llenar el codigo de compeltar ceros y llamarlo
		$this->load->library('sys');
		$sqlfiltro = "AND a.cod_interno IN (";
		foreach($codigos as $key => $codigo)
			$sqlfiltro .= "'".$this->sys->completar_codigo($codigo)."',";
		$sqlfiltro = substr($sqlfiltro, 0, -1).")";
		// armar SQL ya que arreglo es 0=>codigo,1=>codigo y asi
		$sqlexist = "
			SELECT 
				a.cod_msc, 
				a.nom_sucursal, 
				(SELECT cod_sec_arc FROM tc_codmsc WHERE cod_msc = '".$cod_msc."' LIMIT 1 OFFSET 0) as cod_sucursal, 
				a.cod_interno as cod_producto,
				a.txt_descripcion_larga as des_producto,";
			if($cod_tipo == "C")
				$sqlexist .= "a.saldo_producto as can_valor_ant, ";
			if($cod_tipo == "V")
				$sqlexist .= "(select b.mto_precio FROM ta_precio_producto b WHERE b.cod_interno = a.cod_interno AND cod_precio = 0 AND cod_sucursal = '".$sello_msc."' ORDER BY fec_desde DESC LIMIT 1 OFFSET 0) as can_valor_ant, ";
			$sqlexist .= "
				a.saldo_producto, 
				can_valor_ant as precio_producto
			FROM
				EXT2 AS a
			WHERE
				cod_msc = '".$cod_msc."' ".$sqlfiltro."
			ORDER BY a.cod_interno DESC  
		 ";

		// consulta de las exisstencia, a este le adoso los detalles tantas veces exista filas, todo en uno
		$query = $this->dboa->query($sqlexist);
		$arreglo_rep = $query->result_array();
		if(count($arreglo_rep) < 1)
			return FALSE;
		else
			return $arreglo_rep;

	}

	/**
	 * crea la forma 23 para despues seer analizada o modificada por generencia y posterior aprobacion
	 * @name   set_forma_preforma
	 * @param  string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param  array/string  $parametros($cod_depa, $cod_signo, $cod_tipo, $cod_correlativo, $cod_causa)
	 * @param  boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return array   array(resultado, aprobacion )
	 */
	public function set_forma_preforma($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		if( $parametros == NULL)
			return FALSE;
		if( !is_array($parametros) )
			return FALSE;
		if( !array_key_exists('cod_depa',$parametros))
			return FALSE;
		if( !array_key_exists('cod_tipo',$parametros))
			return FALSE;
		if( !array_key_exists('cod_signo',$parametros))
			return FALSE;
		if( !array_key_exists('cod_causa',$parametros))
			return FALSE;
		if( !array_key_exists('cod_msc',$parametros))
			return FALSE;
		if( !array_key_exists('cod_sucursal',$parametros))
			return FALSE;
		if( !array_key_exists('cod_correlativo',$parametros))
			return FALSE;
		if( !array_key_exists('list_codigosajustes',$parametros))
			return FALSE;
		if( !is_array($parametros['list_codigosajustes']))
			return FALSE;

		$this->dboa = $this->load->database('oasisdb', TRUE);
		$driverconected = $this->dboa->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$this->dbmy = $this->load->database('elyanerodb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$cod_depa = $parametros['cod_depa'];
		$cod_tipo = $parametros['cod_tipo'];
		$cod_signo = $parametros['cod_signo'];
		$cod_causa = $parametros['cod_causa'];
		$cod_msc = $parametros['cod_msc'];
		$cod_sucursal = $parametros['cod_sucursal'];
		$cod_correlativo = $parametros['cod_correlativo'];
		$list_codigosajustes = $parametros['list_codigosajustes'];
		unset($parametros['list_codigosajustes']);
		$anio = date('Y');
		$sessionficha = date('YmdHis').'entidad'.$usuario;
		$cod_oajuste = $cod_depa . $cod_signo . $cod_tipo . $anio . $cod_correlativo . '-' .$cod_sucursal .$cod_causa;
		$parametros['cod_oajuste'] = $cod_oajuste;

		$resultchequeo = $this->get_sucursales_oasis($usuario, $cod_msc);	// sino devuelve el count pero en arreglo
		$cod_sucursal_chk = $resultchequeo[0]['cod_sucursal'];
		if( $cod_sucursal != $cod_sucursal_chk)
			return FALSE;

		$existente = $this->get_ajuste_codigo($usuario, $cod_oajuste); // ojo el correlativo lo diferencia
		if($existente != FALSE AND is_array($existente) )
		{
			$array_ajuste = $existente;
			$array_detalle = $existente[0]['codigosajustes'];
			if($array_detalle != TRUE)
				return $existente; // si no hay detalle el proceso anterior salio malo no sigue
			if(count($array_detalle)!=count($list_codigosajustes))
				return $existente; // no se puede hacer update discrepancias, hcer nuevo o revisar
		}

		// preparar insercion o actualizacion ajuste forma 23
		$sqloajustecabeza = "";
		$sqloajustedetalle = "";
		$this->dbmy->trans_begin();
		if($existente == FALSE)
		{
			$parametros['sessionficha'] = $sessionficha;
			$sqloajustecabeza = $this->dbmy->insert_string('yan_oajuste_forma', $parametros);
			$this->dbmy->query($sqloajustecabeza);
			foreach($list_codigosajustes as $index => $rowdetalle)
			{
				$rowdetalle['cod_oajuste']=$cod_oajuste;
				$rowdetalle['sessionficha']=$sessionficha;
				$sqloajustedetalle = $this->dbmy->insert_string('yan_oajuste_detalle', $rowdetalle)."; ";
				$this->dbmy->query($sqloajustedetalle);
			}
		}
		else
		{
			$parametros['sessionflag'] = $sessionficha;
			$sqloajustecabeza = $this->dbmy->update_string('yan_oajuste_forma', $parametros, "cod_oajuste='".$cod_oajuste."'");
			$this->dbmy->query($sqloajustecabeza);
			foreach($list_codigosajustes as $index => $rowdetalle)
			{
				$rowdetalle['cod_oajuste']=$cod_oajuste;
				$rowdetalle['sessionflag']=$sessionficha;
				$sqloajustedetalle = $this->dbmy->update_string('yan_oajuste_detalle', $rowdetalle, "cod_oajuste='".$cod_oajuste."' AND cod_producto='".$rowdetalle['cod_producto'])."';";
				$this->dbmy->query($sqloajustedetalle);
			}
		}
		if ($this->dbmy->trans_status() === FALSE)
			$this->dbmy->trans_rollback();
		else
			$this->dbmy->trans_commit();
		return $cod_oajuste;
	}

	/*
	 * aprueba un ajuste
	 * @name: ajusteaprobar
	 * @param string $usuario si viene desde api
	 * @param array $parametros, (cod_oajuste, des_causa) filtros en el array campo/valor
	 * @param boolean $exportar si se exporta a CSV
	 * @return string/bollean codigo si aplico o falso si error de datos
	 */
	public function ajusteaprobar($usuario = NULL, $parametros = NULL)
	{
		if( !is_array($parametros) )
			return FALSE;
		if( !array_key_exists('cod_oajuste',$parametros))
			return FALSE;
		if( !array_key_exists('cod_aprobado',$parametros))
			$cod_aprobado = date('YmdHis');

		$cod_oajuste = $parametros['cod_oajuste'];
		if($cod_oajuste == '' OR $cod_msc == NULL)
			return FALSE;

		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$sqloajustedetalle = $this->dbmy->insert_string('yan_oajuste_aprobado', $rowdetalle)."; ";
		$this->dbmy->trans_begin();
		$this->dbmy->query($sqloajustedetalle);
		if ($this->dbmy->trans_status() === FALSE)
		{
			$this->dbmy->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->dbmy->trans_commit();
			return $cod_oajuste;
		}
	}

	/*
	 * obtiene listado de causas
	 * @name: get_causas
	 * @param string $usuario si viene desde api
	 * @param array $parametros, (cod_causa, des_causa) filtros en el array campo/valor
	 * @param boolean $exportar si se exporta a CSV
	 * @return array
	 */
	public function get_causas($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$this->dbmy = $this->load->database('elyanerodb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$cod_causa = $des_causa = '';
		if($parametros == NULL)
			$parametros = '*';
		if( !is_array($parametros) )
		{
			if($parametros == '' OR $parametros == NULL)
				return FALSE;
			else
				$cod_causa = $parametros;
		}
		else
		{
			if(count($parametros) < 1)
				return FALSE;
			if( array_key_exists('cod_causa',$parametros))
				$cod_causa = $parametros['cod_causa'];
			if( array_key_exists('des_causa',$parametros))
				$des_causa = $parametros['des_causa'];
		}
		if($cod_causa == '' AND $des_causa == '')
			return FALSE;

		$sqlfiltros = "";
		if($cod_causa != '' AND $cod_causa != '*')
			$sqlfiltros .= " AND cod_causa='".$cod_causa."'";
		if($des_causa != '')
			$sqlfiltros .= " AND des_causa='".$des_causa."'";
		$sqlcausas = "SELECT * FROM yan_oajuste_causa WHERE 1=1 ".$sqlfiltros;
		$querycausas = $this->dbmy->query($sqlcausas);
		$arreglo_causa = $querycausas->result_array();
		if(count($arreglo_causa) < 1)
			return FALSE;
		return $arreglo_causa;

	}

	/*
	 * maneja (inserta o actualiza) listado de causas
	 * @name: set_causas
	 * @param string $usuario si viene desde api
	 * @param array $parametros, filtros en el array campo/valor
	 * @return array
	 */
	public function set_causas($usuario = NULL, $parametros = NULL)
	{
		$this->dbmy = $this->load->database('elyanerodb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;
		if($parametros == NULL)
			return FALSE;
		if( !is_array($parametros) )
			return FALSE;
		if( !array_key_exists('cod_causa',$parametros))
			return FALSE;
		if( !array_key_exists('des_causa',$parametros))
			return FALSE;
		$cod_causa = $parametros['cod_causa'];
		$sqlcausas = "SELECT * FROM yan_oajuste_causa WHERE cod_causa=? ";
		$querycausas = $this->dbmy->query($sqlcausas,$cod_causa);
		$arreglo_causa = $querycausas->result_array();
		if(count($arreglo_causa) > 1)
			return FALSE;
		if(count($arreglo_causa) < 1)
			$sqlcausa = $this->dbmy->insert_string('yan_oajuste_causa', $parametros);
		else
			$sqlcausa = $this->dbmy->insert_string('yan_oajuste_causa', $parametros, 'cod_causa='.$cod_causa);
		$querycausa = $this->dbmy->query($sqlcausa);
		return $querycausa;
	}


}

?>
