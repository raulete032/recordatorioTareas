var headers= new Headers();
    headers.append("Content-type", "application/json");

//**AddEventListener */
document.getElementById('btnRegistrarse').addEventListener('click', registrarse);
document.getElementById('btnIniciarSesion').addEventListener('click', iniciarSesion);




function registrarse(){
    document.getElementById('errorRegistro').innerHTML="";

    let nick= document.getElementById('signNick').value;
    let pass= document.getElementById('signPass').value;
    let pass2= document.getElementById('signPass2').value;
    let email= document.getElementById('signEmail').value;
    var obj={
            controlador: "Usuarios",
            metodo: "comprueba"
    };

    if((pass === pass2) && nick!="" && pass!="" && email!=""){ //las pass soon iguales, el nick, la pass y el email NO están vacíos
        obj.nick= nick;
        obj.pass= pass;
        obj.email= email;


        llamada_fetch(obj, "POST", headers)
            .then(function(data){
                if(data.datos.sResultado=="OK"){//puedo registrarlo
                    obj.metodo= "registra";
                    llamada_fetch(obj, "POST", headers)
                        .then(function(data){
                            if(data.datos.sResultado=="OK")
                                document.getElementById('errorRegistro').innerHTML="Registrado correctamente";
                                vaciaFormRegistro();
                                borraMensaje();
                        })
                }
                else{
                    document.getElementById('errorRegistro').innerHTML="Ese nick ya está en uso";
                }
            })
    }
    else{
        let error= document.getElementById('errorRegistro');
        if(pass!=pass2 && pass!="")
            error.innerHTML+= "Las contraseñas no coinciden<br>";
        if(nick=="")
            error.innerHTML+= "Debes introducir un nick<br>";
        if(pass=="")
            error.innerHTML+= "Debes introducir una contraseña<br>";
        if(email=="")
            error.innerHTML+= "Debes introducir un email";
    }
}





function iniciarSesion(){

    let nick= document.getElementById('logNick').value;
    let pass= document.getElementById('logPass').value;

    var obj={
        controlador: "Usuarios",
        metodo: "iniciaSesion"
    };

    if(nick!="" && pass!=""){
        obj.nick= nick;
        obj.pass= pass;
        llamada_fetch(obj, "POST", headers)
            .then(function(data){
                if(data.datos.sResultado=="OK"){
                    let idNick= data.datos.oResultado[0].id_nick;
                    let userName= data.datos.oResultado[0].nick;
                    creaCookie("userName", userName);
                    obj.controlador="Token";
                    obj.metodo="creaToken";
                    obj.idNick=idNick;

                    llamada_fetch(obj, "POST", headers)
                        .then(function(data){
                            if(data.datos.sResultado=="OK"){
                                creaCookie("token", data.datos.oResultado);
                                creaCookie("sesion", obj.idNick);
                                window.location.href= window.location.origin+"/html/calendario.html";
                            }
                        })
                }
                else{
                    document.getElementById('errorInicioSesion').innerHTML= data.datos.sError;
                }
            })
    }
    else
        document.getElementById('errorInicioSesion').innerHTML="Debes introducir el nick y la contraseña";
}

 





function vaciaFormRegistro(){
    document.getElementById('signNick').value='';
    document.getElementById('signPass').value='';
    document.getElementById('signPass2').value='';
    document.getElementById('signEmail').value='';
}



function borraMensaje(){
    setTimeout(()=>{
        document.getElementById('errorRegistro').innerHTML="";
    }, 5000);
}