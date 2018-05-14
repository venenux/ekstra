# Sistema almacen parte web

* flujo
* pdf ejemplo

## Requisitos de entorno

El sistema asume dispositivos "on-line" es decir 
todos los dispositivos de pick-in deben tener conectividad 
ya que estamos en una era de tecnologia moderna.

Para dispositivos "off-line" no se ofrece compatibilidad 
si estos no son linux o compatibles linux (ejemplo ARM's o Androit's).

## Flujo de trabajo sistema almacen

1. Los flujos son de dos tipos, de entrada y de salida, 
2. Todos son bidireccionales, es decir tiene las mismas fases
3. Fases: `pedido`->`orden`->`despacho`
4. Cada transcicion entre fases genera un asciento en `movimientos`.

### Flujo de entrada de item o productos



### Flujo de salida de item o productos

El proceso de salida comienza en un pedido, aunque 
la orden tambien se puede emitir directamente sin pedido.
Cada transcicion entre pedido, orden y despacho genera un movimeinto.

1. En la interfaz del almacen se crea un pedido sea buscando productos 
o con carga de archivo CSV (2 columnas, codigoproducto, cantidadpedida)
el pedido es creado por usuario actual para otro usuario o entidad

2. Un usuario de almacen o privilegiado, procesa y acepta o rechaza, 
el sistema le muestra segun los codigos pedidos, ubicacion y cantidad 
en el galpon o almacen para poder convertirla en orde y realizar el picking, 
si se acepta/procesa, se inserta cada registro en las dos tablas de pedido, 
que lo estan sacando de tal posicion, el codigo y la cantidad, 
eso queda todo en el tabla de movimientos con el codigo de orden 
y el tipo de movimeinto que era un "pedido->orden"


3. Ahora este pedido es visto en el sistema de lado del almacen 
ya convertido en orden, cuando la selecciona la marca para conteo, 
cuando esto se hace se marca solo en la tabla maestra la orden, 
en estado procesandose, (en este estado los productos y cantidad se extraen de la tabla pedido)
la interfaz le indica al usuario los codigos del pedido y la ubicacion 
de cada uno de los codigos:

4. cada tableta o pda o dispositivo etc va marcando la cantidad de cada 
uno de los codigos de productos de la tabla de deatelle de pedidos, 
cuando terminan de marcar se escribe esto en las tablas de detalle 
de las ordenes, y cuantos productos encontro.
Cabe destacar que se asume dispositivo on-line es decir se marca al vuelo.

5. despues de el picking, si se aprueba, se convierte en un despacho, 
ademas de producir otro asentameinto entre orden y despacho 
que lo estan sacando de tal posicion, el codigo y la cantidad, 
eso queda todo en el tabla de movimientos con el codigo de orden 
y el tipo de movimeinto que era un "pedido->orden"

6. Esta orden se convierte en un despacho no procesado porque hay que 
meterlo todo en el camion, claro esta la tabla maestra tiene el despacho 
creado pero la de detalle no tiene nada porque no han comenzado procesar 
cada codigo,  la tabla de movimientos ya tiene un movimeinto que se 
creo cuando se proceso la orden y se convirtio en despacho pero 
se crera otro cuando se termine procesar debido a registro por destino, 

7. Se empieza meter cada productos disparados en la alcabala mientras 
lo meten al camion, al mismo tiempo lo disparan, como es on-line 
los cambios son en directo y lo ven en la intrefaz en caliente.

### Manejo de existencias

Solo se alteran cuadno se mueve un producto de el estante.

La alteracion es de la tabla de existencias por productos 
y de la de peosiciones no porque la de existencias tiene la clave 
combinada de producto-prosicion, la combinacion no se borra, solo se 
lleva a cero.


## PDF ejemplo que se emite en cada flujo de proceso

Caso de "Orden":

El archivo `assets/elalmacenwebarchivos/RecepcionAlmacen-OrdenCompra-000146.pdf` 
es un ejemplo real de una orde para el ingreso de productos al almacen:

[assets/elalmacenwebarchivos/RecepcionAlmacen-OrdenCompra-000146.pdf](assets/elalmacenwebarchivos/RecepcionAlmacen-OrdenCompra-000146.pdf)

Este es enviado y recibido por correo.
