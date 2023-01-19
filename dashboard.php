<?php
$file = "dashboard.php";
$date = "September 23, 2022";
$title = "Dashboard";

include "./includes/header.php";
?>  


<h1 class="h2">Dashboard</h1>


<?php
    echo $message;
    
    // If the user is not logged in and try to navigate to dashboard.php, redirect them to sign-in.php
    if(!isLoggedIn('user'))
    {
        setMessage("You must be signed in to see the page.");
        redirect("sign-in.php");
    }
?>


<div class="btn-toolbar mb-2 mb-md-0"> 
</div>
</div>



<h4>Current signed in user info:</h4>
<?php
    dump($_SESSION['user']);
?>



<div class="table-responsive">


<?php
include "./includes/footer.php";
?>    