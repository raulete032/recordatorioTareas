<?php

class TokenModel{


    public $idToken;
    public $idNick;
    public $token;
    public $expira;
    public $baja;


    function __construct(){

        $this->idToken=0;
        $this->idNick=0;
        $this->token="";
        $this->expira="";
        $this->baja=0;
    }

}