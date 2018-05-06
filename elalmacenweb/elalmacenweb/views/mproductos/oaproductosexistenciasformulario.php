<?php
/* productos rechazados index*/
/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */
 
    
	echo form_fieldset('Consultar Productos Rechazados') . PHP_EOL;

	if( !isset($avisoresultado) ) $avisoresultado = 'Debe ingresar las fechas y escoger una sucursal';
	echo '<div>'.$avisoresultado.'</div>';

	$htmlformaattributos = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	echo form_open_multipart('mproductos/oaproductosexistencias/mostrarexistencias1formulario', $htmlformaattributos) . PHP_EOL;

	if( !isset($fec_ini) ) $fec_ini = date('Y-m-d');
		echo br().'Fecha inicio (ejem 2018-10-01):'.form_input('fec_ini',$fec_ini);
	if( !isset($fec_fin) ) $fec_fin = date('Y-m-d');
		echo br().'Fecha final (ejem 2018-01-31):'.form_input('fec_fin',$fec_fin);

		echo form_submit('Consultar', '(Re)Consultar', 'class="btn-primary btn"');

	echo form_close() . PHP_EOL;

	echo form_fieldset_close();


  ?>
 
