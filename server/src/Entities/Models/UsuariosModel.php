<?php


class UsuariosModel{


    //Variables de instancia
    public $idUsuario;
    public $nick;
    public $pass;
    public $email;


    function __construct(){

        $this->idUsuario=0;
        $this->nick="";
        $this->pass="";
        $this->email="";      
    }



}