<?php
    
    /* Redirect HTTPS */
    function httpsRedirect(){
        if( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on' ){
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            exit();
        }
    }
    
    /* Redirect */
    function redirect($msg){
        header('HTTP/1.1 307 Temporary Redirect');
        header('Location: ../../index.php?msg='.urlencode($msg));
        exit();
    }
    
    httpsRedirect();
    
?>
