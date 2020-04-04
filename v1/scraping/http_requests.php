<?php

    class HttpRequests 
    {

        function isJSON($string){
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }

        /**
         * encodifica una requestUrl respetando las keys y simbolos de igual "=".
         * @param [String] $requestUrl -> "key1=apples&key2=green and red&key3=value3";
         * @return [String] $queryUrl
         */
        function encodeRequestUrl($requestUrl){
            // $query = "key1=apples&key2=green and red&key3=value3";
            $queryUrl = "";
            foreach (explode('&', $requestUrl) as $chunk) 
            {
                $param = explode("=", $chunk);
                if ($param) {
                    $queryUrl .= $param[0] . "=" . rawurlencode($param[1]) . "&";
                }
            }
            if(strlen($queryUrl) > 0)
                $queryUrl = substr($queryUrl, 0, -1);  // quitamos el ùltimo valor

            return $queryUrl;
        }
    }
?>

