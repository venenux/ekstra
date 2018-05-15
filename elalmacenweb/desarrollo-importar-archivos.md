Primero hay que cargar la libreria 
pre-incluida en el fashioned framework:

`$this->load->library('csvimport')`

Y despues se importa el archivo (ojo este debe claro cargarse al server)

`$this->csvimport->get_array('archivo.csv');`

Claro esta que hay que usar rutas absolutas por ejemplo esto es lo correcto:

```
$dircargabase = 'archivoscargas/';		// ruta respecto el framework
if ( ! is_dir($dircargabase) )
{
	if ( is_file($dircargabase) )		// si no es dir, borro archivo
		unlink($dircargabase);
	mkdir($dircargabase, 0775, true);	// creo el dir
	chmod($dircargabase,0775);			// cambio permisos
}
$this->csvimport->get_array($dircargabase.'/archivo.csv'); // cargo el archivo
```

OJO el primer rom es los nombres de columnas, sino se les asume asi:

`$this->csvimport->get_array('archivo.csv',array('id', 'name', 'company', 'phone'));`

Si el numero de columnas no es igual, esto causara excepcion.

Si se carga desde un sistema que no es linux explota!
