
var filtros= document.querySelectorAll('li[data-filter');

for(let i=0;i<filtros.length;i++)
    filtros[i].addEventListener('click', filtroProyectos);