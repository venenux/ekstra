<?php defined('BASEPATH') OR exit('No direct script access allowed');
		if ( !isset($errl) ) $errl = '';
		$htmlformaattributos = array('name'=>'formulariomanejousuarios','class'=>'formularios','onSubmit'=>'return validageneric(this);');

		echo '<h2><center>Registro de pagos: Inicie Sesión</center></h2>';
		echo form_open('login_usuario/iniciarsesion', $htmlformaattributos) . PHP_EOL;
		echo 'Usuario:'.form_input('username').PHP_EOL;
		echo 'Clave :'.form_password('contrasena').PHP_EOL; 
		$moduloindexarray = array();
		$moduloindexarray['0'] = 'pagos-devel';
		$moduloindexarray['1'] = 'pagos';
		$moduloindexarray['2'] = 'bitacora';
		echo form_dropdown('modulo', $moduloindexarray, '');
		echo form_submit('login', 'Iniciar sesion');
		echo form_close() . PHP_EOL;
		echo br();
		echo '<center>'.$errl.'</center>';
  
?>
