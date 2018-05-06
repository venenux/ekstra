	<h1>Welcome/Bienvenido a VNX Codeigniter</h1>
	<p >Ejemplo de tabla, con datatables y campos input con selects:</p>
	<?php
		echo br().PHP_EOL;  // genera neuva linea en codigo html al navegador
		echo form_fieldset('titulo marco',array('class'=>'containerin ') );
		$this->table->clear();
		// can be: $this->table->set_datatables();  // default values if not set any as array: array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "100", "perPageSelect"=>"false", "fixedColumns" = "false" );
		$this->table->set_datatables( array("sortable" => "true", "searchable" => "true", "fixedHeight" => "true", "perPage" => "10", "fixedColumns" => "false" ) );
			$this->table->set_heading('col1: ','col2');
			$this->table->add_row('celda y alla campo select autogenerado:', form_dropdown('cod_subcategoria', array('1', '2', 'otr'),null,'id="list_subcategoria"'));
			$this->table->add_row('celda tabla2: ','otra celsa22');
			$this->table->add_row('celda tabla3: ','otra celsa23');
			$this->table->add_row('celda tabla4: ','otra celsa24');
			$this->table->add_row('celda tabla2: ','otra celsa22');
			$this->table->add_row('celda tabla3: ','otra celsa23');
			$this->table->add_row('celda tabla4: ','otra celsa24');
			$this->table->add_row('celda tabla2: ','otra celsa22');
			$this->table->add_row('celda tabla3: ','otra celsa23');
			$this->table->add_row('celda tabla4: ','otra celsa24');
			$this->table->add_row('celda tabla2: ','otra celsa22');
			$this->table->add_row('celda tabla3: ','otra celsa23');
			$this->table->add_row('celda tabla4: ','otra celsa24');
			$this->table->add_row('celda tabla2: ','otra celsa22');
			$this->table->add_row('celda tabla3: ','otra celsa23');
			$this->table->add_row('celda tabla4: ','otra celsa24');
		echo $this->table->generate();
		echo form_hidden('campooculto','valoroculto').br().PHP_EOL;
		echo form_submit('nombreboton', 'boton enviar', 'class="btn-primary btn"');
		echo form_fieldset_close() . PHP_EOL;
	?>
