<?php

    class FileUtils 
    {
        /**
         * create file from byte array.
         * @param [String] $nameFile
         * @param [Array<Byte>] $byteArray
         * @return void
         */
        function createFileFromByteArray($nameFile, $byteArray){
            return file_put_contents($nameFile, $byteArray);
        }
        /**
         * create folder.
         * @param [String] $targetDir
         * @return void
         */
        function isFileExist($targetDir){
            return (file_exists($targetDir));
        }

        /**
         * create folder.
         * @param [String] $targetDir
         * @return void
         */
        function createFolder($targetDir){
            if(!$this->isFileExist($targetDir))
                mkdir($targetDir, 0777, true);
        }
    }
?>

