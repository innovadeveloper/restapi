<?php

    class FileUtils 
    {

        public function __construct () {}

        /**
         * create file from byte array.
         * @param [String] $nameFile
         * @param [Array<Byte>] $byteArray
         * @return void
         */
        public function createFileFromByteArray($nameFile, $byteArray){
            return file_put_contents($nameFile, $byteArray);
        }
        /**
         * create folder.
         * @param [String] $targetDir
         * @return void
         */
        public function isFileExist($targetDir){
            return (file_exists($targetDir));
        }

        /**
         * create folder.
         * @param [String] $targetDir
         * @return void
         */
        public function createFolder($targetDir){
            if(!$this->isFileExist($targetDir))
                mkdir($targetDir, 0777, true);

        }
    }
?>

