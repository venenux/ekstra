
This it's a special document based on notes from gabmas mail list and 
also personal mails, about some behaviour of fiscal kit's in printers:

* [Notas de PICCORO](#notas-de-piccoro)
* [Notas de zxMarce](#notas-de-zxmarce)
* [Explicacion kit fiscal similar](#explicacion-kit-fiscal-similar-argentino-dominicana-venezolano)
  * [Protocolo de comunicación fiscal](#protocolo-de-comunicación-fiscal)
  * [El BCC Byte Check Code](#el-bcc-byte-check-code)

Sorry but are mayority on spanish.

## Notas de PICCORO

OK thanks to zxMarce i got the "why", the time to respond from the printer are not fixed
and due for all commands we need/xpected a response from the printer

All the **fiscal kit have a maximunt time to "waith to data"** so in classes 
must be defined at each inherits implementation and must override the waith of the
read event to xpect data on the buffer,s o the "Read" event must be customized 
and specific to each class printer.

This it's a problem taking in consideration the object model of the gambas 
(in java was easy with interfaces) so the proper way it's to implement the write operation, 
and the Read operation too in each class inherints from generic printer "interface class" 
at the [.src/printgeneric.class](.src/printgeneric.class)

## Notas de zxMarce

Now the great information about fiscal kit's from zxMarce:

>   ten en cuenta que unos comandos toman varios segundos 
en ser ejecutados mientras que otros son prácticamente instantáneos):

**El 13 de agosto de 2018, 15:30, ML<zxMarce> escribió:**

Hace ya varios años (2009!) desarrollé una interfase fiscal con un PDF provisto por PnP Desarrollos 
en Venezuela. Fué en Windows/VB6.Proveyeron también un "control OCX" (una especie de Shared Object 
en Linux) con fuentes, escrito horriblemente en VB6. Ni mencionar que hice mi propio OCX.

Pero del PDF que mandaron saco esto en limpio para ver si puedo darte una mano (lo destaqué en rojo; 
ten en cuenta que unos comandos toman varios segundos en ser ejecutados mientras que otros son 
prácticamente instantáneos):

**En definitiva: NO USES RETARDOS FIJOS. Siempre hay que esperar la respuesta.**

Sí debes fijarte en tus manuales si hay un "timeout máximo" para asumir que la impresora fiscal 
no está conectada, y, obviamente, implementarlo.

Otras impresoras fiscales mandan "respuestas intermedias" mientras están ocupadas pensando 
o imprimiendo.

**Para cada comando enviado por el host, éste deberá recibir una respuesta de la impresora 
fiscal antes de que se envíe el próximo.**

El host debe analizar la respuesta a cada comando para garantizar que no ha ocurrido ningún 
error con el manejo de la impresora fiscal.

**IMPORTANTE:** las respuestas deben ser siempre analizadas, debido a que es la única forma 
de garantizar que la secuencia de comandos enviados desde el HOST a la impresora fiscal sea 
ejecutada completamente.


## Explicacion kit fiscal similar argentino dominicana venezolano

La explicacion de xzMarce da luz a el uso de los kit's de Argentina, Dominicana y algunos de Venezuela:


**IMPORTANTE:** Los campos denotados como “Campo no utilizado” pueden ser omitidos. Para hacer 
este protocolo compatible con versiones anteriores se deberá marcar como nulos mediante un (1) 
carácter 127 decimal.


### Protocolo de comunicación fiscal
    
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
| BCC   | codigo de checksum [ver BCC](el-bcc-byte-check-code)

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


### El BCC Byte Check Code

El BCC (block-check-code) es, cuatro bytes transmitidos con los caracteres hexa de la suma 
(a dos bytes) de los códigos ASCII de los datos del paquete, incluyendo STX y ETX.

Ejemplo con un paquete ficticio &H02 - &H41 - &H03 (STX - "A" - ETX) donde el paquete o 
el comando completo con parametros etc etc es simpelmente "A", entonces el BCC es:

    00 02 (STX)
  + 00 41 (A)
    00 03 (ETX)
  -------
    00 46 -> Valor a enviar como BCC

El string a enviar es, entonces: `&H02&H41&H03&H30&H30&H34&H36` que traducido 
en ascii es `STXAETX0046` es decir a diferencia de el kit elepost aqui ETX no esta al final.

