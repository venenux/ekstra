
README base de datos "elyanero"
===========================

El proyecto usa varias bases de datos, pero se conecta a tres DBMS unicamente:

* MYSQL/MARIADB: se conecta a la `10.10.34.23` empleando las db `elyanero` y `sysdbadminis`
* SYBASE: se conecta a la `37.10.252.253` empleando las db `OP_001035` principalmente y otras.
* MSSQLSERVER: se conecta a la `170.10.1.100` empleando distintas db aun no claramente definidas.

El proyecto usa dos tipos de acceso, **MySQL** en el php o en gambas.

En el directorio [elyanerodb](elyanerodb) esta el archivo `elyanerodb.sql` cargar 
esto en el servidor localhost de la maquina instalado en "localhost" y especificar o 
corregir la conexcion en el archivo `elyaneroweb/config/database.php` del grupo correspondiente "elyanerodb".
En el mismo archivo esta ya el string DNS especificado de OASIS, certificar y corregir.

A continuacion se especifica cada uno de estos componentes.

### Configuracion MySQL

Se usa MariaDB, el usuario es `sysdbuser`, y no puede crear esquemas, por ende deben existir siempre, 
al recrear o trabajar en desarrollo, la clave esta definica en el passmanager o en el proyecto.

``` sql
CREATE USER 'sysdbuser'@'%' IDENTIFIED BY '***';
GRANT USAGE ON * . * TO 'sysdbuser'@'%' IDENTIFIED BY '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
GRANT ALL PRIVILEGES ON `sysdbuser` . * TO 'sysdbuser'@'%';
```

Estas sentencias deben estar pre-ejecutadas como administrador en 
el sistema de base de datos MySQL/MariaDB:

``` sql
REVOKE ALL PRIVILEGES ON elyanerodb.* FROM 'sysdbuser'@'%';
GRANT ALL PRIVILEGES ON elyanerodb.* TO 'sysdbuser'@'%' WITH GRANT OPTION ;
REVOKE ALL PRIVILEGES ON sdbprestamos.* FROM 'sysdbuser'@'%';
GRANT ALL PRIVILEGES ON sdbprestamos.* TO 'sysdbuser'@'%' WITH GRANT OPTION ;
```

### Configuracion

Para configurar el acceso ODBC se requeire previa preparacion, leer [odbc-README.md](odbc-README.md) 
en donde se cubre los aspectos importantes para conectarse usando ODBC y DSN definidos.

### Diseño de la DB

El diseño de la db contempla solo dos DB, `elyanero` y `sprestamo` 

* **elyanero** su diseño no es amplio solo accesos de usuarios y de alcances, ya que este proyecto 
lo que realiza es consultas y reportes a otras bases de datos. Se puede visualizar en 
el archivo [elyanerodb.mwb](elyanerodb.mwb) de `Mysql-workbench` el script SQL generado 
esta en el archivo `elyanerodb.sql`.

* **sdbprestamo* en migracion su esquema esta en el archivo [sdbprestamo.mwb](sdbprestamo) 
el nombre original en el server 253 es `prestamo` las fuentes eran ASP/PHP se porto y 
el script no es generado sino sincronizado por estar usandose: `sdbprestamo.sql`

Toda nueva tabla o implementacion debe estar en la db `elyanero`, y su nombre debe ser siempre
con prefijo `yan_` seguido de nombre en formato `<modulo><tabla>` donde modulo es el nombre del 
directorio controlador y tabla el nombre de la tabla que se usara.

**Todo cambio debe reflejarse primero en el diseño y despues de alli generarse los scripts sql.**

![elyanerodb.png](elyanerodb.png)

![sdbprestamo.png](sdbprestamo.png)

# Diccionario datos aproximado

WIP
