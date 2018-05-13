<?php

/** parte de la vista que muestra lso resultados si alguno existe, pinta la tabla de resultados iterando en el arreglo de lso resultados */


	echo form_fieldset('Ejemplo de inputs:') . PHP_EOL;

	$htmlformaattributos = array('name'=>'formulariodescargacsv','onSubmit'=>'return validageneric(this);');
	echo form_open('mproductos/indexmproductos/ejemploforminputs', $htmlformaattributos) . PHP_EOL;

	echo 'input1:'.form_input('username1','admin').PHP_EOL;

	echo br();

	echo 'input2 :'.form_password('userdescripcion1','1').PHP_EOL;

	echo br();

	echo form_submit('Enviar', 'valorenviar', 'class="btn-primary btn"');

	//echo form_hidden('inputhidden1',$valorinputhidden1).PHP_EOL;

	//echo form_hidden('inputhidden2',$valorinputhidden2).PHP_EOL;

	echo form_close() . PHP_EOL;

	echo form_fieldset_close() . PHP_EOL;

	if( !isset($semaforo) ) $semaforo = '';
	
	if($semaforo == 1)
	{
		echo 'enviado1 : '.$respuesta1.br().PHP_EOL;
	echo br();
	echo 'enviado2 : '.$respuesta2.br().PHP_EOL;

	}
	else
	{
		echo "no hay nada aun, ejecuta sumit y veras";
	}
  ?>

