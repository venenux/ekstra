<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'elyanerodb';
$active_record = FALSE;

/* db central de la app la usa para determinar quien entra y sale y ve que cosa*/
$db['elyanerodb']['hostname'] = 'localhost';
$db['elyanerodb']['username'] = 'root';
$db['elyanerodb']['password'] = 'root.1';
$db['elyanerodb']['database'] = 'elyanerodb'; // usar el script que esta en directorio elyanerodb
$db['elyanerodb']['dbdriver'] = 'mysql';
$db['elyanerodb']['dbprefix'] = ''; /*blanks means use public , catalogo not use that due xtreme security */
$db['elyanerodb']['pconnect'] = TRUE;
$db['elyanerodb']['db_debug'] = TRUE;
$db['elyanerodb']['cache_on'] = FALSE;
$db['elyanerodb']['cachedir'] = '';
$db['elyanerodb']['char_set'] = 'utf8';
$db['elyanerodb']['dbcollat'] = 'utf8_general_ci';
$db['elyanerodb']['swap_pre'] = '';
$db['elyanerodb']['stricton'] = FALSE;

/*db de oasis para reportes y manejo de pos a migrar en futuro*/
$db['oasisdb']['hostname'] = 'DRIVER=FreeTDS;SERVER=37.10.252.253;UID=dba;PWD=sql;DATABASE=OP_001037;TDS_Version=5.0;Port=2638;';
$db['oasisdb']['username'] = 'dba';
$db['oasisdb']['password'] = 'sql';
$db['oasisdb']['database'] = 'OP_001037';
$db['oasisdb']['dbdriver'] = 'odbc';
$db['oasisdb']['dbprefix'] = '';
$db['oasisdb']['pconnect'] = FALSE;
$db['oasisdb']['db_debug'] = TRUE;
$db['oasisdb']['cache_on'] = FALSE;
$db['oasisdb']['cachedir'] = '';
$db['oasisdb']['char_set'] = 'utf8';
$db['oasisdb']['dbcollat'] = '';
$db['oasisdb']['swap_pre'] = '';
$db['oasisdb']['stricton'] = FALSE;

/*db de nomina para ver cuadno se desabilita un empelado/usuario de la db central */
$db['saintdb']['hostname'] = '37.10.254.100';
$db['saintdb']['username'] = 'sa';
$db['saintdb']['password'] = 'sa';
$db['saintdb']['database'] = 'XX99';
$db['saintdb']['dbdriver'] = 'odbc';
$db['saintdb']['dbprefix'] = ''; /*blanks means use public , catalogo not use that due xtreme security */
$db['saintdb']['pconnect'] = FALSE;
$db['saintdb']['db_debug'] = TRUE;
$db['saintdb']['cache_on'] = FALSE;
$db['saintdb']['cachedir'] = '';
$db['saintdb']['char_set'] = 'utf8';
$db['saintdb']['dbcollat'] = 'utf8_general_ci';
$db['saintdb']['swap_pre'] = '';
$db['saintdb']['stricton'] = FALSE;

/* db prestamos migrada */
$db['sdbprestamos']['hostname'] = '10.10.34.23';
$db['sdbprestamos']['username'] = 'sysdbuser';
$db['sdbprestamos']['password'] = 'sysdbuser.1';
$db['sdbprestamos']['database'] = 'sdbprestamos';
$db['sdbprestamos']['dbdriver'] = 'mysql';
$db['sdbprestamos']['dbprefix'] = ''; /*blanks means use public , catalogo not use that due xtreme security */
$db['sdbprestamos']['pconnect'] = TRUE;
$db['sdbprestamos']['db_debug'] = TRUE;
$db['sdbprestamos']['cache_on'] = FALSE;
$db['sdbprestamos']['cachedir'] = '';
$db['sdbprestamos']['char_set'] = 'utf8';
$db['sdbprestamos']['dbcollat'] = 'utf8_general_ci';
$db['sdbprestamos']['swap_pre'] = '';
$db['sdbprestamos']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */
