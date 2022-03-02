var req;
var id;
var seatid;


function ajaxRequest(){
    try{ // Non IE Browser?
        var request = new XMLHttpRequest();
    }catch(e1){ // No
        try{ // IE 6+?
            request = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(e2){ // No
            try{ // IE 5?
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(e3){ // No AJAX Support
                request = false;
            }
        }
    }
    return request;
}


/* Handler definition */
function handler(){
    if(req.readyState == 4 && (req.status == 0 || req.status == 200)){
        document.getElementById(id).innerHTML = req.responseText;
        alert("Operazione eseguita con successo.");
        updateCart(seatid);
    }
    else if(req.status == 409){
        document.getElementById(id).innerHTML = req.responseText;
        alert("Impossibile prenotare questo posto.\n\nIl posto da te selezionato è stato acquistato da un altro cliente.");
    }
    else if(req.status == 500){
        alert("ERRORE!\n\nImpossibile connettersi al DB. Riprova più tardi.");
    }
    else if(req.status == 401){
        window.location.replace("../index.php?msg=SessionTimeOut");
    }
}


function postAjax(url, content){
    id="P"+content;
    seatid = content;
    content = "seat=" + content;
    req = ajaxRequest();
    req.onreadystatechange = handler;
    req.open('POST', url, false);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(content);
}


function updateCart(id){
    if(document.getElementById(id).checked && document.getElementById("my-book-list").childElementCount != 0){
        document.getElementById("my-book-list").innerHTML += "<li id=b" + id + ">" + id + "</li>";
        $str = document.getElementById("n-free").innerHTML.substring(0,14);
        $num = document.getElementById("n-free").innerHTML.substring(14);
        $num = parseInt($num, 10) - 1;
        document.getElementById("n-free").innerHTML = $str + $num;
        $str = document.getElementById("n-books").innerHTML.substring(0,17);
        $num = document.getElementById("n-books").innerHTML.substring(17);
        $num = parseInt($num, 10) + 1;
        document.getElementById("n-books").innerHTML = $str + $num;
    }
    else if(document.getElementById(id).checked && document.getElementById("my-book-list").childElementCount == 0){
        document.getElementById("my-book-list").innerHTML = "<li id=b" + id + ">" + id + "</li>";
        $str = document.getElementById("n-free").innerHTML.substring(0,14);
        $num = document.getElementById("n-free").innerHTML.substring(14);
        $num = parseInt($num, 10) - 1;
        document.getElementById("n-free").innerHTML = $str + $num;
        $str = document.getElementById("n-books").innerHTML.substring(0,17);
        $num = document.getElementById("n-books").innerHTML.substring(17);
        $num = parseInt($num, 10) + 1;
        document.getElementById("n-books").innerHTML = $str + $num;
    }
    else{
        var element = document.getElementById("b"+id);
        element.parentNode.removeChild(element);
        if(document.getElementById("my-book-list").childElementCount == 0)
            document.getElementById("my-book-list").innerHTML += "Non hai ancora effettuato prenotazioni";
        $str = document.getElementById("n-free").innerHTML.substring(0,14);
        $num = document.getElementById("n-free").innerHTML.substring(14);
        $num = parseInt($num, 10) + 1;
        document.getElementById("n-free").innerHTML = $str + $num;
        $str = document.getElementById("n-books").innerHTML.substring(0,17);
        $num = document.getElementById("n-books").innerHTML.substring(17);
        $num = parseInt($num, 10) - 1;
        document.getElementById("n-books").innerHTML = $str + $num;

    }
}
