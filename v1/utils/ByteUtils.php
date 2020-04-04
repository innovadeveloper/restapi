<?php
    class ByteUtils 
    {
        public $aMemberVar = 'aMemberVar Member Variable';
        public $aFuncName = 'aMemberFunc';
    
        function aMemberFunc() {
            print 'Inside `aMemberFunc()`';
        }

        /**
         * Convert an string to byte array.
         * @param [string] $obj
         * @return void
         */
        function convertStringToByteArray($string){
            $array = array();
            foreach(str_split($string) as $char)
                array_push($array, sprintf("%02X", ord($char)));
            return implode(' ', $array);
        }

        /**
         * Check if exists file
         * @param [string] $obj
         * @return void
         */
        function isFileExists($path){
            return file_exists($path);
        }

        /**
         * Check if exists dir
         * @param [string] $obj
         * @return void
         */
        function isDirExists($path){
            return is_dir($path);
        }

    }
?>

