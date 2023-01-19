<?php
/*
    Author: Yuto Uogata
    Date:   September 23, 2022
    File:   functions.php
*/



/********************************************* Function to display table *********************************************/
function display_table($table_array, $records, $record_count, $current_page)
{
    // Define variable(s)
    $total_num_of_page = ceil($record_count/RECORDS_PER_PAGE); 



    $output = "";
    $output .= "<div class=\"container-sm\">\n";
    $output .= "<nav aria-label=\"Page navigation\">\n";
    

    /* ----------------------------------------- Table ----------------------------------------- */
    $output .= "<table class=\"table table-bordered table-sm\"\n>";


    // For the headings of tables
    $output .= "<thead>\n";
    $output .= "<tr>\n";
    foreach($table_array as $key => $key_value)
    {
        $output.= "<th scope=\"col\">" . $key_value . "</th>\n";
    }
    $output .= "</tr>\n";
    $output .= "</thead>\n";



    // For the contents of tables
    $output .= "<tbody>\n";
    if($record_count != 0)
    {
        foreach($records as $record)
        {
            $output .= "<tr>\n";
            foreach($record as $key => $key_value)
            {   
                // If $key is "logo_path"
                if($key == "logo_path")
                {
                    if($key_value != null)
                    {
                        // Display logo image
                        $output .= "<td><img src=\"" . $key_value . "\" width=\"50\" height=\"50\" alt=\"logo image\"/></td>\n"; 
                    }
                    else if($key_value == null)
                    {
                        $output .= "<td>N/A</td>\n";
                    }                       
                }
                else
                {
                    if($key_value != null)
                    {
                        // Display $key_value
                        $output .= "<td>" . $key_value . "</td>\n";
                    }
                    else if($key_value == null)
                    {
                        $output .= "<td>N/A</td>\n";
                    } 
                }
                
            }
            $output .= "</tr>\n";
        }
    }
    else
    {
        echo "No record to show";
    }
    $output .= "</tbody>\n";



    $output .= "</table>\n";
    /* ----------------------------------------------------------------------------------------- */



    $output .= "<br/>";

    

    /* --------------------------------------- Pagination --------------------------------------- */
    $output .= "<ul class=\"pagination\">\n";


    // For "Previous" Button
    if($current_page > 1)
    {
        $output .= "<li class=\"page-item\">\n";
    }
    else if($current_page == 1)
    {
        $output .= "<li class=\"page-item disabled\">\n";
    }
    $output .= "<a class=\"page-link\" href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($current_page - 1) . "\">Previous</a>";
    $output .= "</li>";
    



    // For page numbers
    for ($i = 0; $i < $total_num_of_page; $i++)
    {
        // This if else is to determine the active page
        if($i + 1 == $current_page)
        {
            $output .= "<li class=\"page-item active\">\n";
        }
        else
        {
            $output .= "<li class=\"page-item\">\n";
        }


        $output .= "<a class=\"page-link\" href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($i + 1) . "\">" . ($i + 1) . "</a>";
        $output .= "</li>";
    }




    // For "Next" Button
    if($current_page < $total_num_of_page)
    {
        $output .= "<li class=\"page-item\">\n";
    }
    else if($current_page >= $total_num_of_page)
    {
        $output .= "<li class=\"page-item disabled\">\n";
    }
    $output .= "<a class=\"page-link\" href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($current_page + 1) . "\">Next</a>";
    $output .= "</li>";




    $output .= "</ul>\n";
    $output .= "</nav>\n";
    $output .= "</div>\n";
    /* ------------------------------------------------------------------------------------------ */


    echo $output;
}
/********************************************************************************************************************/





/******************************************* Function to display the form *******************************************/
function display_form($form_data)
{    
    $output = "";

    foreach($form_data as $field)
    {   
        $output .= "<div class=\"mb-4\">";
        $output .= "<label class=\"form-label\">". $field['label'] ."</label>\n";

        $output .= "<input type= ". $field['type'] ."   
                           name= ". $field['name'] ."
                           value= ". $field['value'] ."
                           class= \"form-control\"
                           placeholder= \"". $field['label'] ."\" />\n";

        $output .= "</div>";
    }  


    $output .= "<button class=\"btn btn-lg btn-primary\" type=\"submit\">Submit</button>";


    echo $output;
}
/********************************************************************************************************************/





/***************************************** Function to display upload form ******************************************/
function display_upload_file_form()
{    
    $output = "<div class=\"mb-4\">";
    $output .= "<label class=\"form-label\">Select Logo to Upload</label>\n";
    $output .= "(NOTE: File size must be smaller than " . MAX_FILE_SIZE_MB .".)";
    $output .= "<input name=\"uploadFile\" type=\"file\" class= \"form-control-file\">\n";
    $output .= "</div>";


    echo $output;
}
/********************************************************************************************************************/





// Function to determine is a user is on the session
function isLoggedIn($user)
{
    return isset($_SESSION[$user]);
}




// Function to determine if a user is an admin
function isAdmin()
{
	if($_SESSION['user']['user_type'] == ADMIN)
	{
		return true;
	}
	else
	{
		return false;
	}
}




// Function to determine if a user is a sales person
function isSalesPerson()
{
	if($_SESSION['user']['user_type'] == SALES)
	{
		return true;
	}
	else
	{
		return false;
	}
}




// Function to redirect to another page
function redirect($url)
{
    header("Location: $url");
    ob_flush();
}




/* - - - - - - - - - - - - - - - - - - - - Functions for the message - - - - - - - - - - - - - - - - - - - - */
function setMessage($msg)
{
    $_SESSION['message'] = $msg;
}

function getMessage()
{
    return $_SESSION['message'];
}

function isMessage()
{
    return isset($_SESSION['message'])?true:false;  // This is conditional operator
}

function removeMessage()
{
    unset($_SESSION['message']);
}

function flashMessage()
{
    $message = "";
    if(isMessage())
        {
            $message = getMessage();
            removeMessage();
        }
    return $message;
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */




/* - - - - - - - - - - - - - - - - - - - Function to display user info - - - - - - - - - - - - - - - - - - - */
function dump($arg)
{
    echo "<pre>";
    print_r($arg);
    echo "</pre>";
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */




/* - - - - - - - - - - - - - - - - - - Function for activity logging - - - - - - - - - - - - - - - - - - - - */
function writeActivityLog($log_message)
{
    $date = date("Ymd", time());
    $handle = fopen(('logs/' . $date . '_log.txt'), 'a');
    fwrite($handle, $log_message);
    fclose($handle);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */


?>