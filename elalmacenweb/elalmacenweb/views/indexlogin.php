<h3>Sistema modular ERP купить (version 1.0) modulos extras</h3><br>
<?php

	$userurl = $this->input->get_post('userurl');

	echo form_fieldset('Sistema de links yanero') . PHP_EOL;
		$htmlformaattributos = array('name'=>'formulariologin','onSubmit'=>'return validageneric(this);');
		echo form_open('indexlogin/entrarlogin', $htmlformaattributos) . PHP_EOL;
		echo 'Intranet:'.form_input('username1','admin').PHP_EOL;
		echo 'Clave :'.form_password('userclave1','1').PHP_EOL;
		echo form_submit('login', 'Iniciar', 'class="btn-primary btn"');
		echo form_hidden('userurl',$userurl).PHP_EOL; // redireccion
		echo form_hidden('modulourl',$userurl).PHP_EOL; // http://127.0.0.1/registropagos/index.php/Indexlogin/iniciarsesion
		echo form_close() . PHP_EOL;
	echo form_fieldset_close() . PHP_EOL;
	
	?>
	
