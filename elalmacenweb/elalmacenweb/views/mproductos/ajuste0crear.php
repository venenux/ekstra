<?php
/* productos rechazados index*/
/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */
 
    
	echo form_fieldset('Crear un ajuste 23') . PHP_EOL;

	if( !isset($avisoresultado) ) $avisoresultado = 'Debe ingresar CON CUIDADO LOS DATOS';
	echo '<div>'.$avisoresultado.'</div>';

	$htmlformaattributos = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	echo form_open_multipart('mproductos/oajusteforma/ajuste1crear', $htmlformaattributos) . PHP_EOL;

	if( !isset($ultimocorrelativo) ) $ultimocorrelativo = 'sin info!!';
	if( !isset($cod_correlativo) ) $cod_correlativo = 'invalid!';
	if( !isset($cod_depa_list) ) $cod_depa_list = array();
	if( !isset($cod_tipo_list) ) $cod_tipo_list = array();
	if( !isset($cod_signo_list) ) $cod_signo_list = array();
	if( !isset($list_codigos) ) $list_codigos = '';
	if( !isset($list_ajustar) ) $list_ajustar = '';

		$inputDepar = form_dropdown('cod_depa',$cod_depa_list);
		$inputTipo  = form_dropdown('cod_tipo',$cod_tipo_list);
		$inputSigno = form_dropdown('cod_signo',$cod_signo_list);
		$inputCorrelativo = form_input('cod_correlativo',$cod_correlativo,'readonly').'(ultimo:'.$ultimocorrelativo.')';

		if( !isset($cod_causa) ) $cod_causa = '';
		$list_cod_causas = array();
		foreach($arreglocausas as $elc)
			$list_cod_causas[$elc['cod_causa']] = $elc['des_causa'];
		$inputCausa = form_dropdown('cod_causa',$list_cod_causas,$cod_causa);

		if( !isset($cod_tienda) ) $cod_tienda = '';
		$list_cod_tienda = array();
		foreach($arreglotiendas as $elm)
			$list_cod_tienda[$elm['cod_msc']] = $elm['nom_sucursal'] . ' - ' . $elm['cod_sucursal'];
		$inputSucursal = form_dropdown('cod_tienda',$list_cod_tienda,$cod_tienda);

		$this->load->library('table');

		$tmmopen = '<table with=100% border="0" cellpadding="0" cellspacing="0" style="border=0px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->table->clear();
		$this->table->set_template($tablapl);
		$this->table->set_heading(FALSE);
		$this->table->set_datatables(FALSE);
		$this->table->add_row('Depar:',$inputDepar);
		$this->table->add_row('Tipo:',$inputTipo);
		$this->table->add_row('Signo:',$inputSigno);
		$this->table->add_row('Causa:',$inputCausa);
		$this->table->add_row('Sucursal:',$inputSucursal);
		$this->table->add_row('Correlativo:',$inputCorrelativo);
		echo $this->table->generate();

		$inputCodigos = form_textarea('list_codigos',$list_codigos).br();
		$inputAjustes = form_textarea('list_ajustar',$list_ajustar).br();

		$tmmopen = '<table with=100% border="0" cellpadding="0" cellspacing="0" style="border=0px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->table->clear();
		$this->table->set_template($tablapl);
		$this->table->set_heading('Codigos','Ajustes');
		$this->table->set_datatables(FALSE);
		$this->table->add_row($inputCodigos,$inputAjustes);
		echo $this->table->generate();

		echo form_submit('but_proceso1', 'A_Procesar_1', 'class="btn-primary btn"');

	echo form_close() . PHP_EOL;

	echo form_fieldset_close();


  ?>
