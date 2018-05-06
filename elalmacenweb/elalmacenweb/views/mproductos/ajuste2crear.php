<?php
/* productos rechazados index*/
/** vista que se pinta para enviar los parametros de busqueda por formulario via GET/POST a oaproductos */

	echo form_fieldset('PRocesado ajuste 23'.$cod_oajuste) . PHP_EOL;

	if( !isset($avisoresultado) ) $avisoresultado = 'AJUSTE 23 PROCESADO, pendeinte de aprobacion y ejecucion';
	echo '<div>'.$avisoresultado.'</div>';

	$htmlformaattributos = array('name'=>'formulariofiltros','onSubmit'=>'return validageneric(this);');
	echo form_open_multipart('mproductos/oajusteforma/ajuste1crear', $htmlformaattributos) . PHP_EOL;

		$this->load->library('table');

		$tmmopen = '<table with=100% border="0" cellpadding="0" cellspacing="0" style="border=0px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->table->clear();
		$this->table->set_template($tablapl);
		$this->table->set_heading(FALSE);
		$this->table->set_datatables(FALSE);
		$this->table->add_row('Depar:',$cod_depa);
		$this->table->add_row('Tipo:',$cod_tipo);
		$this->table->add_row('Signo:',$cod_signo);
		$this->table->add_row('Causa:',$cod_causa);
		$this->table->add_row('Sucursal:','('.$cod_sucursal.')');
		$this->table->add_row('Correlativo:',$cod_correlativo);
		echo $this->table->generate();

		$this->load->library('table');
		$this->table->clear();
		$tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
		$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
		$this->table->set_template($tablapl);
		$this->table->set_heading('Codigo','descripcion','ajuste anterior','ajuste nuevo');
		$this->table->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "fixedColumns" => "false" ) );

		if(is_array($list_codigosajustes) )
		{
			echo form_fieldset('Productos ajustar:') . PHP_EOL;
				foreach($list_codigosajustes as $aca)
					$this->table->add_row($aca['cod_producto'], $aca['des_producto'], $aca['can_valor_ant'], $aca['can_valor_nue']);
			echo $this->table->generate();
			echo form_fieldset_close() . PHP_EOL;
		}

		echo form_submit('but_proceso1', 'A_Procesar_0', 'class="btn-primary btn"');
		echo form_submit('but_proceso2', 'A_Procesar_1', 'class="btn-primary btn"');

	echo form_close() . PHP_EOL;

	echo form_fieldset_close();




  ?>
