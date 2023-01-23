<?php

include_once RUTABASE."/cargaClases.php";
include_once RUTABASE."/funciones.php";

class TiposEventos{


    //Variables de instancia
    public $modelo;
    public $db;
    public $error;

    function __construct($db=null){

        $this->modelo= new TiposEventosModel();
        $this->error="";

        if(is_null($db))
            $this->db= (new DatabaseConnector())->getConnection();
        else
            $this->db= $db;
    }

    /**
     * Función que hace la consulta a la BDD para obtener TODOS los tipos de eventos
     */
    function dameTiposEventos(){

        $sql= "SELECT *
                    FROM tipos_eventos;";
        
        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }



}