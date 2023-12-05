<?php

include_once(__DIR__ . '\PDOconfig.php');

class PDOdb{

    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;
    
    public function __construct(){
        $this->host     = HOST;
        $this->db       = DB;
        $this->user     = USER;
        $this->password = PASSWORD;
        $this->charset  = 'utf8mb4';
    }
    
    
    //mysql -e "USE todolistdb; select*from todolist" --user=azure --password=6#vWHD_$ --port= 49175 --bind-address

    function connect(){
        
        try{
            $connection = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
            
            $option = [
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES  => false,
            ];
            //$pdo = new PDO($connection, $this->user, $this->password, $options);
            $pdo = new PDO($connection,$this->user,$this->password);
            return $pdo;
            
        }catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}


?>