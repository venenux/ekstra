<?php

	// extraer detalles para la cabecera
	if( !isset($modeloresultados1) ) $modeloresultados1 = 0;
	if($modeloresultados1 > 0)
	{
		$pro_descripcion = $productosarreglo[0]['txt_descripcion_larga'];
		$pro_referencia = ' Referencia: '.$productosarreglo[0]['txt_referencia'];
		$clase = ' Division: '.$productosarreglo[0]['clase'];
		$familia = ' Familia: '.$productosarreglo[0]['familia'];
		$departamento = ' Departamento: '.$productosarreglo[0]['dpto'];
		$cod_asociado = ' Asociado: '.$productosarreglo[0]['proveedores'];
	}

	// pintar los datos
	echo form_fieldset('Resultados:') . PHP_EOL;

	if($modeloresultados1 > 0)
	{

		// alterar para mostrar solo los tres primeras columnas
		foreach($productosarreglo as $rowproducto => $arrayrow)
		{
			$arrayrow = array_slice($arrayrow, 0, 3);
			$productosarreglo[$rowproducto] = $arrayrow;
		}

		$this->load->library('table');
			$this->table->clear();
			$tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->table->set_template($tablapl);
			$this->table->set_heading(FALSE);
			$this->table->set_datatables(FALSE);
			$this->table->add_row('Descripcion:',$pro_descripcion,$pro_referencia,$clase,$departamento,$familia,$cod_asociado);
			$productoexistenciadetalle = $this->table->generate();
		echo $productoexistenciadetalle . PHP_EOL;

		$this->load->library('table');
			$this->table->clear();
			$tmmopen = '<table with=50% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->table->set_template($tablapl);
			$this->table->set_heading(array_keys($productosarreglo[0]));
			$this->table->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "false", "perPage" => "100", "fixedColumns" => "true" ) );
			foreach($productosarreglo as $rowproducto)
				$this->table->add_row(array_values($rowproducto));
			$productostabla = $this->table->generate();
		echo $productostabla . PHP_EOL;
	}
	else
		echo "Sin detalles mayores.. NO SE ENCONTRO PRODUCTO CON DICHO CODIGO O FUE ELIMINADO EN EL MOMENTO";
	echo form_fieldset_close() . PHP_EOL;
  ?>
  
