<?php
    class conexion{
        private $DBType = 'mysqli';
        private $DBServer = '127.0.0.1'; //server name or IP 
        private $DBUser = 'webmaster';
        private $DBPass = '1234';
        private $DBName = 'prograweb';
        
        public function _construct(){}
        
        public function conectar(){
            $con = adoNewConnection($this->DBType);
            $con->debug = false;
            $con->connect($this->DBServer,$this->DBUser,$this->DBPass,$This->DBName);
            return $con;
        }
    }

?>    