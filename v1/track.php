<?php
error_reporting(E_ERROR | E_PARSE);

class Track 
{
    /**
     * create folder.
     * @param [String] $targetDir
     * @return void
     */
    function initRoute($app){
        $app->get('/track', 'authenticate', function() {
            $response = array();
            $autos = array( 
                            array('make'=>'Toyota', 'model'=>'Corolla', 'year'=>'2006', 'MSRP'=>'18,000'),
                            array('make'=>'Nissan', 'model'=>'Sentra', 'year'=>'2010', 'MSRP'=>'22,000')
                    );
            
            $response["error"] = false;
            $response["message_csm"] = "Autos cargados: " . count($autos); //podemos usar count() para conocer el total de valores de un array
            $response["tracks"] = $autos;
            
            // $track = new Track();
            // $track -> echoResponse();
            echoResponse(200, $response);
            // Track() -> echoResponse(200, $response);
        });

        /* Usando POST para crear un auto */
        $app->post('/track', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('make', 'model', 'year', 'msrp'));

            $response = array();
            //capturamos los parametros recibidos y los almacxenamos como un nuevo array
            $param['make']  = $app->request->post('make');
            $param['model'] = $app->request->post('model');
            $param['year']  = $app->request->post('year');
            $param['msrp']  = $app->request->post('msrp');
            
            // validateEmail($param['year']);

            $listMake = $param['make'];
            $listMake = json_decode($listMake); // convertimos objeto lista

            $value = $listMake[2];
            echo json_encode($value); 

            $param['make'] = json_decode($param['make']); // convertimos objeto lista

            if ( is_array($param) ) {
                $response["error"] = false;
                $response["message"] = "Auto creado satisfactoriamente!";
                $response["auto"] = $param;
            } else {
                $response["error"] = true;
                $response["message"] = "Error al crear auto. Por favor intenta nuevamente.";
            }
            echoResponse(201, $response);
        });

        // $app->run();
    }


}

?>