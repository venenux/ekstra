<?php defined('BASEPATH') OR exit('No direct script access allowed');
		if ( !isset($errl) ) $errl = '';
		$htmlformaattributos = array('name'=>'formulariomanejousuarios','class'=>'formularios','onSubmit'=>'return validageneric(this);');

		echo '<h2><center>Registro de pagos: Inicie Sesión</center></h2>';
		echo form_open('Indexlogin/iniciarsesion', $htmlformaattributos) . PHP_EOL;
		echo 'Usuario:'.form_input('username').PHP_EOL;
		echo 'Clave :'.form_password('userclave').PHP_EOL; 
		$moduloindexarray = array();
		$moduloindexarray['0'] = 'pagos';
		//$moduloindexarray['1'] = 'devel';
		//$moduloindexarray['2'] = 'bitacora';
		echo form_dropdown('modulourl', $moduloindexarray, '');
		echo form_submit('login', 'Iniciar sesion');
		echo form_close() . PHP_EOL;
		echo br();
		echo '<center>'.substr($errl,0,50).'</center>';
  
?>
