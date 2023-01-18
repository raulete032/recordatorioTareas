<?php
include_once "../cargaClases.php";

$recibido= file_get_contents('php://input'); //aquí tengo lo que me llega del body del FETCH

$recibido= json_decode($recibido);

$db= (new DatabaseConnector())->getConnection();


switch($recibido->controlador){

    case "Usuarios": $controller= new UsuarioController($recibido->metodo, $db, "POST");
        break;
    case "Token": $controller= new TokenController($recibido->metodo, $db, "POST");
        break;
    case "Eventos": $controller= new EventosController($recibido->metodo, $db, "POST");
        break;
}


$controller->processRequest();