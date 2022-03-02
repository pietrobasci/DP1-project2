<?php
    include 'secure.php';
    include '../dbAccess.php';

    session_start();

    /* destroy session */
    function myDestroySession(){
        $_SESSION = array();
        if(ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600*24, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        
        redirect("Logout");

    }
    
    myDestroySession();
    
?>
