<?php

include_once RUTABASE."/cargaClases.php";

class TokenController{

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
                    case "creaToken":
                        $response= $this->creaToken($input);
                        break;
                    case "compruebaToken":
                        $response= $this->compruebaToken($input);
                        break;                    
                    default:
                        $response= $this->oRes->notFoundResponse();
                }
            break;
            default:
                $response= $this->oRes->notFoundResponse();
        }

        if(is_array($response)){
            header($response["status_code_header"]);
            if($response["body"]){
                echo $response["body"];
            }
        }

    }

    /**
     * Función que crea el token y lo devuelve a la vista
     */
    function creaToken($input){

        $ADOToken= new Token();

        $token= $ADOToken->creaToken($input);

        if($token!="")
            $this->oRes->devolucionResultado(1, "OK", "", $token);
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al crear el token");

        return $this->oRes->okResponse();
        
    }


    function compruebaToken($input){
        date_default_timezone_set('Europe/Madrid');
        $where="";
        if(count($input)>2){
            $ADOTOken= new Token();
            //Antes debo comprobar si el token ha expirado
            $where= $input['idNick'];
            $expirado= $ADOTOken->compruebaExpiracion($where); //obtengo la fecha de expiración

            if($expirado!=false){
                $expirado= strtotime($expirado[0]->expira);
                $now= time();
            }

            if(!$where){
                $this->oRes->devolucionResultado(1, "KO", "");
            }
            else if($expirado<$now){ //ha expirado
                $token= $ADOTOken->expiraToken($where);

                if($token) //se actualizó (se dió de baja)
                    $this->oRes->devolucionResultado(1, "KO", "Token dado de baja");
                else
                    $this->oRes->devolucionResultado(1, "KO", "Token caducado. No se pudo dar de baja");
            }
            else{//sigue vigente

                if($input["token"]!=false && $input["idNick"]!=false){
                        $where= " token = '" . $input['token'] . "' AND id_nick= " . $input['idNick'] . 
                        " AND baja = 0 ";

                    $token= $ADOTOken->compruebaToken($where);

                    if(count($token)>0 && $token!=false) //encuentra el token
                        $this->oRes->devolucionResultado(1, "OK", "");
                    else
                        $this->oRes->devolucionResultado(1, "KO", "No se encontró el token");
                }
                else
                    $this->oRes->devolucionResultado(1, "KO", "No tienes acceso");                
            }
        }
        else{
            $this->oRes->devolucionResultado(1, "KO", "Faltan datos para la comprobación");
        }
        return $this->oRes->okResponse();
    }



}