<?php
/*
	Author: Yuto Uogata
    Date:   September 23, 2023
    File:   db.php
*/

$conn = db_connect();


/* ********************************************* pg_prepare statements ******************************************** */
// user_select 
pg_prepare($conn, "user_select", 'SELECT * FROM cms_users WHERE id = $1');


// client_select 
pg_prepare($conn, "client_select", 'SELECT * FROM cms_clients WHERE client_id = $1');


// user_update_login_time 
pg_prepare($conn, "user_update_login_time", 'UPDATE cms_users SET last_access = $2 WHERE id = $1');


// insert_salespeople
pg_prepare($conn, "insert_salesperson", 
		  	'INSERT INTO cms_users(id, email, first_name, last_name, password, created_time, last_access, phone_extension, user_type)
			 VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9)');


// insert_client
pg_prepare($conn, "insert_client",
			'INSERT INTO cms_clients(client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path)
			 VALUES($1, $2, $3, $4, $5, $6, $7, $8)');


// insert_call
pg_prepare($conn, "insert_call",
			'INSERT INTO cms_calls(client_id, created_at)
			 VALUES($1, $2)');


// retrieve_users
pg_prepare($conn, "retrieve_users", 'SELECT * FROM cms_users WHERE user_type = $1');


// retrieve_clients
pg_prepare($conn, "retrieve_clients", 'SELECT * FROM cms_clients WHERE salesperson_id = $1');


// user_update_password
pg_prepare($conn, "user_update_password", 'UPDATE cms_users SET password = $2 WHERE id = $1');





// retrieve_all_salespeople
pg_prepare($conn, "retrieve_all_salespeople",
			"SELECT id, email, first_name, last_name, created_time, last_access, phone_extension FROM cms_users 
			WHERE user_type = 'S' ORDER BY id ASC LIMIT $1 OFFSET $2");


// count_all_salespeople
pg_prepare($conn, "count_all_salespeople",
			"SELECT id, email, first_name, last_name, created_time, last_access, phone_extension FROM cms_users 
			WHERE user_type = 'S' ORDER BY id ASC");


// retrieve_all_clients
pg_prepare($conn, "retrieve_all_clients",
			'SELECT client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path FROM cms_clients
			ORDER BY client_id ASC LIMIT $1 OFFSET $2'); 


// count_all_clients
pg_prepare($conn, "count_all_clients",
			'SELECT client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path FROM cms_clients
			ORDER BY client_id ASC'); 


// retrieve_all_clients_by_salesperson
pg_prepare($conn, "retrieve_all_clients_by_salesperson",
			'SELECT client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path FROM cms_clients
			WHERE salesperson_id = $1 ORDER BY client_id ASC LIMIT $2 OFFSET $3'); 


// count_all_calls_by_salesperson
pg_prepare($conn, "count_all_clients_by_salesperson", 
			'SELECT client_id, salesperson_id, first_name, last_name, email, phone_number, phone_extension, logo_path FROM cms_clients
		   	WHERE salesperson_id = $1');


// retrieve_all_calls_by_salesperson
pg_prepare($conn, "retrieve_all_calls_by_salesperson", 
			'SELECT id, cms_calls.client_id, created_at FROM cms_calls JOIN cms_clients ON cms_calls.client_id = cms_clients.client_id
		   	WHERE salesperson_id = $1 ORDER BY created_at LIMIT $2 OFFSET $3');


// count_all_calls_by_salesperson
pg_prepare($conn, "count_all_calls_by_salesperson", 
		  	'SELECT id, cms_calls.client_id, created_at FROM cms_calls JOIN cms_clients ON cms_calls.client_id = cms_clients.client_id
		   	WHERE salesperson_id = $1');
/* ***************************************************************************************************************** */






// Function to connect to the database
function db_connect()
{
	$connection = pg_connect("host=".DB_HOST." port=".DB_PORT." dbname=".DATABASE." user=".DB_ADMIN." password=".DB_PASSWORD);
	return $connection;
}





/* ************************************************** sign-in.php ************************************************** */
// This function returns an associative array with the user’s information, 
// or false if that user does not exist.
function user_select($user_id)
{	
	$conn = db_connect();
	$result = pg_execute($conn, "user_select", array($user_id));

	if(pg_num_rows($result) == 1)
	{
		$user_info = pg_fetch_assoc($result, 0);
		return $user_info;
	}
	else
	{
		return false;
	}
}



// Function to update user's last access
function user_update_login_time($id, $time)
{
	$conn = db_connect();
	$result = pg_execute($conn, "user_update_login_time", array($id, $time));

	return $result;
}



// Authenticate the user
function user_authenticate($user_id, $user_password)
{
	$current_time = date("Y-m-d H:i:s", time());
	$user_info = user_select($user_id);
	
	// If the user exists:
	if($user_info != "")
	{		
		if(password_verify($user_password, $user_info['password']) == true)
		{
			$log_message = "Sign in success at " . $current_time . ". " . "User: \"" . $user_id . "\".\n";

			$_SESSION['user'] = $user_info;

			setMessage("Welcome " . $_SESSION['user']['first_name'] . "!! " . 
					"Your last login was on " . $_SESSION['user']['last_access'] . ".");

			$message = getMessage();

			// Update the last access time
			user_update_login_time($user_id, $current_time);
			
			// Activity Logging
			writeActivityLog($log_message);
			
			// Redirection
			redirect("dashboard.php");
		}
		else
		{
			// Activity logging
			$log_message = "Sign in fail at " . $current_time . ". " . "User: \"" . $user_id . "\".\n";
			writeActivityLog($log_message);

			setMessage("The password is incorrect, please try again.");
			$message = getMessage();
			return $message;
		}
	}
	// Else if the user does not exist:
	else
	{
		setMessage("The user does not exist.");
		$message = getMessage(); 
		return $message;
	}
}
/* ***************************************************************************************************************** */





/* ******************************************* Inserting data to database ****************************************** */
// Function to insert a salesperson
function insert_salesperson_function($id, $email, $fname, $lname, $password, $created_time, $last_access, $phone_extension, $user_type)
{
	$conn = db_connect();

	pg_execute($conn, "insert_salesperson", array($id, $email, $fname, $lname, password_hash($password, PASSWORD_BCRYPT), 
												$created_time, $last_access, $phone_extension, $user_type));
}


// Function to insert a clinet
function insert_client_function($client_id, $salesperson_id, $fname, $lname, $email, $phone_num, $phone_ext, $logo_path)
{
	$conn = db_connect();

	pg_execute($conn, "insert_client", array($client_id, $salesperson_id, $fname, $lname, $email, $phone_num, $phone_ext, $logo_path));
}


// Function to insert a clinet
function insert_call_function($client_id, $created_time)
{
	$conn = db_connect();

	pg_execute($conn, "insert_call", array($client_id, $created_time));
}


// Function to check if the user already exists
function user_exists($user_id)
{
	$conn = db_connect();
	$result = pg_execute($conn, "user_select", array($user_id));

	if(pg_num_rows($result) == 1)
	{
		return true;
	}

	else
	{
		return false;
	}
}


// Function to check if the client already exists
function client_exists($client_id)
{
	$conn = db_connect();
	$result = pg_execute($conn, "client_select", array($client_id));

	if(pg_num_rows($result) == 1)
	{
		return true;
	}

	else
	{
		return false;
	}
}


// Function to retrieve all the salespeople
function retrieve_users_function($user_type)
{
	$conn = db_connect();
	$result = pg_execute($conn, "retrieve_users", array($user_type));
	
	return $result;
}


// Function to retrieve all the clients
function retrieve_clients_function($salesperson_id)
{
	$conn = db_connect();
	$result = pg_execute($conn, "retrieve_clients", array($salesperson_id));
	
	return $result;
}
/* ***************************************************************************************************************** */





/* ********************************************** change-password.php ********************************************** */
// Function to update user's last access
function user_update_password($id, $new_password)
{
	$conn = db_connect();
	$result = pg_execute($conn, "user_update_password", array($id, password_hash($new_password, PASSWORD_BCRYPT)));
	
	return $result;
}
/* ***************************************************************************************************************** */





/* ********************************************* For Displaying Tables ********************************************* */
// Function to retrieve all the salespeople from cms_users table where user_type is 'S'
function retrieve_all_salespeople($page)
{
	$conn = db_connect();

	// Define variable(s)
	$record_limit = RECORDS_PER_PAGE;
	$offset = ($page - 1) * $record_limit;

	// Result of the pg_execute
	$result = pg_execute($conn, "retrieve_all_salespeople", array($record_limit, $offset));

	$sales_people_records = pg_fetch_all($result);

	return $sales_people_records;
}


// Function to count the number of rows
function count_all_salespeople()
{
	$conn = db_connect();
	$result = pg_execute($conn, "count_all_salespeople", array());

	$count = pg_num_rows($result);

	return $count;
}


// Function to retrieve all the clients from clients table
function retrieve_all_clients($page)
{
	$conn = db_connect();

	// Define variable(s)
	$record_limit = RECORDS_PER_PAGE;
	$offset = ($page - 1) * $record_limit;

	// Result of the pg_execute
	$result = pg_execute($conn, "retrieve_all_clients", array($record_limit, $offset));

	$clients_records = pg_fetch_all($result);

	return $clients_records;
}



// Function to count the number of rows (all clients)
function count_all_clients()
{
	$conn = db_connect();
	$result = pg_execute($conn, "count_all_clients", array());

	$count = pg_num_rows($result);

	return $count;
}


// Function to retrieve clients based on the salesperosn
function retrieve_all_clients_by_salesperson($salesperson_id, $page)
{
	$conn = db_connect();

	// Define variable(s)
	$record_limit = RECORDS_PER_PAGE;
	$offset = ($page - 1) * $record_limit;

	// Result of the pg_execute
	$result = pg_execute($conn, "retrieve_all_clients_by_salesperson", array($salesperson_id, $record_limit, $offset));
	
	// Count the number of rows 
	$row_count = pg_num_rows($result);
	
	// Convert the returned result to array
	$clients_records_array = pg_fetch_all($result);

	return $clients_records_array;
}


// Function to count the number of rows 
function count_all_clients_by_salesperson($salesperson_id)
{
	$conn = db_connect();
	$result = pg_execute($conn, "count_all_clients_by_salesperson", array($salesperson_id));
	$count = pg_num_rows($result);

	return $count;
}

// Function to retrieve records from cms_calls table
function retrieve_all_calls_by_salesperson($salesperson_id, $page)
{
	$conn = db_connect();

	// Define variable(s)
	$record_limit = RECORDS_PER_PAGE;
	$offset = ($page - 1) * $record_limit;

	// Result of the pg_execute
	$result = pg_execute($conn, "retrieve_all_calls_by_salesperson", array($salesperson_id, $record_limit, $offset));
	
	// Count the number of rows 
	$row_count = pg_num_rows($result);
	
	// Convert the returned result to array
	$calls_records_array = pg_fetch_all($result);

	return $calls_records_array;
}


// Function to count the number of rows 
function count_all_calls_by_salesperson($salesperson_id)
{
	$conn = db_connect();
	$result = pg_execute($conn, "count_all_calls_by_salesperson", array($salesperson_id));
	$count = pg_num_rows($result);

	return $count;
}
/* ***************************************************************************************************************** */
?>
