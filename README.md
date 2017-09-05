Artifacts of software needed and hacked
Artefactos de software necesitados y hackeados

* elcatalogojar: visor java de emergencia para sistemas operativos mediocres.. funciones limitadas y simples.

Paginas en proceso y de recomendada lectura:

* [tecnical-odbc-documentation (como usar ODBC en linux y su entorno con sybase)](https://gitlab.com/nerp/nerpextras/wikis/tecnical-odbc-documentation)
* [tecnical-visor-precio-cd202-serial (como usar el visor serial D202 varias marcas)](https://gitlab.com/nerp/nerpextras/wikis/tecnical-visor-precio-cd202-serial)
* [tecnical-vposjaruniversal-megasoft (como usar el vpos universar verifone/ingenico)](https://gitlab.com/nerp/nerpextras/wikis/vposjaruniversal-megasoft-verifone-ingenico)

# formatos json: PEDIR/BAJAR info o realizar llamadas a datos ofrecidos por el sistema:

Para obtener la respuesta json, se realiza un CURL con metodo POST con el usuairo y clave en los datos
si se envia por json siempre en todo momento se enviaria el usuario y clave en md5 (en futuro sha1+md5)

## formato usuarios

json que ofrecera "elsistema" a "elcliente" : 

`{"userintranet":{"intranetclave":"szsd890fbh6s0d89f7g0sdf76g0sd896g08sdf6"}}`

ejemplo:

`{"salazar_leonardo":{"intranetclave":"szdafsdfafbh6s0d89f7g0sdf76g0sd896g08sdf6"}}`

el string "intranetclave" debe decodificarse por md5, es decir este despues de decodificado sera:

intranet=salazar_leonardo,clave=claveleonardo

para manejarlo se hace split con "," y despeus cada uno se hace split con "="

**NOTA** en el futuro, se usara SHA1+MD5

## formato patrimonios que arrenda el usuario

json que ofrecera "elsistema" a "arrendamientos" : 

`{"PAT2107882111":{"debe":22500,"desde":"20170415","des_patrimonio":"Apartamento  77 Sabal","codpatrimonio":"PAT2107882111"},"PAT2107882112":{"debe":23500,"desde":"20170415","des_patrimonio":"Apartamento  77 Sabal","codpatrimonio":"PAT2107882112"}}`


# formatos json: ENVIAR info o realizar SUBIR datos hacia el sistema:

Hay dos formas..  O "SE ENVIA" O "SE PIDE"

* si se envia , se realiza un "PUT" soportadopor el servidor web
* si se pide, se realiza un "POST" por url emulando un formulario

donde siempre en todo momento se enviaria el usuario y clave combinados en md5 (en futuro sha1+md5) por post

La respuesta siempre sera en formato json.

## formato usuarios

si no es exitosa la operacion, se recibe esto:

`{"resultadoerror":{"string que":"string donde"}}`

despues de cada modificacion de dato, se recibira siempre esto en caso exitoso de usuario:

`{"userintranet":{"intranetclave":"szsd890fbh6s0d89f7g0sdf76g0sd896g08sdf6"}}`

ejemplo:

`{"salazar_leonardo":{"intranetclave":"szdafsdfafbh6s0d89f7g0sdf76g0sd896g08sdf6"}}`

el string "intranetclave" debe decodificarse por md5, es decir este despues de decodificado sera:

intranet=salazar_leonardo,clave=claveleonardo

para manejarlo se hace split con "," y despeus cada uno se hace split con "="

**NOTA** en el futuro, se usara SHA1+MD5

## formato patrimonios, catalogo etc

json que ofrecera "elsistema" a "arrendamientos" : 

`{"PAT2107882111":{"debe":22500,"desde":"20170415","des_patrimonio":"Apartamento  77 Sabal","codpatrimonio":"PAT2107882111"},"PAT2107882112":{"debe":23500,"desde":"20170415","des_patrimonio":"Apartamento  77 Sabal","codpatrimonio":"PAT2107882112"}}`

igual para cualqueir otro controlador o dato, igual en los casos de errores
