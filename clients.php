<?php
$file = "clients.php";
$date = "October 6, 2022";
$title = "Clients";

include "./includes/header.php";
?>  

<h1 class="h2">Clients</h1>


<?php
    // If the user is not an admin or salesperson, send them to dashboard.php 
    if(isAdmin() == false && isSalesPerson() == false)
    {
        redirect("dashboard.php");
        setMessage("Only admins and salespeople are allowed to access Clients page.");
    }
?>


<div class="btn-toolbar mb-2 mb-md-0">
</div>
</div>


<?php
$clientsForm = [
    [
        "type" => "number",
        "name" => "clientId",
        "value" => "\"\"",
        "label" => "Client ID"
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
        "name" => "phone",
        "value" => "\"\"",
        "label" => "Phone Number"
    ],
    [
        "type" => "number",
        "name" => "phoneExt",
        "value" => "\"\"",
        "label" => "Phone Number Extension (Optional)"
    ]
];



// Define variable(S)
$error_message = "";
$sales_id = "";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Import data from the form
    $client_id = trim($_POST["clientId"]);
    $client_fname =  trim($_POST["firstName"]);
    $client_lname = trim($_POST["lastName"]);
    $client_email = trim($_POST["email"]);
    $client_phone_num = trim($_POST["phone"]);
    $client_phone_num_extension = trim($_POST["phoneExt"]);

    // If the user is admin, they need to choose salesperson from the dropdown menu
    if(isLoggedIn('user') && isAdmin() == true)
    {
        $sales_id = $_POST["salesPeople"];
    }

    // If the user is salesperson, then the new client will be associated with that salesperson
    else if(isLoggedIn('user') && isSalesPerson() == true)
    {
        $sales_id = $_SESSION['user']['id'];
    }



    /********************************************************* Validation  *********************************************************/
    /* ---------------------------- Validation for File Upload ----------------------------- */
    // Check ['uploadFile']['error'] value and if it's not 0, display error
    if($_FILES['uploadFile']['error'] != 0)
    {
        $error_message .= "ERROR: There was no file uploaded or there was a problem uploading your file.<br/>";
    }

    // Check the file type
    else if($_FILES['uploadFile']['type'] != "image/jpeg" && $_FILES['uploadFile']['type'] != "image/jpg" 
            && $_FILES['uploadFile']['type'] != "image/pjpeg" && $_FILES['uploadFile']['type'] != "image/gif" 
            && $_FILES['uploadFile']['type'] != "image/png")
    {
        $error_message .= "ERROR: File must be of type JPEG, JPG, PJPEG, GIF, or PNG.<br/>";
    }

    // Check file size
    else if($_FILES['uploadFile']['size'] > MAX_FILE_SIZE)
    {
        $error_message .= "ERROR: File size must be smaller than " . MAX_FILE_SIZE_MB . ".<br/>";
    }

    // If valid, create logo path
    else
    {
        $logo_path = "./file_uploads/" . $client_id . "_" . $client_fname . "_logo.jpeg";
    }
    /* ------------------------------------------------------------------------------------- */



    /* ------------------------------ Validation for Sales ID ------------------------------ */
    // Check if a sales person is selected
    if($sales_id == -1)
    {
        $error_message .= "ERROR: Please select a salesperson. <br/>";
    }
    /* ------------------------------------------------------------------------------------- */
    


    /* ------------------------------ Validation for Client ID ----------------------------- */
    // Check if the Client ID is empty
    if(strlen($client_id) == "")
    {
        $error_message .= "ERROR: Please enter Client ID. <br/>";
    }

    // Check if the Client ID is numeric
    else if(is_numeric($client_id) == false)
    {
        $error_message .= "ERROR: Client ID must be numeric. ";
        $error_message .= "You entered \"" . $client_id . "\".<br/>";
    }

    // Check the length of Client ID
    else if(strlen($client_id) > MAX_CLIENT_ID_LENGTH)
    {
        $error_message .= "ERROR: The length of Client ID must be equal to or less than " . MAX_CLIENT_ID_LENGTH . ". ";
        $error_message .= "You entered \"" . $client_id . "\".<br/>";
    }

    // Check if the Login ID already exists.
    else if(client_exists($client_id) == true)
    {
        $error_message .= "ERROR: The Client ID already exists. Please choose a different ID. ";
        $error_message .= "You entered \"" . $client_id . "\".<br/>";
    }

    
    else
    {
        $clientsForm[0]['value'] = $client_id;
    }
    /* ------------------------------------------------------------------------------------- */



    /* -------------------------  Validation for client first name ------------------------- */
    // Check if the first name of the client is empty
    if(strlen($client_fname) == 0)
    {
        $error_message .= "ERROR: Please enter the first name of the client. <br/>";
    }

    // Check if the first name includes number
    else if(ctype_alpha($client_fname) == false)
    {
        $error_message .= "ERROR: First name cannot include numeric values. ";
        $error_message .= "You entered \"" . $client_fname . "\".<br/>";
    }

    else
    {
        $clientsForm[1]['value'] = $client_fname;
    }
    /* ------------------------------------------------------------------------------------- */



    /* -------------------------- Validation for client last name -------------------------- */
    // Check if the last name of the client is empty
    if(strlen($client_lname) == 0)
    {
        $error_message .= "ERROR: Please enter the last name of the client. <br/>";
    }

    // Check if the first name includes number
    else if(ctype_alpha($client_lname) == false)
    {
        $error_message .= "ERROR: Last name cannot include numeric values. ";
        $error_message .= "You entered \"" . $client_lname . "\".<br/>";
    }

    else
    {
        $clientsForm[2]['value'] = $client_lname;
    }
    /* ------------------------------------------------------------------------------------- */



    /* ---------------------------- Validation for client email ---------------------------- */
    // Check if the email is empty
    if(strlen($client_email) == 0)
    {
        $error_message .= "ERROR: Please enter the email of the client. <br/>";
    }

    // Check if it's a valid email
    else if(filter_var($client_email, FILTER_VALIDATE_EMAIL) == false)
    {
        $error_message .= "ERROR: The entered email address is not valid. ";
        $error_message .= "You entered \"" . $client_email . "\".<br/>";
    }

    else
    {
        $clientsForm[3]['value'] = $client_email;
    }
    /* ------------------------------------------------------------------------------------- */
    

    
    /* ------------------------ Validation for client phone number ------------------------- */
    // Check if the phone number is empty
    if(strlen($client_phone_num) == 0)
    {
        $error_message .= "ERROR: Please enter the phone number of the client. <br/>";
    }

    // Check if the phone number is numeric
    else if(is_numeric($client_phone_num) == false)
    {
       $error_message .= "ERROR: Client phone number must be numeric. ";
       $error_message .= "You entered \"" . $client_phone_num . "\".<br/>";
    }

    // Check the length of phone number
    else if(strlen($client_phone_num) != PHONE_NUM_LENGTH)
    {
        $error_message .= "ERROR: The length of phone number must be " . PHONE_NUM_LENGTH . ". ";
        $error_message .= "You entered \"" . $client_phone_num . "\".<br/>";
    }

    else
    {
        $clientsForm[4]['value'] = $client_phone_num;
    }
    /* ------------------------------------------------------------------------------------- */


    
    /* ------------------- Validation for client phone number extension -------------------- */
    // This field is optional, thus can be left empty
    // Check if the phone number extension is empty and if so, set the value to null
    if(strlen($client_phone_num_extension) == 0)
    {
        $client_phone_num_extension = null;
    }

    // Check if the phone number extension is entered
    else if(strlen($client_phone_num_extension) > 0)
    {
        // Check if the extension is numeric
        if(is_numeric($client_phone_num_extension) == false)
        {
            $error_message .= "ERROR: Extension must be numeric. ";
            $error_message .= "You entered \"" . $client_phone_num_extension . "\".<br/>";
        }

        else
        {
           $clientsForm[5]['value'] = $client_phone_num_extension;
        }
    }
    /* ------------------------------------------------------------------------------------- */



    /* ------------------------------- If everything is valid -------------------------------*/
    if($error_message == "")
    {
        // Move the file to the folder where uploaded files are stored
        move_uploaded_file($_FILES['uploadFile']['tmp_name'], $logo_path);


        // Insert client
        insert_client_function($client_id, $sales_id, $client_fname, $client_lname, 
                               $client_email, $client_phone_num, $client_phone_num_extension, $logo_path);

        for($i = 0; $i < 6; $i++)
        {
            $clientsForm[$i]['value'] = "\"\"";
        }

        $sales_id = "";

        echo "Client created successfully.";
    }
    /* ------------------------------------------------------------------------------------- */
    /*******************************************************************************************************************************/ 
}
?>


<?php 
    echo $error_message; 
?>



<!-- ******************************************************* Clients Form ******************************************************* -->
<br/>
<form class="form" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php

    // File Upload form
    display_upload_file_form();
 


    /* ---------------------------------- Drop Down Menu ----------------------------------- */
    // If the user is admin, display the drop down menu
    if(isLoggedIn('user') && isAdmin() == true)
    {
        $result = retrieve_users_function(SALES);

        $output = "<div class=\"mb-4\">\n";
        $output .= "<label class=\"form-label\">Select Salesperson</label>\n";
        $output .= "<select select class=\"form-control\" name=\"salesPeople\">\n";
        $output .= "<option selected value=\"-1\">Select Salesperson</option>\n";

        for($i = 0; $i < pg_num_rows($result); $i++)
        {
            $sales_person = pg_fetch_assoc($result, $i);

            // This if else is to make the drop down menu sticky
            // If the $sales_id (chosen option) does not match the $sales_person['id'], don't make it selected when the page loads
            if($sales_id != $sales_person['id'])
            {
                $output .= "<option value=" . $sales_person['id'] . ">" . $sales_person['first_name'] . " " . $sales_person['last_name'] . "</option>\n";
            }

            // If the $sales_id (chosen option) matches the $sales_person['id'], make it selected so that that option is selected when the page loads
            else
            {
                $output .= "<option selected value=" . $sales_person['id'] . ">" . $sales_person['first_name'] . " " . $sales_person['last_name'] . "</option>\n";
            }
        }

        $output .= "</select>\n";
        $output .= "</div>";

        echo $output;
        /* ------------------------------------------------------------------------------------- */
    }

    display_form($clientsForm);
?>
</form>
<!-- **************************************************************************************************************************** -->



<br/><br/>



<!-- ****************************************************** Clients Table ******************************************************* -->
<?php
// Define variable(s)
$page = 1;

if (isset($_GET['page']))
{
    $page = $_GET['page'];
}


// Define variables based on user type
if(isLoggedIn('user') && isAdmin() == true)
{
    $records = retrieve_all_clients($page);
    $count = count_all_clients();
}
else if(isLoggedIn('user') && isSalesPerson() == true)
{
    $records = retrieve_all_clients_by_salesperson($_SESSION['user']['id'], $page);
    $count = count_all_clients_by_salesperson($_SESSION['user']['id']);
}



// Define array
$clientsTableArray = [
    "client_id" => "Client ID",
    "salesperson_id" => "Salesperson ID",
    "first_name" => "Client First Name", 
    "last_name" => "Client Last Name",
    "email" => "Client Email",
    "phone_number" => "Client Phone Number",
    "phone_extension" => "Client Phone Extension",
    "logo_path" => "Client Logo"
];


display_table($clientsTableArray, $records, $count, $page);

?>
<!-- **************************************************************************************************************************** -->





<div class="table-responsive">


<?php
include "./includes/footer.php";
?>    
