<?php
    include 'secure.php';
    include '../dbAccess.php';

    /* Registration */
    function registation(){
        
        if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['user']) && isset($_POST['psw'])){
            
            $conn = dbConnect();
            $user = sanitizeString($conn, $_POST['user']);
            
            $ris = insertUser($conn, $_POST['user'], $_POST['name'], $_POST['surname'], $_POST['psw']);
            
            if ($ris){
                session_start();
                $_SESSION['s266004user'] = stripslashes($user);
                $_SESSION['s266004time'] = time();
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: ../my-home.php?msg=RegistrationCompleted');
                exit();
            }
            else
                redirect("RegistrationFailed");
            
        }
        else
            redirect("RegistrationFailed");
        
    }
    
    registation();
    
?>
