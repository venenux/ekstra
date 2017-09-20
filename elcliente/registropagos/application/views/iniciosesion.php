<?php defined('BASEPATH') OR exit('No direct script access allowed');
		if ( !isset($paso) ) $paso = '';
		$htmlformaattributos = array('name'=>'formulariomanejousuarios','class'=>'formularios','onSubmit'=>'return validageneric(this);');

		echo '<h2><center>Registro de pagos: Inicie Sesión</center></h2>';
		echo form_open('login_usuario/iniciarsesion', $htmlformaattributos) . PHP_EOL;
		echo 'Usuario:'.form_input('username').PHP_EOL;
		echo form_hidden('paso',$paso).PHP_EOL;
		echo 'Clave :'.form_password('contrasena').PHP_EOL; 
		$moduloindexarray = array('1'=>'1','2'=>'2');
		echo form_dropdown('modulo', $moduloindexarray, '1');
		echo form_submit('login', 'Iniciar sesion');
		echo form_close() . PHP_EOL;
  
?>
