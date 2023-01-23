<?php

include_once RUTABASE."/cargaClases.php";


class TiposEventosController{

    //Variables de instancia
    private $metodo;
    private $db;
    private $requestMethod;
    private $oRes;
    private $mError;


    function __construct($metodo, $db, $requestMethod){

        $this->metodo= $metodo;
        $this->db= $db;
        $this->requestMethod= $requestMethod;
        $this->oRes= new ApiResponse();
        $this->mError="";        
    }


    function processRequest(){

        switch($this->requestMethod){

            case "POST":
                $input= json_decode(file_get_contents('php://input'), true);
                switch($this->metodo){
                    case "dameTiposEventos":
                        $response= $this->dameTiposEventos();
                        break;
                }
                break;
            default:
                $response= $this->oRes->notFoundResponse();
                break;
        }

        if(is_array($response)){
            header($response["status_code_header"]);
            if($response["body"])
                echo $response["body"];
        }
    }


    /**
     * Función que pide TODOS los tipos de eventos
     */
    function dameTiposEventos(){

        $ADOTiposEventos= new TiposEventos();
        $tiposEventos= $ADOTiposEventos->dameTiposEventos();

        if(count($tiposEventos)>=0)
            $this->oRes->devolucionResultado(1, "OK", "", $tiposEventos);
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al obtener los datos");

        return $this->oRes->okResponse();
    }


}
