<?php
    include 'auth/secure.php';
    include 'auth/sessions.php';
    include 'dbAccess.php'
?>
<?php
    
    if(isset($_SESSION['s266004user']) && isset($_SESSION['s266004books'])){
       
        $user = $_SESSION['s266004user'];
        $books = $_SESSION['s266004books'];

        if(count($books) == 0){
            header('HTTP/1.1 307 Temporary Redirect');
            header('Location: my-home.php?msg=EmptyCart');
            exit();
        }
        else {
            setBusy($user, $books);
            header('HTTP/1.1 307 Temporary Redirect');
            header('Location: my-home.php?msg=PurchaseCompleted');
            exit();
        }
    
    }
    
?>
