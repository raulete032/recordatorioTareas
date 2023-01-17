<?php
include_once RUTABASE."/accesoBD.php";

class DatabaseConnector{

    /**
     * Variables de instancia
     */

     private $dbConnection= null;

     /**
      * Constructor
      */
     public function __construct(){
        
        $host= BD_SERVIDOR;
        $user= BD_USER;
        $pass= BD_PASS;
        $db= BD_BASE_DATOS;
        
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{
            $this->dbConnection= new \mysqli($host, $user, $pass, $db);
        }
        catch(\mysqli_sql_exception $e){
            error_log($e->getMessage());
            exit($e->getMessage());
        }
     }

     /**
      * Función que obtiene la conexión con la BDD
      */
     public function getConnection(){
        return $this->dbConnection;
     }

     /**
      * Función que cierra la conexión con la BDD
      */
     public function closeConnection(){
        mysqli_close($this->dbConnection);
     }



}
