
# eladminuser

Proyecto que permite administrar usuarios en un servidor linux.

Este constara de dos partes, un demonio que esta en el servidor
y un cliente que es la parte grafica, el demonio escucha en CGI 
y el cliente el envia comandos.

El demonio tambien ejecuta los scripts o tareas pendientes en la DB.

## arquitectura

Inicialmente solo leera la DB y segun el valor de la columna "estado" 
modificara todas las demas DB o incluso correra comandos en el servidor.

Por ahora el codigo unicamente tiene y se centra en el desarrollo del demonio.
LA administrcion es web usandoel proyecto "elsistema".
