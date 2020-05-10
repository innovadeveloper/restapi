# REST-API (PHP)
[![N|InnovaDeveloper](https://avatars2.githubusercontent.com/u/60544934?s=460&v=4)](https://github.com/innovadeveloper)

# Resumen

El siguiente es un repositorio API-REST desarrollado con PHP y pensado para realizar request por medio de los "manejadores" que estarán esperando recibir archivos y almacenarlos. Tratamos de cubrir la necesidad de balancear un poco la carga que recibe el servidor principal. 


# Explicación de diagrama de flujo Cliente-Manejador-Archivos!

A continuación se muestra una imagen referencial.

[![N|Diagrama Cliente-Manejador](https://scrapingdeveloper2.000webhostapp.com/assets/images/handler_files.png)](https://www.oscarblancarteblog.com/2017/03/07/escalabilidad-horizontal-y-vertical/)

# Resumen
Los siguientes son los endpoints más básicos para ser consumidos una vez sean alojados dentro de un servidor web como APACHE-WAMP.

### POST /v1/handler_files
Almacena uno o más archivos dentro de un directorio ("v1/upload/files") y enseguida realiza un request POST a otro endpoint para almacenar los paths de los archivos guardados.

**Params : form-data**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `class_id` | required | number  | El id de la clase <br/><br/> Ejemplos : `1`, `2`                                                                    |
|     `section_id` | required | string  | El id de la sección  <br/><br/> Valores soportados: `1222`,`333333`                                                                     |
|     `subject_id` | required | string  | El id del tema/sujeto <br/><br/> Ejemplo : `333333` |
**Headers**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `Authorization` | required | string  | La key es : `3d524a53c110e4c22463b10ed32cef9d`                                                                     |


**Response 200**

```
// Respuesta correcta : Status : 200
{
    "error": false,
    "message": "Registro exitoso",
    "main_server_response": {
        "error": false,
        "message": "Se guardò con èxito el registro ",
        "files_url": [
            "http://hostfiles.com/aula_virtual//upload/files/teguio_2.pdf",
            "http://hostfiles.com/aula_virtual//upload/files/teguio_1.pages"
        ]
    }
}
```

**Response Error - 500**
```
// Respuesta de error : Status : 500 (Por no enviar la param esperado)
{
    "error": true,
    "message": "Required field(s) class_id is missing or empty"
}
```


### POST /v1/save_data (Simple simulador)
Simula el registro de los paths de los archivos almacenados anteriormente, ademàs de algunos datos como class_id, etc..

**Nota**

Este endpoint debe ser reemplazado por uno similar que se encuentre alojado en el servidor principal. Solo por fines de pruebas se incluyò este endpoint dentro del mismo proyecto.

**Params : form-data**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `class_id` | required | number  | El id de la clase <br/><br/> Ejemplos : `1`, `2`                                                                    |
|     `section_id` | required | string  | El id de la sección  <br/><br/> Valores soportados: `1222`,`333333`                                                                     |
|     `subject_id` | required | string  | El id del tema/sujeto <br/><br/> Ejemplo : `333333` |
|     `files_url` | required | string[]  | Las urls (paths) de los archivos almacenados/sujeto <br/><br/> Ejemplo : `[http://hostfiles.com/aula_virtual//upload/files/teguio_2.pdf, http://hostfiles.com/aula_virtual//upload/files/teguio_1.pdf]` |
**Headers**

|          Name | Required |  Type   | Description                                                                                                                                                           |
| -------------:|:--------:|:-------:| --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|     `Authorization` | required | string  | La key es : `3d524a53c110e4c22463b10ed32cef9d`                                                                     |


**Response 200**

```
// Respuesta correcta : Status : 200
{
    "error": false,
    "message": "Se guardò con èxito el registro ",
    "files_url": [
        "http://hostfiles.com/aula_virtual//upload/files/teguio_2.pdf",
        "http://hostfiles.com/aula_virtual//upload/files/teguio_1.pages"
    ]
}
```

**Response Error - 500**
```
// Respuesta de error : Status : 500 (Por no enviar la param esperado)
{
    "error": true,
    "message": "Required field(s) class_id is missing or empty"
}
```


### Frase

Comparto una frase que puede empujarlos a que se dispongan a intentarlo cada vez

> Nunca me cansaré de empezar de nuevo 

### Referencias

Sientete libre de investigar un poco más de las tecnologías utilizadas para este pequeño y útil respositorio.

* [Slim](http://www.weblantropia.com/2016/08/30/restful-api-api-php-mysql/) - Creación de una API-REST con Slim.
* [000webhost](https://www.000webhost.com/) - Servicio de hosting con planes gratuitos y que soportan PHP con MySQL


**Free Software!**

