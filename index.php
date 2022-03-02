<?php
    include 'php/auth/secure.php';
    include 'php/auth/checkSession.php';
    include 'php/dbAccess.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link href="css/block.css" rel="stylesheet" type="text/css">
    <link href="css/block2.css" rel="stylesheet" type="text/css">
    <link href="css/plane.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/func.js"></script>
    <script type="text/javascript" src="js/alert.js"></script>
</head>

<noscript>
    <div style="hight:auto">
        <img style="margin: auto; width: 10%; display: block;" src="images/warn.png" alt="warning image">
        <h3 align="center">Non hai JavaScript abilitato. </h3>
        <h3 align="center"> Il sito potrebbe non funzionare correttamente, riabilitalo e
            <a href="index.php">riprova.</a>
        </h3>
    </div>
</noscript>

<script type="text/javascript">
    //Set a Cookie
    document.cookie="test=testcookie";
    //Check if cookie exists
    cookiesEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
    if (!cookiesEnabled){
        document.write("<div style=\"hight:auto\">"+
                       "<img style=\"padding-top: 15%; margin: auto; width: 10%; display: block;\" src=\"images/warn.png\" alt=\"warning image\">"+
                           "<h3 align=\"center\">Non hai i Cookie abilitati. </h3>"+
                           "<h3 align=\"center\"> Abilitali nelle impostazioni del tuo browser e "+
                               "<a href=\"index.php\">riprova.</a>"+
                           "</h3>"+
                       "</div>"+
                       "<style type=\"text/css\">"+
                            ".header, .topnav, .footer, .row {display: none;}"+
                       "</style>");
    }
    document.cookie = "test= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
</script>

<?php
    $states = getSeatsState();
    $a = array();
    while($r=mysqli_fetch_array($states, MYSQLI_ASSOC))
    array_push($a,$r['id'],$r['stato']);
?>

<body onLoad="showAlert()">
    <div class="header">
        <h1>Fly PoliTO</h1>
        <h3>Prenota il tuo volo.</h3>
    </div>
    <div class="topnav">
        <a style="float:right; width:auto;"
            onclick="document.getElementById('id01').style.display='block'">Accedi</a>
    </div>
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>Dettaglio posti:</h2>
                <ul style="text-align:left">
                    <li>Posti Totali:
                        <?php
                            echo ($righe*$colonne);
                        ?>
                    </li>
                    <li>Posti Liberi:
                        <?php                            
                            $counts = array_count_values($a);
                            $busy = $counts['busy'];
                            $booked = $counts['booked'];
                            
                            if (!$busy)
                                $busy = 0;
                            if (!$booked)
                                $booked = 0;
                                
                            echo ($righe*$colonne)-($busy+$booked);
                        ?>
                    </li>
                    <li>Posti Prenotati:
                        <?php
                            echo $booked;
                        ?>
                    </li>
                    <li>Posti Occupati:
                        <?php
                            echo $busy;
                        ?>
                    </li>
                </ul>
            </div>
            <div class="card">
                <h3>Accedi per vedere le tue prenotazioni</h3>
                <button style="float:center" id="login-button"
                    onclick="document.getElementById('id01').style.display='block'">Accedi</button>
            </div>
        </div>
        <div class="rightcolumn">
            <div class="card">
                <h1>Posti Disponibili</h1>
                <h5>Ultimo aggiornamento: <?php echo date("d-m-Y H:i:s", strtotime('+2 hours')); ?></h5>
                <?php
                    echo "<div class=\"plane\" style=\"max-width:".$px."px;\">";
                ?>
                <!--<div class="plane">-->
                    <div class="cockpit">
                        <h1>Head</h1>
                    </div>
                    <div class="exit exit--front fuselage">
                    </div>
                    <ol class="cabin fuselage">
                        <?php
                            
                            echo "<script type=\"text/javascript\">addSeatStyle(".$group.", ".$perc.");</script>";
                                
                            $c = count($a);

                            for ($j = 1; $j <= $righe; $j++){
                                echo "<li class=\"row row--1\"><ol class=\"seats\" type=\"A\">";
                                $ch = 'A';
                                for ($i = 1; $i <= $colonne; $i++){
                                    $state="free";
                                    for ($k=0; $k<$c; $k+=2){
                                        if($a[$k] == $ch.$j){
                                            $state=$a[$k+1];
                                            break;
                                        }
                                    }
                                    switch($state){
                                        case 'busy':
                                            echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\">".
                                                    "<input type=\"checkbox\" disabled id=".$ch.$j." />".
                                                    "<label for=".$ch.$j.">".$ch.$j."</label>".
                                                  "</li>";
                                            break;
                                        case 'booked':
                                        echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\">".
                                                    "<input type=\"checkbox\" id=".$ch.$j." />".
                                                    "<label class=\"booked\" for=".$ch.$j.">".$ch.$j."</label>".
                                                 "</li>";
                                            break;
                                        default:
                                        echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\">".
                                                    "<input type=\"checkbox\" id=".$ch.$j." />".
                                                    "<label for=".$ch.$j.">".$ch.$j."</label>".
                                                 "</li>";
                                    }
                                    $ch++;
                                }
                                echo "</ol></li>";
                            }
                        ?>
                    </ol>
                    <div class="exit exit--back fuselage">
                    </div>
                    <div class="cockpit1">
                        <h1>Tail</h1>
                    </div>
                </div>
                <div>
                <h3>Descrizione:</h3>
                <ul>
                    <li>I posti in verde sono ancora disponibili;</li>
                    <li>I posti in giallo sono quelli da te prenotati;</li>
                    <li>I posti in rosso sono gi&agrave; stati acquistati da altri passeggeri;</li>
                    <li>I posti in arancione sono stati prenotati ma non ancora acquistati.</li>
                </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <h6>Realizzato da:</h6>
        <h6>Pietro Basci</h6>
    </div>
    
    
    <div id="id01" class="modal">
        <form class="modal-content animate" action="php/auth/login.php" method="POST">
            <div class="imgcontainer">
                <span class="close" onclick="document.getElementById('id01').style.display='none'" title="Close Modal">&times;</span>
                <img src="images/login.png" alt="Avatar" class="avatar">
            </div>
            <div class="container">
                <label for="user"><b>Username</b></label>
                <input type="email" placeholder="Inserisci Username" id="user" name="user" maxlength="50" autofocus required>
                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Inserisci Password" id="psw" name="psw" maxlength="25" required>
                <button type="submit">Accedi</button>
            </div>
            <div class="container" style="background-color:#dcf0ff; font-weight:5">
                <span>Oppure <a href="#" id="new-register" onclick="document.getElementById('id02').style.display='block'">registrati</a></span>
            </div>
        </form>
    </div>

    <div id="id02" class="modal">
        <form class="modal-content animate" action="php/auth/registration.php" method="POST">
            <div class="imgcontainer">
                <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
                <img src="images/registration.png" alt="Avatar" class="avatar">
            </div>
            <div class="container">
                <label for="name" style="margin: 24px 0"><b>Nome</b></label>
                <input type="text" placeholder="Inserisci Nome" id="name" name="name" maxlength="50" autofocus required>
                <label for="surname" style="margin: 24px 0"><b>Cognome</b></label>
                <input type="text" placeholder="Inserisci Cognome" id="surname" name="surname" maxlength="50" required>
                <label for="user"><b>Username</b></label>
                <input type="email" placeholder="Inserisci Username" id="reg-user" name="user" maxlength="50" required>
                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Inserisci Password" id="reg-psw" name="psw" maxlength="25" onKeyUp="checkPsw()" onBlur="matchPsw()" required>
                <label for="psw2"><b>Conferma Password</b></label>
                <input type="password" placeholder="Reinserisci Password" id="reg-psw2" name="psw2" maxlength="25" onKeyUp="matchPsw()" required>
                <span id='message'></span>
                <button type="submit" id="register">Registrati</button>
            </div>
            <div class="container" style="background-color:#dcf0ff; font-weight:5">
                <span><a href="#" onclick="document.getElementById('id02').style.display='none'">Annulla</a></span>
            </div>
        </form>
    </div>

    
    <script type="text/javascript">
        // Get the modal
        var modal = document.getElementById('id01');
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal)
                modal.style.display = "none";
        }
    </script>
    
    <script type="text/javascript">
        re = /\d+[A-Z]*/;
        document.onclick = function(event) {
            if(event.target.type=='checkbox' && re.test(event.target.id)){
                event.target.checked = false;
                alert("ATTENZIONE!\n\nAccedi per prenotare.");
            }
        }
    </script>

    
</body>

</html>
