
This it's a special document based on notes from gabmas mail list and 
also personal mails, about some behaviour of fiscal kit's in printers:
Sorry but are mayority on spanish.

> OK thanks to zxMarce i got the "why", the time to respond from the printer are not fixed
and due for all commands we need/xpected a response from the printer

> all the fiscal kit have a maximunt time to "waith to data" (i think it's how write it in english)
so in my classes must be defined at each inherits implementatino and must override the waith of the
read event to xpect data on the buffer

This it's a problem taking in consideration the object model of the gambas (in java was easy with interfaces)
so the proper way it's to implement also not only the write operation, the Read operation too in each class inherints

> so my question: can i override the "Read" event in each class without impact in performance and object class model?

Now the great information about fiscal kit's from zxMarce:

>   ten en cuenta que unos comandos toman varios segundos 
en ser ejecutados mientras que otros son prácticamente instantáneos):

nunca se penso en eso, por eso menciona no colocar tiempos fijos de espera
asi que se debe definir un tiempo maximo de espera en cada clase de cada driver fiscal especifico
y recorrer el buffer hasta agotar el tiempo


> Hace ya varios años (2009!) desarrollé una interfase fiscal con un PDF provisto por PnP Desarrollos 
en Venezuela. Fué en Windows/VB6.Proveyeron también un "control OCX" (una especie de Shared Object 
en Linux) con fuentes, escrito horriblemente en VB6. Ni mencionar que hice mi propio OCX.

**Ese OCX es famoso por ser tremendamnte horrible** y ademas inestable, se le comprende.

## Explicacion kit fiscal similar argentino (Correo zxMarce)

La explicacion de xzMarce da luz a el uso de los kit's de Argentina, Dominicana y algunos de Venezuela:

**El 13 de agosto de 2018, 15:30, ML<zxMarce> escribió:**

Hace ya varios años (2009!) desarrollé una interfase fiscal con un PDF provisto por PnP Desarrollos 
en Venezuela. Fué en Windows/VB6.Proveyeron también un "control OCX" (una especie de Shared Object 
en Linux) con fuentes, escrito horriblemente en VB6. Ni mencionar que hice mi propio OCX.

Pero del PDF que mandaron saco esto en limpio para ver si puedo darte una mano (lo destaqué en rojo; 
ten en cuenta que unos comandos toman varios segundos en ser ejecutados mientras que otros son 
prácticamente instantáneos):

    **Protocolo de comunicación fiscal**
    
La comunicación entre el host y la impresora fiscal es bi-direccional. El protocolo es 
del tipo “maestro/esclavo” y se basa en los siguientes principios:

*  El host (“maestro”) inicia todas las comunicaciones.
*  La impresora fiscal (“esclavo”) nunca enviará un mensaje no solicitado.
    
El formato de los mensajes intercambiados entre el host y la impresora fiscal es el siguiente:

| campo | descripcion |
| ----- | ------------ |
| STX   | inicio del string del mensage 0x02 |
| sec   | numero de secuencia |
| comando | Número de comando [0x30 – 0xAF] |
|   -   | separador de campo |
| campo1 | campo de datos |
| .... | ... mas campos n veces |
| campon | ultimo campo numero "n" |
| ETX   | Fin de texto [0x03 |
| BCC   | nnnnn

Tanto los comandos enviados por el host como las respuestas de la impresora fiscal, 
están enmarcadas por los códigos de control ASCII de “inicio de texto” (STX) (0x02) y de 
“fin de texto” (ETX) (0x03).

Los caracteres de verificación de bloque (BCC <nnnn>) deben ser enviados al final de 
la trama de datos. El BCC <nnnn> es la suma sencilla de todos los caracteres desde el 
inicio (STX) hasta el fin de datos (ETX), y se representa mediante 4 caracteres hexadecimales.

Los comandos enviados por el host a la impresora fiscal deben tener un número de secuencia 
en el rango desde 0x20 a 0x7F (hexadecimal) o desde 32 a 127 (decimal). 

Las respuestas de la impresora fiscal tendrán un número de secuencia coincidente. 
No es necesario que los números de secuencia sean correlativos, pero deberán ser 
diferentes del número de secuencia del comando anterior.

**Para cada comando enviado por el host, éste deberá recibir una respuesta de la impresora 
fiscal antes de que se envíe el próximo.**

El host debe analizar la respuesta a cada comando para garantizar que no ha ocurrido ningún 
error con el manejo de la impresora fiscal.

**IMPORTANTE:** las respuestas deben ser siempre analizadas, debido a que es la única forma 
de garantizar que la secuencia de comandos enviados desde el HOST a la impresora fiscal sea 
ejecutada completamente.

**IMPORTANTE:** Los campos denotados como “Campo no utilizado” pueden ser omitidos. Para hacer 
este protocolo compatible con versiones anteriores se deberá marcar como nulos mediante un (1) 
carácter 127 decimal.


Espero que te sirva. **En definitiva: NO USES RETARDOS FIJOS. Siempre hay que esperar la respuesta.**

Sí debes fijarte en tus manuales si hay un "timeout máximo" para asumir que la impresora fiscal 
no está conectada, y, obviamente, implementarlo.

Otras impresoras fiscales mandan "respuestas intermedias" mientras están ocupadas pensando 
o imprimiendo.
