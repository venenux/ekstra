
# Gencontroler y Menu

Este documento toma en cuenta lo ya descrito en [desarrollo-modulos-controlador.md](desarrollo-modulos-controlador.md)
por lo que se asume las siguientes condiciones:

* todas las funcionalidades de un mismo proceso son un modulo
* un modulo es un subdirectorio del directorio de controladores `controllers`
* todos los controladores de funcionalidades de procesos estan en un subdirectorio
* los unicos controladores fuera de subdirectorios son los index de cada modulo
* un index de modulo es un controlador que redirecciona al otro del modulo
* todos los controladores index, tienen la palabra `index` en su nombre
* todos los directorios de controladores comienzan por la letra `m`
* todos lso controladores extienden de la clase `YA_Controller`

### Getcontroller

* `YA_Controller`::`getcontrollers`

* getcontrollers: obtiene nombre de controladores o nombre de directorios de controladores
	 * @access public
	 * @param string $moduledir nombre del directorio de controllers especifico sino directorios de modulos
	 * @return array con los nombres de archivos de controladores o los directorios si no se especifica modulo dir

Esta funcion/metodo devuelve un arreglo con los indices de todos los archivos
que sean controllers, dentro de el subdiretorio señalado popr el parametro, 
si no se pasa parametro o este es invalido devuelve los nombres de subdirectorios 
y los nombres de los index controllers que esten en el mismo mas no los de adentro.

### Uso de gencontroller

Su primera utilidad es la generacion automatica de los menus principales y submenus.
La documentacion del menu esta mas adelante.

Esta funcion es importante emplearla en la permisologia.. 
teniendo el nombre de la clase se puede comparar con los valores del arreglo, 
y despues se comparar con valores guardados para un usuario.. 
si el usuario tiene el nombre de dicha clase asociado y esta es 
igual al nombre o aparece en el arreglo y a su vez es la clase/controllador llamado, 
entonces se le peermite ejecutarlo..

## Genmenu

* `YA_Controller`::`genmenu`

* genmenu: genera un menu de enlaces plano usando `getcontrollers` segun los nombres de controladores del directorio
	 * @param string $moduledir nombre del directorio de controllers especifico sino directorios de modulos
	 * @return string html table con los nombres de archivos de controladores o los directorios si no se especifica modulo dir

Esta funcion/metodo construye una tabla html con enlaces sin bordes, basado en 
los directorios dentro del directorio `controllers` y en los nombres de los mismos, 
tambien construye enlaces si dentro de cada subdirectorio hay al menos un index..

Segun el parametro `$moduledir` el construira un menu principal o secundario.

1. Menu principal
  * parametro `$moduledir` vacio
  * la vista `views/header.php` detecta la variable y la imprime en pantalla.
2. Menu modulos
  * parametro `$moduledir` un nombre de directorio
  * se le pasa a `$data['submenun']` ejemplo con `$data['submenu'] = $this->genmenu('mproductos');`
  * la vista `views/header.php` detecta la variable y la imprime en pantalla.
