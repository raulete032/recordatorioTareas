<?php
include_once RUTABASE."/cargaClases.php";
include_once RUTABASE."/funciones.php";

class Token{


    public $modelo;
    public $db;
    public $error;


    function __construct($db=null){

        $this->modelo= new TokenModel();
        $this->error="";

        if(is_null($db))
            $this->db= (new DatabaseConnector())->getConnection();
        else
            $this->db= $db;
        
    }





    /**
     * Función que crea e inserta el token en la BDD
     */
    function creaToken($input){

        $token="";
        //Genero nº aleatorios del 48 al 94
        for($i=0;$i<200;){
            $num= rand(48, 94);
            if($this->numValidos($num)){
                $token.= chr($num);
                $i++;
            }           
        }

        $sql= "INSERT INTO token (id_nick, token, expira)
                VALUES (".
                    mysqli_real_escape_string($this->db, intval($input['idNick'])) . ",'".
                    mysqli_real_escape_string($this->db, $token) ."',"."
                    date_add(CURRENT_TIMESTAMP, INTERVAL 30 MINUTE))";

        $insert= mysqli_query($this->db, $sql);

        $id= mysqli_insert_id($this->db);
        $nick= $input['idNick'];
        $sql = "UPDATE token
                    SET baja = 1
                    WHERE id_token <> $id AND id_nick= $nick";
        $update= mysqli_query($this->db, $sql);

        if($insert && $update){
            return $token;
        }
        else
            return 0;        
    }


    private function numValidos($num){
        $sw=false;
        if($num>=48 && $num<=57)
            $sw=true;
        if($num>=65 && $num<=90)
            $sw=true;
        if($num>=97 && $num<=122)
            $sw=true;
        
        return $sw;
    }


    /**
     * Función que hace la consulta a la BDD para comprobar si existe el token
     */
    function compruebaToken($where){

        $sql= "SELECT * 
                    FROM token
                    WHERE " . $where;

        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }






    /**
     * Función que devuelve la fecha de expiración del token
     */
    function compruebaExpiracion($where){
        $sql= "SELECT expira
                    FROM token
                    WHERE baja = 0 AND id_nick= " . $where;
        
        $consulta= mysqli_query($this->db, $sql);

        return obtenerResultadoConsulta($consulta);
    }



    /**
     * Función que hace que de de baja el token porqué ha expirado
     */
    function expiraToken($where){
        $sql= "UPDATE token
                    SET baja = 1 AND id_nick= " . $where;

        $update= mysqli_query($this->db, $sql);

        return $update;
    }


}




    