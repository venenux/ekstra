<?php

/** parte de la vista que muestra lso resultados si alguno existe, pinta la tabla de resultados iterando en el arreglo de lso resultados */


		if( $productos_query === FALSE )
			$infodata = "No se pudo consultar OP, problemas con la conexcion intentar mas tarde";
		else if( $productos_query === NULL )
			$infodata = "No se pudo obtener resultados con estos datos, intente otros filtros";
		else if( !is_array($productos_query) )
			$infodata = "No hay resultados con esos parametros, intente otros!";
		else if( count($productos_query) < 1 )
			$infodata = "No hay resultados con esos parametros, o estos parametros pueden que prpicien error de busqueda, intente otros!";
		else if( $productos_query[0]['resultado'] > 40000 )
			$infodata = 'La busqueda es muy ambigua, los filtros deben ser mas especificos o palabra un poco mas larga';
		else
		{
			$infoheader = 'Se encontraron unos '.$productos_query[0]['resultado'].' resultados';
			if($productos_query[0]['resultado'] > 30000)
				$infoheader = 'La busqueda es muy ambigua, son mas de 30000 resultados, <hr>los filtros deben ser mas especificos o palabra un poco mas larga<hr>';
			$row = 0;
			foreach($productos_query as $rowproducto)
			{
				$rowproducto = array_slice($rowproducto, 1, 4);
				$rowproducto['cod_producto'] = anchor('mproductos/productomanager/existenciaproductos/'.$rowproducto['cod_interno'],$rowproducto['cod_interno']);
				$productos_query[$row] = $rowproducto;
				$row++;
			}
			$this->load->library('table');
			$this->table->clear();
			$tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
			$tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
			$this->table->set_template($tablapl);
			$this->table->set_heading(array_keys($productos_query[0]));
			$this->table->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "fixedColumns" => "false" ) );
			foreach($productos_query as $rowproducto)
				$this->table->add_row(array_values($rowproducto));
			$infodata = $infoheader . br(). $this->table->generate();
		}

	echo form_fieldset('Resultados:') . PHP_EOL;
	echo $infodata . PHP_EOL;
	echo form_fieldset_close() . PHP_EOL;

	$htmlformaattributos = array('name'=>'formulariodescargacsv','onSubmit'=>'return validageneric(this);');
	echo form_open('mproductos/productomanager/descargar_en_csv', $htmlformaattributos) . PHP_EOL;
	echo form_submit('download_csv', 'Descargar en CSV', 'class="btn-primary btn"');
	echo form_hidden('userurl',$this->userurl).PHP_EOL; // $this->input->get_post('userurl') url es una variable URL invocada'.PHP_EOL;
	echo form_hidden('currenturl',$this->currenturl).PHP_EOL; // $currenturl es una variable ACTUAL'.PHP_EOL;
	echo form_close() . PHP_EOL;
	
  ?>

