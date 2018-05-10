
# Modelo de desarrollo MVC

Se emplea Codeigniter 2 y no 3, se describe mas abajo como iniciar el codigo, se describe como funciona aqui:

* **elyaneroweb/controllers** cada archivo representa una llamada web y determina que se mostrara
* **elyaneroweb/views** aqui se puede separar la presentacion de los datos desde el controller
* **elyaneroweb/libraries** toma los datos y los amasa, moldea y manipula para usarse al momento o temporal
* **elyaneroweb/models** toma los datos y los amasa, modea y prepara para ser presentados o guardados

## Implementacion de modulos para cada migracion

Todos los controladores debe extender de `YA_Controller` adicional 
cada metodo implementar la revision de sesion `checku()` que verifica el usuario automatico.

Se tratara de proveer todo query en modelos separados por controlador o funcion, 
En estos casos para los SQL complejos se debe hacer en un modelo separado en el directorio 
de el modulo, es decri dentro de elyaneroweb/model debera existir undirectorio del modulo.

## Ejemplo de un controlador de modulo

Ejemplo, para un nuevo modulo "mkardex" se crea un nuevo controlador "oakardex" asi:

1. nuevo directorio  en `elyaneroweb/controllers/` llamado `mkardex`
2. nuevo el archvo en `elyaneroweb/controllers/mkardex` llamado `oakardex.php`
3. nuevo directorio para las vistas en `elyaneroweb/views/` llamado `mkardex`
4. nuevo el archvo en `elyaneroweb/views/mkardex` llamado `oakardexindex.php`
5. el resto de los archivos a crear bajo o para el modulo deben estar dentro de esos directorios
6. el resto de los nombres es libre pero deben estar dentro de esos directorios
7. El codigo fuente de todos los controladores debe extender de `YA_Controller` siempre
8. cada metodo implementar la revision de sesion `checku()` que verifica el usuario automatico.
9. la carga de vista es usando el metodo `render` en vez de `load->view` con directorio incluido

ejemplo de parte del codigo de controlador para mkardex/oakardex.php :

``` php
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oakardex extends YA_Controller 
{
    function __construct()
    {
	parent::__construct();
    }

    public function index()
    {
	$data = array();
        $this->checku();
	$this->render('mkardex/oakardexindex',$data);
    }
<?
```

## Correcta implementacion de Modelo en controlador, y magic tables

Este es un rapido ejemplo de como se usa el modelo en el controlador, 
y en la cual se emplea `vanilla-datatables` el cual soporta gran cantidad de datos:

``` php

    //se configura filtro en un arreglo, la cantidad es opcional, puede pasarse nulo
    $arregloconcampos = array();
    // anadimos un filtrado por descripcion
    $arregloconcampos['txt_descripcion_larga'] = 'pantalon';

    $this->load->model('oaproductosmodel');
    $arregloproductos = $this->oaproductosmodel->get_productos($arregloconcampos);
    if( !is_array($arregloproductos) )
        return 'sin informacion';
    $this->load->library('table');
    $this->table->clear();
    $tmmopen = '<table with=100% border="1" cellpadding="3" cellspacing="3" style="border=1px;"  >';
    $tablapl = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>'<td>','cell_end'=>'</td>','table_close'=>'</table>');
        $this->table->set_template($tablapl);
        $this->table->set_heading(array_keys($arregloproductos[0]));
        $this->set_datatables();
    foreach($arregloproductos as $rowproducto)
        $this->table->add_row(array_values($rowproducto));
    $infodata = $this->CI->table->generate();
```

Es importante notar que `$this->set_datatables();` es quien hara el resto de la magia 
del lado del navegador web, porque establece un minibuscador, autopaginacion y ordenamiento 
automatico por columnas, liberando de esto a el servidor.

## Controladores de reportes y exportar a CSV

Todo reporte debe soportar hoja de calculo openoffice, es parte de la migracion

**OJO: no se puede usar un objeto distinto de "$this-db"**

este codigo permite realizar ello a partir de un SQL:

``` php
/*
 * ddo se usa una db alterna y no el objeto db de CI no sirve exportar
 * el codigo unico correcto que que odbc se carge en al db objeto por defecto
 * aqui el codigo por separado de como se expoeta CSV
 */
	        $this->load->dbutil();
	        $this->load->helper('file');
	        $this->load->helper('download');
	        $delimiter = "\t";
	        $newline = "\n";
	        $filename = "filename_you_wish.odt";
	        $query = "SELECT * FROM table_name WHERE 1";
	        $result = $this->db->query($query);
	        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
	        force_download($filename, $data);

```
