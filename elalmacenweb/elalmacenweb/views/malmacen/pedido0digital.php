	<?php

	// si variables vacias llenar con datos mientras tanto
	if( !isset($accionejecutada) ) $accionejecutada = 'cargardatos';
	if( !isset($list_codigos) ) $list_codigos = '';
	if( !isset($list_cantida) ) $list_cantida = '';
	if( !isset($list_almacenes_origen) ) $list_almacenes_origen = array('codigomsc' => 'narnai 1','codigomsc2' => 'narnia 2');
	if( !isset($list_almacenes_destino) ) $list_almacenes_destino = array('codigomsc' => 'narnia 3','codigomsc2' => 'narnia 4');
	// detectar que mostrar segun lo enviado desde el controlador
	if ($accionejecutada == 'cargardatos')
	{
		$htmlformaattributos = array('name'=>'formularioordendespachogenerar','class'=>'formularios','onSubmit'=>'return validageneric(this);');
		echo form_fieldset('Ingrese los datos por favor',array('class'=>'container_blue containerin ')) . PHP_EOL;
		echo form_open_multipart('pedido/pedido1digital/', $htmlformaattributos) . PHP_EOL;
		echo 'Origen:'.form_dropdown('ubicacionorigen', $list_almacenes_origen).br().PHP_EOL;
		echo 'Destino:'.form_multiselect('ubicaciondestin[]', $list_almacenes_destino).br().PHP_EOL;
		echo $inputCodigos = 'Codigos: '.form_textarea('list_codigos',$list_codigos).br();
		echo $inputAjustes = 'Cantidad: '. form_textarea('list_cantida',$list_cantida).br();
		echo 'Archivo?:'.form_upload('archivoproductosprecionom').br().PHP_EOL;
		$separadores = array(''=>'', '\t'=>'Tabulador (|)', ','=>'Coma (,)',';'=>'PuntoComa (;)');
		echo 'Separar:'.form_dropdown('archivoproductospreciosep', $separadores).br().PHP_EOL;
		echo form_submit('login', 'Generar', 'class="btn btn-primary btn-large b10"');
		echo form_close() . PHP_EOL;
		echo form_fieldset_close() . PHP_EOL;
		echo "".PHP_EOL;
		echo form_fieldset('EJEMPLO DE COMO ES EL ARCHIVO',array('class'=>'container_blue containerin ')) . PHP_EOL;
		echo $tableejemplo;
		echo form_fieldset_close() . PHP_EOL;
	}
	else if ($accionejecutada == 'resultadocargardatos')
	{
		echo form_fieldset('Orden de compra generada',array('class'=>'container_blue containerin ')) . PHP_EOL;
		echo 'Origen: '.$ubicacionorigen.', Destino: '.$eldestinosinsertar.'<br>'.PHP_EOL;
		echo 'Pedido generada: '.$filenamen.', Procesados: '.$cantidadLineas.'<br>'.PHP_EOL;
		echo $htmltablageneradodetalle;
		echo form_fieldset_close() . PHP_EOL;
		echo anchor('generarordenconcarga', 'Revisar las ordenes existentes no procesadas.');
	}
	?>
