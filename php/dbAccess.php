<?php
    $dbHost = 'localhost';
    $dbUsername = 's266004';
    $dbPassword = 'ajadvegi';
    $dbName = 's266004';
    $righe = 10;
    $colonne = 6;
    $px = $colonne * 50;
    $perc = 100 / ($colonne + 1);
    $group = $colonne / 2;
    error_reporting(0);
    
    
    
    /* DB Connect */
    function dbConnect(){
        global $dbHost, $dbUsername, $dbPassword, $dbName;
        try{
            $conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
            if (!$conn)
                throw new Exception("Connection-Error");
            return $conn;
        } catch (Exception $e){
            alert($e->getMessage());
        }
    }
    
    
    /* Get Number of Busy seats */
    function getNumBusy($conn){
        $conn = dbConnect();
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT COUNT(*) FROM PRENOTAZIONI WHERE stato = 'busy';";
            $ris = mysqli_query($conn, $query);
            $ris = mysqli_fetch_array($ris)[0];
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    /* Get Number of Booked seats */
    function getNumBooked($conn){
        $conn = dbConnect();
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT COUNT(*) FROM PRENOTAZIONI WHERE stato = 'booked';";
            $ris = mysqli_query($conn, $query);
            $ris = mysqli_fetch_array($ris)[0];
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    /* Get Busy seats by user */
    function getBusy($user){
        $conn = dbConnect();
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT id FROM PRENOTAZIONI WHERE stato = 'busy' AND username = '".$user."';";
            $ris = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    /* Get Booked seats by user */
    function getBooked($user){
        $conn = dbConnect();
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT id FROM PRENOTAZIONI WHERE stato = 'booked' AND username = '".$user."';";
            $ris = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    
    /* Get Seats state */
    function getSeatsState(){
        $conn = dbConnect();
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT * FROM PRENOTAZIONI;";
            $ris = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    /* Get Seat state */
    function getSeatState($id){
        $conn = dbConnect();
        $id = sanitizeString($conn, $id);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT * FROM PRENOTAZIONI WHERE id='".$id."';";
            $ris = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    /* Check if seat is free */
    function checkFree($conn, $id){
        $id = sanitizeString($conn, $id);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "SELECT COUNT(*) FROM PRENOTAZIONI WHERE id = '".$id."' AND stato='booked';";
            $ris = mysqli_query($conn, $query);
            return ($ris==0)? true : false;
        } catch (Exception $e){
            alert($e->getMessage());
        }
    }
    
    
    /* Unset Booked seat */
    function unsetBooked($conn, $id){
        $id = sanitizeString($conn, $id);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "DELETE FROM PRENOTAZIONI WHERE id = '".$id."' AND stato='booked';";
            $ris = mysqli_query($conn, $query);
            return $ris;
        } catch (Exception $e){
            alert($e->getMessage());
        }
    }
    
    
    /* Cancel Book seat */
    function cancelBook($id, $user){
        $conn = dbConnect();
        $id = sanitizeString($conn, $id);
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            $query = "DELETE FROM PRENOTAZIONI WHERE id = '".$id."'  AND username='".$user."' AND stato='booked';";
            $ris = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    /* Set Booked seat */
    function setBooked($id, $user){
        $conn = dbConnect();
        $id = sanitizeString($conn, $id);
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            if (!validateSeat($id))
                throw new Exception("NotFound-Error");
            
            mysqli_autocommit($conn, false);
            mysqli_query($conn, "SELECT * FROM PRENOTAZIONI FOR UPDATE;");
            
            /* if booked, free seat */
            if (!checkFree($conn, $id))
                unsetBooked($conn, $id);

            $query = "INSERT INTO PRENOTAZIONI (id, stato, username) VALUES ('".$id."', 'booked', '".$user."');";
            $ris = mysqli_query($conn, $query);
            if(!$ris)
                throw new Exception("Booking-Error");
            
            mysqli_autocommit($conn, true);
            mysqli_close($conn);
            return $ris;
            
        } catch (Exception $e){
            mysqli_rollback($conn);
            mysqli_autocommit($conn, true);
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    /* Set Busy seats */
    function setBusy($user, $toBuy){
        $conn = dbConnect();
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");

            $query = "DELETE FROM PRENOTAZIONI WHERE username = '".$user."' AND stato='booked';";
            $ris = mysqli_query($conn, $query);
            
            $values = array();
            foreach($toBuy as $row){
                $id = sanitizeString($conn, $row);
                $values[] = "('".$id."', 'busy', '".$user."')";
            }
            
            $query = "INSERT INTO PRENOTAZIONI (id, stato, username) VALUES ";
            $query .= implode(',', $values);
            
            $ris = mysqli_query($conn, $query);
            
            $n = mysqli_affected_rows($conn);
            if ($n != count($toBuy))
                throw new Exception("Buying-Error");
            
            mysqli_close($conn);
            return $ris;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    /* Check username and password */
    function checkUserPsw($conn, $user, $psw){
        //$conn = dbConnect();
        $user = sanitizeString($conn, $user);
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            
            $query = "SELECT password FROM UTENTI WHERE username = '".$user."';";
            $ris = mysqli_query($conn, $query);
            
            $actual_psw = mysqli_fetch_array($ris)[0];
            mysqli_close($conn);
            return md5($psw) == $actual_psw;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    /* Insert user */
    function insertUser($conn, $user, $name, $surname, $psw){
        //$conn = dbConnect();
        try{
            if ($conn==null)
                throw new Exception("Connection-Error");
            if (!filter_var($user, FILTER_VALIDATE_EMAIL) || strlen($user) > 50)
                throw new Exception("Registration-Error");
            if (!preg_match("/.*([a-z]+.*[A-Z|0-9]+)|([A-Z|0-9]+.*[a-z]+).*/", $psw) || strlen($psw) > 25)
                throw new Exception("Psw-Error");
            
            $user = sanitizeString($conn, $user);
            $name = sanitizeString($conn, $name);
            $surname = sanitizeString($conn, $surname);
            $enc_psw = md5($psw);
            
            $query = "INSERT INTO UTENTI (username, nome, cognome, password) VALUES ('".$user."', '".$name."', '".$surname."', '".$enc_psw."');";
            $res = mysqli_query($conn, $query);
            mysqli_close($conn);
            return $res;
        } catch (Exception $e){
            mysqli_close($conn);
            alert($e->getMessage());
        }
    }
    
    
    
    function sanitizeString($conn, $var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        return mysqli_real_escape_string($conn, $var);
    }
    
    
    function validateSeat($id) {
        global $colonne;
        global $righe;
        $col = ord(substr($id,0,1)) - ord('A') + 1;
        $row = (int)substr($id,1);
        if(0<$col && $col<=$colonne && 0<$row && $row<=$righe)
            return true;
        return false;
    }
    
    
    
    
    function alert($msg){
        switch($msg){
            case "Buying-Error":
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: my-home.php?msg=PurchaseFailed');
                exit();
                break;
            case "Booking-Error":
                header('HTTP/1.1 409 Conflict');
                break;
            case "NotFound-Error":
                header('HTTP/1.1 404 Not Found');
                exit();
                break;
            case "Registration-Error":
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: ../../index.php?msg='.urlencode("RegistrationFailed"));
                exit();
                break;
            case "Psw-Error":
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: ../../index.php?msg='.urlencode("InvalidPsw"));
                exit();
                break;
            case "Connection-Error":
                header('HTTP/1.1 500 Internal Server Error');
                //echo "<script type=\"text/javascript\">alert(\"ERRORE!\\n\\nImpossibile connettersi al DB. Riprova più tardi.\")</script>";
                echo "<div style=\"padding-top: 15%\">".
                        "<h3 align=\"center\">Errore interno al server.</h3>".
                        "<h3 align=\"center\">Impossibile accedere al database, riprova più tardi.</h3>".
                     "</div>";
                exit();
                break;
        }
    }
    
    
?>
