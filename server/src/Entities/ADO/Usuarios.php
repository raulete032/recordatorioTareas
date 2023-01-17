<?php

include_once RUTABASE."/cargaClases.php";
include_once RUTABASE."/funciones.php";

class Usuarios{

    //Variables de instancia
    public $modelo;
    public $db;
    public $error;

    function __construct($db=null){

        $this->modelo= new UsuariosModel();
        $this->error="";

        if(is_null($db))
            $this->db= (new DatabaseConnector())->getConnection();
        else
            $this->db= $db;
    }



    function compruebaUsuario($where){

        $sql= "SELECT *
                FROM usuarios
                WHERE " . $where;

        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);

    }


    function dameUsuarios($where){

        $sql= "SELECT *
                FROM usuarios";
        
        if($where!="")
            $sql.= " WHERE " . $where . ";";
            
        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }




    function anadeUsuario(){
        $id=0;
        $sql= "INSERT INTO `usuarios`(`nick`, `pass`, `email`) 
                    VALUES ('" . mysqli_escape_string($this->db, $this->modelo->nick) . "', " . 
                            "md5('" . mysqli_escape_string($this->db, $this->modelo->pass) . "') ," .
                            "'" . mysqli_escape_string($this->db, $this->modelo->email) . "'); ";

        $insert= mysqli_query($this->db, $sql);
        if($insert)
            $id= mysqli_insert_id($this->db);
        
        return $id;
    }


    function iniciaSesion($where){

        $sql= "SELECT *
                    FROM usuarios
                    WHERE " . $where;
            
        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }










    /**
     * Función que carga los datos en el modelo
     */
    function cargarDatos($datos){

        foreach($datos as $key => $valor){
            if(isset($this->modelo->$key))
                $this->modelo->$key= $valor;
        }
    }
}