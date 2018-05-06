# CODEGINER VNX

...es un derivado de codeigniter el cual se concentra en mejorar los aspectos avanzados, 
con bases de datos de verdad y no la porqueria de cafeterias de mysql...
Para esto tiene algunos añadidos a los drivers pgsql y odbc, asi como ajustes de estetica a los helpers.

...its a codeigniter derived work that focused in improved related avanced aspects, 
with truly powered relational databases and not only the poor mysql ...
For that, have some additions in the pgsql and odbc drivers, as stetics ajust on the helpers.

## FEATURES

* Core: autodetection of localhost security denied and locale settings to GMT if not set datetime zone
* Bootstrap: css autoiniciado en cada tag html sin necesidad de usar clases especificas
* Helpers: el tag de tabla y de select adiciona datatables y inputsearch sin usar jquery
* Libraries: nuevas librerias, CSV importer, Date para multiconversiones y Console para impresiones 
* Forms: los campos inputs autodetectan parametros y autogeneran un id siempre
* Paginacion: se agrega posibilidad de indicar inicio y final sobreescrito a pagina 1 y pagina final
* PGSQL: Soporte postgresql mejorado (en progreso) con integracion CRUD
* Esquemas: Posibilidad de usar esquemas parciales en postgresql, con active recors (solo falla la verificacion si tabla existe)
* Menus: Implementacion de libreria de menu, basada en modelos, en el futuro el menu se carga en un controlador
* Importador: librerias de importacion de hojas de calculo y archivos separados por comas.
* Exportador: opciones avanzadas en la exportacion de archivos CSV
* Filehandler: corregido el renombre de subr archivos, asi como soporte correcto de pdf, csv y ods/odt

# Why so older? Porque tan viejo?

Lamentablemente no todo los paises del mundo tienen el dinero para cambiar todos los dias su software 
el cambio de software no es solo por "seguridad", sino porque el hardware lo obliga.
En muchos lugares se usa versiones viejas de php pero con sus parches de seguridad, 
por ende eso de actualizar es una escusa para impulsar la obsolescencia programada.

Unfortunately not all the countries of the world have the money to change their software every day
The software change is not just for "security", but because the hardware forces it.
In many places old versions of php are used but with their security patches,
So that updating is an excuse to boost scheduled obsolescence.

NOTE: our date time picker are the most older due renders! faster! in older browsers!

# Contact, contributions and thanks

Please if u are a mocosoft dont mess up, and go off!

* Teng-Yong Ng datetimepicker http://www.rainforestnet.com/datetimepicker/datetimepicker-terms.htm commit (50e42e36b57ab0b957df7e42fd7c2cf3568e1b6e)
* Vojtěch Klos (VojtechKlos) helps me implementing https://github.com/Symphony9/pickathing in comboboxes commit (b7c6ae9d631f74516799885a60bf21865407456c)
* Karl (Mobius1) for their great https://github.com/Mobius1/Vanilla-DataTables in tables class (outdated) (26dc09766e5ef2e92cb9aca929a9f729e2509375)
* Spir https://github.com/Spir/vd-dump for var_dump re-implementation commit (daeccba018dd499a7c5a932987e4cc78bee1c212)
* Lonnie Ezell (lonnieezell)for their https://github.com/lonnieezell/codeigniter-forensics to enhanced profiler (ee9eacc3a3868af0ab6e854a5a8e983a66553f70)
* Brad Stinson (bradstinson) for their https://github.com/bradstinson/csv-import for CVS import (pending)

Planned:

* https://github.com/bradstinson/httpful to sustitute the cURL library implementation
* https://github.com/chriskacerguis/codeigniter-restserver REST server for CI 2.X and not 3.X
* https://github.com/chriskacerguis/codeigniter-restclient REST client for CI 2.X and not 3.X

We try to keep (and also track) as many updates and changes as posibles from version 3 of codeigniter, 
