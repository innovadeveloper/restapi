<?php
include('./utils/utils.php');
include('./utils/ByteUtils.php');
include('./utils/FileUtils.php');
include('./utils/define.php');

$byteUtils = new ByteUtils();
$fileUtils = new FileUtils();
$httpRequests = new HttpRequests();

class HandlerFiles
{
    private $HOSTING_NAME = "http://hostfiles.com/aula_virtual/"; // (Optional)
    private $PATH_ENDPOINT_SAVE_DATA = "192.168.1.88:8888/restapi/v1/save_data"; // (PATH ENDPOINT DE SALVADO DE DATOS)
    
    private $PATH_DIR_ORIGINAL = "upload/files";
    private $PATH_DIR_ORIGINAL_PHOTOS = "../upload/files/";

    function initRoute($app){
        // http://localhost:8888/restapi/v1/handler_files
        $app->post('/handler_files', 'authenticate', function() use ($app) {
            // check for required params
            global $fileUtils;
            global $byteUtils;
            global $httpRequests;

            verifyRequiredParams(array('class_id', 'section_id', 'subject_id', 'comment'));

            $response = array();
            //capturamos los parametros recibidos y los almacxenamos como un nuevo array
            $class_id  = $app->request->post('class_id');
            $section_id = $app->request->post('section_id');
            $subject_id = $app->request->post('subject_id');
            $comment = $app->request->post('comment');
            
            // Comprobamos que la solicitud POST tenga files..
            if ($_FILES['file_name']['size'] == 0 && $_FILES['file_name']['error'] == 0)
            {
                $response["error"] = true;
                $response["message"] = "No se encontraron atributos files";
                echoResponse(500, $response);
                return;
            }

            $pathDir = $this->PATH_DIR_ORIGINAL . "/";
            $fileUtils->createFolder($pathDir);
            
            // guardar imàgenes
            $numFilesUploaded = 0;
            $filesLinkList = array();

            foreach($_FILES as $file)
            {
                $isCreado = $byteUtils->isFileExists($pathDir . $file["name"]);
                $targetFile = $pathDir . basename($file["name"]);
                $HOSTING_FILES_PATH = ($this->HOSTING_NAME . "/". $this->PATH_DIR_ORIGINAL);    // http://hostfiles.com/aula_virtual/
                $targetFileHosting = $HOSTING_FILES_PATH . "/" . $file["name"];

                if($isCreado){
                    $numFilesUploaded += 1;
                    array_push($filesLinkList, $targetFileHosting);
                    continue;
                }

                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // lista de formatos permitidos
                if( $fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && 
                    $fileType != "pdf" && $fileType != "pages" ) 
                {
                    $response["error"] = true;
                    $response["message"] = "Los formatos de archivos no son soportados : *." . $fileType;
                    echoResponse(500, $response);
                    exit();
                }

                // Almacenando ficheros
                if (move_uploaded_file($file["tmp_name"], $targetFile)){
                    $numFilesUploaded += 1;
                    array_push($filesLinkList, $targetFileHosting);
                }
            }

            if($numFilesUploaded != count($filesLinkList))
            {
                $response["error"] = true;
                $response["message"] = "Solo se guardaron " . $numFilesUploaded . "/" . count($filesLinkList) . " archivos.";
                echoResponse(500, $response);
                return;
            }

            $fields = array();
            $fields = array (
                'class_id' => $class_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id,
                'comment' => $comment,
                'photos_url' => json_encode($filesLinkList)
            );

            // Realizamos peticiòn al servidor http://kenzitvirtual.com/ ....
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,            $this->PATH_ENDPOINT_SAVE_DATA);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_POST,           true );
            curl_setopt($ch, CURLOPT_POSTFIELDS,    $fields);
            curl_setopt($ch, CURLOPT_HTTPHEADER,    array("Authorization: 3d524a53c110e4c22463b10ed32cef9d"));
            // curl_setopt($ch, CURLOPT_HTTPHEADER,    json_decode($headersArray));
            $result = curl_exec ($ch);
            // $header[] = 'Authorization: OAuth SomeHugeOAuthaccess_tokenThatIReceivedAsAString';

            // headers:["Cookie: MALSESSIONID=baokdo2jsvijglr3huvbk5j631; MALHLOGSESSID=cf1d15a6d6f6516cb50a7c63a023d6eb;", "Authorization: 3d524a53c110e4c22463b10ed32cef9d"]
            $isJSON = $httpRequests -> isJSON($result);

            // Comprobar el código de estado HTTP
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // echo $result;
            if ($http_status == 200)
            {
                $response2 = json_decode($result); // convertimos objeto lista

                $response["error"] = false;
                $response["message"] = "Registro exitoso"; // un error por lado del servidor principal... 
                $response["main_server_response"] = $response2;
                echoResponse(200, $response);
                return;
            }else // còdigo 500 y otros..
            { 
                $isJSON = $httpRequests -> isJSON($result);
                if($isJSON)
                    $result = json_decode($result); // validar si 

                $response["error"] = true;
                $response["message"] = "Error en la peticiòn";
                $response["main_server_response"] =  $result;
                echoResponse(500, $response);
                return;
            }
        });

        /**
         * Este endpoint simularà el proceso de almacenamiento 
         * de registro en la base de datos (SQL, MySQL, PostgreSQL, etc..)
         * 
         * Nota : La ruta suplantada està siendo : student/delivery/file/
         */
        // http://localhost:8888/restapi/v1/save_data
        $app->post('/save_data', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('class_id', 'section_id', 'subject_id', 'comment', 'photos_url'));

            $response = array();
            $class_id  = $app->request->post('class_id');
            $section_id = $app->request->post('section_id');
            $subject_id = $app->request->post('subject_id');
            $comment = $app->request->post('comment');
            $photos_url = $app->request->post('photos_url'); // arreglo de strings..
            
            if(!is_array(json_decode($photos_url)) ) 
            {
                $response["error"] = false;
                $response["message"] = "photos_url no es un arreglo";
                echoResponse(500, $response);
                return;
            } 

            $response["error"] = false;
            $response["message"] = "Se guardò con èxito el registro ";
            $response["photos_url"] = json_decode($photos_url);
            
            echoResponse(200, $response);
        });
    }
    
}
?>