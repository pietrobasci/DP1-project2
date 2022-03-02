<?php
    include 'auth/secure.php';
    include 'auth/checkAuth.php';
    include 'dbAccess.php'
?>
<?php
    
    if(isset($_POST['seat']) && isset($_SESSION['s266004user']) && isset($_SESSION['s266004books'])){
        
        $id = $_POST['seat'];
        $user = $_SESSION['s266004user'];
    
        $ris = setBooked($id, $user);
    
        if($ris){
            array_push($_SESSION['s266004books'], $id);
            $mine = true;
            $state = "booked";
        }
        else{
            $ris = getSeatState($id);
            $r = mysqli_fetch_array($ris, MYSQLI_ASSOC);
        
            $mine = false;
            $state = "free";
            
            if($r['username'] == $user)
                $mine = true;
            $state = $r['stato'];
        }
        
        switch($state){
            case 'busy':
                echo "<input type=\"checkbox\" disabled id=".$id." />".
                     "<label for=".$id.">".$id."</label>";
                break;
            case 'booked':
                if($mine)
                    echo "<input type=\"checkbox\" checked onclick=\"postAjax('cancelBook.php', '".$id."'); \" id=".$id." />".
                         "<label class=\"booked\" for=".$id.">".$id."</label>";
                else
                    echo "<input type=\"checkbox\" onclick=\"postAjax('book.php', '".$id."'); \" id=".$id." />".
                         "<label class=\"booked\" for=".$id.">".$id."</label>";
                break;
            default:
                echo "<input type=\"checkbox\" onclick=\"postAjax('book.php', '".$id."'); \" id=".$id." />".
                     "<label for=".$id.">".$id."</label>";
        }
        
    }

?>
