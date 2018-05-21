	<?php

	// si variables vacias llenar con datos mientras tanto
	if( !isset($accionejecutada) ) $accionejecutada = 'cargardatos';
	if( !isset($list_codigos) ) $list_codigos = '';
	if( !isset($list_cantida) ) $list_cantida = '';
	if( !isset($entidad_origen) ) $entidad_origen = '';
	if( !isset($entidad_destino) ) $entidad_destino = '';
	if( !isset($pedido_digital_archivo) ) $pedido_digital_archivo = '';
	if( !isset($list_entidades_origen) ) $list_entidades_origen = array('codigomsc' => 'narnai 1','codigomsc2' => 'narnia 2');
	if( !isset($list_entidades_destino) ) $list_entidades_destino = array('codigomsc' => 'narnia 3','codigomsc2' => 'narnia 4');

		echo form_fieldset('Ingrese los datos por favor',array('class'=>'container_blue containerin ')) . PHP_EOL;

			$htmlformaattributos = array('name'=>'formularioordendespachogenerar','class'=>'formularios','onSubmit'=>'return validageneric(this);');
			echo form_open_multipart('malmacen/pedido/pedido1digital/', $htmlformaattributos) . PHP_EOL;

			$tmmopen = '<table with=100% border="0" cellpadding="0" cellspacing="0" style="border=0px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->load->library('table');
			$this->table->clear();
			$this->table->set_template($tablapl);
			$this->table->set_heading(FALSE);
			$this->table->set_datatables(FALSE);

			$inputOrigen = form_dropdown('entidad_origen', $list_entidades_origen, $entidad_origen);
			$this->table->add_row('Origen (automatico sera su sucursal primaria):',$inputOrigen);

			$inputDestino = form_multiselect('entidad_destino[]', $list_entidades_destino, $entidad_destino);
			$this->table->add_row('Destino (las sucursales a donde enviara los items:',$inputDestino);


			$this->table->add_row('Codigos de items o productos: ','Cantidad solicitada: ');
			$inputCodigos = form_textarea('list_codigos',$list_codigos);
			$inputAjustes = form_textarea('list_cantida',$list_cantida);
			$this->table->add_row($inputCodigos,$inputAjustes);

			$inputArchivo = form_upload('pedido_digital_archivo',$pedido_digital_archivo).PHP_EOL;
			$this->table->add_row('Usar archivo digital? (list_codigos|list_cantidad): ',$inputArchivo . '( Cargado: archivo '.$pedido_digital_archivo.')');

			$buttonPedido0 = form_submit('but_proceso1', 'A_Procesar_Paso_1', 'class="btn-primary btn"');
			$buttonPedido1 = form_submit('but_proceso2', 'A_Procesar_Paso_2', 'class="btn-primary btn"');
			$this->table->add_row($buttonPedido0,$buttonPedido1);

			echo $this->table->generate();

			echo form_close() . PHP_EOL;

		echo form_fieldset_close() . PHP_EOL;
			echo PHP_EOL;

	?>
