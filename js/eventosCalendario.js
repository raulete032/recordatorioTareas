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


    let obj= {
            controlador: "Eventos",
            metodo: "dameEventos",
            limiteSuperior: limiteSuperior,
            limiteInferior: limiteInferior
    }

    //Quiero TODOS los eventos que se vayan a dar en ese mes (empiecen o terminen)

    llamada_fetch(obj, "POST", headers)
        .then(function(data){
            let datos= data.datos.oResultado;

            distribuyeEventos(datos);

            let aux=0;


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
    span2.appendChild(span);

    parent.appendChild(span2);


    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}


