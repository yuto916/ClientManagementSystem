<?php
    include "./includes/header.php";

    if(!isset($_SESSION['user']))
    {
        redirect("sign-in.php");
    }
    else
    {
        // - - - - - - - - - - Activity Logging - - - - - - - - - -
        $current_time = date("Y-m-d H:i:s", time());
        $log_message = "Sign out success at " . $current_time . ". " . "User: \"" . $_SESSION['user']['id'] . "\".\n";
        writeActivityLog($log_message);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        session_unset();
        session_destroy();
        session_start();
            
        setMessage("You have successfully logged out.");
        $message = getMessage();

        redirect("sign-in.php");
    }
    
    

    
    include "./includes/footer.php";

?>