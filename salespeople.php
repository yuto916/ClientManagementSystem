<?php
$file = "salespeople.php";
$date = "October 6, 2022";
$title = "Sales People";

include "./includes/header.php";
?>  


<h1 class="h2">Sales People</h1>


<?php
    // If the user is not an admin, send them to dashboard.php 
    if(isAdmin() == false)
    {
        redirect("dashboard.php");
        setMessage("Only admins are allowed to access Sales People page.");
    }
?>


<div class="btn-toolbar mb-2 mb-md-0">
</div>
</div>


<?php
// Define array
$salespersonForm = [
    [
        "type" => "text",
        "name" => "id",
        "value" => "\"\"",
        "label" => "Login ID"
    ],
    [
        "type" => "text",
        "name" => "firstName",
        "value" => "\"\"",
        "label" => "First Name"
    ],
    [
        "type" => "text",
        "name" => "lastName",
        "value" => "\"\"",
        "label" => "Last Name"
    ],
    [
        "type" => "email",
        "name" => "email",
        "value" => "\"\"",
        "label" => "E-Mail"
    ],
    [
        "type" => "number",
        "name" => "extension",
        "value" => "\"\"",
        "label" => "Phone Extension"
    ],
    [
        "type" => "password",
        "name" => "password",
        "value" => "\"\"",
        "label" => "Password"
    ]     
];



// Define variable(S)
$current_time = date("Y-m-d H:i:s", time());
$error_message = "";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Import data from the form
    $sales_login_id = trim($_POST["id"]);
    $sales_fname = trim($_POST["firstName"]);
    $sales_lname = trim($_POST["lastName"]);
    $sales_email = trim($_POST["email"]);
    $sales_extension_num = trim($_POST["extension"]);
    $sales_password = trim($_POST["password"]); 



    
    /**************************************************** Validation **************************************************** */
    /* - - - Validation for sales login ID - - - */
    // Check if the Login ID is empty
    if(strlen($sales_login_id) == 0)
    {
        $error_message .= "ERROR: Please enter Login ID. <br/>";
    }

    // Check the Login ID's length 
    else if(strlen($sales_login_id) < MIN_LOGIN_ID_LENGTH || strlen($sales_login_id) > MAX_LOGIN_ID_LENGTH)
    {
        $error_message .= "ERROR: The length of Login ID must be between " . MIN_LOGIN_ID_LENGTH . " and " . MAX_LOGIN_ID_LENGTH . ". ";
        $error_message .= "You entered \"" . $sales_login_id . "\".<br/>";
    }

    // Check if the Login ID already exists.
    else if(user_exists($sales_login_id) == true)
    {
        $error_message .= "ERROR: The Login ID already exists. Please choose a different ID. ";
        $error_message .= "You entered \"" . $sales_login_id . "\".<br/>";
    }

    else
    {
        $salespersonForm[0]['value'] = $sales_login_id;
    }
    /* - - - - - - - - - - - - - - - - - - - - - */



    /* - - - Validation for sales first name - - - */
    // Check if the first name of the salesperson is empty
    if(strlen($sales_fname) == 0)
    {
        $error_message .= "ERROR: Please enter the first name of the salesperson. <br/>";
    }

    // Check if the first name includes number
    else if(ctype_alpha($sales_fname) == false)
    {
        $error_message .= "ERROR: First name cannot include numeric values. ";
        $error_message .= "You entered \"" . $sales_fname . "\".<br/>";
    }

    else
    {
        $salespersonForm[1]['value'] = $sales_fname;
    }
    /* - - - - - - - - - - - - - - - - - - - - - - */



    /* - - - - Validation for sales last name - - - - */
    // Check if the lastt name of the salesperson is empty
    if(strlen($sales_lname) == 0)
    {
        $error_message .= "ERROR: Please enter the last name of the salesperson. <br/>";
    }

    // Check if the last name includes number
    else if(ctype_alpha($sales_lname) == false)
    {
        $error_message .= "ERROR: Last name cannot include numeric values. ";
        $error_message .= "You entered \"" . $sales_lname . "\".<br/>";
    }

    else
    {
        $salespersonForm[2]['value'] = $sales_lname;
    }
    /* - - - - - - - - - - - - - - - - - - - - - - - */



    /* - - - - - Validation for sales email - - - - - */
    // Check if the email is empty
    if(strlen($sales_email) == 0)
    {
        $error_message .= "ERROR: Please enter the email of the salesperson. <br/>";
    }

    // Check if it's a valid email
    else if(filter_var($sales_email, FILTER_VALIDATE_EMAIL) == false)
    {
        $error_message .= "ERROR: The entered email address is not valid. ";
        $error_message .= "You entered \"" . $sales_email . "\".<br/>";
    }

    else
    {
        $salespersonForm[3]['value'] = $sales_email;
    }
    /* - - - - - - - - - - - - - - - - - - - - - - - - */
    


    /* - - - - - Validation for sales extension - - - - - */
    // Check if the extension is empty
    if(strlen($sales_extension_num) == 0)
    {
        $error_message .= "ERROR: Please enter the extension number of the salesperson. <br/>";
    }
    
    // Check if the extension is numeric
    else if(is_numeric($sales_extension_num) == false)
    {
        $error_message .= "ERROR: Extension must be numeric. ";
        $error_message .= "You entered \"" . $sales_extension_num . "\".<br/>";
    }

    else
    {
        $salespersonForm[4]['value'] = $sales_extension_num;
    }
    /* - - - - - - - - - - - - - - - - - - - - - - - - */



    /* - - - - - Validation for password - - - - - */
    // Check if the password is empty
    if(strlen($sales_password) == 0)
	{
		$error_message .= "ERROR: Please enter a Password.<br/>";
	}

    // Check if the password length
    else if(strlen($sales_password) < MIN_PASSWORD_LENGTH || strlen($sales_password) > MAX_PASSWORD_LENGTH)
    {
        $error_message .= "ERROR: The length of password must be between  " . MIN_PASSWORD_LENGTH . " and " . MAX_PASSWORD_LENGTH . ".<br/>";
    }
    /* - - - - - - - - - - - - - - - - - - - - - - - - */



    // If everything is valid
    if($error_message == "")
    {  
        insert_salesperson_function($sales_login_id, $sales_email, $sales_fname, $sales_lname, $sales_password, 
                       $current_time, $current_time, $sales_extension_num, 'S');

        for($i = 0; $i < 5; $i++)
        {
            $salespersonForm[$i]['value'] = "\"\"";
        }

        echo "Salesperson created successfully.";
    }
    /******************************************************************************************************************** */    
}
?>



<?php 
    echo $error_message; 
?>



<!-- ************************************************ Sales People Form *********************************************** -->
<br/><form class="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
    display_form($salespersonForm);
?>
</form>
<!-- ****************************************************************************************************************** -->



<br/><br/>



<!-- *********************************************** Sales People Table *********************************************** -->
<?php
// Define variable(s)
$page = 1;

if (isset($_GET['page']))
{
    $page = $_GET['page'];
}

$records = retrieve_all_salespeople($page);
$count = count_all_salespeople();



// Define array
$salespeopleTableArray = [
    "id" => "Login ID",
    "email" => "E-Mail",
    "first_name" => "First Name", 
    "last_name" => "Last Name",
    "created_time" => "Created Time",
    "last_access" => "Last Access",
    "phone_extension" => "Phone Extension"
];


display_table($salespeopleTableArray, $records, $count, $page);

?>
<!-- ****************************************************************************************************************** -->





<div class="table-responsive">


<?php
include "./includes/footer.php";
?>    