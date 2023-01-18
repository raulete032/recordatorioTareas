<?php

include_once RUTABASE."/cargaClases.php";


class EventosController{

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
                    case "dameEventos":
                        $response= $this->dameEventos($input);
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



    function dameEventos($input){

        $where="";
        if (isset($input['limiteSuperior']) && isset($input['limiteInferior'])) {
            // $where= " fecha_inicio >= '" . $input['limiteSuperior'] . "' OR " .
            // "fecha_fin <= '" . $input['limiteInferior'] . "';";
            $where= " fecha_inicio BETWEEN '" . $input['limiteSuperior'] . "' AND '" . $input['limiteInferior'] . "' OR 
                      fecha_fin BETWEEN '" . $input['limiteSuperior'] . "' AND '" . $input['limiteInferior'] ."';"; 

            $ADOEventos= new Eventos();
            $eventos= $ADOEventos->dameEventos($where);

            if(count($eventos)>=0)
                $this->oRes->devolucionResultado(1, "OK", "", $eventos);
            else
                $this->oRes->devolucionResultado(2, "KO", "Error al obtener los datos");
        } 
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al recibir los datos");
    
        
        return $this->oRes->okResponse();
    }






}