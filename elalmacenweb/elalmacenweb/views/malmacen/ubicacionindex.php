<?php

/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */

	
	$namemodu = $this->uri->segment(1);
	$nameform = $this->uri->segment(2);
	$formuri = $namemodu .'/'.$nameform;
	

	$camposfiltroH1 = form_hidden('userurl',$this->userurl).PHP_EOL; // $this->input->get_post('userurl') url es una variable URL invocada'.PHP_EOL;
	$camposfiltroH2 = form_hidden('currenturl',$this->currenturl).PHP_EOL; // $currenturl es una variable ACTUAL'.PHP_EOL;

	if( !isset($des_posicion) ) $des_posicion = '';
	if( !isset($des_producto) ) $des_producto = '';
	
	echo form_fieldset('Administracion de ubicaciones del galpon') . PHP_EOL;
	$htmlformaattributosbuscr = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	echo $htmlformularioproductosbuscarAbre = form_open($formuri.'/ubicacioneslistar', $htmlformaattributosbuscr) . PHP_EOL;
	echo 'Por producto en posicion:' . $camposfiltro1 = form_input('des_producto',$des_producto);
	echo 'Por nombre de posicion:' . $camposfiltro2 = form_input('des_posicion',$des_posicion);
	echo $camposfiltroB = form_submit('consultarbusca', '(Re)Consultar', 'class="btn-primary btn"');
	echo $htmlformularioproductosbuscarCier = form_close() . PHP_EOL;
	echo form_fieldset_close();


  ?>
 
