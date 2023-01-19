<?php
$file = "calls.php";
$date = "October 6, 2022";
$title = "Calls";

include "./includes/header.php";
?> 


<h1 class="h2">Calls</h1>



<?php
    // If the user is not an salesperson, send them to dashboard.php 
    if(isSalesPerson() == false)
    {
        redirect("dashboard.php");
        setMessage("Only salespeople are allowed to access Calls page.");
    }
?>


<div class="btn-toolbar mb-2 mb-md-0">
</div>
</div>


<?php
// Define variable(S)
$current_time = date("Y-m-d H:i:s", time());
$error_message = "";


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Import data from the form
    $client_id = $_POST["clients"];

    /******************************************* Validation *******************************************/
    /* - - - - - Validation for Client ID - - - - - */
    // Check if a client is selected
    if($client_id == -1)
    {
        $error_message .= "ERROR: Please select a client. <br/>";
    }
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - -  */


    // If everything is valid
    if($error_message == "")
    {
        insert_call_function($client_id, $current_time);

        echo "Call created successfully.";
    }
    /****************************************************************************************************/    
}
?>


<?php 
    echo $error_message; 
?>


<!-- ****************************************** Calls Form ******************************************* -->
<br/>
<form class="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
    $result = retrieve_clients_function($_SESSION['user']['id']);
    
    $output = "<div class=\"mb-4\">\n";
    $output .= "<label class=\"form-label\">Select Client</label>\n";
    $output .= "<select class=\"form-control\" name=\"clients\">\n";
    $output .= "<option value=\"-1\">Select Client</option>\n";

    
    for($i = 0; $i < pg_num_rows($result); $i++)
    {
        $client = pg_fetch_assoc($result, $i);
        $output .= "<option value=" . $client['client_id'] . ">" . $client['first_name'] . " " . $client['last_name'] . "</option>\n";
    }

    $output .= "</select>\n";
    $output .= "</div>";

    $output .= "<button class=\"btn btn-lg btn-primary\" type=\"submit\">Submit</button>";

    echo $output;

?>
</form>
<!-- ************************************************************************************************* -->


<br/><br/>


<!-- ****************************************** Calls Table ****************************************** -->
<?php
// Define variable(s)
$page = 1;

if (isset($_GET['page']))
{
    $page = $_GET['page'];
}

$records = retrieve_all_calls_by_salesperson($_SESSION['user']['id'], $page);
$count = count_all_calls_by_salesperson($_SESSION['user']['id']);



// Define array
$callTableArray = [
    "id" => "Calls ID",
    "client_id" => "Client ID",
    "created_at" => "Created Time"
];


display_table($callTableArray, $records, $count, $page);

?>
<!-- ************************************************************************************************* -->





<div class="table-responsive">


<?php
include "./includes/footer.php";
?>    