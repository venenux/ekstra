<?php 
/**
 * esk_usuario.php
 * 
 * abstraccion para obtener datos de usuario en codeigniter
 *
 * tabla y campos
 * * esk_usuario(ficha,username,userclave,)
 * * esk_usuario_modulo(username, cod_perfil)
 *
 * objeto sesion manejado
 * * ci_session:
 * ** user_data seteado a nulo no se usa
 * ** cuantos, username, ficha, ...)
 * 
 * Copyright 2017 PICCORO Lenz McKAY <mckaygerhard@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 or any other.
 * 
 */
class Esk_usuario extends CI_Model 
{

	public $db1;

	public function __construct() 
	{
		parent::__construct();
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
	}

	/**
	 * verifica si el usuario es valido con la clave provista en md5
	 *
	 * @access	public
	 * @param	string  username
	 * @param	string  userclave
	 * @param	string  credential(md6 de usuario y clave juntos)
	 * @return	boolean TRUE si datos son validos
	 */
	public function getusuario($username, $clavename = '', $credential = NULL)
	{
		$clavelen = strlen($clavename);
	
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);

		// determino que es lo que se pide un usuario en todo perfil o todos los usuarios
		if ( trim($username) != '*' AND trim($username) != '' AND $clavelen != 32 )
			$queryfiltro1 = " username <> '' AND ( `username`='".$username."' AND `userclave` = md5('".$clavename. "') ) OR ( `ficha`='".$username."' AND `userclave` = md5('".$clavename. "') )";
		else if ( trim($username) != '*' AND trim($username) != '' AND $clavelen == 32 )
			$queryfiltro1 = " username <> '' AND ( `username`='".$username."' AND `userclave` = '".$clavename. "' ) OR ( `ficha`='".$username."' AND `userclave` = '".$clavename. "' )";
		else if ( $username == '*')
			$queryfiltro1 = " `username` <> '' ";
		else
			$queryfiltro1 = " `username` <> '' AND userclave = '' ";

		$cuantos = 0;
		// primero cuento cuantos hay en la misma entidad // TODO hacer join con las entidades asociadas
		$sqldbusuarios1c = "
			SELECT count(*) as cuantos 
			FROM elalmacenwebdb.`esk_usuario` 
			WHERE ( ".$queryfiltro1." ) AND `username` <> '' LIMIT 1 OFFSET 0";
		$querydbusuarios1c = $this->db1->query($sqldbusuarios1c);
		// el resultado es el mismo usuario repetido tantas entidades tenga asociada, esto se cuenta cuantos hay
		$resultobjusuario = $querydbusuarios1c->row_array();
		$cuantos = $resultobjusuario['cuantos'];
		// una vez el numero, se inserta como parte del sql para que se vaya en el arreglo de respuesta
		$sqldbusuarios2u = "
			SELECT ".$cuantos." as cuantos,`esk_usuario`.* 
			FROM elalmacenwebdb.`esk_usuario` 
			WHERE ( ".$queryfiltro1." ) AND `username` <> '' ";
		$querydbusuarios2u = $this->db1->query($sqldbusuarios2u);	// sino devuelve el count pero en arreglo

		if ( $cuantos < 1 ) 
			$esk_usuarios_result = $querydbusuarios1c->result_array();	// pero solo si hay 1 o mas
		else
			$esk_usuarios_result = $querydbusuarios2u->result_array();

		return $esk_usuarios_result;	// devuelve un arreglo y el primer elemento del elemento '0' es 'cuantos'
	}

	/**
	 * actualiza data del usuario segiun filtro, si campos desconocidos en filtro falla
	 *
	 * @access	public
	 * @param	array  datauser campos de la tabla con sus valores
	 * @param	string  filter  parte "where" del query con los filtros
	 * @return	boolean TRUE si actualizacion exitosa
	 */
	public function updusuario($datauser=NULL, $filter=NULL)
	{

		if($datauser == NULL OR $filter == NULL)
			return false;

		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
		$sqluser = $this->db1->update_string('esk_usuario', $datauser, $filter);
		$queryrst = $this->db1->query($sqluser);
		return $queryrst;
    }

	/**
	 * retorna informacion de un usuario especifico, solo campos visibles no sensibles
	 *
	 * @access	public
	 * @param	string  username
	 * @return	array('cuandos'=>integer,...) arreglo de un arreglo conlos datos del usuario, incluyendo clave
	 */
	public function getuserinfo($username)
	{
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);

		$queryfiltro1 = " `username`='".$username."' ";
		// primero cuento cuantos hay en la misma entidad // TODO hacer join con las entidades asociadas
		$sqldbusuarios1c = "
			SELECT count(*) as cuantos 
			FROM elalmacenwebdb.`esk_usuario` 
			WHERE ( ".$queryfiltro1." ) AND `username` <> '' ";
		$querydbusuarios1c = $this->db1->query($sqldbusuarios1c);
		// el resultado es el mismo usuario repetido tantas entidades tenga asociada, esto se cuenta cuantos hay
		$resultobjusuario = $querydbusuarios1c->row();
		$cuantos = $resultobjusuario->cuantos;
		// una vez el numero, se inserta como parte del sql para que se vaya en el arreglo de respuesta
		$sqldbusuarios2u = "
			SELECT ".$cuantos." as unicidad,`ficha`,`username`,`cod_nivel`,`ses_ip`,`sessionlast` 
			FROM elalmacenwebdb.`esk_usuario` 
			WHERE ( ".$queryfiltro1." ) AND `username` <> '' ";
		$querydbusuarios2u = $this->db1->query($sqldbusuarios2u);	// sino devuelve el count pero en arreglo

		if ( $cuantos < 1 )
			$esk_usuarios_result = $querydbusuarios1c->result_array();	// pero solo si hay 1 o mas
		else
			$esk_usuarios_result = $querydbusuarios2u->result_array();

		return $esk_usuarios_result;	// devuelve un arreglo y el primer elemento del elemento '0' es 'cuantos'
	}

	/**
	 * retorna true/false/nombres si puede  usar/acceder el controlador del parametro pasado a la funcion
	 * es usada en los controladores para ver si hay permiso del mismo
	 * tambien por la libreria de usuario
	 */
	public function getpermisos($controlador = "none")
	{
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
		$queryfiltro1 = "";

		// para saber si encontro el controlador y devolver null
		$leninput = strlen($controlador);

		$this->load->library('encrypt');
		$this->load->library('session');
		// determinar el usuario
		if( $this->session->userdata('cuantos') )
		{
			$userdata = $this->session->userdata('cuantos'); // se consulta "0" o cualquiera porque ya traen "cuantos" como columna
			if ( $userdata > 0 )
				$username = $this->session->userdata('username');
			else
				return FALSE; // si no hay un minimo de "cuantos" es porque o salio o se destruyo (salio forzado)
		}
		else
			return 0;
		
		$queryfiltro1 = $queryfiltro1 . " username <> '' AND ";
		// determino si es sobre un controlador especifico o varios, o preguntar todos/cuales
		if ( !is_array($controlador) )
			$controlador = array($controlador);
		foreach ( $controlador as $namecontrolador )
		{
			if ( $namecontrolador != '*' and trim($namecontrolador) != '' )
				$queryfiltro1 = $queryfiltro1 . " (SUBSTR(`nam_acceso`, 1, 3)= '".$namecontrolador."' OR `nam_acceso`= '".$namecontrolador."') AND ";
			else
				$queryfiltro1 = $queryfiltro1 . " `nam_acceso` <> '' AND ";
		}
		// por ultimo debe seer unicamente sobre este usuario
		$queryfiltro1 = $queryfiltro1 . " `username` = '".$username. "' ";

		$sqldbusuarios1c = "
			SELECT *
			FROM elalmacenwebdb.`adm_acceso`
			WHERE 1=1 AND ".$queryfiltro1." ";
		$querydbusuarios1c = $this->db1->query($sqldbusuarios1c);

		$loscontroladores = $querydbusuarios1c->result();	// pero solo si hay 1 o mas
		$cuales = "";
		foreach ($loscontroladores as $row)
			$cuales = $cuales . ' ' . $row->nam_acceso;

		$cuales = str_ireplace(" ","", $cuales);
		if ( strlen($cuales) < 1 )
			return FALSE;
		else if ( strlen($cuales) == $leninput )
			return TRUE;
		else
			return $cuales;	// devuelve todos los nombres separados por comas
		
	}

	/**
	 * retorna arreglo de accesos para poner lols enlaces de menu, se usa en all_menu->getmenuprincipal
	 */
	public function getmenuaindex()
	{
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
		$queryfiltro1 = "";

		$this->load->library('encrypt');
		$this->load->library('session');
		// determinar el usuario
		if( $this->session->userdata('0') )
		{
			$userdata = $this->session->userdata('0'); // se consulta "0" o cualquiera porque ya traen "cuantos" como columna
			if ( $userdata['cuantos'] > 0 )
				$username = $userdata['username']; // TODO recorrer el array con el usuario ir a DB traer donde puede entrar
			else
				return FALSE; // si no hay un minimo de "cuantos" es porque o salio o se destruyo (salio forzado)
		}
		else
			return FALSE;
		// TODO: SELECT * FROM elalmacenwebdb.sys_menu; TODO: pendiente
		$sqldbusuarios1c = "
			SELECT * 
			FROM elalmacenwebdb.adm_acceso 
			WHERE nam_acceso LIKE '%aindex%' AND `username` = '".$username. "' 
			ORDER BY des_acceso";
		$querydbusuarios1c = $this->db1->query($sqldbusuarios1c);

		$menusprincipales = $querydbusuarios1c->result_array();	// pero solo si hay 1 o mas
		
		if ( count($menusprincipales) < 1 )
			return FALSE;
		else
			return $menusprincipales;	// devuelve todos los nombres separados por comas
	}

	/** 
	 * inserta o actualiza una usuario, basado en sus parametros, en un arreglo lineal
	 * ejemplo array('cod_usuario' => date("YmdHis") , 'des_usuario' => 'tienda nueva');
	 * el array siempre debe tener el elemento cod_usuario, ejemplo //$paramusuario = array('cod_usuario' => '342' , 'des_usuario' => 'tienda nueva');
	 * @paramns array('campo1'=>'valor1, campo2=>valor2,...)
	 * @return FALSE si no inserta, TRUE/array si insercion exitoso
	 */
	public function usuarios_set($parametros = null )
	{
		if ( $parametros == null)					// verificamos si hay parametros
			return FALSE;
		if ( ! is_array($parametros) ) 				// aqui verifica que sea un arreglo
			return FALSE;
		if ( ! array_key_exists('username',$parametros) )	// aqui verifica si esta username
			return FALSE;
		if( empty($parametros['username']) )	// aqui verifica que no este vacio username
			return FALSE;
		$username = $parametros['username'];
		// $parametros es un arreglo con los datos, si solo uno, no tiene sentido, si existe: es update si no: es insert
		$numeroparametros = count($parametros);
		if ( $numeroparametros < 2 )
			return TRUE;
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
		$sqldbusuario = "SELECT count(username) as existe FROM elalmacenwebdb.esk_usuario WHERE username = '".$username."'";
		$querydbusuario = $this->db1->query($sqldbusuario);
		$queryexiste = $querydbusuario->row()->existe; 
		if ( (int)$queryexiste == 0 )
			$sqldbusuario = $this->db1->insert_string("elalmacenwebdb.esk_usuario", $parametros);
		else
			$sqldbusuario = $this->db1->update_string("elalmacenwebdb.esk_usuario", $parametros, "username = '".$username."'");
		$rsdbusuario = $this->db1->query($sqldbusuario);
		if($rsdbusuario === TRUE)
			return TRUE;
		else
			return FALSE;
		// si resultado exitoso se retorna un array de la nueva/modificada usuario
	}

	/**
	 * retorna un arreglo de usuarios y/o su asociacion a una entidad (pendiente esto ultimo)
	 * array ( codigo -> (codigo, username, nombre, ...) )
	 * el formato del arreglo depende del parametro si no se dan parametros es <br>
	 * array ( username -> nombre (username), username1 -> nombre2 (username2) ...) )
	 * @parametros array arreglos opcionales
	 * los parametros son: 
	 * * username : la ficha o username/session forma de cod_usuario o * o un arreglo de los username que se desean
	 * * tipo_usuario: NOADM trae todas menos las administrativas, TODAS trae todas menos las invalidas y vacio o sin setear todas, permite arreglo con los tipos deseados
	 * * clase_usuario: TODAS o * trae todas menos las invalidas sino un arreglo con las deseadas
	 */
	public function usuarios_get($parametros = '' )
	{
		$tipo_usuario = ''; // todas
		$formato = 'array'; // solo codigo y nombre array dos column
		$username = '';
		$clase_usuario = '';
		$arreglousuarios = array();
		if ($parametros == '')
		    $parametros = array('username'=>'', 'tipo_usuario'=>'', 'formato'=>'');
		if (is_array($parametros) ) 
		{
			if( !empty($parametros['username']) )
				if( $parametros['username'] != null)
					$username = $parametros['username'];
			if( !empty($parametros['tipo_usuario']) )
				if( $parametros['tipo_usuario'] != null)
					$tipo_usuario = $parametros['tipo_usuario'];
			if( !empty($parametros['clase_usuario']) )
				if( $parametros['clase_usuario'] != null)
					$clase_usuario = $parametros['clase_usuario'];
			if( !empty($parametros['formato']) )
				if( $parametros['formato'] != null )
					$formato = $parametros['formato'];
		}
		else
		{
			if ( $parametros != '' and !empty($parametros) )
				$username = $parametros;
		}
		$this->db1 = $this->load->database('elalmacenwebdb', TRUE);
		// armarndo consulta sql parcial de los filtros
		$queryfiltros = "";
		if( !is_array($username) )
		{
			$username = str_replace (" ","",$username);
			if( $username != '' and $username != '*' and $username != 'all')
				$queryfiltros .= " AND ( us.username='".$username."' OR us.ficha='".$username."') ";
		}
		else
		{
			foreach($username as $intra)
				$queryfiltros .= " AND ( us.username='".$intra."' OR us.ficha='".$intra."') ";
		}
		if( ! is_array($tipo_usuario) )
		{
			if( $tipo_usuario == 'TODAS' or $tipo_usuario == '*')
				$queryfiltros .= " AND us.tipo_usuario <> 'INVALIDA' ";
			else if( $tipo_usuario == 'NOADM' or $tipo_usuario == '*')
				$queryfiltros .= " AND us.tipo_usuario <> 'INTERNO' ";
			else if( $tipo_usuario != '' and $tipo_usuario != '*')
				$queryfiltros .= " AND us.tipo_usuario = '".$tipo_usuario."'";
		}
		else
		{
			foreach($tipo_usuario as $tipo)
				if ( $tipo == 'NOADM' )
					$queryfiltros .= " AND us.tipo_usuario <> 'INTERNO'";
				else
					$queryfiltros .= " AND us.tipo_usuario = '".$tipo."'";
		}
		if( !is_array($clase_usuario) )
		{
			if( $clase_usuario == 'TODAS' or $clase_usuario == '*')
				$queryfiltros .= " AND us.clase_usuario <> 'INVALIDO' ";
			else if( $clase_usuario != '' and $clase_usuario != '*')
				$queryfiltros .= " AND us.clase_usuario = '".$clase_usuario."'";
		}
		else
		{
			foreach($clase_usuario as $clase)
				$queryfiltros .= " AND us.clase_usuario = '".$clase."'";
		}
		// consulta sql principal a la que se adjunta el filtro
		$sqldbusuarios = "
			SELECT
				us.ficha,
				us.username,
				/*us.realm,*/
				us.nombre,
				us.origen as cod_localidad_origen,
				lo.des_localidad as des_localidad_origen,
				us.condicion,
				us.estado,
				us.tipo_usuario,
				us.clase_usuario,
				us.foto_binario,
				us.foto_usuario,
				us.fecha_ingreso,
				us.fecha_ultimavez,
				us.fecha_egreso,
				/*us.cod_perfil,*/
				us.sessionflag,
				us.sessionficha
			FROM 
				esk_usuario AS us
			LEFT JOIN 
				adm_localidad as lo
			ON 
				us.origen = lo.cod_localidad
			WHERE 
				us.username <> '' ".$queryfiltros."
			";
		$querydbusuarios = $this->db1->query($sqldbusuarios);
		$resultobjusuarios = $querydbusuarios->result();
		if ( $formato === 'selector' OR $formato === 's')
			$arreglousuarios = array(''=>''); // TODO: fotos https://github.com/Mobius1/Selectr/wiki/Options#renderoption
		foreach ($resultobjusuarios as $iuarray)
		{
			if ( $formato != 'selector' AND $formato != 's' ) //|| SI CUALQ ES FALSO , si no para selector
			{
				$arrayusuario = array();
				foreach( $iuarray as $columna => $valores)
				{
					$arrayusuario[$columna] = $valores;
				}
				$arreglousuarios[$iuarray->username . $formato] = $arrayusuario;
			}
			else
			{
				$arreglousuarios[$iuarray->username] = $iuarray->nombre . ' ('.$iuarray->username.')';
			}
		}
		return $arreglousuarios;
	}
		// FOTOS:
		// <select>
//  <option style="background-image:url(male.png);">male</option>
//  <option style="background-image:url(female.png);">female</option>
//  <option style="background-image:url(others.png);">others</option>
//</select> 
//<select name="form[location]">
    //<option value="ad" style="background: url(img/flags/ad.gif) no-repeat; padding-left: 20px;">Andorra</option>
    //<option value="ae" style="background: url(img/flags/ae.gif) no-repeat; padding-left: 20px;">United Arab Emirates</option>
    //<option value="af" style="background: url(img/flags/af.gif) no-repeat; padding-left: 20px;">Afghanistan</option>
    //<option value="ag" style="background: url(img/flags/ag.gif) no-repeat; padding-left: 20px;">Antigua and Barbuda</option>
    //<option value="ai" style="background: url(img/flags/ai.gif) no-repeat; padding-left: 20px;">Anguilla</option>
    //<option value="al" style="background: url(img/flags/al.gif) no-repeat; padding-left: 20px;">Albania</option>
    //<option value="am" style="background: url(img/flags/am.gif) no-repeat; padding-left: 20px;">Armenia</option>
    //<option value="an" style="background: url(img/flags/an.gif) no-repeat; padding-left: 20px;">Netherlands Antilles</option>
    //<option value="ao" style="background: url(img/flags/ao.gif) no-repeat; padding-left: 20px;">Angola</option>
    //<option value="ar" style="background: url(img/flags/ar.gif) no-repeat; padding-left: 20px;" selected="selected">Argentina</option>

    //[...] - I think you get the idea.

//</select>
		//$this->db1->close();	}

}
