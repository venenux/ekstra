
Aplicaciones para interconexciones entre apps

Emplea CURL mucho: [../docs/README-cURL.md](../docs/README-cURL.md)
Los datos se reciben y manejan en JSON (un string con elemento tipo arreglo)

Listado:

* elcliente: web que se conecta a otra web en vez de una db

# registropagos

Su funcion es registrar los pagos o deposito realizados, 
por cada mes que se paga al origen o web de origen.

### Como toma datos?

Facil, simplemente hace uan llamada como si fuera un navegador, 
para esto se emplea Curl, y se envia credenciales en modo POST 
la llamada es simpel http, obviamente la respuesta hay que manejarla

La respuesta es json, de esta manera se puede tratar los datos 
facilmente con cualquier tecnologia.

### Como envia datos o se guardan datos en la web remota?

De igual manera, en la llamada http se envia todo la data por
medio de POST o GET, y la respuesta se recibe en json.

**Esto significa que el modelo de datos no se conecta a una db 
como siempre, sino a una web.**

### CI3 con RESTen elcliente

El codeigniter empleado en "elcliente" es 3.1.X, pero incluye 
adicional REST server y REST client, ademas incluye CUrl.

