<?php


/**
 * obtenerResultadoConsulta
 *
 * @param  mixed $query
 * @return array|bool
 */
function obtenerResultadoConsulta($query){
    if ($query === FALSE) 
        return $query;    
    else {
        $res = array();
        if (mysqli_num_rows($query) >= 0) {
            while ($fila = $query->fetch_object()) {
                $res[] = $fila;
            }
        } 
        else {
            return false;
        }
        return $res;
    }
}