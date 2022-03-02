function checkPsw(){
    re = /.*([a-z]+.*[A-Z|0-9]+)|([A-Z|0-9]+.*[a-z]+).*/;
    
    if(!re.test(document.getElementById("reg-psw").value)){
        document.getElementById("register").disabled = true;
        document.getElementById("message").style.color = "red";
        document.getElementById("message").innerHTML = "La password deve contenere almeno un carattere alfabetico minuscolo, ed almeno un altro carattere che sia alfabetico maiuscolo oppure un carattere numerico.";
    }
    else{
        document.getElementById("message").innerHTML= "";
        document.getElementById("register").disabled = false;
    }
}

function checkUser(){
    if(!document.getElementById("reg-user").value.includes("@") || !document.getElementById("reg-user").value.includes(".")){
        document.getElementById("register").disabled = true;
        document.getElementById("message").style.color = "red";
        document.getElementById("message").innerHTML = "Username non valido.";
    }
    else{
        document.getElementById("message").innerHTML= "";
        document.getElementById("register").disabled = false;
    }
}

function matchPsw(){

    if(document.getElementById("reg-psw").value != document.getElementById("reg-psw2").value){
        document.getElementById("register").disabled = true;
        document.getElementById("message").style.color = "red";
        document.getElementById("message").innerHTML = "Le due password non coincidono.";
    }
    else{
        document.getElementById("message").innerHTML= "";
        document.getElementById("register").disabled = false;
    }
    
}

function addSeatStyle(colonne, percentuale){
    var styleSheet = document.styleSheets[2];
    var rule = '.seat:nth-child('+colonne+') {margin-right: '+percentuale+'%;}';
    styleSheet.insertRule(rule, 0);
}
