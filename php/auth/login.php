<?php
    include 'secure.php';
    include '../dbAccess.php';

    /* Login */    
    function login(){
        
        if(isset($_POST['user']) && isset($_POST['psw'])){
            
            $conn = dbConnect();
            $user = sanitizeString($conn, $_POST['user']);
            
            $ris = checkUserPsw($conn, $_POST['user'], $_POST['psw']);
            
            if ($ris){
                session_start();
                $_SESSION['s266004user'] = stripslashes($user);
                $_SESSION['s266004time'] = time();
                header('HTTP/1.1 307 Temporary Redirect');
                header('Location: ../my-home.php');
                exit();
            }
            else
                redirect("LoginFailed");
            
        }
        else
            redirect("LoginFailed");
        
    }
    
    
    login();
    
?>
