var headers= new Headers();
    headers.append("Content-type", "application/json");

const meses= ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];


comprueba();


/**
 * Función que comprueba el token
 */
function comprueba(){
    
    let token= dameCookie("token");
    let idNick= dameCookie("sesion");

    API_autorization(idNick, token)
        .then(function(data){
            if(data.datos.sResultado=="OK"){
                document.getElementById("container").style.display="block";
                document.getElementById('nombreUsuario').innerHTML= dameCookie("userName");
            }        
            else //no es correcto, redirige al login
                window.location.href= window.location.origin;
        })
        .catch(function(er){
            console.error("ERROR: " + er);
        })   
}



var fecha= new Date();

let spanPrev= creaNodo("span", "chevron_left");
    spanPrev.className= "col-4 material-icons";
    spanPrev.addEventListener('click', prevMonth);
let mes= creaNodo("span", meses[fecha.getMonth()] + " " + fecha.getFullYear());
    mes.className= "col-4 titulo-mes";
    mes.dataset.value= meses[fecha.getMonth()] + " " + fecha.getFullYear();
let spanNext= creaNodo("span", "chevron_right");
    spanNext.className= "col-4 material-icons"; 
    spanNext.addEventListener('click', nextMonth)

let legend= document.querySelector("#mes");

legend.appendChild(spanPrev);
legend.appendChild(mes);
legend.appendChild(spanNext);



let tbody= generaMesCalendario(fecha);
document.getElementsByClassName("table")[0].appendChild(tbody);


pideEventos();


/**
 * Función que se ejecuta al pulsar sobre mes ANTERIOR
 */
function prevMonth(){

    let fecha= new Date(JSON.parse(dameCookie("fecha")));

    fecha= new Date(fecha.setMonth(fecha.getMonth()-1));

    creaCookie("fecha", JSON.stringify(fecha));

    document.getElementsByClassName("table")[0].appendChild(generaMesCalendario(fecha));

    document.getElementsByClassName('titulo-mes')[0].textContent= meses[fecha.getMonth()] + " " + fecha.getFullYear();
    
    mes.dataset.value= meses[fecha.getMonth()] + " " + fecha.getFullYear();
    pideEventos();
}


/**
 * Función que se ejecuta al pulsar sobre mes SIGUIENTE
 */
function nextMonth(){
    let fecha= new Date(JSON.parse(dameCookie("fecha")));

    fecha= new Date(fecha.setMonth(fecha.getMonth()+1));

    creaCookie("fecha", JSON.stringify(fecha));

    document.getElementsByClassName("table")[0].appendChild(generaMesCalendario(fecha));

    document.getElementsByClassName('titulo-mes')[0].textContent= meses[fecha.getMonth()] + " " + fecha.getFullYear();

    mes.dataset.value= meses[fecha.getMonth()] + " " + fecha.getFullYear();
    pideEventos();

}


/**
 * Función que añadirá un evento al calendario
 */
function anadeEvento(){

    let hoy= new Date();
    let diaHoy= hoy.getDate();
    let mesHoy= hoy.getMonth();
    let anoHoy= hoy.getFullYear();
    hoy= new Date(anoHoy, mesHoy, diaHoy);

    let diaPulsado= this.getElementsByClassName("numDia")[0].textContent;
    let valueMesAno= document.getElementsByClassName("titulo-mes")[0].dataset.value;
    let mesPulsado= valueMesAno.split(" ")[0];
    mesPulsado= meses.indexOf(mesPulsado);
    let anoPulsado= valueMesAno.split(" ")[1];

    let fechaPulsada= new Date(anoPulsado, mesPulsado, diaPulsado);

    let modalBody= document.getElementsByClassName('modal-body')[0];
    document.getElementById("guardarEvento").style.display="none";

    if(hoy<=fechaPulsada){ //solo se podrán añadir eventos en el FUTURO
        modalBody.innerHTML= "AÑADE EVENTO";
        document.getElementById("guardarEvento").style.display="block";
    }
    else{
        modalBody.innerHTML= "NO PUEDES AÑADIR EVENTO EN EL PASADO";
    }
}



function guardarEvento(){

    let aux=0;


    location.reload();
}


