<?php
/* productos rechazados index*/
/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */

	echo form_fieldset('Listado de pedidos para ordenes') . PHP_EOL;

	if( !isset($avisoresultado) ) $avisoresultado = '';
	echo '<div>'.$avisoresultado.'</div>';

	// la carga de tablas distintas solo se puede en el controlador, por eso no se maneja aqui:
	echo $presentarpedidos;
	
	echo form_fieldset_close();




  ?>
