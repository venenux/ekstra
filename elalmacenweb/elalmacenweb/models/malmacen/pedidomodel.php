<?php
/* @Autor: Lenz McKAY Gerhard
 * @Descripción: Modelo para consultas de busquedas sobre pedidos
 * @Fecha: 22 febrero 2018
 */ 
class Pedidomodel extends CI_Model {

	public $dbmy; // cargar el conex aqui la conex principal.. 
	public $dboa; // cargar otra conex a otra db aqui

	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * obtiene una lista de las pedidos y sus nombres 
	 * devuelve SIEMPRE un arreglo a menos que se produzca un error y es NULL, 
	 * en este el indice es el codigo pedido, usese en combox selects
	 *
	 * @param       string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param       array/string  $parametros   arreglo con ( des_pedido, cod_pedido) o cod_pedido unicamente
	 * @param       boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return      array   array(cod_entidad, cod_pedido - des_pedido)
	 */
	public function get_pedidos_box($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		$pedidosselectbox = array();
		$arreglo = $this->get_pedido_codigo($usuario, $parametros);
		if($arreglo != FALSE)
		{
			foreach($arreglo as $indice => $pedido)
			{
				$pedidosselectbox[$pedido['cod_pedido']] = $pedido['cod_pedido'] .' - '. $pedido['cod_entidad'];
			}
			return $pedidosselectbox;
		}
		return FALSE;
	}

	/*
	 * obtiene ultimo pedido o si pasa el codigo verifica si existe el pedido argumentado
	 * @name: get_pedido_codigo
	 * @param string $usuario si viene desde api
	 * @param string $codigo del pedido para varios separar por comas
	 * @return array $arreglo_opedido(array(codigo, tipo, signo, $arreglo_detalle))
	 */
	public function get_pedido_codigo($usuario = NULL, $codigo = NULL)
	{
		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$cod_pedido = $codigo;
		if($cod_pedido === FALSE)
			return FALSE;

		$queryfiltros = "";
		if(stripos($cod_pedido,',') !== FALSE AND $cod_pedido != NULL)
		{
			$cod_pedidoes = explode(',',$cod_pedido);
			foreach($cod_pedidoes as $alm=>$cod_pedido)
			{
				$cod_pedido = $this->dbmy->escape($cod_pedido,TRUE);
				$queryfiltros .= " and c.cod_pedido = ".$cod_pedido." ";
			}
		}
		else if( $cod_pedido != NULL)
		{
			$cod_pedido = $this->dbmy->escape($codigo,TRUE);
			$queryfiltros = " and c.cod_pedido = ".$cod_pedido." ";
		}

		$sqlcabeza = "
			SELECT 
				p.cod_aprobado1, 
				p.cod_aprobado2, 
				c.* 
			FROM 
				esk_pedido AS c 
			LEFT JOIN 
				esk_proceso_aprobado AS p 
				ON c.cod_pedido = p.cod_proceso 
				WHERE c.cod_pedido <> '' ".$queryfiltros." 
			ORDER BY c.cod_pedido DESC LIMIT 1000 OFFSET 0
			";
		$querycabeza = $this->dbmy->query($sqlcabeza);
		$arreglo_opedido = $querycabeza->result_array();
		if(count($arreglo_opedido) < 1)
			return FALSE;
		if(!array_key_exists('cod_pedido', $arreglo_opedido[0]))
			return FALSE;
		foreach($arreglo_opedido as $index => $opedido)
		{
			$this->load->library('sys');
			$cod_pedido = $arreglo_opedido[$index]['cod_pedido'];
			$sqlfiltro = " WHERE cod_pedido='".$cod_pedido."' ";
			$sqldetalle = "SELECT * FROM esk_pedido_producto ".$sqlfiltro." ";
			$querydetalle = $this->dbmy->query($sqldetalle);
			$arreglo_detalle = $querydetalle->result_array();
			if(count($arreglo_detalle) < 1)
				$arreglo_detalle = array();
			$arreglo_opedido[$index]['codigospedidos'] = $arreglo_detalle;
		}

		return $arreglo_opedido;
	}

	/**
	 * crea un pedido basado en parametros, es el mas facil ya que no tiene dependencia le sigue la orden
	 * @name   set_pedido
	 * @param  string  $usuario    nombre del usaurio intranet de la sesion que ejecuta la consulta
	 * @param  array/string  $parametros($cod_depa, $cod_signo, $cod_tipo, $cod_correlativo, $cod_causa)
	 * @param  boolean  $exportarcsv    Si TRUE solo escupe un string con el fluo del archivo CSV a rellenar
	 * @return array   array(resultado, aprobacion )
	 */
	public function set_pedido($usuario = NULL, $parametros = NULL, $exportarodt = FALSE)
	{
		if( $parametros == NULL)
			return FALSE;
		if( !is_array($parametros) )
			return FALSE;
		if( !array_key_exists('cod_pedido',$parametros))
			return FALSE;
		if( !array_key_exists('cod_ejecuta',$parametros))
			return FALSE;
		if( !array_key_exists('cod_origen',$parametros))
			return FALSE;
		if( !array_key_exists('cod_destino',$parametros))
			return FALSE;
		if( !array_key_exists('list_codigos',$parametros))
			return FALSE;
		if( !is_array($parametros['list_codigos']))
			return FALSE;
		if( count($parametros['list_codigos']) < 1)
			return FALSE;

		$this->dbmy = $this->load->database('elalmacenwebdb', TRUE);
		$driverconected = $this->dbmy->initialize();
		if($driverconected != TRUE)
			return FALSE;

		$cod_pedido = $parametros['cod_pedido'];
		$cod_ejecuta = $parametros['cod_ejecuta'];
		$cod_origen = $parametros['cod_origen'];
		$cod_destino = $parametros['cod_destino'];
		$list_codigos = $parametros['list_codigos'];
		unset($parametros['list_codigos']);
		$fecha = date('Ymd');
		$estado = 'ABIERTO';
		$sessionflag = date('YmdHis').'entidad'.$usuario;
		$sessionficha = $sessionflag;
		$parametros['num_pedido'] = $cod_pedido.'-'.$cod_origen.'-'.$cod_destino;
		$parametros['fecha'] = $fecha;
		$parametros['estado'] = $estado;

		$existente = $this->get_pedido_codigo($usuario, $cod_pedido); // ojo el correlativo lo diferencia
		if($existente != FALSE AND is_array($existente) )
		{
			$array_ajuste = $existente;
			$array_detalle = $existente[0]['list_codigos'];
			if($array_detalle != TRUE)
				return $cod_pedido; // si no hay detalle el proceso anterior salio malo no sigue
			if(count($array_detalle)!=count($list_codigos))
				return FALSE; // no se puede hacer update discrepancias, hcer nuevo o revisar
		}

		// preparar insercion o actualizacion ajuste forma 23
		$sqloajustecabeza = "";
		$sqloajustedetalle = "";
		$this->dbmy->trans_begin();
		if($existente == FALSE)
		{
			$parametros['sessionficha'] = $sessionficha;
			$sqloajustecabeza = $this->dbmy->insert_string('esk_pedido', $parametros);
			$this->dbmy->query($sqloajustecabeza);
			foreach($list_codigos as $index => $tosql)
			{
				$tosql['cod_pedido']=$cod_pedido;
				$tosql['sessionficha']=$sessionficha;
				$sqloajustedetalle = $this->dbmy->insert_string('esk_pedido_producto', $tosql)."; ";
				$this->dbmy->query($sqloajustedetalle);
			}
		}
		else
		{
			$parametros['sessionflag'] = $sessionficha;
			$sqloajustecabeza = $this->dbmy->update_string('esk_pedido', $parametros, "cod_pedido='".$cod_pedido."'");
			$this->dbmy->query($sqloajustecabeza);
			foreach($list_codigosajustes as $index => $tosql)
			{
				$tosql['cod_pedido']=$cod_pedido;
				$tosql['sessionflag']=$sessionficha;
				$sqloajustedetalle = $this->dbmy->update_string('esk_pedido_producto', $tosql, "cod_pedido='".$cod_pedido."' AND cod_producto='".$tosql['cod_producto'])."';";
				$this->dbmy->query($sqloajustedetalle);
			}
		}
		if ($this->dbmy->trans_status() === FALSE)
			$this->dbmy->trans_rollback();
		else
			$this->dbmy->trans_commit();
		return $cod_pedido;
	}


}

?>
