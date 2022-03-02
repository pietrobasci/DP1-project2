<?php 
    session_start();
    $t = time();
    $diff = 0;
    $new = false;
    if (isset($_SESSION['s266004time'])){
        $t0 = $_SESSION['s266004time'];
        $diff = ($t - $t0);  // inactivity period
    } else {
        $new = true;
    }
    if ($new || ($diff > 120)) { // new or with inactivity period too long
        //session_unset(); 	// Deprecated
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600*24,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();  // destroy session

        header('HTTP/1.1 401 Unauthorized');
        exit();
    } else {
        $_SESSION['s266004time'] = time(); /* update time */
        //echo '<html><body>Updated last access time: '.$_SESSION['time'].'</body></html>';
    }
    
?>
