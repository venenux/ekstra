EL VPS es un OS completo con recursos propios por lo general virtualizado

EL hosting es un espacio compartido donde solo se accede a la tecnologia por uso, generalmetne apache y php con db.

1. analisis de hosts
2. analisis de VPS
3. conclusiones

# 1. Analisis de VPS y HOSTS

## 1.1 x2hosting.ga

Sistema opensource de servicio de hosting basico, provee todolo necesario, no muy integrado felizmente pero perfectamente funcionando.

* SQL o mysql es Percona Server, version super avanzada de mysql
* webserver es  Apache/2.2.15 (CentOS)
* El cliente conexcion mysql es viejo, no es el mismo del percona
* PHP extension: mysqli 

Asemeja un sistema montado por "nuevos", centrado mas en limitar y promocionar cuentas premiun.
**Apmplio repertorio de autoinstalacion** inclusive versiones distintas de algunas de las autoinstalaciones.

| item         | datos         |  observaciones |
|--------------|---------------|----------------|
| webserver    | apache 2.2    |  lento pero muy conocido |
| base de datos| percona 5.6   | **2 db's**, es version mejorada de mysql |
| php          | 5.4,5.6,7.0   | no muchas opciones,otros ofrecen mas |
| espacio      | **10G** gigas      | el mismo para db, web, host, correo |
| correo       | pocos         | **correo limitadisimo** y siempre **llega como spam** |
| ftp          | 1 normal      | unico metodo para subir archivos |
| seb apps auto| softaculous   | gran cantidad de websoft para autoinstalar, muy amplia, inclusive versiones del mismo app |
| **PRO**      | webapps!!!    | **mucho espacio**, muy bueno para instalar cosas ya las trae y se puede en varios subdirs,envia mails en cada inst |
| **CON**      | dominios!!!   | pocas db, **pocos subdominios**, algnas app autoinstallables fallan sin mas (opencart) |


## 1.2. http://daryhost.com

Tienen el mismo sistema que el anterior, Sistema opensource de servicio de hosting basico, provee todolo necesario, no muy integrado felizmente pero perfectamente funcionando.

* SQL o mysql es Percona Server, version super avanzada de mysql
* webserver es uno solo Apache/2.2.15 (CentOS) y no acepta aparentemente apps cgi

Asemeja un sistema montado por "nuevos", centrado mas en limitar y promocionar cuentas premiun.
**Apmplio repertorio de autoinstalacion** inclusive versiones distintas de algunas de las autoinstalaciones.

| item         | datos         |  observaciones |
|--------------|---------------|----------------|
| webserver    | apache 2.2    |  lento pero muy conocido |
| base de datos| percona 5.6   | **3 db's**, es version mejorada de mysql |
| php          | 5.4,5.6,7.0   | no muchas opciones,otros ofrecen mas |
| espacio      | **2G** gigas  | el mismo para db, web, host, correo |
| correo       | pocos         | **correo limitadisimo** y siempre **llega como spam** |
| ftp          | 1 normal      | unico metodo para subir archivos |
| seb apps auto| softaculous   | gran cantidad de websoft para autoinstalar, muy amplia, inclusive versiones del mismo app |
| **PRO**      | webapps!!!    | **ilimitado subdominios**, muy bueno para instalar cosas ya las trae y se puede en varios subdirs |
| **CON**      | espacio!!!    | **poco espacio**, pocas db, algunas apps autoinstallabe fallan sin mas (opencart) |


## 1.3. http://h8t.us/es/?i=1 (host)

Tienen el mismo sistema que el anterior, PERO ESTE NO LLEGA SPAM, Sistema opensource de servicio de hosting basico, provee todolo necesario, no muy integrado felizmente pero perfectamente funcionando.

* SQL o mysql es Percona Server, version super avanzada de mysql
* webserver es uno solo Apache/2.2.15 (CentOS) y no acepta aparentemente apps cgi

Mismos capacidades que infinityfree pero menos intuitivo, ademas sus correos no llegan spam
**Apmplio repertorio de autoinstalacion** inclusive versiones distintas de algunas de las autoinstalaciones.

| item         | datos         |  observaciones |
|--------------|---------------|----------------|
| webserver    | apache 2.2    |  lento pero muy conocido |
| base de datos| percona 5.6   | **400**, es version mejorada de mysql |
| php          | 5.4,5.6,7.0   | no muchas opciones,otros ofrecen mas |
| espacio      | **9000G** gig | **muucho**, el mismo para db, web, host, correo |
| correo       | ilimitados    | muchas cuentas pero poco configurable, pero **no llega spam** |
| ftp          | 1 normal      | unico metodo para subir archivos |
| seb apps auto| softaculous   | gran cantidad de websoft para autoinstalar, muy amplia, inclusive versiones del mismo app |
| **PRO**      | ilimitado!!!  | **ilimitado en todo**, muy bueno para instalar cosas ya las trae y se puede en varios subdirs |
| **CON**      | interfaz!!!   | **interfaz lenta y marica**, ademas no envia datos de la cuenta y si se olvida revisar se pasa |

## 1.4. https://infinityfree.net

Tremendamente serio y muy limpia interfaz con pajas maricas y bonitas. Su correo no llega nunca spam, 
el proceso de creacion de cuenta gratis es mas complicado pero mas ordenado, sus recursos dicen ilimitado incluso en bases de datos.

* SQL o mysql es Percona Server, version super avanzada de mysql
* webserver es uno solo Apache/2.2.15 (CentOS) y no acepta aparentemente apps cgi

Asemeja un sistema montado por "nuevos", centrado mas en limitar y promocionar cuentas premiun.
**Apmplio repertorio de autoinstalacion** inclusive versiones distintas de algunas de las autoinstalaciones.

| item         | datos         |  observaciones |
|--------------|---------------|----------------|
| webserver    | apache 2.2    |  lento pero muy conocido |
| base de datos| percona 5.6   | **400**, es version mejorada de mysql |
| php          | 5.4,5.6,7.0   | no muchas opciones,otros ofrecen mas |
| espacio      | **9000G** gig | **muucho**, el mismo para db, web, host, correo |
| correo       | ilimitados    | muchas cuentas pero poco configurable, pero **no llega spam** |
| ftp          | 1 normal      | unico metodo para subir archivos |
| seb apps auto| softaculous   | gran cantidad de websoft para autoinstalar, muy amplia, inclusive versiones del mismo app |
| **PRO**      | ilimitado!!!  | **ilimitado en todo**, muy bueno para instalar cosas ya las trae y se puede en varios subdirs |
| **CON**      | interfaz!!!   | **interfaz lenta y marica**, ademas no envia datos de la cuenta y si se olvida revisar se pasa |


## 1.5. https://www.heliohost.org

Siendo mas complejo, sus procesos tardan mucho ademas muchos pasos para solo tener pocas cosas.. 

* Ofrece postgresql viejo y el mysql es comunidad normal
* webserver es  Apache/2.2.15 (redhat), pero para cada proceso hay un host/webserver propio

Asemeja un sistema montado por "nuevos", centrado mas en limitar y promocionar cuentas premiun.
**Apmplio repertorio de autoinstalacion** inclusive versiones distintas de algunas de las autoinstalaciones.

| item         | datos           |  observaciones |
|--------------|-----------------|----------------|
| webserver    | cpsrvd 11.62.0.16 |  lento y raro |
| base de datos| mysql5.6/pgsql8.4 | **ofrece postgres**, pero viejo 8.4, mysql nuevo 5.6 |
| php**perl/ruby**| 5.4,5.6,7.0/2.1   | ofrece muchos subsistemas |
| espacio      | **1G** gig    | **limitado, pero separado** de db, web, host, correo |
| correo       | ilimitados    | **correo super configurable**, y no llega spam |
| ftp          | 1 normal      | unico metodo para subir archivos |
| seb apps auto| softaculous   | gran cantidad de websoft para autoinstalar, muy amplia, inclusive versiones del mismo app |
| **PRO**      | ilimitado!!!  | **con postgres y ilimitado en todo**, ofrece postgres ya que algunas app empresariales la exigen, auque poco espacio |
| **CON**      | estabilidad!!!   | **interfaz lenta y fallas**, algunas veces la disponibilidad se cae |


# 2. analisis VPS:

## 2.1. VPS https://www.gigarocket.net

No es viable, requiere mucho para configurar uno, en la web esta ya bien debajo (aunque ponen muchos botones) esto:

> To qualify for a free VPS you should register on our 90,000+ members web development community forums 
> and make 25 non-spam forum posts. 
> GigaRocket will only grant genuine requests who intend to use the free VPS offer for web and application education 
> and development purposes.

Hay que enviar 25 correos o hacerlos en el foro.

## 2.2. https://www.ilbello.com/en/free-vps

No es viable, porque solo es provisto si se tiene un dominio propio, se adjunta al dns del dominio propio.. 

> Please note at this beta stage we require an email address associated to your own domain name.
> The VPS will be configured as dev.your-domain.com

Una manera de "enganchar" la compra de dominios con ellos.. lo compras y te dan aparte una VPS gratis.. 
que seria como comprar la VPS pero la mas "chimba".

## 2.3. http://www.instafree.com/free-vps.php

No se puede realizar el registro desde Venezuela, esta en una lista de paises vetados.. 

Sin embargo se puede realizar desde un equipo remoto en otro lado...

**PRO** Esta seria, si se pudiese, la mejor opcion ya que ofrece muy buenas capacidades, acceso ssh y 5G de espacio. La ram sigue en 512.
**CON** Si ocurriese un problema, seria peligroso ya que el pais esta vetado y las ip se detectan.

## 2.4. http://ohosti.com/vpshosting.php

Dependen de un dominio, al igual que los dos primeros, sin embargo permiten registro gratis.. 

Lastimosamente el registro falla sin decir porque o dar error en la consola js.

# 3. Conclusiones

**HOSTINGS**: La eleccion seria la numero 3 seguida por la numero 4, https://infinityfree.net ofrece mucho espacio 
e incluso mas bases de datos por si uno desea montar aparte una aplicacion 
que no sea la que ellos ofrecen autoinstalable.
Cabe especial mencion a la opcion 4 permite postgres, muy importante ya que applicaciones empresariales solo admiten esta.

**VPS:** Todos los servicios analizados aun si se hubiran podido usar, estaban limitados a 512M ram y 1G espacio, 
El servicio segundo, era el mejor y aun es posible usarlo, realizando la operacion desde una maquina remota en otro pais.
El unico servicio que "era" bueno era el de snphost, pero sus registros de vps gratuitos ceso, yo tenia uno 
pero dado requeria visita periodica desde el correo, y al estar baneada la ip que use, lo perdi.

**La recomendación es usar la numero 3 de hosting (infinityfree.net) con un host alternativo en la numero 4 (heliohost.org)**,
es importante adicional, el establecer tareas de monitoreo (por si exigen atencion), 
ya que pueden detectar si la gente solo los crea para no usarlos.
