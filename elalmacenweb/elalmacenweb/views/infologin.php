<?php

	echo br().PHP_EOL;
	echo form_fieldset('Sistema modular ERP купить 1.0') . PHP_EOL;
	echo br().PHP_EOL;
	echo $presentar.PHP_EOL;
	echo br().'modulo url actual = '. $currenturl. PHP_EOL;
	echo br().'modulo url pedida = '. $this->input->get_post('userurl'). PHP_EOL;
	echo br().PHP_EOL;
	echo br().PHP_EOL;
	echo ' OJO: currenturl: '.$this->currenturl . 'es una variable ACTUAL'.PHP_EOL;
	echo br().PHP_EOL;
	echo ' OJO: userurl: '.$this->userurl . 'es una variable IRL invocada'.PHP_EOL;
	echo br().PHP_EOL;
	echo form_fieldset_close() . PHP_EOL;
?>
	
