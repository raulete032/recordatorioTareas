<?php
define("RUTABASE", dirname(__FILE__)); //para tener en la raiz en una CONSTANTE

spl_autoload_register(function ($clase) {

    if(preg_match("/Controller/", $clase)){
        $ruta = RUTABASE . "/src/Controller/";
        $fichero = $ruta . "$clase.php";
    }
    else if(preg_match("/Model/", $clase)){
        $ruta = RUTABASE . "/src/Entities/Models/";
        $fichero = $ruta . "$clase.php";
    }
    else if(preg_match("/Response/", $clase)){
        $ruta = RUTABASE . "/src/System/";
        $fichero = $ruta . "$clase.php";
    }
    else if(preg_match("/Connector/", $clase)){
        $ruta = RUTABASE . "/src/System/";
        $fichero = $ruta . "$clase.php";
    }
    else{
        $ruta = RUTABASE . "/src/Entities/ADO/";
        $fichero = $ruta . "$clase.php";
    }

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {
        throw new Exception("La clase $clase no se ha encontrado.");
    }
});



