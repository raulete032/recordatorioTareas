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
                    case "infoEvento":
                        $response= $this->infoEvento($input);
                        break;
                    case "anadeEvento":
                        $response= $this->anadeEvento($input);
                        break;
                    case "eliminarEvento":
                        $response= $this->eliminarEvento($input);
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
     * Función que pide TODOS los eventos de ese usuario de ese mes
     */
    function dameEventos($input){

        $where="";
        if (isset($input['limiteSuperior']) && isset($input['limiteInferior']) && isset($input['idNick'])) {
            // $where= " fecha_inicio >= '" . $input['limiteSuperior'] . "' OR " .
            // "fecha_fin <= '" . $input['limiteInferior'] . "';";
            $where= " (fecha_inicio BETWEEN '" . $input['limiteSuperior'] . "' AND '" . $input['limiteInferior'] . "' OR 
                      fecha_fin BETWEEN '" . $input['limiteSuperior'] . "' AND '" . $input['limiteInferior'] ."')" .
                      " OR (('" . $input['limiteSuperior'] . "' BETWEEN fecha_inicio AND fecha_fin) AND ('" . $input['limiteInferior'] . "' BETWEEN fecha_inicio AND fecha_fin))" .
                      "AND id_nick= " . $input['idNick'] . ";"; 

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


    /**
     * Función que devuelve la información de un evento en concerto
     */
    function infoEvento($input){

        $where="";
        if(isset($input['idEvento'])){
            $where= " id_evento = " . $input['idEvento'] . ";";

            $ADOEventos= new Eventos();
            $infoEvento= $ADOEventos->infoEvento($where);

            if(count($infoEvento)>=0)
                $this->oRes->devolucionResultado(1, "OK", "", $infoEvento);
            else
                $this->oRes->devolucionResultado(2, "KO", "Error al obtener los datos");
        }
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al recibir los datos");
        
        return $this->oRes->okResponse();

    }

    function anadeEvento($input){

        if( isset($input["idTipoEvento"]) && 
            is_numeric($input["idTipoEvento"]) &&
            isset($input["idNick"]) &&
            is_numeric($input["idNick"]) &&
            isset($input["nombre"]) &&
            isset($input["descripcion"]) &&
            isset($input["fechaInicio"]) &&
            isset($input["fechaFin"])){

            $ADOEventos= new Eventos();

            $ADOEventos->cargarDatos($input);
            
            $evento= $ADOEventos->anadeEvento();

            if($evento!=0)
                $this->oRes->devolucionResultado(1, "OK", "", $evento);
            else
                $this->oRes->devolucionResultado(2, "KO", "Error al registrar el evento");
        
            return $this->oRes->okResponse();
        }
    }


    function eliminarEvento($input){

        $where="";
        if(isset($input["idEvento"])){
            $where= " id_evento = " . $input["idEvento"] . ";";

            $ADOEventos= new Eventos();

            $eliminado= $ADOEventos->eliminarEvento($where);

            if($eliminado)
                $this->oRes->devolucionResultado(1, "OK", "Evento eliminado");
            else
                $this->oRes->devolucionResultado(1, "KO", "No se pudo eliminar el evento");

        }
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al recibir los datos");

        return $this->oRes->okResponse();


    }


}
