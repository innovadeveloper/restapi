<?php
include_once('./scraping/simple_html_dom.php');


class HandlerHtml
{
    function initRoute($app){
        /* Usando POST para crear un auto */
        // http://localhost:8888/restapi/v1/handler_html
        $app->post('/handler_html', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('baseUrl', 'requestUrl'));

            $response = array();
            //capturamos los parametros recibidos y los almacxenamos como un nuevo array
            $baseUrl  = $app->request->post('baseUrl');
            $requestUrl = $app->request->post('requestUrl');

            $httpRequests = new HttpRequests();
            $requestUrl = $httpRequests -> encodeRequestUrl($requestUrl);

            $hrefRequest = $baseUrl . $requestUrl; // https//animelist...?param1=2&param3=33
            $result = file_get_html($hrefRequest);  

            if (preg_match('/\b403 Forbidden\b/', $result) || empty($result)) {
                $response["error"] = false;
                $response["message"] = "Error por Forbidden o contenido vacío";
                echoResponse(500, $response);
                return;
            } 
            echoResponseHtml(200, $result);
        });

        // http://localhost:8888/restapi/v1/handler_api_master
        $app->post('/handler_html_master', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('baseUrl', 'requestUrl'));

            $response = array();
            //capturamos los parametros recibidos y los almacxenamos como un nuevo array
            $baseUrl  = $app->request->post('baseUrl');
            $requestUrl = $app->request->post('requestUrl');
            
            $httpRequests = new HttpRequests();
            $requestUrl = $httpRequests -> encodeRequestUrl($requestUrl);

            $hrefRequest = $baseUrl . $requestUrl; // https//animelist...?param1=2&param3=33
            $listDomains = LIST_DOMAINS_SCRAPING; // array($firstDomain, $secondDomain, $thirdDomain);


            $isRespuestaEnviada = False;
            //capturamos los parametros recibidos y los almacxenamos como un nuevo array
            $fields = array (
                'baseUrl' => $baseUrl,
                'requestUrl' => $requestUrl
            );

            for ($i = 0; $i <= count($listDomains) - 1; $i++) 
            {
                $domain = $listDomains[$i];
              
                $hrefRequest = $domain; 
                // http://localhost:8888/restapi/v1/handler_html
                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL,            $hrefRequest);
                curl_setopt($ch, CURLOPT_URL,            "http://localhost:8888/restapi/v1/handler_html");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt($ch, CURLOPT_POST,           true );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER,    array("Authorization: 3d524a53c110e4c22463b10ed32cef9d"));

                $result=curl_exec ($ch);
                // Comprobar el código de estado HTTP
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($http_status == 200){
                    // respondemos la solicitud del cliente
                    echoResponseHtml(200, $result);
                    $isRespuestaEnviada = True;
                    break;
                }else
                    continue;
            }

            if(!$isRespuestaEnviada){
                $response["error"] = true;
                $response["message"] = "No se consiguió alguna respuesta satisfactoria.Lo sentimos";
                echoResponse(500, $response);
            }
        });
    }
    
}
?>