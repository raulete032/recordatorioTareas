

const meses= ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];

var fecha= new Date();

let spanPrev= creaNodo("span");
    spanPrev.className= "col-4 fa-solid fa-angle-left";
    spanPrev.addEventListener('click', prevMonth);
let mes= creaNodo("span", meses[fecha.getMonth()] + " " + fecha.getFullYear());
    mes.className= "col-4 titulo-mes";
let spanNext= creaNodo("span");
    spanNext.className= "col-4 fa-solid fa-angle-right"; 
    spanNext.addEventListener('click', nextMonth)

let legend= document.querySelector("#mes");

legend.appendChild(spanPrev);
legend.appendChild(mes);
legend.appendChild(spanNext);



let tbody= generaMesCalendario(fecha);
document.getElementsByClassName("table")[0].appendChild(tbody);






function prevMonth(){

    let fecha= new Date(JSON.parse(dameCookie("fecha")));

    fecha= new Date(fecha.setMonth(fecha.getMonth()-1));

    creaCookie("fecha", JSON.stringify(fecha));

    document.getElementsByClassName("table")[0].appendChild(generaMesCalendario(fecha));

    document.getElementsByClassName('titulo-mes')[0].textContent= meses[fecha.getMonth()] + " " + fecha.getFullYear();
}


function nextMonth(){
    let fecha= new Date(JSON.parse(dameCookie("fecha")));

    fecha= new Date(fecha.setMonth(fecha.getMonth()+1));

    creaCookie("fecha", JSON.stringify(fecha));

    document.getElementsByClassName("table")[0].appendChild(generaMesCalendario(fecha));

    document.getElementsByClassName('titulo-mes')[0].textContent= meses[fecha.getMonth()] + " " + fecha.getFullYear();


}