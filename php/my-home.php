<?php
    include 'auth/secure.php';
    include 'auth/sessions.php';
    include 'dbAccess.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link href="../css/block.css" rel="stylesheet" type="text/css">
    <link href="../css/block2.css" rel="stylesheet" type="text/css">
    <link href="../css/plane.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../js/sectionUpdate.js"></script>
    <script type="text/javascript" src="../js/alert.js"></script>
    <script type="text/javascript" src="../js/func.js"></script>
</head>

<noscript>
    <div style="hight:auto">
        <img style="margin: auto; width: 10%; display: block;" src="../images/warn.png" alt="warning image">
            <h3 align="center">Non hai JavaScript abilitato. </h3>
            <h3 align="center"> Il sito potrebbe non funzionare correttamente, riabilitalo e
                <a href="my-home.php">riprova.</a>
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
                       "<img style=\"padding-top: 15%; margin: auto; width: 10%; display: block;\" src=\"../images/warn.png\" alt=\"warning image\">"+
                       "<h3 align=\"center\">Non hai i Cookie abilitati. </h3>"+
                       "<h3 align=\"center\"> Abilitali nelle impostazioni del tuo browser e "+
                       "<a href=\"my-home.php\">riprova.</a>"+
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
    array_push($a,$r['id'],$r['stato'],$r['username']);
?>
    
<body onLoad="showAlert();">
    <div class="header">
        <h1>Fly PoliTO</h1>
        <h3>Prenota il tuo volo.</h3>
    </div>
    <div class="topnav">
        <a style="float:right; width:auto;"
            onclick="document.getElementById('id01').style.display='block'">Esci</a>
    </div>
    <div class="row">
        <div class="leftcolumn">
            <div class="card" id="status">
                <h2>Dettaglio posti:</h2>
                <ul style="text-align:left">
                    <li>Posti Totali:
                        <?php
                            echo ($righe*$colonne);
                        ?>
                    </li>
                    <li id="n-free">Posti Liberi:
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
                    <li id="n-books">Posti Prenotati:
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
                <button style="float:center" id="update"
                    onclick="location.href='my-home.php'">Aggiorna dati</button>
            </div>
            <div class="card" id="my-book">
                <h2>Le tue prenotazioni:</h2>
                <ul style="text-align:left" id="my-book-list">
                    <?php
                        $user = "";
                        
                        if(isset($_SESSION['s266004user']))
                            $user = $_SESSION['s266004user'];

                        $booked = array();
                        $c = count($a);

                        for($k=0; $k<$c; $k+=3)
                            if($a[$k+2] == $user && $a[$k+1] == 'booked')
                                array_push($booked,$a[$k]);

                        $_SESSION['s266004books'] = $booked;
                        
                        $count = count($booked);
                    
                        if($count == 0)
                            echo "Non hai ancora effettuato prenotazioni";
                        
                        for ($i=0; $i<$count; $i++)
                            echo "<li id=b".$booked[$i].">".$booked[$i]."</li>";
                    ?>
                </ul>
                <form action="buy.php" method="POST">
                    <button type="submit">Acquista</button>
                </form>
            </div>
            <div class="card" id="my-purchase">
                <h2>I tuoi acquisti:</h2>
                <ul style="text-align:left">
                    <?php
                        $busy = array();

                        for($k=0; $k<$c; $k+=3)
                            if($a[$k+2] == $user && $a[$k+1] == 'busy')
                                array_push($busy,$a[$k]);
                        
                        $count = count($busy);

                        if($count == 0)
                            echo "Non hai ancora effettuato acquisti";
                        
                        for ($i=0; $i<$count; $i++)
                            echo "<li>".$busy[$i]."</li>";
                    ?>
                </ul>
            </div>
            <div class="card">
                <h2>Account:</h2>
                <span style="word-wrap: break-word"><?php echo stripslashes($user) ?></span>
                <button style="float:center;"
                    onclick="document.getElementById('id01').style.display='block'">Esci</button>
            </div>
        </div>
        <div class="rightcolumn">
            <div class="card" id="main">
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

                            for ($j = 1; $j <= $righe; $j++){
                                echo "<li class=\"row row--1\"><ol class=\"seats\" type=\"A\">";
                                $ch = 'A';
                                for ($i = 1; $i <= $colonne; $i++){
                                    $mine=false;
                                    $state="free";
                                    for ($k=0; $k<$c; $k+=3){
                                        if($a[$k] == $ch.$j){
                                            if($a[$k+2] == $user)
                                                $mine=true;
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
                                            if($mine)
                                                echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\" id=P".$ch.$j.">".
                                                        "<input type=\"checkbox\" checked onclick=\"postAjax('cancelBook.php', '".$ch.$j."'); \" id=".$ch.$j." />".
                                                        "<label class=\"booked\" for=".$ch.$j.">".$ch.$j."</label>".
                                                     "</li>";
                                            else
                                                echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\" id=P".$ch.$j.">".
                                                        "<input type=\"checkbox\" onclick=\"postAjax('book.php', '".$ch.$j."'); \" id=".$ch.$j." />".
                                                        "<label class=\"booked\" for=".$ch.$j.">".$ch.$j."</label>".
                                                     "</li>";
                                            break;
                                        default:
                                            echo "<li class=\"seat\" style=\"flex: 0 1 ".$perc."%\" id=P".$ch.$j.">".
                                                    "<input type=\"checkbox\" onclick=\"postAjax('book.php', '".$ch.$j."'); \" id=".$ch.$j." />".
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
        <form class="modal-content animate" action="auth/logout.php" method="POST">
            <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                <img src="../images/logout.png" alt="Avatar" class="avatar">
            </div>
            <div class="container">
                <label for="user"><h3>Esci dalla tua pagina personale</h3></label>
                <button type="submit">Esci</button>
            </div>
        </form>
    </div>
     
    <script type="text/javascript">
        // Get the modal
        var modal = document.getElementById('id01');
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>

</html>
