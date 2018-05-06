<?php

/** parte de la vista que muestra lso resultados si alguno existe, pinta la tabla de resultados iterando en el arreglo de lso resultados */
	
			$this->load->library('table');
			$this->table->clear();
			$tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->table->set_template($tablapl);
			$this->table->set_heading(array_keys($productos_rechazados[0]));
			$this->table->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "fixedColumns" => "false" ) );
			foreach($productos_rechazados  as $rowproducto)
				$this->table->add_row(array_values($rowproducto));
			$infodata =  $this->table->generate();
		

	echo form_fieldset('Productos Rechazados: Resultados') . PHP_EOL;
	echo $infodata . PHP_EOL;
	echo form_fieldset_close() . PHP_EOL;
	
  ?>

