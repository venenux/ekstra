
# Libreria y llamadas asincronas emuladas

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

## Emulacion asincrona

samehost -> libasync -> http:/localhost -> controller -> socket (otro proceso)
              |
              --------> http:/localhost -> controller -> samehost (continua proceso original)

Una llamada http no puede esperar se envie el correo, asi que le dice 
a la libreria que lo "mande enviar" ya que emplea socket para realizar esto, 
el socket por su lado, y la libreria libera el proceso de espera http, 
lo que permite que no se tenga que esperar el response de la invocacion 
de la libreria, sino que se sigue normalmente...

1. desde un controller se invoca la libreria y se pasa los parametros
2. la libreria recibe y fabrica la llamada con su socket
3. el control se devuelve ya que no se tiene que esperar por el socket y respuesta
4. por otro lado el socket llama a otro controlador del mismo sistema, o puede ser otro
5. aqui hay ya dos procesos ocurriendo, pero uno no se esperara respuesta alguna, se asume

### async library

* `async`::`tobackground`
 * name: tobackground 
 * @param array $parametros cada key es el nombre de cada valor que se enviara por POST
 * @param string $moduleurlandmethod url a partir de index.php/ del modulo y methodo a invocar en el sistema
 * @return void

Esta funcion/metodo se encarga de invocar cualqueir metodo/controller que
este citado en el segundo parametro por medio de http pero usando socket.

Es como invocar un controller pero aparte, la libreria no espera respuesta, la asume.

### async controller

* `async`::`mailto_backend`
 * name: mailto_backend 
 * @param void (POST) $parametros (correode,correoa,correoas,correome,rutaarchivo) datos del envio del correo incluido el adjunto
 * @return void

envio de correos en un proceso aparte, asume la llamada "aparte" de este proceso por la libreria async

Otros controladores invocan la libreria `async` y le pasan los parametros como un arreglo, 
cada llave es el nombre de los POST y el valor de cada un o sera enviado en la libreria 
como uan llamada http a el controlador aqui `mailto_backend` pero por socket.

Esto emula como si se realizara el proceso aparte, auqnue sea en el mismo server.