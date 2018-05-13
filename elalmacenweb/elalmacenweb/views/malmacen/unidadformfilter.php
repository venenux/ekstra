<?php

/** parte de la vista que muestra lso resultados si alguno existe, pinta la tabla de resultados iterando en el arreglo de lso resultados */


	echo form_fieldset('Filtrar las unidades de cantidad de producto por:') . PHP_EOL;

	$htmlformaattributos = array('name'=>'formulariodescargacsv','onSubmit'=>'return validageneric(this);');
	echo form_open('malmacen/unidadmanager/unidadeslistar', $htmlformaattributos) . PHP_EOL;

	if( !isset($cod_unidad) ) $cod_unidad = '';
	if( !isset($des_unidad) ) $des_unidad = '';
	echo 'Por codigo?: '.form_input('cod_unidad',$cod_unidad).PHP_EOL;
	echo 'Por nombre?: '.form_input('des_unidad',$des_unidad).PHP_EOL;
	echo form_submit('Filtrar resultados', 'botonfiltrar', 'class="btn-primary btn"');
	//echo form_hidden('inputhidden1',$valorinputhidden1).PHP_EOL;
	//echo form_hidden('inputhidden2',$valorinputhidden2).PHP_EOL;

	echo form_close() . PHP_EOL;

	echo form_fieldset_close() . PHP_EOL;

  ?>
