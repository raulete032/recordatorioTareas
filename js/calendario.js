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

    location.reload();
}



/**
 * Función que pintará la info del evento en el menú lateral
 */
function infoEvento(){

    let idEvento= this.dataset.evento;

    let obj={
            controlador: "Eventos",
            metodo: "infoEvento",
            idEvento: idEvento
    }

    llamada_fetch(obj, "POST", headers)
        .then(function(data){
            muestraInfoMenuLateral(data.datos.oResultado[0]);
        })
        .catch(function(er){
            console.log("ERROR: " + er);
        })
}


function muestraInfoMenuLateral(datos){

    let nombreEvento= datos.nombre_evento;
    let descripcion= datos.descripcion;
    let fechaInicio= datos.fecha_inicio;
    let fechaFin= datos.fecha_fin;
    let tipoEvento= datos.id_tipo_evento;
    let nombreTipoEvento= datos.nombre_tipo_evento;

    fechaInicio= formateaFecha(fechaInicio);
    fechaFin= formateaFecha(fechaFin);

    document.getElementById("nombreEvento").innerHTML= nombreEvento;
    document.getElementById("descripcionEvento").innerHTML= descripcion;
    document.getElementById("fechaInicioEvento").innerHTML= "Fecha inicio: " + fechaInicio;
    document.getElementById("fechaFinEvento").innerHTML= "Fecha fin: " + fechaFin;
    document.getElementById("nombreEventoCabecera").innerHTML= "("+ nombreTipoEvento +")"
    document.getElementById("iconoEventoInfo").innerHTML= logoEvento[tipoEvento];


}





/**
 * Desde aquí se pedirán y cargarán en el calendario TODOS los eventos
 */



logoEvento={
    1: "cake",
    2: "groups",
    3: "flight",
    4: "beach_access"
};



var limiteSuperior;
var limiteInferior;
function pideEventos(){

    let mesAno= document.getElementsByClassName('titulo-mes')[0].textContent;
    let mes= mesAno.split(" ")[0];
    mes= meses.indexOf(mes)+1;
    let ano= mesAno.split(" ")[1];
    
    limiteSuperior= new Date(ano, mes-1, 1);
    limiteInferior= new Date(ano, mes-1, 1);
    let sw=true;
    for(let i=1;i<32 && sw; i++){
        limiteInferior= new Date(limiteInferior.setDate(limiteInferior.getDate()+1));

        if((mes-1)!= limiteInferior.getMonth()){
            sw=false;
            limiteInferior= new Date(limiteInferior.setDate(limiteInferior.getDate()-1));
        }
    }

    //Creo la fecha de inicio (aaaa-mm-01) y la fecha fin (aaaa-mm-dd) (dd-> último día de ese mes)
    limiteSuperior= limiteSuperior.getFullYear()+"-"+(limiteSuperior.getMonth()+1)+"-"+limiteSuperior.getDate();
    limiteInferior= limiteInferior.getFullYear()+"-"+(limiteInferior.getMonth()+1)+"-"+limiteInferior.getDate();

    let idNick= dameCookie("sesion");
    let obj= {
            controlador: "Eventos",
            metodo: "dameEventos",
            limiteSuperior: limiteSuperior,
            limiteInferior: limiteInferior,
            idNick: idNick
    }

    //Quiero TODOS los eventos que se vayan a dar en ese mes (empiecen o terminen)

    llamada_fetch(obj, "POST", headers)
        .then(function(data){
            let datos= data.datos.oResultado;

            distribuyeEventos(datos);

        })
}

/**
 * En esta función se pitarán en el calendario los eventos de ese mes
 */
function distribuyeEventos(datos){

    let dias= document.querySelectorAll('.numDia');
    let evento=null;

    for(let i=0;i<datos.length;i++){

        evento= datos[i];

        let inicioEvento= new Date((datos[i].fecha_inicio).split(" ")[0]);
        let finEvento= new Date((datos[i].fecha_fin).split(" ")[0]);

        limiteSuperior= new Date(limiteSuperior);
        limiteInferior= new Date(limiteInferior);

        let diaIniEvento= inicioEvento.getDate();
        let diaFinEvento;

        if(finEvento>limiteInferior)
            diaFinEvento= limiteInferior.getDate();
        else
            diaFinEvento= finEvento.getDate();

        //Ahora debo comprobar si ha empezado en el mes anterior

        if(inicioEvento<limiteSuperior) //ha empezado en el mes anterior
            diaIniEvento= 1;
        else
            diaIniEvento= inicioEvento.getDate();

        
        for(let j=0;j<dias.length;j++){

            let dia= parseInt(dias[j].textContent); //obtengo el día del calendario

            if(diaIniEvento<=dia && diaFinEvento>=dia){
                let parent= dias[j].parentNode;
                pintaEvento(parent, evento);
            }
        }
    }
}




function pintaEvento(parent, evento){
    
    let span= creaNodo("span", logoEvento[evento.id_tipo_evento]);  
        span.className= "material-icons material-icons-evento";
        span.setAttribute("data-bs-toggle", "tooltip");
        span.setAttribute("data-bs-placement", "top");
        span.setAttribute("data-bs-custom-class", "custom-tooltip");
        span.setAttribute("data-bs-title", evento.nombre_evento);

    let span2= creaNodo("span");
        span2.setAttribute("data-bs-toggle", "offcanvas");
        span2.setAttribute("data-bs-target", "#offcanvas");
        span2.setAttribute("data-evento", evento.id_evento);
    span2.appendChild(span);

    span2.addEventListener('click', infoEvento);
    

    parent.appendChild(span2);


    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}


