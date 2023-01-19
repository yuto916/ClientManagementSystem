<?php
/*
    Author: Yuto Uogata
    Date: September 23, 2022
    File:   constants.php
*/


// Cookies
define("COOKIE_LIFESPAN", "2592000");   //60x60x24x30 = 2592000


// User types
define("ADMIN", 'A');
define("SALES", 'S');
define("CLIENT", 'C');



// Database constants
define("DB_HOST", "127.0.0.1");
define("DATABASE", "");
define("DB_ADMIN", "");
define("DB_PORT", "");
define("DB_PASSWORD", "");



// Constants for user input validation
define("MIN_LOGIN_ID_LENGTH", 5);
define("MAX_LOGIN_ID_LENGTH", 20);
define("MIN_PASSWORD_LENGTH", 8);
define("MAX_PASSWORD_LENGTH", 20);
define("MAX_CLIENT_ID_LENGTH", 10);
define("PHONE_NUM_LENGTH", 10);



// Constant for the number of records to display per page
define("RECORDS_PER_PAGE", 5);



// Constants for file upload validation
define("MAX_FILE_SIZE", 2000000);
define("MAX_FILE_SIZE_MB", "2MB");
?>