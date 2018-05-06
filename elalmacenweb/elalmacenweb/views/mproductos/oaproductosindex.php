<?php

/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */


	$camposfiltroH1 = form_hidden('userurl',$this->userurl).PHP_EOL; // $this->input->get_post('userurl') url es una variable URL invocada'.PHP_EOL;
	$camposfiltroH2 = form_hidden('currenturl',$this->currenturl).PHP_EOL; // $currenturl es una variable ACTUAL'.PHP_EOL;

	if( !isset($txt_referencia) ) $txt_referencia = '';
	if( !isset($txt_descripcion_larga) ) $txt_descripcion_larga = '';
	$htmlformaattributosbuscr = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	$htmlformularioproductosbuscarAbre = form_open('mproductos/oaproductos/mostrarproductos', $htmlformaattributosbuscr) . PHP_EOL;
	$camposfiltro1 = form_input('descripcion',$txt_descripcion_larga);
	$camposfiltro2 = form_input('referencia',$txt_referencia);
	$camposfiltroB = form_submit('consultarbusca', '(Re)Consultar', 'class="btn-primary btn"');
	$this->load->library('table');
	$this->table->clear();
	$tmmopen1 = '<table with=80% border="0" cellpadding="1" cellspacing="1" style="border=0px;"  >';
	$tablapl1 = array('table_open'=>$tmmopen1,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
	$this->table->set_template($tablapl1);
	$this->table->set_datatables( FALSE );
	$this->table->add_row('¿Por descripcion?:',$camposfiltro1);
	$this->table->add_row('¿Por referencia? :',$camposfiltro2);
	$this->table->add_row('(puede las dos al mismo tiempo)',$camposfiltroB);
	$productostablafiltros1 = $this->table->generate();
	$htmlformularioproductosbuscarCier = form_close() . PHP_EOL;

	$formproductosbuscar = '';
	$formproductosbuscar .= form_fieldset('Busqueda de productos') . PHP_EOL;
	$formproductosbuscar .= $htmlformularioproductosbuscarAbre;
	$formproductosbuscar .= $productostablafiltros1;
	$formproductosbuscar .= $camposfiltroH1;
	$formproductosbuscar .= $camposfiltroH2;
	$formproductosbuscar .= $htmlformularioproductosbuscarCier;
	$formproductosbuscar .= form_fieldset_close();


	if( !isset($cod_producto) ) $cod_producto = '';
	$htmlformaattributosexis = array('name'=>'formularioexistencia','onSubmit'=>'return validageneric(this);');
	$htmlformularioproductosexisteAbre = form_open('mproductos/oaproductos/existenciaproductos', $htmlformaattributosexis) . PHP_EOL;
	$campocodigo1 = form_input('cod_producto',$cod_producto);
	$campocodigoB = form_submit('consultarexis', '(Re)Consultar', 'class="btn-primary btn"');
	$this->load->library('table');
	$this->table->clear();
	$tmmopen2 = '<table with=80% border="0" cellpadding="1" cellspacing="1" style="border=0px;"  >';
	$tablapl2 = array('table_open'=>$tmmopen2,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
	$this->table->set_template($tablapl2);
	$this->table->set_datatables( FALSE );
	$this->table->add_row('¿Código Producto?:',$campocodigo1);
	$this->table->add_row('.','');
	$this->table->add_row('(sirve tambien desde busquedas)',$campocodigoB);
	$productoetablafiltros2 = $this->table->generate();
	$htmlformularioproductosexisteCier = form_close() . PHP_EOL;

	$formproductosexiste = '';
	$formproductosexiste .= form_fieldset('Existecia de un producto') . PHP_EOL;
	$formproductosexiste .= $htmlformularioproductosexisteAbre;
	$formproductosexiste .= $productoetablafiltros2;
	$formproductosexiste .= $camposfiltroH1;
	$formproductosexiste .= $camposfiltroH2;
	$formproductosexiste .= $htmlformularioproductosexisteCier;
	$formproductosexiste .= form_fieldset_close();




	$this->load->library('table');
	$this->table->clear();
	$tmmopen = '<table with=100% border="0" cellpadding="1" cellspacing="1" style="border=0px;"  >';
	$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
	$this->table->set_template($tablapl);
	$this->table->set_datatables( FALSE );
	$this->table->add_row($formproductosbuscar,$formproductosexiste);
	echo $this->table->generate();

  ?>
 
