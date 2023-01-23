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


    
    /**
     * Función que hace la consulta a la BDD para obtener TODOS los eventos de ese mes para ese usuario
     */
    function dameEventos($where){

        $sql= "SELECT e.id_evento, e.id_nick, e.id_tipo_evento, e.nombre as nombre_evento, e.descripcion, e.fecha_inicio, e.fecha_fin, te.nombre as nombre_tipo_evento
                FROM `eventos` e
                JOIN tipos_eventos te USING (id_tipo_evento)
		WHERE " . $where;

        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);

    }


    /**
     * Función que hace la consulta a la BDD para obtener la info de un evento en concreto
     */
    function infoEvento($where){

        $sql= "SELECT e.id_evento, e.id_nick, e.id_tipo_evento, e.nombre as nombre_evento, e.descripcion, e.fecha_inicio, e.fecha_fin, te.nombre as nombre_tipo_evento
                FROM eventos e
                JOIN tipos_eventos te using(id_tipo_evento)
                WHERE " . $where;

        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }



    function anadeEvento(){
        $id=0;
        $sql= "INSERT INTO `eventos`(`id_nick`, `id_tipo_evento`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`)
                VALUES (" . mysqli_escape_string($this->db, $this->modelo->idNick) . ", " . 
                            mysqli_escape_string($this->db, $this->modelo->idTipoEvento) . ", '" . 
                            mysqli_escape_string($this->db, $this->modelo->nombre) . "', '" . 
                            mysqli_escape_string($this->db, $this->modelo->descripcion) . "', '" . 
                            mysqli_escape_string($this->db, $this->modelo->fechaInicio) . "', '" .
                            mysqli_escape_string($this->db, $this->modelo->fechaFin) . "');";

        $insert= mysqli_query($this->db, $sql);
        if($insert)
            $id= mysqli_insert_id($this->db);

        return $id;
    }



    function eliminarEvento($where){

        $sql= "DELETE FROM `eventos` 
            WHERE " . $where;

        $delete= mysqli_query($this->db, $sql);

        return $delete;
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