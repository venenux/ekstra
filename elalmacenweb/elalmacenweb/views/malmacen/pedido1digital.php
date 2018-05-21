	<?php

	// si variables vacias llenar con datos mientras tanto

		echo form_fieldset('Datos a procesar',array('class'=>'container_blue containerin ')) . PHP_EOL;

			$tmmopen = '<table with=100% border="1" cellpadding="0" cellspacing="0" style="border=0px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->load->library('table');
			$this->table->clear();
			$this->table->set_template($tablapl);
			$this->table->set_heading(FALSE);
			$this->table->set_datatables(TRUE);
			$arraycodigos = explode(PHP_EOL,$list_codigos);
			$arraycantida = explode(PHP_EOL,$list_cantida);
			if( count($arraycodigos) != count($arraycantida) )
			{
				$this->table->add_row('Los datos se corrompieron procese bien el archivo/listado');
			}
			else
			{
				$this->table->add_row('Codigos procesar','Producto','Cantidad disponible','Cantidad procesar');
				foreach($arraycodigos as $indic => $codigop)
				{
					$this->table->add_row($codigop,'descripcion -- ','X',$arraycantida[$indic]);
				}
			}
			echo $this->table->generate();

		echo form_fieldset_close() . PHP_EOL;

	?>
