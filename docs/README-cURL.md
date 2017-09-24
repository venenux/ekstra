# cURL : como enviar y recibir sin libreria

La libreria **cURL** provee un programa que puede ***emular un navegador web, formularios y llamadas web, inclusio envios de archivos***

When sending data via a POST or PUT request, two common formats (specified via the `Content-Type` header) are:
  * `application/json`
  * `application/x-www-form-urlencoded`

Many APIs will accept both formats, so if you're using `curl` at the command line, it can be a bit easier to use the form urlencoded format instead of json because
  * the json format requires a bunch of extra quoting
  * curl will send form urlencoded by default, so for json the `Content-Type` header must be explicitly set

**Generalmente usamos un navegador, este *envia una peticion (request)* y obtenemos una *respuesta bonita con graficos (response)*, con surl podemos emular el navegador web, incluso emular un formulario.**


## Uso general rapido

For sending data with POST and PUT requests, these are common `curl` options:

 * request type, definicion de que tipo de "envio" o "request" se hace, emulamos un envio 'POST' o el poco usado 'PUT'

   * `-X POST`
   * `-X PUT`

 * content type header, se le especifi a que emulamos, si un envio json o un envio de navegador:

  * `-H "Content-Type: application/x-www-form-urlencoded"`
  * `-H "Content-Type: application/json"`
 
* data, que se envia por ejemplo campos de un formulario:

  * form urlencoded: `-d "param1=value1&param2=value2"` or `-d @data.txt`
  * json: `-d '{"key1":"value1", "key2":"value2"}'` or `-d @data.json`
  
# Ejemplos

## Consola directo

### POST application/x-www-form-urlencoded


    curl -d "param1=value1&param2=value2" -X POST http://localhost:3000/data

`application/x-www-form-urlencoded` explicit:

    curl -d "param1=value1&param2=value2" -H "Content-Type: application/x-www-form-urlencoded" -X POST http://localhost:3000/data

with a data file:
 
    curl -d "@data.txt" -X POST http://localhost:3000/data

### POST application/json

    curl -d '{"key1":"value1", "key2":"value2"}' -H "Content-Type: application/json" -X POST http://localhost:3000/data
    
with a data file:
 
    curl -d "@data.json" -X POST http://localhost:3000/data

## Uso en php/CI

Emulamos la DB en el modelo de datos, y en el modelo de datos es que realiamos la llamada a db, que realmente es una llamada cURL hacia otro sistema tambien web. La idea se ilustra asi:

```
{hostcliente}controller->modelo->cURL+json<------------->cURL+json->db{host.remoto}
```

En el CI3 o CI2 es facil o usando directamente la llamada cURL nativa, sea como sea el framework provee uan libreria lista para usar:

``` php
public function registrarpago( $parametros = array() )
{ 
		$laurl =$this->config->item('json_post_registropagos'); // en application/config crear un seccion con la setup json
		$eljson =json_encode($parametros);     // la data siempre es en forma de array, jerarquico claro
		$scurl = curl_init();
		curl_setopt($scurl, CURLOPT_URL, $laurl);
		curl_setopt($scurl , CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($scurl , CURLOPT_POST, 1);
		curl_setopt($scurl , CURLOPT_POSTFIELDS,$eljson);
		curl_setopt($scurl, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($scurl );     // aqui se realiza la llamada, y se espera la respuesta
		curl_close($scurl );
		return $response;                 // el formato de la respuesta es definido segun el host a donde se hace request
}
```

Si no tiene la libreria, tomela de internet o de spark plugins.

## Uso en .Net

(WIP) pero ojo aqui no se necesit ael maldito webserver okey

## Uso en Gambas

(WIP)

## Parametros comunes consola

En consola se deben realizar pruebas primero, para saber si la data del host remoto esta correcta, estos parametros son muy utiles:

`-#, --progress-bar`
        Make curl display a simple progress bar instead of the more informational standard meter.

`-b, --cookie <name=data>`
        Supply cookie with request. If no `=`, then specifies the cookie file to use (see `-c`).

`-c, --cookie-jar <file name>`
        File to save response cookies to.

`-d, --data <data>`
        Send specified data in POST request. Details provided below.

`-f, --fail`
        Fail silently (don't output HTML error form if returned). 

`-F, --form <name=content>`
        Submit form data.

`-H, --header <header>`
        Headers to supply with request.

`-i, --include`
        Include HTTP headers in the output.

`-I, --head`
        Fetch headers only.

`-k, --insecure`
        Allow insecure connections to succeed.

`-L, --location`
        Follow redirects.

`-o, --output <file>`
        Write output to <file>. Can use `--create-dirs` in conjunction with this to create any directories
        specified in the `-o` path.

`-O, --remote-name`
        Write output to file named like the remote file (only writes to current directory).

`-s, --silent`
        Silent (quiet) mode. Use with `-S` to force it to show errors.

`-v, --verbose`
        Provide more information (useful for debugging).

`-w, --write-out <format>`
        Make curl display information on stdout after a completed transfer. See man page for more details on
        available variables. Convenient way to force curl to append a newline to output: `-w "\n"` (can add
        to `~/.curlrc`).
        
`-X, --request`
        The request method to use.
