<?php

class EventosModel{

    //Variables de instancia
    public $idEvento;
    public $idNick;
    public $idTipoEvento;
    public $nombre;
    public $descripcion;
    public $fechaInicio;
    public $fechaFin;

    
    function __construct(){

        $this->idEvento=0;
        $this->idNick=0;
        $this->idTipoEvento=0;
        $this->nombre="";
        $this->descripcion="";
        $this->fechaInicio="1900-01-01";
        $this->fechaFin="1900-01-01";
        
    }
}