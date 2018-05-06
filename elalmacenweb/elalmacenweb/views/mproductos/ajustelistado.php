<?php
/* productos rechazados index*/
/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */

	echo form_fieldset('Ajustes de forma 23, estados') . PHP_EOL;

	if( !isset($avisoresultado) ) $avisoresultado = 'AJUSTE 23 PROCESADO, pendeinte de aprobacion y ejecucion';
	echo '<div>'.$avisoresultado.'</div>';

	$htmlformaattributos = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	// la carga de tablas distintas solo se puede en el controlador, por eso no se maneja aqui:
	echo $presentar_ajustes;
	
	echo form_fieldset_close();




  ?>
