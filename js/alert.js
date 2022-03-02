function findGetParameter(parameterName) {
    var result = null,
    tmp = [];
    location.search
    .substr(1)
    .split("&")
    .forEach(function (item) {
             tmp = item.split("=");
             if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
             });
    return result;
}

function showAlert(){
    var parameterValue = findGetParameter("msg");
    
    switch(parameterValue){
        case "LoginFailed":
            alert("ATTENZIONE\n\nUsername o password errati!");
            window.history.replaceState({}, document.title, "./index.php");
            document.getElementById('login-button').click();
            break;
        case "RegistrationFailed":
            alert("Registrazione fallita.\n\nRiprova con un altro Username.");
            window.history.replaceState({}, document.title, "./index.php");
            document.getElementById('new-register').click();
            break;
        case "InvalidPsw":
            alert("Registrazione fallita.\n\nPassword non valida: la password deve contenere almeno un carattere alfabetico minuscolo, ed almeno un altro carattere che sia alfabetico maiuscolo oppure un carattere numerico.");
            window.history.replaceState({}, document.title, "./index.php");
            document.getElementById('new-register').click();
            break;
        case "RegistrationCompleted":
            alert("Registrazione completata.");
            window.history.replaceState({}, document.title, "./my-home.php");
            //document.getElementById('login-button').click();
            break;
        case "SessionTimeOut":
            alert("Sessione scaduta!\n\nEffettua di nuovo il login.");
            window.history.replaceState({}, document.title, "./index.php");
            document.getElementById('login-button').click();
            break;
        case "PurchaseCompleted":
            alert("Acquisto completato con successo.");
            window.history.replaceState({}, document.title, "./my-home.php");
            break;
        case "PurchaseFailed":
            alert("Impossibile procedere con l'acquisto.\n\nUno o più posti da te selezionati sono stati prenotati o acquistati da altri clienti.");
            window.history.replaceState({}, document.title, "./my-home.php");
            break;
        case "EmptyCart":
            alert("ATTENZIONE!\n\nNon hai ancora effettuato prenotazioni, seleziona uno o più posti per procedere all'acquisto.");
            window.history.replaceState({}, document.title, "./my-home.php");
            break;
    }
    
}


