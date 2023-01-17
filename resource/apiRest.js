

/**
 * En este archivo irán todas las llamadas a la API
 */

/* Generales */
function API_autorization(username, token) {

    let obj={
            controlador:"Token",
            metodo:"compruebaToken",
            user: username,
            token: token
    }

    let headers = new Headers();
        headers.append("Content-type", "application/json");
    return new Promise(function(resolve, reject){
        fetch(API_url(),{
            method: "POST",
            headers: headers,
            body: JSON.stringify(obj)
        })
        .then(function(resp){
            if(resp.ok)
                resp.json()
                    .then(function(data){
                        resolve({datos: data})
                    })
                    .catch(function(er){
                        console.error("ERROR: " + er);
                    })
        })
        .catch(function(er){
            reject({error: er})
        })
    })

    
}

function API_url() {
    var url = window.location.origin+"/server/public/";
    return url;
}


function llamada_fetch(obj, method, headers){    

    return new Promise(function(resolve, reject){
        fetch(API_url(), {
            method: method,
            headers: headers,
            body: JSON.stringify(obj)
        })
        .then(function(resp){
            if(resp.ok)
                resp.json()
                    .then(function(data){
                       resolve({datos: data})
                    })
                    .catch(function(er){
                        console.error("ERROR: " + er);
                    })
        })
        .catch(function(er){
           reject({error: er})
        })
    })
    
}