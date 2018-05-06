# Como comenzar
===========================

Este documento le indicara instrucciones de como comenzar desarrollar y que usar en este proyecto

* [Como comenzar a trabajar](#como-comenzar-a-trabajar)
 * [1 Requisitos para trabajar](#1-requisitos-para-trabajar)
 * [2 Configurar tu entorno](#2-configurar-tu-entorno)
 * [3 clonar las fuentes](#3-clonar-las-fuentes)
 * [4 Cargar en Geany y ver en web](#4-cargar-en-geany-y-ver-en-web)
 * [5 Inicializar la base de datos](#inicializar-la-base-de-datos)
* [Estructura de desarrollo](#estructura-de-desarrollo)
 * [Modelo de datos y base de datos](#modelo-de-datos-y-base-de-datos)
 * [Codigo y fuentes](#codigo-y-fuentes)
 * [Querys SQL](#querys-sql)
 * [codigo PHP](#codigo-php)
 * [Como trabajar git](#como-trabajar-git)
* [Logica aplicacion web](#logica-aplicacion-web)
 * [Inicio sesion y modelo usuario](#inicio-sesion-y-modelo-usuario)
 * [empleo de Grocery Crud y sybase](#empleo-de-grocery-crud-y-sybase)
 * [Envio de correos](#envio-de-correos)
 * [Trabajar local webserver desde home](#trabajar-local-webserver-desde-home)


## Como comenzar a trabajar
---------------------------

Crear un directorio `Devel` en home, cambiarse a este y alli clonar el repo, iniciar y arrancar el editor Geany.

Todo esto se explica en detalle a continuacion por partes


### 1 Requisitos para trabajar

* git (manejador de repositorio y proyecto) `apt-get install git git-core giggle`
* mysql (manejador y servidor DB que hara de pivote) `apt-get install mysql-client mysql-server`
* geany (editor para manejo php asi como ver el preview) `apt-get install geany geany-plugin-webhelper`
* lighttpd/apache2 (webserver localmente para trabajar el webview) `apt-get install lighttpd`
* php5 (interprete) `apt-get install php*-cgi php*-mysql php*-gd php*-mcrypt php*-curl php*-xmlrcp`
* curl (invocar urls) `apt-get install curl`

Se recomienda usar mysql-workbench con `apt-get install mysql-workbench` para carga y trabajo con data sql.

### 2 Configurar tu entorno

configura el usuario git y coloca un enlace en la raiz del webserver a Devel para usar el proyecto:

```
git config --global status.submoduleSummary true
git config --global diff.submodule log
git config --global fetch.recurseSubmodules on-demand
git config --global user.email apellido_nombre@intranet1.net.ve
su
ln -s /home/systemas/Devel /var/www/Devel
ln -s /home/systemas/Devel /var/www/html/Devel
chown -R systemas:www-data /home/systemas/Devel
find /home/systemas/Devel/ -type f -exec chmod 664 {} ";"
find /home/systemas/Devel/ -type d -exec chmod 775 {} ";"
```

**IMPORTANTE** Asumiendo que su usuario es `systemas`, los dos ultimos comandos como root.

**IMPORTANTE** No usar variable `$HOME` ya que al usar su, esta sera igual a "root" y no su usuario.

### 3 clonar las fuentes

Se usa git para tener las fuentes y se arranca el ide geany para condificar, 
como usuario `systemas` clonar las fuentes en Devel de home:

**NOTA** usar ip 10.10.34.20 en vez de 200.82.144.73 si esta en ruices!

``` 
mkdir -p ~/Devel
cd Devel
git clone --recursive http://200.82.144.73/lagranja/elsistema/ekstra/.git
cd ekstra/elalmacenweb
git pull
git submodule init
git submodule update --rebase
git submodule foreach git checkout master
git submodule foreach git pull
```

**IMPORTANTE** Asumiendo que `200.82.144.73` es la ip de ruices publica.


### 4 Cargar en Geany y ver en web

* abrir el geany
    * ir a menu->herramientas->admincomplementos
    * activar webhelper(ayudante web), treebrowser(visor de arbol) y addons(añadidos extras)
    * aceptar y probar el visor web (que se recarga solo) abajo en la ultima pestaña de las de abajo
    * cargar abajo en la ultima pestaña de webpreview la ruta http://127.0.0.1/Devel/ y visitar el proyecto
* en el menu proyectos abrir, cargar el archivo `Devel/ekstra/elalmacenweb/elalmacenweb.geany` y cargara el proyecto
    * en la listado seleccionar el proyecto o el directorio `~/Devel/ekstra/elalmacenweb`
    * instalar `elalmacenweb` sino esta aun instalado, esto es carga la db en 127.0.0.1 y se recarga solo

**NOTA IMPORTANTE** esto es asumiendo que su usuario se llama `systemas` y 
si no es asi debe modificar el proyecto para que cumpla con su ruta:

* con un editor de texto plano abrir el archivo elalmacenweb.geany NO ABRIR CON EL GEANY!!
* en la variable `base_path=` cambiar la ruta de home `systemas` sustituya "systemas" por su usuario linux
* borrar toda la seccion files si existiese y salvar el archivo

5. Inicializar la base de datos

En el directorio [elalmacenwebdb](elalmacenwebdb) esta el archivo `elalmacenwebdb.sql` cargar 
esto en el servidor localhost de la maquina instalado en "localhost" y especificar o 
corregir la conexcion en el archivo `ekstra/elalmacenwebweb/config/database.php` del grupo correspondiente "elalmacenwebdb".

# Estructura de desarrollo
===========================

El sistema central tiene una interfaz web, por ahora construida con `PHP/codeigniter`, en futuro con `GAMBAS`, 
tiene una tabla de usuarios y una de usuario/modulos el usuario solo usa lo que aparece en esta ultima.

El sistema automaticamente genera un menu basado en la cantidad de "controladores" o directorios (cada 
uno de ellos se asume ser un modulo del sistema).

## Modelo de datos y base de datos

El directorio [elalmacenwebdb](elalmacenwebdb) contiene el modelo, imagenes y scripts SQL, se usa una DB central que 
actualiza la tablas de usuarios y modulos, y se conecta a sybase para obtener los datos de reportes.

* base de datos MySQL/MariaDB, Sybase. Se emplea MySQL solo para pintar los reportes en tablas al vuelo.
* modelado de datos en mysqlworkbench formato script STANDARD usuario no especificado
* formato tablas en pares cabecera/detalle como maximo la tabla detalle incluye el nombre cabecera separado por `_`
* formato columnas es `<tip>_<nombre>` donde tipo puede ser cod, mon, ind, des y nobmre autodescriptivo
* llave primaria es `cod_<nombretabla>` en caso detalle la separacion de nombretabla por `_` se omite
* no hay llaves foraneas, integracion de los datos viene data por la aplicacion, puesto se maneja otras db
* no hay llaves foraneas, permitiendo la manipulacion de los datos para modularizacion y flexibilidad
* todos los campos con comentario no mayor a 40 caracteres, sqlite no admite COMMENT y Sybase lo hace por separado.


## Codigo y fuentes

El directorio [elalmacenwebweb](elalmacenwebweb) contiene el codigo fuente del sistema, se trabajara SQL y PHP con 
framework codeigniter2 y se maneja con GIT, abajo se describe cada uno y como comenzar de ultimo.

### Querys SQL

* No usar `TOP X` ni algun otro SQL, si usase, encapsular dentro de procedimientos almacenados (sybase/mssql)
* `COALESCE` = `IFNULL` ya que actua distinto, se debe usar  `COALESCE` que verifica null
* `GROUPO_CONCAT` es solo mysql realiza referencia cruzada, pero no funciona en columnas de igual nombre

### Codigo PHP

Se emplea Codeigniter 2 y no 3, se describe mas abajo como iniciar el codigo, se describe como funciona aqui:

* **elalmacenwebweb/controllers** cada archivo representa una llamada web y determina que se mostrara
* **elalmacenwebweb/views** aqui se puede separar la presentacion de los datos desde el controller
* **elalmacenwebweb/libraries** toma los datos y los amasa, moldea y manipula para usarse al momento o temporal
* **elalmacenwebweb/models** toma los datos y los amasa, modea y prepara para ser presentados o guardados

### Modulos y Menu automatico

Los **Modulos** seran sub directorios dentro del directorio de controladores, cada 
sub directorio sera un modulo del sistema, y dentro cada clase controller sera una llamada web url, 
ademas de los que ya esten en el directorio `elalmacenwebweb/controllers` que tambien 
seran una llamada web url.

El **Menu** sera automaticamente construido a partir de los subdirectorios y controladores, 
hay dos niveles de menu, el menu principal que es todo lo de primer nivel (directorios y los index) 
y el menu de cada modulo, que se construye pasando el nombre del subdirectorio (solo lso controlers).

En el directorio `elalmacenwebweb/controllers`, para todo archivo que tenga en el nombre "index" 
sera incluido en el menu principal, adicional a todo subdirectorio, el resto de archivos, asi 
como los archivos despues de dicho primer nivel no seran incluidos para generar el menu principal.
Para el sub menu, segun el nombre el modulo (subdirectorio) de `elalmacenwebweb/controllers`, 
se buscara todo archivo controller y sera incluido en la generacion de el submenu, y este se 
muestra debajo del menu principal.

## Como trabajar con git

El repositorio principal "elalmacenweb" contine adentro el de codeingiter, de esta forma si se actualiza, 
si tiene contenido nuevo, hay que primero traerlo al principal, 
y despues actualizar la referencia de esta marca, entonces el repositorio principal tendra los cambios marcados.

**POR ENDE**: los commits dentro de un submodulo son independientes del git principal
1. primero antes de acometer cambios revise si hay desde elprincipal con fetch y pull
2. segundo haga commit y push en los submodulos antes de hacer commit y push en el principal
3. despues haga commit en el principal y push hacia el principal, todos estaran al dia

``` bash
git fetch
git pull
git submodule init && git submodule update --rebase
git submodule foreach git checkout master
git submodule foreach git pull
editor archivo.nuevo # (o abres el geany aqui y trabajas)
git add <archivo.nuevo> # agregas este archivo nuevo cambiado en geany al repo para acometer
git commit -a -m 'actualizado el repo adicionado <archivo.nuevo> modificaciones'
git push
```

En la sucesion de comandos se trajo todo trabajo realizado en los submodulos y actualiza "su marca" en el principal, 
despues que tiene todo a lo ultimo se editar un archivo nuevo y se acomete

**NOTA** Geany debe tener los plugins addons y filetree cargados y activados


# Logica aplicacion web
---------------------------

## Inicio sesion y modelo usuario

Este proyecto emplea un esquema de migracion hibrida, se emplea una db base mas no central, donde se hace 
pivote de usuario, acceso y acciones

En cada controlador solo se debe usar el "checku" y este se encargara de sacar o no de sesion 
a el usuario si no ha iniciado sesion, esto es todo, no hay que realizar mayores verificaciones.

En la tabla `esk_usuario` se lista usuario y clave, pero su acceso se define realmente por `esk_usuario_modulo` 
que define a donde puede ir, cada entrada de modulo es un directorio de controlador que puede invocar, 
cada controlador es una presentacion de datos especifica, por ende si no esta listada en la tabla de 
los modulos no puede ser visitada, adicional si no esta en la de relacion de usuario-modulo tampoco.

### Core YA_Controler

Inicializa objetos de verificacion de sesion, modelo y libreria de usuario, y lo hace disponible.
Todos los controladores de lso modulos deben heredar de este, para poder facilmente ovidarse de 
verificaciones de sesion o de usuario.

* checku: revisa la sesion actual, empleando la libreria que a su vez emplea el modelo, si invalido, redirige login.
     * @access	public
     * @return	void

* render: pinta igual que el CI view, solo que este antepone el header y pone despeus el footer
     * @access	public
     * @param	array/string  Si string, el nombre de la vista a cargar, si array cada vista se carga en secuencia
     * @param	array     $data a pasar a las vistas tal comolo hace CI
     * @return	void

Ninguna de las funciones de este retorna valores, porque este controlador altera el flujo segun las credenciales.

### Libreria Login

Se encarga de el transporte de informacion y datos entre la representacion de los datos y el manejo de acceso.
La libreia usa el modelo esk_usuario para verificar las credenciales,y toda operacion de acceso de datos.

* userlogin: validation user credentials, instancia el modelo esk_usuario y verifica las credenciales en db
     * @access	public
     * @param	string    usuaername o ficha
     * @param	string    userclave
     * @return	bool      TRUE o -1 si credenciales validas

* userlogout: destruye la sesion e invalida los datos en la db
     * @access	public
     * @param	string    usuaername o ficha
     * @return	void

* usercheck: check session user, if user are null check if there a currentl loged in user in true
     * @access	public
     * @param	string    usuaername o ficha, si no se provee detecta el actual
     * @return	integer   0/FALSE si el usuario no es ya valido

* userpass actualiza la clave en la tabla particular de esta base de datos. TAMBIEN LA ACTUALIZA OTRAS APPS
    * @access  public
    * @param  string Username
    * @param  string older passowrd
    * @param  string newer password
    * @return integer 0 si no se pudo o usuario invalido

### Modelo esk_usuario

Interactua con al libreria login para abstraer un usuario de la base de datos y maneja la informacion con la libreia.
La libreria usa el modelo para obtener datos y verificarlos asi como cambiarlos, el inicio de sesion es 
mediante la verificacion de los datos usando este modelo.

* getusuario: verifica si el usuario es valido con la clave provista en md5
     * @access	public
     * @param	string  username
     * @param	string  userclave
     * @param	string  credential(md6 de usuario y clave juntos)
     * @return	boolean TRUE si datos son validos

* udpusuario: actualiza data del usuario segiun filtro, si campos desconocidos en filtro falla
     * @access	public
     * @param	array  datauser campos de la tabla con sus valores
     * @param	string  filter  parte "where" del query con los filtros
     * @return	boolean TRUE si actualizacion exitosa

* getuserinfo: retorna informacion de un usuario especifico, solo campos visibles no sensibles
     * @access	public
     * @param	string  username
     * @return	array('cuandos'=>integer,...) arreglo de un arreglo conlos datos del usuario, incluyendo clave


# Modulos

IMPORTANTE: (WIP) la tabla de modulos se actualiza sola en cada request.


### empleo de Grocery Crud y sybase

Grocery Crud solo sirve en mysql, para poder pintar reportes desde otras db, 
se emplea esa db base y tablas dinamicas:

1. se realiza el query que ofrece el reporte, 
2. estos datos se guardan en una tabla creada al vuelo
3. el nombre de la tabla tiene como sufijo el nombre del usuario y un numero aleatorio
4. se indica al GC que pinte el reporte desde esta tabla
5. no se coloca uan isntruccion que borre la tabla al final, esta se coloca al principio

Obviamente esto significa dos cosas:

* bajo rendimiento, pero no importa ya que estos reportes son solo accedidos por pocos.
* deja siempre una o dos tablas de usuario basura creadas..


### envio de correos

Cada modulo al presentar debe poder enviar por correo la descarga de CSV, lo correcto para no preocupar 
por asincronia es:

1. verificar si el host de correo responde
2. preparar el correo
3. enviar el correo

Este ultimo paso no es seguridad que se envio, pero si el primero no e da se debe sacar un mensaje 
diciendo que no hay internet rapido en estos momentos.


### trabajar en home public_html

Trabaja desde su propio home, sin tener que alterar permisos o copiar manualmente a la raiz del htdocs:

```
su
apt-get install mariadb-server mariadb-client mysqlworkbench lighttpd php5-fpm php5-cgi php5-gd php5-mysql geany geany-addons
lighty-enable-mod accesslog cgi debian-doc dir-listing expire fastcgi proxy status userdir usertrack
lighty-disable-mod flv-streaming javagateway no-www proxyjabber rrdtool simple-vhost ssi ssl no-www
service lighttpd restart
exit
mkdir -p ~
ln ~/Devel/elalmacenweb ~/public_html/elalmacenweb
```

**NOTA** esto asume que tiene lighttpd usando public_html como directorio web en el home,en VenenuX es Html
