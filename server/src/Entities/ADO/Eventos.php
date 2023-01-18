<?php

include_once RUTABASE."/cargaClases.php";
include_once RUTABASE."/funciones.php";


class Eventos{

    //Variables de instancia
    public $modelo;
    public $db;
    public $error;

    function __construct($db=null){

        $this->modelo= new EventosModel();
        $this->error="";

        if(is_null($db))
            $this->db= (new DatabaseConnector())->getConnection();
        else
            $this->db= $db;   
    }


    

    function dameEventos($where){

        $sql= "SELECT e.id_evento, e.id_nick, e.id_tipo_evento, e.nombre as nombre_evento, e.descripcion, e.fecha_inicio, e.fecha_fin, te.nombre as nombre_tipo_evento
                FROM `eventos` e
                JOIN tipos_eventos te USING (id_tipo_evento)
		WHERE " . $where;

        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);

    }




}