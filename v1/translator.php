<?php
include('./scraping/http_requests.php');
include('./utils/define.php');

class Translator 
{
    function initRoute($app){
        // http://localhost:8888/restapi/v1/translator?languageOrigen=en&languageDestination=es&text=hello world. My name is kane.
        $app->get('/translator', 'authenticate', function() 
        {
            verifyRequiredParams(array('languageOrigen', 'languageDestination', 'text'));

            $languageOrigen = $_REQUEST["languageOrigen"];
            $languageDestination = $_REQUEST["languageDestination"];
            $text = $_REQUEST["text"];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,            "https://www.online-translator.com/services/soap.asmx/GetSelectionInfo" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($ch, CURLOPT_POST,           1 );
            curl_setopt($ch, CURLOPT_POSTFIELDS,     "{ dirCode:'$languageOrigen-$languageDestination', text:'$text', lang:'$languageDestination',useAutoDetect:true, page: 0, pageSize:15, options: 64}" ); 
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-type: application/json; charset=UTF-8')); 
            $result=curl_exec ($ch);

            $httpRequests = new HttpRequests();
            $isJSON = $httpRequests -> isJSON($result);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // Valida si se ha producido errores y muestra el mensaje de error
            if(preg_match('/\bRuntime Error\b/', strtolower($result)) || $errno = curl_errno($ch) || !$isJSON || ($http_status != 200 && $http_status != 201)) {
                $response["error"] = true;
                $response["message"] = "error en tiempo de ejecuciòn";
                echoResponse(500, $response);
                curl_close($ch);
                return;
            }
            curl_close($ch);
            // convert string to object
            $translate = json_decode($result); 

            // extrayendo los datos màs relevantes
            $translation = $translate -> d -> Translation;

            // $data
            $dataResponse -> ptsDirCode = $translation -> ptsDirCode;
            $dataResponse -> result = $translation -> result;

            $response["error"] = false;
            $response["message"] = "success";
            $response["data"] = $dataResponse;

            echoResponse(200, $response);
        });

        // http://localhost:8888/restapi/v1/translator_master?languageOrigen=en&languageDestination=es&text=hello world. My name is kane.
        $app->get('/translator_master', 'authenticate', function() 
        {
            verifyRequiredParams(array('languageOrigen', 'languageDestination', 'text'));
            
            $languageOrigen = $_REQUEST["languageOrigen"];
            $languageDestination = $_REQUEST["languageDestination"];
            $text = $_REQUEST["text"];

            $httpRequests = new HttpRequests();
            $text = rawurlencode($text);    // 

            $requestUrl = "languageOrigen=$languageOrigen&languageDestination=$languageDestination&text=$text";

            $listDomains = LIST_DOMAINS_TRANSLATOR; // array($firstDomain, $secondDomain, $thirdDomain);


            $isRespuestaEnviada = False;

            for ($i = 0; $i <= count($listDomains) - 1; $i++) {
                $domain = $listDomains[$i];
              
                $hrefRequest = $domain . $requestUrl; 
                // inicio de peticiòn
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, $hrefRequest );
                // curl_setopt($ch, CURLOPT_URL, 'http://localhost:8888/restapi/v1/translator?languageOrigen=en&languageDestination=es&text=hello%20world.%20My%20name%20is%20kane.');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
                $headers = array();
                $headers[] = 'Content-length: 0';
                $headers[] = 'Content-type: application/json; charset=UTF-8';
                $headers[] = 'Authorization: 3d524a53c110e4c22463b10ed32cef9d'; // necesario
                $headers[] = 'Accept-Language: es-419,es;q=0.9,gl;q=0.8,en;q=0.7,pt;q=0.6,pt-BR;q=0.5';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);
                // Comprobar el código de estado HTTP
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)){
                    curl_close($ch);
                    continue;
                }
                curl_close($ch);

                // respuesta correcta..
                $isJSON = $httpRequests -> isJSON($result);
                // Valida si se ha producido errores y muestra el mensaje de error
                if(preg_match('/\bRuntime Error\b/', strtolower($result)) || $errno = curl_errno($ch) || !$isJSON) 
                    continue;

                if ($http_status == 200){
                    // respondemos la solicitud del cliente
                    $response = json_decode($result); // convertimos objeto lista
                    echoResponse(200, $response);
                    $isRespuestaEnviada = True;
                    break;
                }
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