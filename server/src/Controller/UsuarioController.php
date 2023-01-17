<?php

include_once RUTABASE . "/cargaClases.php";


class UsuarioController{


    //Varibales de instancia
    private $metodo;
    private $db;
    private $requestMethod;
    private $oRes;
    private $mError;


    function __construct($metodo, $db, $requestMethod){
        $this->metodo= $metodo;
        $this->db= $db;
        $this->requestMethod= $requestMethod;
        $this->oRes = new ApiResponse();
        $this->mError="";   
    }



    function processRequest(){

        switch($this->requestMethod){
            case "POST":
                $input= json_decode(file_get_contents('php://input'), true);
                switch($this->metodo){
                    case "comprueba" : 
                        $response= $this->compruebaUsuario($input);
                        break;
                    case "dameUsuarios":
                        $response= $this->dameUsuarios($input);
                        break;
                    case "registra":
                        $response= $this->registraUsuario($input);
                        break;
                    case "iniciaSesion":
                        $response= $this->iniciaSesion($input);
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
     * Función que comprueba que el usuario NO existe
     */
    function compruebaUsuario($input){

        $where="";
        if(count($input)>=3){ //llega algo más que el controlador y el método

            if(isset($input['nick'])){

                $where= "nick= '" . $input['nick'] . "';";

                $ADOUsuario= new Usuarios();
                $usuario= $ADOUsuario->compruebaUsuario($where);

                if(count($usuario)==0){
                    $this->oRes->devolucionResultado(1, "OK");
                }
                else
                    $this->oRes->devolucionResultado(1, "KO", "Ese nick ya está registrado");
            }
            else
                $this->oRes->devolucionResultado(2, "KO", "Error al recibir los datos");
            
            return $this->oRes->okResponse();
        }
    }

    function dameUsuarios(){

        $where="";
        if(isset($input['idNick']))
            $where= " id_nick= " . $input['idNick'];

        $ADOUsuario= new Usuarios();
        $usuarios= $ADOUsuario->dameUsuarios($where);

        if($usuarios)
            $this->oRes->devolucionResultado(1, "OK", "", $usuarios);
        else
            $this->oRes->devolucionResultado(2, "KO", "Error al obtener los usuarios");
        
        return $this->oRes->okResponse();
    }



    function registraUsuario($input){

        if($this->nick($input) && $this->pass($input) && $this->email($input)){

            $ADOUsuario= new Usuarios();
            $ADOUsuario->cargarDatos($input);

            $usuario= $ADOUsuario->anadeUsuario();

            if($usuario!=0)
                $this->oRes->devolucionResultado(1, "OK", "", $usuario);
            else
                $this->oRes->devolucionResultado(2, "KO", "Error al registrar el usuario");
            
            return $this->oRes->okResponse();

        }
    }


    function iniciaSesion($input){
        $where="";
        if($this->nick($input) && $this->pass($input)){

            $where= " nick= '".$input['nick']."' AND pass=md5('".$input["pass"]."');";

            $ADOUsuario= new Usuarios();
            $ADOUsuario->cargarDatos($input);

            $inicioSesion= $ADOUsuario->iniciaSesion($where);

            if(count($inicioSesion)>0)
                $this->oRes->devolucionResultado(1, "OK", "", $inicioSesion);
            else
                $this->oRes->devolucionResultado(1, "KO", "Usuario y/o contraseña erróneas");
            
            return $this->oRes->okResponse();
        }


    }









    function nick($input){
        return (isset($input['nick']) && is_string($input['nick']));
    }


    function pass($input){
        return (isset($input['pass']) && is_string($input['pass']));
    }

    function email($input){
        return (isset($input['email']) && is_string($input['email']));
    }



}