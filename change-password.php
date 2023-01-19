<?php
$file = "change-password.php";
$date = "November 1, 2022";
$title = "Change Password";

include "./includes/header.php";
?>  


<h1 class="h2">Change Password</h1>


<?php
    echo $message;
    
    // If the user is not logged in and try to navigate to change-password.php, redirect them to sign-in.php
    if(!isLoggedIn('user'))
    {
        setMessage("You must be signed in to see the page.");
        redirect("sign-in.php");
    }
?>


<div class="btn-toolbar mb-2 mb-md-0">
</div>
</div>


<?php
// Create an array of array
$passwordChangeForm = [
    [
        "type" => "password",
        "name" => "newPassword",
        "value" => "\"\"",
        "label" => "New Password"
    ],
    [
        "type" => "password",
        "name" => "confirmNewPassword",
        "value" => "\"\"",
        "label" => "Re-type New Password"
    ]
];



// Define variable(S)
$error_message = "";
$user_id = $_SESSION['user']['id'];


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Import data from the form
    $new_password = trim($_POST["newPassword"]);
    $confirm_new_password = trim($_POST["confirmNewPassword"]);

    
    
    /***************************************************** Validation *******************************************************/
    /* ---------------------- Validation for new password ---------------------- */
    // Check if the new password is empty
    if(strlen($new_password) == 0)
    {
        $error_message .= "ERROR: Please enter New Password. <br/>";
    }


    // Check the length of the new password
    else if(strlen($new_password) < MIN_PASSWORD_LENGTH || strlen($new_password) > MAX_PASSWORD_LENGTH)
    {
        $error_message .= "ERROR: The length of password must be between " . MIN_PASSWORD_LENGTH . " and " . MAX_PASSWORD_LENGTH . ". ";
        $error_message .= "You entered \"" . $new_password . "\".<br/>";
    }


    // Check if the confirm new password is empty.
	else if(strlen($confirm_new_password) == 0)
	{
		$error_message .= "ERROR: Please re-type the new password.<br/>";
	}

    
    // Check if the new password and the confirm new password are the same.
	else if(strcmp($new_password, $confirm_new_password))
	{
		$error_message .= "ERROR: Please make sure that the New Password matches the Re-type New Password.<br/>";
	}
    /* ------------------------------------------------------------------------- */


    // If everything is valid
    if($error_message == "")
    {  
        user_update_password($user_id, $new_password);
        redirect("dashboard.php");
        setMessage("Password sucessfully updated.");
    }
    /************************************************************************************************************************/
}
?>


<?php 
    echo $error_message; 
?>


<br/><form class="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
    // Display the form 
    display_form($passwordChangeForm);
?>
</form>


<div class="table-responsive">


<?php
include "./includes/footer.php";
?>    