<?php
$file = "sign-in.php";
$date = "September 23, 2022";
$title = "Sign-In Page";

include "./includes/header.php";
?>



<?php
 // If the user is logged in and try to navigate to sign-in.php, redirect them to dashboard.php
 if(isLoggedIn('user'))
 {
    setMessage("You are already signed in.");
    redirect("dashboard.php");
 }


if($_SERVER["REQUEST_METHOD"] == "GET")
{
	$user_id = "";
	$user_password = "";
}

else if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Import data from the form and trim it
	$user_id = trim($_POST["id"]);
	$user_password = trim($_POST["password"]);
 

    // User Authentication
    $message = user_authenticate($user_id, $user_password);
}
?>

<?php echo $message; ?>


<!--__________________________________________ Form ________________________________________________-->
<form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

    <label class="sr-only">Id</label>
    <input type="text" name="id" value="<?php echo $user_id; ?>" class="form-control" placeholder="User ID" required autofocus>

    <label class="sr-only">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
<!--________________________________________________________________________________________________-->


<?php
    echo "User Type: Admin<br/>
          ID: jdoe101<br/>
          Password: somepassword<br/><br/>";
    
    echo "User Type: Salesperson<br/>
          ID: sales1<br/>
          Password: sales_password1<br/><br/>";
?>

<?php
include "./includes/footer.php";
?>    