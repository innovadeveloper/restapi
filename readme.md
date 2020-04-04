# REST-API (PHP)
[![N|InnovaDeveloper](https://avatars2.githubusercontent.com/u/60544934?s=460&v=4)](https://github.com/innovadeveloper)

# Resumen

El siguiente es un repositorio API-REST desarrollado con PHP y pensado para realizar request por medio de los "manejadores" que estarán esperando recibir las url-bases, request-params, headers(opcional) para poder extraer el contenido y evitarnos a nosotros hacer el trabajo desde un ordenador que pueda caer muy rápido en el error "403-Forbidden".

[![N|Image-403-Forbidden](https://scrapingdevelop.000webhostapp.com/assets/images/error-403-forbidden.png)](https://www.hostinger.es/tutoriales/error-403-prohibido-arreglarlo/)

# Explicación de diagrama de flujo Cliente-Manejador!

A continuación se muestra el diagrama nombrado cliente-manejador para su mejor comprensión.

[![N|Diagrama Cliente-Manejador](https://scrapingdevelop.000webhostapp.com/assets/images/diagrama-cliente-manejador.png)](https://www.hostinger.es/tutoriales/error-403-prohibido-arreglarlo/)

# Resumen
Los siguientes son los endpoints más básicos para ser consumidos una vez sean alojados dentro de un servidor web como APACHE-WAMP.

### GET /v1/translator_master?
Obtiene un response de tipo JSON con la traducción deseada. Importante considerar que algunos sìmbolos como apostrofes (') pueden afectar en la respuesta del endpoint.

**Parameters**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `languageOrigen` | required | string  | El idioma de origen del texto <br/><br/>  Por defecto es `en` <br/><br/>  Valores soportados: `en`,`es`,`ja`....                                                                     |
|     `languageDestination` | required | string  | El idioma al que se requiere traducir el texto. <br/><br/> Valores soportados: `en`,`es`,`ja`....                                                                        |
|     `text` | required | string  | El texto a traducir. <br/><br/> Ejemplo : `Hello my name es Kane`                                                                     |
**Headers**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `Authorization` | required | string  | La key es : `3d524a53c110e4c22463b10ed32cef9d`                                                                     |


**Response 200**
```
// Respuesta correcta : Status : 200
{
    "error": false,
    "message": "success",
    "data": {
        "ptsDirCode": "en-es",
        "result": "hola mi nombre es Kane"
    }
}
```

**Response Error - 500**
```
// Respuesta correcta : Status : 500 (Por envìar en el texto un apostrofe `This is Kane's car`)
{
    "error": true,
    "message": "error en tiempo de ejecuciòn"
}

```


### POST /v1/handler_api_master
Realiza un request get a la ruta que le pidamos y obtiene un response de tipo JSON con el contenido encontrado. Considerar que muchos de estas APIS que querramos consumir pedirán algún token de autorización. Queda en nosotros averiguar el token y enviarselo a este endpoint.

**Params : form-data**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `baseUrl` | required | string  | La url base del sitio web <br/><br/> Ejemplos : `https://myanimelist.net/anime/api/video/around.json?`, `https://domain.com/api/data?`                                                                    |
|     `requestUrl` | required | string  | El request params o query string que espera recibir el sitio web <br/><br/> Valores soportados: `id=21&p=32`,`param1=hola mundo`                                                                     |
|     `headers` | required | string[]  | Los headers requeridos para llevar acabo la consulta <br/><br/> Ejemplo : `["Cookie: MALSESSIONID=baokdo2jsvijglr3huvbk5j631; MALHLOGSESSID=cf1d15a6d6f6516cb50a7c63a023d6eb;",  "Authorization: 3d524a53c110e4c22463b10ed32cef9d"]` |
**Headers**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `Authorization` | required | string  | La key es : `3d524a53c110e4c22463b10ed32cef9d`                                                                     |


**Response 200**

```
// Respuesta correcta : Status : 200
{
    "videos": [
        {
            "id": 2084264,
            "anime_id": 21,
            "provider_id": 1,
            "subdub_type": "sub",
            "episode_number": 622,
            "url": "http://www.crunchyroll.com/redirect?url=%2Fone-piece%2Fepisode-622-a-touching-reunion-momonosuke-and-kinemon-646763&aff=af-12299-plwa",
            "embed_tag": null,
            "external_url": "http://www.crunchyroll.com/redirect?url=%2Fone-piece%2Fepisode-622-a-touching-reunion-momonosuke-and-kinemon-646763&aff=af-12299-plwa",
            "thumbnail": "https://img1.ak.crunchyroll.com/i/spire3-tmb/23159278423bb5fd479bbcdfd644ebb01385257240_large.jpg",
            "title": "A Touching Reunion! Momonosuke and Kin'emon!",
            "embed_type": "link",
            "published_at": 1385258400,
            "free_published_at": 1385258400,
            "expired_at": 4294967295,
            "provider_episode_id": 646763,
            "created_at": 1470219722,
            "updated_at": 1585855202,
            "availableForOnlyPremium": false,
            "isCurrent": false,
            "episodeUrl": "/anime/21/One_Piece/episode/622"
        },
        ....
    ]
}
```

**Response Error - 500**
```
// Respuesta correcta : Status : 500 (Por no enviar la API-KEY (Authorization) esperada)
{
    "error": true,
    "message": "Acceso denegado. Token inválido"
}
```

### POST /v1/handler_html_master
Realiza un request get a la ruta que le pidamos y obtiene un response de tipo HTML con el contenido encontrado. Y ya una vez nosotros recuperemos el contenido html podremos realizar nuestro scrapeado desde nuestro ordenador o servidor sin que este haya hecho la solicitud. 

**Params : form-data**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `baseUrl` | required | string  | La url base del sitio web <br/><br/> Ejemplos : `https://myanimelist.net/anime/api/video/around.json?`, `https://domain.com/api/data?`                                                                    |
|     `requestUrl` | required | string  | El request params o query string que espera recibir el sitio web <br/><br/> Valores soportados: `id=21&p=32`,`param1=hola mundo`                                                                     |

**Headers**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `Authorization` | required | string  | La key es : `3d524a53c110e4c22463b10ed32cef9d`                                                                     |


**Response 200**

```
// Respuesta correcta : Status : 200
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head>   <link rel="preconnect" href="//fonts.gstatic.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//fonts.googleapis.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//tags-cdn.deployads.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//www.googletagservices.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//www.googletagmanager.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//apis.google.com/" crossorigin="anonymous" /> <link rel="preconnect" href="//pixel-sync.sitescout.com/" crossorigin="anonymous" /> ..........

```
**Response Error - 500**

```
// Respuesta correcta : Status : 500 (Por no enviar algùn paràmetro correcto como la base-url)
{
    "error": false,
    "message": "Error por Forbidden o contenido vacío"
}
```

### Frase

Comparto una frase que puede empujarlos a que se dispongan a intentarlo cada vez

> Nunca me cansaré de empezar de nuevo 

This text you see here is *actually* written in Markdown! To get a feel for Markdown's syntax, type some text into the left window and watch the results in the right.

### Referencias

Sientete libre de investigar un poco más de las tecnologías utilizadas para este pequeño y útil respositorio.

* [Slim](http://www.weblantropia.com/2016/08/30/restful-api-api-php-mysql/) - Creación de una API-REST con Slim.
* [000webhost](https://www.000webhost.com/) - Servicio de hosting con planes gratuitos y que soportan PHP con MySQL


**Free Software!**

